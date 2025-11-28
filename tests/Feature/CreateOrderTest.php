<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use App\Jobs\ProcessOrderJob;

class CreateOrderTest extends TestCase
{
    use RefreshDatabase;

    #[\PHPUnit\Framework\Attributes\Test]
    public function create_order_returns_201_and_persists()
    {
        $payload = [
            'customer_name' => 'Fulano',
            'items' => [
                ['product_name' => 'Produto A', 'quantity' => 2, 'price' => 10.00],
                ['product_name' => 'Produto B', 'quantity' => 1, 'price' => 5.50],
            ]
        ];

        $response = $this->postJson('/api/orders', $payload);

        $response->assertStatus(201);

        $this->assertDatabaseHas('orders', [
            'customer_name' => 'Fulano',
            'status' => 'completed'
        ]);

        $this->assertDatabaseCount('order_items', 2);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function job_is_dispatched_on_create()
    {
        Queue::fake();

        $payload = [
            'customer_name' => 'Fulano',
            'items' => [
                ['product_name' => 'Produto A', 'quantity' => 1, 'price' => 10.00],
            ]
        ];

        $this->postJson('/api/orders', $payload)->assertStatus(201);

        Queue::assertPushed(ProcessOrderJob::class);
    }
}

