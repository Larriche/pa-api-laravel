<?php

namespace Database\Factories;

use DB;
use App\Models\User;
use App\Domain\ExpensesTracker\Models\IncomeSource;
use Illuminate\Database\Eloquent\Factories\Factory;

class IncomeSourceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = IncomeSource::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'color' => '#' . bin2hex(openssl_random_pseudo_bytes(3)),
            'user_id' => User::factory()->create()->id
        ];
    }
}
