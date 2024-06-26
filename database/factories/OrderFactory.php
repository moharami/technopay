<?php

namespace Database\Factories;

use App\Enums\OrderStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'amount' => $this->faker->randomFloat(2, 10, 1000),
            'status' => $this->faker->randomElement(OrderStatus::class),
        ];
    }


    /**
     * Indicate that the order has a specific status.
     *
     * @param  string $status
     * @return self
     */
    public function pending(): self
    {
        return $this->state([
            'status' => OrderStatus::PENDING,
        ]);
    }

    public function amount($value): self
    {
        return $this->state([
            'amount' => $value,
        ]);
    }

    public function for_user($user_id): self
    {
        return $this->state([
            'user_id' => $user_id,
        ]);
    }
}
