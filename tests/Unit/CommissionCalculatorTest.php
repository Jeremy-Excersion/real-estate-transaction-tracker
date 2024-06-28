<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Services\CommissionCalculator; // Adjust the namespace to where your class is located

class CommissionCalculatorTest extends TestCase
{
    public function testCalculateAgentCommissionWithVariousFees()
    {
        $calculator = new CommissionCalculator();

        $sale = [
            'sold_price' => 500000,
            'percentage' => 3,
            'agent_split_percentage' => 50,
            'fees' => [
                ['split_type' => 1, 'fee_amount' => 10000], // Seller Concessions 500000 - 10000 = 490000
                ['split_type' => 2, 'fee_amount' => 5000],  // Seller Additions 490000 + 5000 = 495000
                ['split_type' => 3, 'fee_amount' => 2000],  // Pre-Team Fees 495000 * 3% = 14850 - 2000 = 12850
                ['split_type' => 4, 'fee_amount' => 3000],  // Pre-Team Additions 12850 + 3000 = 15850
                ['split_type' => 5, 'fee_amount' => 1000],  // Post-Team Fees 15850 * 50% = 7925 - 1000 = 6925
                ['split_type' => 6, 'fee_amount' => 500]    // Post-Team Additions 6925 + 500 = 7425
            ]
        ];

        $agentCommission = $calculator->calculateAgentCommission($sale);
        $this->assertEquals('7425.00', $agentCommission);
    }

    public function testCalculateAgentCommissionWithNoFees()
    {
        $calculator = new CommissionCalculator();

        $sale = [
            'sold_price' => 500000,
            'percentage' => 3,
            'agent_split_percentage' => 50,
            'fees' => []
        ];

        $agentCommission = $calculator->calculateAgentCommission($sale);
        $this->assertEquals('7500.00', $agentCommission);
    }

    public function testCalculateAgentCommissionWithNegativeFees()
    {
        $calculator = new CommissionCalculator();

        $sale = [
            'sold_price' => 500000,
            'percentage' => 2,
            'agent_split_percentage' => 50,
            'fees' => [
                ['split_type' => 1, 'fee_amount' => -10000], // Seller Concessions 500000 + 10000 = 510000
                ['split_type' => 2, 'fee_amount' => -5000]   // Seller Additions 510000 - 5000 = 505000
            ]
        ];

        $agentCommission = $calculator->calculateAgentCommission($sale);
        $this->assertEquals('5050.00', $agentCommission);
    }

    public function testCalculateAgentCommissionWithEdgeCaseFees()
    {
        $calculator = new CommissionCalculator();

        $sale = [
            'sold_price' => 500000,
            'percentage' => 3,
            'agent_split_percentage' => 0,
            'fees' => [
                ['split_type' => 1, 'fee_amount' => 10000], // Seller Concessions
                ['split_type' => 2, 'fee_amount' => 5000],  // Seller Additions
                ['split_type' => 3, 'fee_amount' => 2000],  // Pre-Team Fees
                ['split_type' => 4, 'fee_amount' => 3000],  // Pre-Team Additions
                ['split_type' => 5, 'fee_amount' => 1000],  // Post-Team Fees
                ['split_type' => 6, 'fee_amount' => 500]    // Post-Team Additions
            ]
        ];

        $agentCommission = $calculator->calculateAgentCommission($sale);
        $this->assertEquals('-500.00', $agentCommission);
    }

    // Test flat fee commission
    public function testCalculateAgentCommissionWithFlatFee()
    {
        $calculator = new CommissionCalculator();

        $sale = [
            'sold_price' => 500000,
            'percentage' => 3,
            'agent_split_percentage' => 50,
            'fees' => [
                ['split_type' => 8, 'fee_amount' => 5000], // Flat Fee Commission
            ]
        ];

        $agentCommission = $calculator->calculateAgentCommission($sale);
        $this->assertEquals('2500.00', $agentCommission);
    }

    // test flat fee commission with a tc fee of $175
    public function testCalculateAgentCommissionWithFlatFeeAndTcFee()
    {
        $calculator = new CommissionCalculator();

        $sale = [
            'sold_price' => 500000,
            'percentage' => 3,
            'agent_split_percentage' => 50,
            'fees' => [
                ['split_type' => 8, 'fee_amount' => 5000], // Flat Fee Commission
                ['split_type' => 5, 'fee_amount' => 175],  // Post-Team Fees
            ]
        ];

        $agentCommission = $calculator->calculateAgentCommission($sale);
        $this->assertEquals('2325.00', $agentCommission);
    }

    // Add more tests for different scenarios
}
