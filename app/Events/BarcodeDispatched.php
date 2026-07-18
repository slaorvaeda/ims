<?php

namespace App\Events;

use App\Models\InwardItemCode;
use App\Models\DispatchItemCode;
use App\Models\Sale;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BarcodeDispatched implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $uid;
    public $productName;
    public $updatedBy;
    public $dispatchedAt;
    public $stats;

    /**
     * Create a new event instance.
     */
    public function __construct($inwardItem, $uid, $updatedBy)
    {
        $this->uid = $uid;
        $this->productName = $inwardItem->product->product_name ?? 'N/A';
        $this->updatedBy = $updatedBy;
        $this->dispatchedAt = now()->toDateTimeString();
        
        // Recalculate stats for live dashboard updates
        $this->stats = [
            'total_dispatch' => DispatchItemCode::count(),
            'active_stock' => InwardItemCode::count() - DispatchItemCode::count(),
            'total_sales' => Sale::count(),
        ];
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('dispatches'),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'barcode.dispatched';
    }
}
