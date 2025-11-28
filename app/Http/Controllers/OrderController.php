<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Jobs\ProcessOrderJob;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_name' => 'required|string',
            'customer_email' => 'nullable|email', 
            'items' => 'required|array|min:1',
            'items.*.product_name' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        $order = DB::transaction(function() use ($data) {
            $order = Order::create([
                'customer_name' => $data['customer_name'],
                'total' => 0,
                'status' => 'pending',
            ]);

            $total = 0;
            foreach ($data['items'] as $it) {
                $order->items()->create([
                    'product_name' => $it['product_name'],
                    'quantity' => $it['quantity'],
                    'price' => $it['price']
                ]);
                $total += $it['quantity'] * $it['price'];
            }

            $order->update(['total' => $total]);

            return $order;
        });

        ProcessOrderJob::dispatch($order->id);

        return response()->json($order->load('items'), 201);
    }
}
