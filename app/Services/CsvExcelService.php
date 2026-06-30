<?php

namespace App\Services;

use Symfony\Component\HttpFoundation\StreamedResponse;

class CsvExcelService
{
    /**
     * Parse an uploaded Excel or CSV file into an array of associative arrays.
     *
     * @param string $filePath
     * @param array $expectedHeaders Optional list of expected headers to validate
     * @return array
     * @throws \Exception
     */
    public static function import(string $filePath, array $expectedHeaders = []): array
    {
        if (!file_exists($filePath) || !is_readable($filePath)) {
            throw new \Exception("File not found or is not readable.");
        }

        // Read first 4 bytes to detect file signature (ZIP files start with PK\x03\x04)
        $handle = fopen($filePath, 'r');
        if ($handle === false) {
            throw new \Exception("Failed to open the uploaded file.");
        }
        $magicBytes = fread($handle, 4);
        fclose($handle);

        if ($magicBytes === "PK\x03\x04") {
            $rows = self::parseXlsx($filePath);
        } else {
            $rows = self::parseCsv($filePath);
        }

        if (empty($rows)) {
            throw new \Exception("The file appears to be empty.");
        }

        $headers = array_shift($rows);

        // Clean UTF-8 BOM if present on the first header field
        if (isset($headers[0]) && substr($headers[0], 0, 3) === "\xEF\xBB\xBF") {
            $headers[0] = substr($headers[0], 3);
        }

        // Trim whitespace from headers
        $headers = array_map('trim', $headers);

        // Optional validation of headers
        if (!empty($expectedHeaders)) {
            $missing = [];
            foreach ($expectedHeaders as $expected) {
                if (!in_array($expected, $headers, true)) {
                    $missing[] = $expected;
                }
            }
            if (!empty($missing)) {
                throw new \Exception("Missing required column headers: " . implode(', ', $missing));
            }
        }

        $records = [];
        $headerCount = count($headers);

        foreach ($rows as $row) {
            // Skip empty rows
            if (empty($row) || (count($row) === 1 && $row[0] === null)) {
                continue;
            }

            // Pad or trim row to match headers count
            $row = array_pad($row, $headerCount, '');
            if (count($row) > $headerCount) {
                $row = array_slice($row, 0, $headerCount);
            }

            // Map row values to headers
            $record = array_combine($headers, array_map('trim', $row));
            $records[] = $record;
        }

        return $records;
    }

    /**
     * Parse a CSV file.
     */
    private static function parseCsv(string $filePath): array
    {
        $handle = fopen($filePath, 'r');
        if ($handle === false) {
            throw new \Exception("Failed to open the uploaded CSV file.");
        }

        $rows = [];
        while (($row = fgetcsv($handle)) !== false) {
            $rows[] = $row;
        }
        fclose($handle);

        return $rows;
    }

    /**
     * Parse an XLSX file natively using PHP's ZipArchive and SimpleXML.
     */
    private static function parseXlsx(string $filePath): array
    {
        if (!class_exists('ZipArchive')) {
            throw new \Exception("ZipArchive PHP extension is not enabled on this server.");
        }

        $zip = new \ZipArchive();
        if ($zip->open($filePath) !== true) {
            throw new \Exception("Unable to open the Excel workbook.");
        }

        // 1. Read shared strings
        $sharedStrings = [];
        $sharedStringsData = $zip->getFromName('xl/sharedStrings.xml');
        if ($sharedStringsData) {
            $xml = simplexml_load_string($sharedStringsData);
            if ($xml) {
                foreach ($xml->si as $si) {
                    if (isset($si->t)) {
                        $sharedStrings[] = (string) $si->t;
                    } elseif (isset($si->r)) {
                        $text = '';
                        foreach ($si->r as $r) {
                            $text .= (string) $r->t;
                        }
                        $sharedStrings[] = $text;
                    } else {
                        $sharedStrings[] = '';
                    }
                }
            }
        }

        // 2. Read sheet1.xml
        $sheetData = $zip->getFromName('xl/worksheets/sheet1.xml');
        if (!$sheetData) {
            $zip->close();
            throw new \Exception("Sheet1 not found in Excel workbook.");
        }

        $xml = simplexml_load_string($sheetData);
        if (!$xml) {
            $zip->close();
            throw new \Exception("Failed to parse worksheet XML.");
        }

        $rows = [];
        $colLetterToIndex = function ($ref) {
            $letter = preg_replace('/[0-9]/', '', $ref);
            $index = 0;
            $len = strlen($letter);
            for ($i = 0; $i < $len; $i++) {
                $index = $index * 26 + (ord($letter[$i]) - 64);
            }
            return $index - 1;
        };

        foreach ($xml->sheetData->row as $rowNode) {
            $rowNum = (int) $rowNode['r'];
            $rowData = [];
            
            foreach ($rowNode->c as $cellNode) {
                $ref = (string) $cellNode['r'];
                $colIndex = $colLetterToIndex($ref);
                
                $type = (string) $cellNode['t'];
                $val = '';
                
                if (isset($cellNode->v)) {
                    $val = (string) $cellNode->v;
                }
                
                if ($type === 's') {
                    $val = $sharedStrings[(int) $val] ?? '';
                } elseif ($type === 'inlineStr' && isset($cellNode->is->t)) {
                    $val = (string) $cellNode->is->t;
                } elseif ($type === 'b') {
                    $val = ($val === '1');
                }
                
                $rowData[$colIndex] = trim($val);
            }
            
            if (!empty($rowData)) {
                $maxIndex = max(array_keys($rowData));
                for ($i = 0; $i <= $maxIndex; $i++) {
                    if (!isset($rowData[$i])) {
                        $rowData[$i] = '';
                    }
                }
                ksort($rowData);
                $rows[$rowNum] = $rowData;
            }
        }

        $zip->close();
        ksort($rows);
        return array_values($rows);
    }

    /**
     * Stream an array of data as a downloadable Excel-compatible CSV.
     *
     * @param array $headers Column headers
     * @param array $data Multi-dimensional array or collection of rows (each row mapped key-to-value or numerical)
     * @param string $filename Download filename
     * @return StreamedResponse
     */
    public static function export(array $headers, array $data, string $filename = 'export.csv'): StreamedResponse
    {
        $response = new StreamedResponse(function () use ($headers, $data) {
            $handle = fopen('php://output', 'w');

            // Add UTF-8 BOM so Excel opens it with correct encoding
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            // Put header row
            fputcsv($handle, $headers);

            // Put data rows
            foreach ($data as $row) {
                // Ensure row is simple array
                if (is_object($row)) {
                    $row = (array) $row;
                }
                
                fputcsv($handle, array_values($row));
            }

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv; charset=UTF-8');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '"');
        $response->headers->set('Cache-Control', 'no-cache, no-store, must-revalidate');
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Expires', '0');

        return $response;
    }
}
