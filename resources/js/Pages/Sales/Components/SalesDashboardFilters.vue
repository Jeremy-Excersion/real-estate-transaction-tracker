<template>
  <div>
    <div class="flex flex-wrap justify-center lg:justify-start gap-4 mb-4 px-4">
      <!-- Filtering Dropdown -->
      <div class="w-full md:w-auto mb-2">
        <label for="statusFilter" class="block text-sm font-medium text-gray-700">
          Filter by Status:
        </label>
        <select
          v-model="selectedStatus"
          id="statusFilter"
          name="statusFilter"
          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
          @change="handleChange('status', selectedStatus)"
        >
          <option value="">All</option>
          <option value="Pending">Pending</option>
          <option value="Paid">Paid</option>
          <option value="Closed">Closed</option>
          <option value="Cancelled">Cancelled</option>
        </select>
      </div>
      <!-- Filtering Dropdown -->
      <div class="w-full md:w-auto mb-2">
        <label for="agentFilter" class="block text-sm font-medium text-gray-700">
          Filter by Agent:
        </label>
        <select
          v-model="selectedAgent"
          id="agentFilter"
          name="agentFilter"
          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
          @change="handleChange('user_id', selectedAgent)"
        >
          <option value="">All</option>
          <option v-for="agent in getUniqueAgents()" :key="agent.id" :value="agent.id">
            {{ agent.name }}
          </option>
        </select>
      </div>

      <!-- Sorting Dropdown -->
      <div class="w-full md:w-auto mb-2">
        <label for="sortBy" class="block text-sm font-medium text-gray-700">Sort By:</label>
        <select
          v-model="sortBy"
          id="sortBy"
          name="sortBy"
          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
          @change="handleChange('sort_by', sortBy)"
        >
          <option value="id">Newest</option>
          <option value="-id">Oldest</option>
          <option value="sold_price">Sold Price (Low to High)</option>
          <option value="-sold_price">Sold Price (High to Low)</option>
          <option value="status">Status (A-Z)</option>
          <option value="-status">Status (Z-A)</option>
        </select>
      </div>

      <!-- Search Input -->
      <div class="w-full md:w-auto mb-2">
        <label for="search-bar" class="block text-sm font-medium text-gray-700">Search:</label>
        <div class="flex items-center mt-1">
          <input
            name="search-bar"
            type="text"
            class="block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
            v-model="state.filterCriteria.searchQuery"
            @input="handleChange('search', state.filterCriteria.searchQuery)"
          />
        </div>
      </div>
      <!-- Checkbox to toggle showing fees -->

      <!-- date range filters -->
      <div class="w-full md:w-auto mb-2">
        <label for="pending_start_date" class="block text-sm font-medium text-gray-700">
          Pending Start Date:
        </label>
        <input
          type="date"
          id="pending_start_date"
          name="pending_start_date"
          v-model="state.filterCriteria.pending_start_date"
          @input="handleChange('pending_start_date', state.filterCriteria.pending_start_date)"
          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
        />
      </div>
      <div class="w-full md:w-auto mb-2">
        <label for="pending_end_date" class="block text-sm font-medium text-gray-700">
          Pending End Date:
        </label>
        <input
          type="date"
          id="pending_end_date"
          name="pending_end_date"
          v-model="state.filterCriteria.pending_end_date"
          @input="handleChange('pending_end_date', state.filterCriteria.pending_end_date)"
          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
        />
      </div>
      <div class="w-full md:w-auto mb-2">
        <label for="close_start_date" class="block text-sm font-medium text-gray-700">
          Close Start Date:
        </label>
        <input
          type="date"
          id="close_start_date"
          name="close_start_date"
          v-model="state.filterCriteria.close_start_date"
          @input="handleChange('close_start_date', state.filterCriteria.close_start_date)"
          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
        />
      </div>
      <div class="w-full md:w-auto mb-2">
        <label for="close_end_date" class="block text-sm font-medium text-gray-700">
          Close End Date:
        </label>
        <input
          type="date"
          id="close_end_date"
          name="close_end_date"
          v-model="state.filterCriteria.close_end_date"
          @input="handleChange('close_end_date', state.filterCriteria.close_end_date)"
          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
        />
      </div>
    </div>
    <div class="flex flex-wrap items-center pb-6 gap-4 justify-center lg:justify-start">
      <SwitchGroup as="div" class="flex items-center">
        <Switch
          v-model="state.showFees"
          @change="handleChange('showFees', state.showFees)"
          :class="[
            state.showFees ? 'bg-indigo-600' : 'bg-gray-200',
            'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-offset-2',
          ]"
        >
          <span
            aria-hidden="true"
            :class="[
              state.showFees ? 'translate-x-5' : 'translate-x-0',
              'pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out',
            ]"
          />
        </Switch>
        <SwitchLabel as="span" class="ml-3 text-sm">
          <span class="font-medium text-gray-900">Show Fees</span>
          {{ ' ' }}
        </SwitchLabel>
      </SwitchGroup>
      <a
        :href="route('sales.create')"
        class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
      >
        Add New Sale
      </a>
      <button
        @click="exportSales"
        class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
      >
        Export Sales
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { Switch, SwitchGroup, SwitchLabel } from '@headlessui/vue';

const props = defineProps({
  sales: Array,
  state: Object,
  handleChange: Function,
  exportSales: Function,
});

const selectedStatus = ref('');
const selectedAgent = ref('');
const sortBy = ref('');

const getUniqueAgents = () => {
  const agents = [];
  props.sales.forEach((sale) => {
    if (sale.user && !agents.some((agent) => agent.id === sale.user.id)) {
      agents.push({ id: sale.user.id, name: sale.user.name });
    }
  });
  return agents.sort((a, b) => a.name.localeCompare(b.name));
};
</script>
