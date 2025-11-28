<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Bus;
use App\Jobs\ProcessOrderJob;

class OrderJobDispatchedTest extends TestCase
{
    use RefreshDatabase;

    #[\PHPUnit\Framework\Attributes\Test]
    public function job_processorderjob_e_despachado_quando_um_pedido_e_criado()
    {
        Bus::fake();

        $response = $this->postJson('/api/orders', [
            'customer_name' => 'Maria Teste',
            'items' => [
                ['product_name' => 'Produto X', 'price' => 120, 'quantity' => 1],
                ['product_name' => 'Produto Y', 'price' => 60, 'quantity' => 2],
            ]
        ]);

        $response->assertStatus(201);

        Bus::assertDispatched(ProcessOrderJob::class, function ($job) use ($response) {
            return $job->orderId === $response->json('id');
        });
    }
}
