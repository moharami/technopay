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
     * Test retrieving data without filters.
     */
    public function test_it_can_get_data_without_filter_and_check_output_strcture(): void
    {
        Order::factory(3)->create();
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

    /**
     * Test retrieving data without filters.
     */
    public function test_it_can_filter_base_on_status_successful(): void
    {
        Order::factory(3)->create();
        $response = $this->get(self::URI . '?status=pending');

        $response->assertStatus(Response::HTTP_OK);
    }


    /**
     * Test retrieving data without filters.
     */
    public function test_it_can_not_filter_base_on_invalid_status(): void
    {
        Order::factory(3)->create();
        $response = $this->get(self::URI . '?status=invalidstatus');

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
