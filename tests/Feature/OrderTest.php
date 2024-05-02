<?php

namespace Tests\Feature;

use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;
    protected function setUp(): void
    {
        parent::setUp();
        DB::enableQueryLog();
    }

    protected function tearDown(): void
    {
        DB::disableQueryLog();
        parent::tearDown();
    }

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
        Order::factory(30)->create();
        $response = $this->get(self::URI . '?status=invalidstatus');
        $response_data = $response->json()['data'];
        $this->assertEquals(count($response_data), 0);
        $response->assertStatus(Response::HTTP_OK);
    }

    /**
     * Test retrieving data without filters.
     */
    public function test_it_can_filter_base_on_status(): void
    {
        // Arrange
        Order::factory()->pending()->create();
        $status = 'pending';

        // Act
        $response = $this->get(self::URI . '?status=' . $status);

        $executedQueries = DB::getQueryLog();
        $query = $executedQueries[1]['query'];
        $binding = $executedQueries[1]['bindings'][0];

        $expected_query = "select * from `orders` where `status` = ?";

        // Assert
        $this->assertEquals($expected_query, $query);
        $this->assertEquals($binding, $status);
        $response->assertStatus(Response::HTTP_OK);
    }

    /**
     * Test retrieving data without filters.
     */
    public function test_it_can_filter_base_on_amount(): void
    {
        // Arrange
        $min_amount = 100;
        $max_amount = 1000;
        Order::factory()->amount($min_amount)->create();

        // Act
        $response = $this->get(self::URI . '?min_amount=' . $min_amount . '&max_amount=' . $max_amount);

        $executedQueries = DB::getQueryLog();
        $query = $executedQueries[1]['query'];
        $binding_min = $executedQueries[1]['bindings'][0];
        $binding_max = $executedQueries[1]['bindings'][1];

        $expected_query = "select * from `orders` where `amount` > ? and `amount` < ?";

        // Assert
        $this->assertEquals($expected_query, $query);
        $this->assertEquals($binding_min, $min_amount);
        $this->assertEquals($binding_max, $max_amount);
        $response->assertStatus(Response::HTTP_OK);
    }

    /**
     * Test retrieving data without filters.
     */
    public function test_it_can_filter_base_on_amount_when_min_is_null(): void
    {
        // Arrange
        $max_amount = 1000;
        Order::factory()->amount($max_amount)->create();

        // Act
        $response = $this->get(self::URI . '?min_amount=' . '&max_amount=' . $max_amount);

        $executedQueries = DB::getQueryLog();
        $query = $executedQueries[1]['query'];
        $binding_max = $executedQueries[1]['bindings'][0];

        $expected_query = "select * from `orders` where `amount` < ?";

        // Assert
        $this->assertEquals($expected_query, $query);
        $this->assertEquals($binding_max, $max_amount);

        $response->assertStatus(Response::HTTP_OK);
    }

    /**
     * Test retrieving data without filters.
     */
    public function test_it_can_filter_base_on_amount_when_max_is_null(): void
    {
        // Arrange
        $min_amount = 1000;
        Order::factory()->amount($min_amount)->create();

        // Act
        $response = $this->get(self::URI . '?min_amount=' . $min_amount . '&max_amount=');

        $executedQueries = DB::getQueryLog();
        $query = $executedQueries[1]['query'];
        $binding_min = $executedQueries[1]['bindings'][0];

        $expected_query = "select * from `orders` where `amount` > ?";

        // Assert
        $this->assertEquals($expected_query, $query);
        $this->assertEquals($binding_min, $min_amount);

        $response->assertStatus(Response::HTTP_OK);
    }

    /**
     * Test retrieving data without filters.
     */
    public function test_it_can_filter_base_on_amount_when_min_and_max_is_null(): void
    {
        // Arrange
        $min_amount = 1000;
        Order::factory()->amount($min_amount)->create();

        // Act
        $response = $this->get(self::URI . '?min_amount=' . '&max_amount=');

        $executedQueries = DB::getQueryLog();
        $query = $executedQueries[1]['query'];

        $expected_query = "select * from `orders`";

        // Assert
        $this->assertEquals($expected_query, $query);
        $response->assertStatus(Response::HTTP_OK);
    }
}
