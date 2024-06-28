<?php

namespace App\Services;

class CommissionCalculator
{
    function calculateAgentCommission($sale)
    {
        $soldPrice = (float) $sale['sold_price'];

        // set amount for flat fee commission
        $flatFee = null;

        // If sale has sale.fees == pre-commission fees, sum the fees and subtract from sold price
        foreach ($sale['fees'] as $fee) {
            if ($fee['split_type'] === 1) {
                $soldPrice -= (float) $fee['fee_amount'];
            }

            // If sale has sale.fees == pre-commission fees, sum the fees and subtract from sold price
            if ($fee['split_type'] === 2) {
                $soldPrice += (float) $fee['fee_amount'];
            }

            // if sale has split_type == 8 set flatFee = true
            if ($fee['split_type'] === 8) {
                $flatFee = (float) $fee['fee_amount'];
            }
        }

        // Do commission calculation now for sale.commision_percentage
        // if flatFee is true, set totalCommission to the flat fee amount
        // $totalCommission = ($soldPrice * $sale['percentage']) / 100;
        if ($flatFee != null) {
            $totalCommission = $flatFee;
        } else {
            $totalCommission = ($soldPrice * $sale['percentage']) / 100;
        }

        // If sale has sale.fees == pre-team fees, sum the fees and subtract from total commission
        foreach ($sale['fees'] as $fee) {
            if ($fee['split_type'] === 3) {
                $totalCommission -= (float) $fee['fee_amount'];
            }
        }

        // do referral fee percentage calculation now
        foreach ($sale['fees'] as $fee) {
            if ($fee['split_type'] === 7) {
                $totalCommission -= ($totalCommission * $fee['fee_amount']) / 100;
            }
        }

        // pre-team additions
        foreach ($sale['fees'] as $fee) {
            if ($fee['split_type'] === 4) {
                $totalCommission += (float) $fee['fee_amount'];
            }
        }


        // Do agent_split_percentage calculation now
        $agentCommission = ($totalCommission * $sale['agent_split_percentage']) / 100;

        // Do post-team fees calculation now
        foreach ($sale['fees'] as $fee) {
            if ($fee['split_type'] === 5) {
                $agentCommission -= (float) $fee['fee_amount'];
            }
        }

        // Do post-team additions calculation now
        foreach ($sale['fees'] as $fee) {
            if ($fee['split_type'] === 6) {
                $agentCommission += (float) $fee['fee_amount'];
            }
        }

        // Return agent commission rounded up to the cent with 2 decimals
        return ceil($agentCommission * 100) / 100;
    }
}