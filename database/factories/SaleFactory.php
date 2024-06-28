<?php

namespace Database\Factories;

use App\Models\Sale;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Sale>
 */
class SaleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Sale::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $askingPrice = $this->faker->randomFloat(2, 250000, 2000000);
        $soldPrice = $this->faker->randomFloat(2, 0.95, 1.05) * $askingPrice;
        // get list of users
        $users = \App\Models\User::pluck('id')->toArray();
        return [
            'user_id' => $this->faker->randomElement($users),
            'client_name' => $this->faker->name,
            'client_email' => $this->faker->email,
            'address' =>  $this->faker->buildingNumber . ' ' . $this->faker->streetName . ' ' . $this->faker->streetSuffix,
            'city' => $this->faker->city,
            'state' => $this->faker->stateAbbr,
            'zip' => $this->faker->postcode,
            'asking_price' => $askingPrice,
            'sold_price' => $soldPrice,
            'percentage' => $this->faker->randomElement([2.00, 2.5, 3.00]),
            'agent_split_percentage' => $this->faker->randomElement([50, 60, 70]),
            'pending_date' => $this->faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
            'closing_date' => $this->faker->dateTimeBetween('now', '+1 year')->format('Y-m-d'),
            'status' => $this->faker->randomElement(['Pending', 'Closed', 'Paid', 'Cancelled']),
            'buyer' => $this->faker->boolean(90),
            'notes' => $this->faker->paragraph
        ];
    }
}