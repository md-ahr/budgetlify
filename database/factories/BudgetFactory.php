<?php

namespace Database\Factories;

use App\Models\Budget;
use App\Models\User;
use App\Support\FinanceCategories;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Budget>
 */
class BudgetFactory extends Factory
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
            'name' => fake()->words(2, true),
            'category' => fake()->randomElement(FinanceCategories::ALL),
            'monthly_limit' => (string) fake()->randomFloat(2, 100, 2000),
        ];
    }
}
