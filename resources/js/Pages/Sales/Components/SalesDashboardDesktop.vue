<script setup>
import { formatPrice } from '@/Utils/FormatPrice';

const props = defineProps({
  sales: Array,
  showFees: Boolean,
  formatPrice: Function,
  handleEdit: Function,
});
</script>

<template>
  <table class="min-w-full divide-y divide-gray-200 overflow-x-auto">
    <thead class="bg-gray-50">
      <tr>
        <th
          scope="col"
          class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
        >
          Agent
        </th>
        <th
          scope="col"
          class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
        >
          Status
          <br />
          Type
        </th>
        <th
          scope="col"
          class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
        >
          Client Name
          <br />
          Address
        </th>
        <th
          scope="col"
          class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
        >
          Split Percent
        </th>
        <th
          scope="col"
          class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
        >
          Sold Price
          <br />
          Agent Commission
        </th>
        <th
          scope="col"
          class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
        >
          Pending Date
          <br />
          Close Date
        </th>
        <th scope="col" class="relative px-6 py-3">
          <span class="sr-only">Actions</span>
        </th>
      </tr>
    </thead>
    <tbody class="bg-white divide-y divide-gray-200">
      <template v-for="sale in sales" :key="sale.id">
        <tr>
          <td class="px-6 py-4 whitespace-nowrap">
            <div class="text-sm text-gray-900">
              <button @click="handleEdit(sale.user?.id)">
                {{ sale.user?.name }}
              </button>
            </div>
          </td>
          <td class="px-6 py-4 whitespace-nowrap">
            <div class="text-sm text-gray-900">{{ sale.status }}</div>
            <div class="text-sm text-gray-900">
              {{ sale.buyer ? 'Buyer' : 'Seller' }}
              {{ sale.referral ? '(R)' : '' }}
            </div>
          </td>
          <td class="px-6 py-4 whitespace-nowrap">
            <div class="text-sm text-gray-900">{{ sale.client_name }}</div>
            <div class="text-sm text-gray-900">
              {{ sale.address }},
              <br />
              {{ sale.city }}, {{ sale.state }},
              {{ sale.zip }}
            </div>
          </td>
          <td class="px-6 py-4 whitespace-nowrap">
            <div class="text-sm text-gray-900">
              {{ sale.percentage }}%
              <br />
              {{ sale.agent_split_percentage }}%
            </div>
          </td>
          <td class="px-6 py-4 whitespace-nowrap">
            <div class="text-sm text-gray-900">${{ formatPrice(sale.sold_price) }}</div>
            <div class="text-sm text-gray-900">${{ formatPrice(sale.agent_commission) }}</div>
          </td>
          <td class="px-6 py-4 whitespace-nowrap">
            <div class="text-sm text-gray-900">
              {{ sale.pending_date }}
              <br />
              {{ sale.closing_date }}
            </div>
          </td>
          <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
            <div class="flex gap-4">
              <a
                v-if="$page.props.auth.roles.includes('admin')"
                :href="route('sales.edit', sale.id)"
                class="text-indigo-600 hover:text-indigo-900"
              >
                Edit
              </a>
              <a
                :href="route('sales.report', sale.id)"
                target="_blank"
                class="text-indigo-600 hover:text-indigo-900"
              >
                Report
              </a>
            </div>
          </td>
        </tr>
        <tr v-if="showFees" :key="`${sale.id}-fees`">
          <td :colspan="showFees ? 10 : 7" class="px-6 py-4 whitespace-nowrap">
            <ul class="list-disc ml-6">
              <li v-for="fee in sale.fees" :key="fee.id">
                <p>
                  <strong>{{ fee.fee_name }}:</strong>
                  <span v-if="fee.split_type === 7">{{ fee.fee_amount }}%</span>
                  <span v-else>${{ fee.fee_amount }}</span>
                  ({{ fee.split_type_name }})
                </p>
              </li>
            </ul>
          </td>
        </tr>
      </template>
    </tbody>
  </table>
</template>
