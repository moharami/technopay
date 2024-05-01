<?php

namespace Tests\Feature;

use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    const URI = "api/backoffice/orders";

    /**
     * A basic feature test example.
     */
    public function test_it_can_get_data_without_filter(): void
    {
        Order::factory(100)->create();
        $response = $this->get(self::URI);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'amount',
                        'status',
                    ]
                ]
            ]);
    }
}
