<?php

namespace Database\Factories;

use App\Models\SaleFee;
use Illuminate\Database\Eloquent\Factories\Factory;

class SaleFeeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SaleFee::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        // Office dues are $225, home warranty is $500, inspection fee is $350
        $fees = [
            'Office Dues' => 225,
            'Home Warranty' => 500,
            'Inspection Fee' => 350,
            'TC Fee' => 250,
            'Seller Concession' => 1000,
        ];

        // Get random fee
        $fee_name = $this->faker->randomElement(array_keys($fees));

        // Define split types based on fee names
        $split_types = [
            'Office Dues' => 'Post-Team Fees',
            'Home Warranty' => 'Pre-Team Fees',
            'Inspection Fee' => 'Pre-Team Fees',
            'TC Fee' => 'Post-Team Fees',
            'Seller Concession' => 'Seller Concessions',
        ];

        return [
            'fee_name' => $fee_name,
            'fee_amount' => $fees[$fee_name],
            'split_type' => array_search($split_types[$fee_name], SaleFee::SPLIT_TYPES),
        ];
    }
}
