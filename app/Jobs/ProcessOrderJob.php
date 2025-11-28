<?php

namespace App\Jobs;

use App\Models\Order;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessOrderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $orderId;

    public function __construct(int $orderId)
    {
        $this->orderId = $orderId;
    }

    public function handle()
    {
        $order = Order::find($this->orderId);

        if (!$order) {
            return;
        }

        try {
            // Simula cálculo de frete
            sleep(5);
            $order->update(['status' => 'processing']);

            // Simula envio de notificação
            sleep(2);
            $order->update(['status' => 'completed']);
        } catch (Exception $e) {
            $order->update(['status' => 'failed']);
        }
    }
}
