<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SaleSource>
 */
class SaleSourceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $names = [
            'Zillow',
            'Redfin',
            'Realtor.com',
            'Trulia',
            'MLS',
            'Facebook',
            'Instagram',
            'Referral',
            'Sign',
            'Open House',
            'Yard Sign',
            'Google',
            'Bing',
            'Yahoo',
            'Other',
        ];

        return [
            'name' => $this->faker->randomElement($names),
        ];
    }
}
