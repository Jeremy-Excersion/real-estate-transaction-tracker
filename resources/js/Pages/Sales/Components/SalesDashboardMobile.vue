<script setup>
import { ref } from 'vue';
import { formatPrice } from '@/Utils/FormatPrice';

const props = defineProps({
  sales: Array,
  showFees: Boolean,
  formatPrice: Function,
  handleEdit: Function,
});

const expandedSale = ref(null);

const toggleExpand = (saleId) => {
  expandedSale.value = expandedSale.value === saleId ? null : saleId;
};
</script>

<template>
  <div class="space-y-4">
    <div v-for="sale in sales" :key="sale.id" class="bg-white shadow-md rounded-lg p-4">
      <div class="flex justify-between items-center">
        <div>
          <p class="text-sm font-medium text-gray-900">{{ sale.user?.name }}</p>
          <p class="text-sm text-gray-500">
            {{ sale.status }} - {{ sale.buyer ? 'Buyer' : 'Seller'
            }}{{ sale.referral ? ' (R)' : '' }}
          </p>
        </div>
        <button @click="toggleExpand(sale.id)" class="text-indigo-600 hover:text-indigo-900">
          {{ expandedSale === sale.id ? 'Collapse' : 'Expand' }}
        </button>
      </div>
      <div class="mt-2">
        <p class="text-sm font-medium text-gray-900">{{ sale.client_name }}</p>
        <p class="text-sm text-gray-500">
          {{ sale.address }}, {{ sale.city }}, {{ sale.state }}, {{ sale.zip }}
        </p>
      </div>
      <div v-if="expandedSale === sale.id" class="mt-4 space-y-2">
        <div>
          <p class="text-sm font-medium text-gray-900">Split Percent:</p>
          <p class="text-sm text-gray-500">
            {{ sale.percentage }}% / {{ sale.agent_split_percentage }}%
          </p>
        </div>
        <div>
          <p class="text-sm font-medium text-gray-900">Sold Price / Agent Commission:</p>
          <p class="text-sm text-gray-500">
            ${{ formatPrice(sale.sold_price) }} / ${{ formatPrice(sale.agent_commission) }}
          </p>
        </div>
        <div>
          <p class="text-sm font-medium text-gray-900">Pending Date / Close Date:</p>
          <p class="text-sm text-gray-500">{{ sale.pending_date }} / {{ sale.closing_date }}</p>
        </div>
        <div v-if="showFees">
          <p class="text-sm font-medium text-gray-900">Fees:</p>
          <ul class="list-disc list-inside text-sm text-gray-500">
            <li v-for="fee in sale.fees" :key="fee.id">
              <strong>{{ fee.fee_name }}:</strong>
              <span v-if="fee.split_type === 7">{{ fee.fee_amount }}%</span>
              <span v-else>${{ fee.fee_amount }}</span>
              ({{ fee.split_type_name }})
            </li>
          </ul>
        </div>
      </div>
      <div class="mt-4 flex gap-4 text-sm font-medium text-indigo-600">
        <a
          v-if="$page.props.auth.roles.includes('admin')"
          :href="route('sales.edit', sale.id)"
          class="hover:text-indigo-900"
        >
          Edit
        </a>
        <a :href="route('sales.report', sale.id)" target="_blank" class="hover:text-indigo-900">
          Report
        </a>
      </div>
    </div>
  </div>
</template>
