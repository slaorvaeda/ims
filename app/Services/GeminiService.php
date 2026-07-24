<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    protected ?string $apiKey;
    protected string $model = 'gemini-2.5-flash';

    public function __construct()
    {
        $this->apiKey = config('services.gemini.key');
    }

    /**
     * Generate response from prompt and history.
     */
    public function generateResponse(string $prompt, string $systemInstruction = '', array $history = []): string
    {
        if (empty($this->apiKey)) {
            return $this->getMockResponse($prompt);
        }

        try {
            $url = "https://generativelanguage.googleapis.com/v1beta/models/{$this->model}:generateContent?key={$this->apiKey}";

            // Structure chat history and context
            $contents = [];
            
            // Format history
            foreach ($history as $msg) {
                // Ignore system messages from history, Gemini API expects role: user or model
                if ($msg['role'] === 'system') {
                    continue;
                }
                
                $contents[] = [
                    'role' => $msg['role'] === 'assistant' ? 'model' : 'user',
                    'parts' => [['text' => $msg['content']]],
                ];
            }

            // Append current prompt
            $contents[] = [
                'role' => 'user',
                'parts' => [['text' => $prompt]],
            ];

            $payload = [
                'contents' => $contents,
            ];

            if (!empty($systemInstruction)) {
                $payload['systemInstruction'] = [
                    'parts' => [
                        ['text' => $systemInstruction]
                    ]
                ];
            }

            // Simple generation parameters
            $payload['generationConfig'] = [
                'maxOutputTokens' => 800,
                'temperature' => 0.2,
            ];

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->timeout(30)->post($url, $payload);

            if ($response->successful()) {
                $data = $response->json();
                
                // Extract output text
                if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
                    return $data['candidates'][0]['content']['parts'][0]['text'];
                }
            }

            Log::error('Gemini API request failed', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            return "I apologize, but I encountered an error communicating with the AI service. Please verify your Gemini API key in the `.env` file.";

        } catch (\Exception $e) {
            Log::error('Gemini API exception', ['message' => $e->getMessage()]);
            return "I apologize, but an unexpected error occurred: " . $e->getMessage();
        }
    }

    /**
     * A high-fidelity local mock simulator when no API key is specified.
     */
    protected function getMockResponse(string $prompt): string
    {
        $promptLower = strtolower($prompt);

        if (str_contains($promptLower, 'low stock') || str_contains($promptLower, 'stock count') || str_contains($promptLower, 'available') || str_contains($promptLower, 'reorder')) {
            return "📋 **Low Stock & Availability Alert (Demo Mode)**\n\nBased on your database snapshot:\n- **Kenstar Smart Fan**: 3 units remaining (Reorder threshold is 5).\n- **Zigma Smart Bulb**: 4 units remaining.\n- **Indivolt Inverter**: 0 units left (Out of Stock).\n\n*Configure your `GEMINI_API_KEY` in the `.env` file to chat with live database data!*";
        }

        if (str_contains($promptLower, 'dispatch') || str_contains($promptLower, 'recent scan') || str_contains($promptLower, 'inward') || str_contains($promptLower, 'activity')) {
            return "🔄 **Recent Activity Scan Summary (Demo Mode)**\n\nIn the last 24 hours:\n- **Inward Scans**: 142 units successfully registered.\n- **Dispatch Scans**: 94 units scanned and shipped.\n- **Fulfillment Goal**: 84.2% completed.\n\n*Configure your `GEMINI_API_KEY` in the `.env` file to query active scan logs!*";
        }

        return "👋 **Hello! I am your IMS AI Copilot.**\n\nI can help you analyze your inventory statistics, look up low stock levels, track dispatches, and audit your Excel/CSV spreadsheets.\n\n⚠️ **API Key Required**: Currently running in *Demo Mode*. To unlock real-time database queries and advanced spreadsheet auditing, please add your Google Gemini API key to your `.env` file:\n```env\nGEMINI_API_KEY=your_gemini_api_key_here\n```\n\n**Try asking me**: \n- *\"Which products are low in stock?\"*\n- *\"Show recent inward activity\"*";
    }
}
