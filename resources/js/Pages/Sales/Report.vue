<template>
  <AuthenticatedLayout>
    <div class="report bg-white text-gray-900">
      <div class="flex items-center">
        <h1 class="text-3xl font-bold my-8">Sales Report</h1>
        <button type="button" @click="handlePrint" id="print-button">
          <PrinterIcon class="h-[25px] ml-2" />
        </button>
      </div>

      <div class="agent-info mb-6">
        <p>
          <strong>Agent Name:</strong>
          {{ sale.user.name }}
        </p>
        <p>
          <strong>Client Name:</strong>
          {{ sale.client_name }}
        </p>
        <p>
          <strong>Address:</strong>
          {{ sale.address }}, {{ sale.city }}, {{ sale.state }} {{ sale.zip }}
        </p>
        <p>
          <strong>Sold Price:</strong>
          ${{ sale.sold_price }}
        </p>
        <p>
          <strong>Asking Price:</strong>
          ${{ sale.asking_price }}
        </p>
        <p>
          <strong>Percentage:</strong>
          {{ sale.percentage }}%
        </p>
        <p>
          <strong>Agent Split Percentage:</strong>
          {{ sale.agent_split_percentage }}%
        </p>
        <p>
          <strong>Pending Date:</strong>
          {{ new Date(sale.pending_date).toLocaleDateString() }}
        </p>
        <p>
          <strong>Closing Date:</strong>
          {{ new Date(sale.closing_date).toLocaleDateString() }}
        </p>
        <p>
          <strong>Status:</strong>
          {{ sale.status }}
        </p>
      </div>

      <div class="fees-info mb-6">
        <h2 class="text-xl font-semibold mb-2">Commission Calculation Breakdown</h2>
        <table class="w-full border-collapse">
          <thead>
            <tr>
              <th class="border-b py-2 text-left">Step</th>
              <th class="border-b py-2 text-right">Amount</th>
              <th class="border-b py-2 text-right">Commission State</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td class="border-b py-2">Sold Price</td>
              <td class="border-b py-2 text-right">
                ${{ parseFloat(sale.sold_price).toFixed(2) }}
              </td>
              <td class="border-b py-2 text-right">-</td>
            </tr>
            <tr v-for="(step, index) in breakdown" :key="index">
              <td class="border-b py-2">{{ step.description }}</td>
              <td class="border-b py-2 text-right">
                {{ step.amount > 0 ? '+' : '-' }}${{ Math.abs(step.amount).toFixed(2) }}
              </td>
              <td class="border-b py-2 text-right">${{ step.commissionState }}</td>
            </tr>
            <tr>
              <td class="border-b py-2 font-bold">Total Commission</td>
              <td class="border-b py-2 text-right font-bold">
                ${{ commissionStates[commissionStates.length - 1] }}
              </td>
              <td class="border-b py-2 text-right">-</td>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="sources-info mb-6">
        <h2 class="text-xl font-semibold mb-2">Sources</h2>
        <ul class="list-disc pl-5">
          <li v-for="source in sale.sources" :key="source.id">
            {{ source.name }}
          </li>
        </ul>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref, watchEffect } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { PrinterIcon } from '@heroicons/vue/20/solid';

const props = defineProps({
  sale: Object,
});

const commissionStates = ref([]);
const breakdown = ref([]);

const handlePrint = () => {
  window.print();
};

class CommissionCalculator {
  calculateInitialCommission(sale) {
    let soldPrice = parseFloat(sale.sold_price);

    // Apply pre-commission fees (split_type 1)
    sale.fees.forEach((fee) => {
      if (fee.split_type === 1) {
        soldPrice -= parseFloat(fee.fee_amount);
      }
    });

    return soldPrice;
  }

  calculateTotalCommission(soldPrice, percentage) {
    return (soldPrice * percentage) / 100;
  }

  applyReferralFees(commission, fees) {
    fees.forEach((fee) => {
      if (fee.split_type === 7) {
        commission -= (commission * parseFloat(fee.fee_amount)) / 100;
      }
    });
    return commission;
  }

  applyTeamFees(commission, fees) {
    fees.forEach((fee) => {
      if (fee.split_type === 3) {
        commission -= parseFloat(fee.fee_amount);
      }
    });
    return commission;
  }

  applyTeamAdditions(commission, fees) {
    fees.forEach((fee) => {
      if (fee.split_type === 4) {
        commission += parseFloat(fee.fee_amount);
      }
    });
    return commission;
  }

  applyPostTeamFees(commission, fees) {
    fees.forEach((fee) => {
      if (fee.split_type === 5) {
        commission -= parseFloat(fee.fee_amount);
      }
    });
    return commission;
  }

  applyPostTeamAdditions(commission, fees) {
    fees.forEach((fee) => {
      if (fee.split_type === 6) {
        commission += parseFloat(fee.fee_amount);
      }
    });
    return commission;
  }
}

// Calculate commission states dynamically
const calculateCommissionStates = () => {
  const commissionCalculator = new CommissionCalculator();
  let currentSoldPrice = parseFloat(props.sale.sold_price);
  let commissionState = currentSoldPrice;
  commissionStates.value.push(commissionState.toFixed(2));

  // Apply pre-commission fees (split_type 1)
  props.sale.fees.forEach((fee) => {
    if (fee.split_type === 1) {
      currentSoldPrice -= parseFloat(fee.fee_amount);
      breakdown.value.push({
        description: `${fee.fee_name} (${fee.split_type_name})`,
        amount: -parseFloat(fee.fee_amount),
        commissionState: currentSoldPrice.toFixed(2),
      });
    }
  });

  // Calculate total commission
  let totalCommission = commissionCalculator.calculateTotalCommission(
    currentSoldPrice,
    props.sale.percentage
  );
  breakdown.value.push({
    description: `Commission (${props.sale.percentage}%)`,
    amount: totalCommission,
    commissionState: totalCommission.toFixed(2),
  });

  // Apply referral fees (split_type 7)
  let referralFeeAmount = 0;
  props.sale.fees.forEach((fee) => {
    if (fee.split_type === 7) {
      referralFeeAmount += (totalCommission * parseFloat(fee.fee_amount)) / 100;
      breakdown.value.push({
        description: `${fee.fee_name} (${fee.split_type_name})`,
        amount: -(totalCommission * parseFloat(fee.fee_amount)) / 100,
        commissionState: (totalCommission - referralFeeAmount).toFixed(2),
      });
    }
  });
  totalCommission -= referralFeeAmount;

  // Apply pre-team fees (split_type 3)
  let preTeamFeeAmount = 0;
  props.sale.fees.forEach((fee) => {
    if (fee.split_type === 3) {
      preTeamFeeAmount += parseFloat(fee.fee_amount);
      breakdown.value.push({
        description: `${fee.fee_name} (${fee.split_type_name})`,
        amount: -parseFloat(fee.fee_amount),
        commissionState: (totalCommission - preTeamFeeAmount).toFixed(2),
      });
    }
  });
  totalCommission -= preTeamFeeAmount;

  // Apply pre-team additions (split_type 4)
  let preTeamAdditionAmount = 0;
  props.sale.fees.forEach((fee) => {
    if (fee.split_type === 4) {
      preTeamAdditionAmount += parseFloat(fee.fee_amount);
      breakdown.value.push({
        description: `${fee.fee_name} (${fee.split_type_name})`,
        amount: parseFloat(fee.fee_amount),
        commissionState: (totalCommission + preTeamAdditionAmount).toFixed(2),
      });
    }
  });
  totalCommission += preTeamAdditionAmount;

  // Calculate agent split
  let agentCommission =
    -(totalCommission * parseFloat(100 - props.sale.agent_split_percentage)) / 100;
  totalCommission += agentCommission;
  breakdown.value.push({
    description: `Agent Split (${props.sale.agent_split_percentage}%)`,
    amount: agentCommission,
    commissionState: totalCommission.toFixed(2),
  });

  // Apply post-team fees (split_type 5)
  let postTeamFeeAmount = 0;
  props.sale.fees.forEach((fee) => {
    if (fee.split_type === 5) {
      postTeamFeeAmount += parseFloat(fee.fee_amount);
      breakdown.value.push({
        description: `${fee.fee_name} (${fee.split_type_name})`,
        amount: -parseFloat(fee.fee_amount),
        commissionState: (totalCommission - postTeamFeeAmount).toFixed(2),
      });
    }
  });
  totalCommission -= postTeamFeeAmount;

  // Apply post-team additions (split_type 6)
  let postTeamAdditionAmount = 0;
  props.sale.fees.forEach((fee) => {
    if (fee.split_type === 6) {
      postTeamAdditionAmount += parseFloat(fee.fee_amount);
      breakdown.value.push({
        description: `${fee.fee_name} (${fee.split_type_name})`,
        amount: parseFloat(fee.fee_amount),
        commissionState: (totalCommission + postTeamAdditionAmount).toFixed(2),
      });
    }
  });
  totalCommission += postTeamAdditionAmount;

  commissionStates.value.push(totalCommission.toFixed(2));
};

// Initialize commission states
watchEffect(() => {
  breakdown.value = [];
  commissionStates.value = [];
  calculateCommissionStates();
});
</script>

<style scoped>
.report {
  max-width: 800px;
  margin: auto;
}
p {
  margin: 0.5rem 0;
}

@media print {
  h1 {
    font-size: 20px;
  }

  h2 {
    font-size: 16px;
  }

  p,
  table,
  li {
    font-size: 12px;
  }

  #print-button {
    display: none;
  }
}
</style>
