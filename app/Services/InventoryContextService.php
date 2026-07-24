<?php

namespace App\Services;

use App\Models\Product;
use App\Models\InwardItemCode;
use App\Models\DispatchItemCode;
use App\Models\Brand;
use App\Models\PortalVendor;

class InventoryContextService
{
    /**
     * Build prompt context text representing real-time database stats.
     */
    public function getInventoryContextText(): string
    {
        // 1. Core Metrics
        $totalProducts = Product::count();
        $totalInward = InwardItemCode::count();
        $totalDispatch = DispatchItemCode::count();
        $activeStock = max(0, $totalInward - $totalDispatch);

        // 2. Per-product breakdown
        $products = Product::with(['brand'])->get();
        $productBreakdowns = [];
        $lowStockProducts = [];

        foreach ($products as $product) {
            $in = InwardItemCode::where('product_id', $product->id)->count();
            $out = DispatchItemCode::where('product_id', $product->id)->count();
            $avail = max(0, $in - $out);

            $info = "- {$product->product_name} (SKU: {$product->sku}, ID: {$product->product_id}): Brand: " . ($product->brand->name ?? 'N/A') . " | Inward: {$in} | Outward: {$out} | Available: {$avail}";
            
            $productBreakdowns[] = $info;

            // Low stock check (less than 5 units left)
            if ($avail < 5) {
                $lowStockProducts[] = "- {$product->product_name} (Available: {$avail} units left, SKU: {$product->sku})";
            }
        }

        // 3. Recent activity
        $recentInward = InwardItemCode::with('product')->orderBy('id', 'desc')->limit(5)->get()->map(function($item) {
            return "- Inward scan: {$item->uid} for Product " . ($item->product->product_name ?? 'N/A') . " at " . $item->created_at->format('Y-m-d H:i');
        })->toArray();

        $recentDispatch = DispatchItemCode::with('product')->orderBy('id', 'desc')->limit(5)->get()->map(function($item) {
            return "- Dispatch scan: {$item->uid} for Product " . ($item->product->product_name ?? 'N/A') . " at " . $item->created_at->format('Y-m-d H:i');
        })->toArray();

        // Assemble instruction context
        $context = "=== REAL-TIME INVENTORY DATABASE SNAPSHOT ===\n";
        $context .= "OVERALL STATS:\n";
        $context .= "- Total Product Types: {$totalProducts}\n";
        $context .= "- Total Inward Scans: {$totalInward} units\n";
        $context .= "- Total Dispatch Scans: {$totalDispatch} units\n";
        $context .= "- Current Active Stock: {$activeStock} units\n\n";

        if (!empty($lowStockProducts)) {
            $context .= "CRITICAL: LOW STOCK WARNINGS (Available < 5 units):\n";
            $context .= implode("\n", $lowStockProducts) . "\n\n";
        } else {
            $context .= "CRITICAL: No low stock warnings. All items have 5+ units.\n\n";
        }

        $context .= "PRODUCTS MASTER LIST & STOCK STATUS:\n";
        $context .= implode("\n", $productBreakdowns) . "\n\n";

        $context .= "RECENT INWARD ACTIVITIES:\n";
        $context .= empty($recentInward) ? "No recent inward logs.\n" : implode("\n", $recentInward) . "\n";
        $context .= "\nRECENT DISPATCH ACTIVITIES:\n";
        $context .= empty($recentDispatch) ? "No recent dispatch logs.\n" : implode("\n", $recentDispatch) . "\n";
        
        $context .= "=== END OF SNAPSHOT ===";

        return $context;
    }

    /**
     * Get system instruction for the AI Copilot.
     */
    public function getSystemInstruction(): string
    {
        return "You are 'Sari', a direct and concise AI assistant embedded inside an Inventory Management System (IMS). " .
               "Your goal is to answer user queries directly about their inventory catalog, stock levels, inward scan history, and dispatches. " .
               "Use the REAL-TIME INVENTORY DATABASE SNAPSHOT provided as context.\n\n" .
               "CRITICAL RULES:\n" .
               "1. Always respond directly. NEVER output your internal thinking process, reasoning steps, or tags like <thinking>.\n" .
               "2. When listing products, stock levels, quantities, or individual item profiles (such as product details, inward/dispatch counts, SKU, and status), you MUST format the data in a standard Markdown Table using pipes (`|`) and hyphens (`|---|---|`), like this:\n" .
               "| Parameter | Value |\n" .
               "|---|---|\n" .
               "| Product Name | [Name] |\n" .
               "| SKU | [SKU] |\n" .
               "| Total Inward | [Count] units |\n" .
               "| Total Dispatched | [Count] units |\n" .
               "| Current Stock | [Count] units |\n" .
               "| Dispatch ID | [UID] |\n" .
               "| Dispatch Date | [Date] |\n" .
               "3. If asked about something not present in the snapshot or inventory domain, politely guide the user back to inventory management.\n" .
               "4. Personality: You are witty, lighthearted, and slightly humorous. Inject playful tech or warehouse-themed humor in your text summaries (e.g. comparing fast moving stock to developers running out on Fridays, or low stock items looking lonely). If asked to tell a joke, tell a funny inventory/database dad joke. If asked about your boss/creator, respond with a playful remark.";
    }
}
