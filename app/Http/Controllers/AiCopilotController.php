<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GeminiService;
use App\Services\InventoryContextService;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\IOFactory;

class AiCopilotController extends Controller
{
    protected GeminiService $gemini;
    protected InventoryContextService $contextService;

    public function __construct(GeminiService $gemini, InventoryContextService $contextService)
    {
        $this->gemini = $gemini;
        $this->contextService = $contextService;
    }

    /**
     * Handle natural language database queries.
     */
    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
            'history' => 'nullable|array',
        ]);

        $message = $request->input('message');
        $history = $request->input('history', []);

        // Retrieve system instructions and live inventory snapshot
        $systemInstruction = $this->contextService->getSystemInstruction();
        $dbSnapshot = $this->contextService->getInventoryContextText();

        // Stitch the database snapshot directly into the system instruction/context
        $fullInstruction = $systemInstruction . "\n\n" . $dbSnapshot;

        // Get response from Gemini
        $response = $this->gemini->generateResponse($message, $fullInstruction, $history);

        return response()->json([
            'response' => $response,
        ]);
    }

    /**
     * Parse and analyze an Excel/CSV file upload.
     */
    public function analyzeFile(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt,xlsx,xls|max:5120', // Limit to 5MB
            'instruction' => 'nullable|string',
        ]);

        $file = $request->file('file');
        $instruction = $request->input('instruction') ?: "Analyze this spreadsheet, summarize its columns, and check for any stock or SKU anomalies.";
        
        $path = $file->getRealPath();
        $extension = strtolower($file->getClientOriginalExtension());
        $fileName = $file->getClientOriginalName();
        
        $rows = [];
        
        try {
            if ($extension === 'csv' || $extension === 'txt') {
                // Parse CSV natively
                if (($handle = fopen($path, 'r')) !== false) {
                    $count = 0;
                    while (($data = fgetcsv($handle, 1000, ',')) !== false && $count < 100) {
                        $rows[] = $data;
                        $count++;
                    }
                    fclose($handle);
                }
            } else {
                // Parse Excel file using PhpSpreadsheet
                if (class_exists('PhpOffice\PhpSpreadsheet\IOFactory')) {
                    $spreadsheet = IOFactory::load($path);
                    $sheet = $spreadsheet->getActiveSheet();
                    
                    $count = 0;
                    foreach ($sheet->getRowIterator() as $row) {
                        if ($count >= 100) break;
                        
                        $cellIterator = $row->getCellIterator();
                        $cellIterator->setIterateOnlyExistingCells(false);
                        
                        $rowData = [];
                        foreach ($cellIterator as $cell) {
                            $rowData[] = $cell->getValue();
                        }
                        $rows[] = $rowData;
                        $count++;
                    }
                } else {
                    return response()->json([
                        'response' => "⚠️ **PhpSpreadsheet Package Missing**: To support `.{$extension}` Excel spreadsheets, please make sure the composer packages are fully installed, or convert the file to a standard `.csv` file format.",
                    ]);
                }
            }

            if (empty($rows)) {
                return response()->json([
                    'response' => "The uploaded spreadsheet appears to be empty.",
                ]);
            }

            // Convert spreadsheet rows to a simplified CSV-like text representation for the AI
            $textData = '';
            foreach ($rows as $index => $row) {
                // Sanitize row cells for prompt formatting
                $cleanRow = array_map(function($cell) {
                    return str_replace(['"', "\n", "\r"], ['', ' ', ' '], (string)$cell);
                }, $row);
                
                $textData .= implode(', ', $cleanRow) . "\n";
            }

            // Construct Prompt
            $prompt = "You are analyzing the uploaded spreadsheet file named '{$fileName}'.\n" .
                      "USER INSTRUCTION: {$instruction}\n\n" .
                      "SPREADSHEET CONTENT SNAPSHOT (First " . count($rows) . " rows):\n" .
                      $textData;

            $systemInstruction = "You are a professional inventory spreadsheet auditor. Your job is to analyze, summarize, and find insights or issues (like duplicate SKUs, invalid values, empty rows) from the spreadsheet data provided by the user. Answer in a highly organized, bulleted or table format. Keep your analysis concise.";

            $response = $this->gemini->generateResponse($prompt, $systemInstruction, []);

            return response()->json([
                'fileName' => $fileName,
                'rowCount' => count($rows),
                'response' => $response,
            ]);

        } catch (\Exception $e) {
            Log::error('Spreadsheet analysis error', ['message' => $e->getMessage()]);
            return response()->json([
                'response' => "An error occurred while parsing the spreadsheet: " . $e->getMessage(),
            ], 500);
        }
    }
}
