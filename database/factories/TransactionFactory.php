<?php

namespace Database\Factories;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Transaction>
 */
class TransactionFactory extends Factory
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
            'title' => fake()->words(3, true),
            'amount' => (string) fake()->randomFloat(2, 1, 500),
            'type' => fake()->randomElement(['income', 'expense']),
            'category' => fake()->randomElement(['Bills', 'Dining', 'Groceries', 'Income', 'Salary', 'Shopping', 'Transport']),
            'occurred_on' => fake()->date('Y-m-d'),
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
