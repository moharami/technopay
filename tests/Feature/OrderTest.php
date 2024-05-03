<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
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
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
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
        $select = $this->getQuerySelect($executedQueries);
        $query = $select['query'];
        $binding = $select['bindings'][0];

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
        $select = $this->getQuerySelect($executedQueries);
        $query = $select['query'];
        $binding_min = $select['bindings'][0];
        $binding_max = $select['bindings'][1];


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
        $select = $this->getQuerySelect($executedQueries);
        $query = $select['query'];
        $binding_max = $select['bindings'][0];
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
        $select = $this->getQuerySelect($executedQueries);
        $query = $select['query'];
        $binding_min = $select['bindings'][0];

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
        $select = $this->getQuerySelect($executedQueries);
        $query = $select['query'];

        $expected_query = "select * from `orders`";

        // Assert
        $this->assertEquals($expected_query, $query);
        $response->assertStatus(Response::HTTP_OK);
    }

    /**
     * Test retrieving data without filters.
     */
    public function test_it_can_filter_base_on_mobile_number(): void
    {
//        DB::disableQueryLog();
        $mobile_number = 1000;

        // Arrange
        $count_order = 3;
        $first_user = User::factory()->create();
        Order::factory($count_order)->for_user($first_user->id)->create();

        $second_user = User::factory()->create();
        Order::factory(10)->for_user($second_user->id)->create();


        DB::enableQueryLog();
        // Act
        $response = $this->get(self::URI . '?mobile_number='. $first_user->mobile_number);


        $executedQueries = DB::getQueryLog();

        $select = $this->getQuerySelect($executedQueries);
        $query = $select['query'];
        $binding = $select['bindings'][0];

        $expected_query = "select * from `orders` where exists (select * from `users` where `orders`.`user_id` = `users`.`id` and `mobile_number` = ?)";

        // Assert
        $this->assertEquals($expected_query, $query);
        $this->assertEquals($binding, $first_user->mobile_number);
        $response->assertStatus(Response::HTTP_OK);
        $this->assertEquals(count($response->json()['data']), $count_order);
    }

    /**
     * Test retrieving data without filters.
     */
    public function test_it_can_filter_base_on_national_code(): void
    {
//        DB::disableQueryLog();
        $mobile_number = 1000;

        // Arrange
        $count_order = 3;
        $first_user = User::factory()->create();
        Order::factory($count_order)->for_user($first_user->id)->create();

        $second_user = User::factory()->create();
        Order::factory(10)->for_user($second_user->id)->create();


        DB::enableQueryLog();
        // Act
        $response = $this->get(self::URI . '?national_code='. $first_user->national_code);


        $executedQueries = DB::getQueryLog();

        $select = $this->getQuerySelect($executedQueries);
        $query = $select['query'];
        $binding = $select['bindings'][0];

        $expected_query = "select * from `orders` where exists (select * from `users` where `orders`.`user_id` = `users`.`id` and `national_code` = ?)";

        // Assert
        $this->assertEquals($expected_query, $query);
        $this->assertEquals($binding, $first_user->national_code);
        $response->assertStatus(Response::HTTP_OK);
        $this->assertEquals(count($response->json()['data']), $count_order);
    }



    public function test_it_can_filter_when_occure_exception(): void
    {
        // Arrange

        $first_user = User::factory()->create();
        Order::factory()->for_user($first_user->id)->create();

        // Act
        $response = $this->get(self::URI . '?fee=1');

        // Assert
        $response->assertStatus(Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    private function getQuerySelect(array $executedQueries)
    {
        $q = array_filter($executedQueries, function($item) {
            return stripos($item['query'], "select") === 0;
        });
        return collect($q)->values()->first();
    }
}
