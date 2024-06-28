<template>
  <AuthenticatedLayout>
    <div class="container mx-auto">
      <h1 class="text-3xl font-bold my-8">Sales Dashboard</h1>

      <!-- Filtering and Sorting Controls -->
      <div class="flex flex-wrap justify-start gap-4 mb-4">
        <!-- Filtering Dropdown -->
        <div class="mb-2">
          <label
            for="statusFilter"
            class="block text-sm font-medium text-gray-700"
          >
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
            <!-- Add more options if needed -->
          </select>
        </div>
        <!-- Filtering Dropdown -->
        <div class="mb-2">
          <label
            for="agentFilter"
            class="block text-sm font-medium text-gray-700"
            >Filter by Agent:</label
          >
          <select
            v-model="selectedAgent"
            id="agentFilter"
            name="agentFilter"
            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
            @change="handleChange('user_id', selectedAgent)"
          >
            <option value="">All</option>
            <!-- get unique users from getuniqueagents -->
            <option
              v-for="agent in getUniqueAgents()"
              :key="agent.id"
              :value="agent.id"
            >
              {{ agent.name }}
            </option>
          </select>
        </div>

        <!-- Sorting Dropdown -->
        <div class="mb-2">
          <label for="sortBy" class="block text-sm font-medium text-gray-700"
            >Sort By:</label
          >
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
        <div class="mb-2">
          <div class="flex">
            <label
              for="search-bar"
              class="block text-sm font-medium text-gray-700"
            >
              Search:
            </label>
            <button type="button" class="ml-2" @click="handleReset">
              <ArrowPathIcon class="h-[15px]" />
            </button>
          </div>
          <input
            name="search-bar"
            type="text"
            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
            v-model="state.filterCriteria.searchQuery"
            @input="handleChange('search', state.filterCriteria.searchQuery)"
          />
        </div>
        <!-- Checkbox to toggle showing fees -->
      </div>
      <div class="flex gap-4 pb-6 items-center">
        <!-- date range filters -->
        <div class="mb-2">
          <label
            for="pending_start_date"
            class="block text-sm font-medium text-gray-700"
            >Pending Start Date:</label
          >
          <input
            type="date"
            id="pending_start_date"
            name="pending_start_date"
            v-model="state.filterCriteria.pending_start_date"
            @input="
              handleChange(
                'pending_start_date',
                state.filterCriteria.pending_start_date
              )
            "
            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
          />
        </div>
        <div class="mb-2">
          <label
            for="pending_end_date"
            class="block text-sm font-medium text-gray-700"
            >Pending End Date:</label
          >
          <input
            type="date"
            id="pending_end_date"
            name="pending_end_date"
            v-model="state.filterCriteria.pending_end_date"
            @input="
              handleChange(
                'pending_end_date',
                state.filterCriteria.pending_end_date
              )
            "
            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
          />
        </div>
        <div class="mb-2">
          <label
            for="close_start_date"
            class="block text-sm font-medium text-gray-700"
            >Close Start Date:</label
          >
          <input
            type="date"
            id="close_start_date"
            name="close_start_date"
            v-model="state.filterCriteria.close_start_date"
            @input="
              handleChange(
                'close_start_date',
                state.filterCriteria.close_start_date
              )
            "
            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
          />
        </div>
        <div class="mb-2">
          <label
            for="close_end_date"
            class="block text-sm font-medium text-gray-700"
            >Close End Date:</label
          >
          <input
            type="date"
            id="close_end_date"
            name="close_end_date"
            v-model="state.filterCriteria.close_end_date"
            @input="
              handleChange(
                'close_end_date',
                state.filterCriteria.close_end_date
              )
            "
            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
          />
        </div>
      </div>
      <div class="flex items-center pb-6 gap-4">
        <SwitchGroup as="div" class="flex items-center">
          <Switch
            v-model="showFees"
            :class="[
              showFees ? 'bg-indigo-600' : 'bg-gray-200',
              'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-offset-2',
            ]"
          >
            <span
              aria-hidden="true"
              :class="[
                showFees ? 'translate-x-5' : 'translate-x-0',
                'pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out',
              ]"
            />
          </Switch>
          <SwitchLabel as="span" class="ml-3 text-sm">
            <span class="font-medium text-gray-900">Show Fees</span>
            {{ " " }}
          </SwitchLabel>
        </SwitchGroup>
        <a
          :href="route('sales.create')"
          class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
        >
          Add New Sale
        </a>
        <!-- export button, will post in vue function -->
        <button
          @click="exportSales"
          class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
        >
          Export Sales
        </button>
      </div>
      <!-- total sales, total sold price, total agent commission from current filtered table data -->
      <div class="flex justify-end gap-4">
        <div>
          <p class="text-sm font-medium text-gray-500">
            <strong>Total Sales:</strong> {{ state.filteredSales.length }}
          </p>
        </div>
        <div>
          <p class="text-sm font-medium text-gray-500">
            <strong>Total Sold Price: $</strong>
            {{
              formatPrice(
                state.filteredSales.reduce(
                  (acc, sale) => acc + parseFloat(sale.sold_price),
                  0
                )
              )
            }}
          </p>
        </div>
        <div>
          <p class="text-sm font-medium text-gray-500">
            <strong>Total Agent Commission: $</strong>
            {{
              formatPrice(
                state.filteredSales
                  .reduce(
                    (acc, sale) => acc + parseFloat(sale.agent_commission),
                    0
                  )
                  .toFixed(2)
              )
            }}
          </p>
        </div>
      </div>
      <!-- Sales Table -->
      <table class="min-w-full divide-y divide-gray-200">
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
              Type
            </th>
            <th
              scope="col"
              class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
            >
              Client Name
            </th>
            <th
              scope="col"
              class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
            >
              Address
            </th>
            <th
              scope="col"
              class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
            >
              Sold Price
            </th>
            <th
              scope="col"
              class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
            >
              Status
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
              Pending Date<br />
              Close Date
            </th>
            <th
              scope="col"
              class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
            >
              Agent Commission
            </th>
            <th scope="col" class="relative px-6 py-3">
              <span class="sr-only">Actions</span>
            </th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <template v-for="sale in state.filteredSales" :key="sale.id">
            <tr>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">
                  <button @click="handleEdit(sale.user?.id)">
                    {{ sale.user?.name }}
                  </button>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">
                  {{ sale.buyer ? "Buyer" : "Seller" }}
                  {{ sale.referral ? "(R)" : "" }}
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">{{ sale.client_name }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">
                  {{ sale.address }},<br />{{ sale.city }}, {{ sale.state }},
                  {{ sale.zip }}
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">
                  ${{ formatPrice(sale.sold_price) }}
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">{{ sale.status }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">
                  {{ sale.percentage }}%<br />{{ sale.agent_split_percentage }}%
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">
                  {{ sale.pending_date }}<br />
                  {{ sale.closing_date }}
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">
                  ${{ formatPrice(sale.agent_commission) }}
                </div>
              </td>
              <td
                class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"
              >
                <div class="flex gap-4">
                  <a
                    v-if="$page.props.auth.roles.includes('admin')"
                    :href="route('sales.edit', sale.id)"
                    class="text-indigo-600 hover:text-indigo-900"
                    >Edit</a
                  >
                  <a
                    :href="route('sales.report', sale.id)"
                    target="_blank"
                    class="text-indigo-600 hover:text-indigo-900"
                    >Report</a
                  >
                </div>
              </td>
            </tr>
            <tr v-if="showFees" :key="`${sale.id}-fees`">
              <td
                :colspan="showFees ? 10 : 7"
                class="px-6 py-4 whitespace-nowrap"
              >
                <ul class="list-disc ml-6">
                  <li v-for="fee in sale.fees" :key="fee.id">
                    <p>
                      <strong>{{ fee.fee_name }}:</strong>
                      <span v-if="fee.split_type === 7">
                        {{ fee.fee_amount }}%
                      </span>
                      <span v-else> ${{ fee.fee_amount }} </span>
                      ({{ fee.split_type_name }})
                    </p>
                  </li>
                </ul>
              </td>
            </tr>
          </template>
        </tbody>
      </table>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { ref, reactive } from "vue";
import { Switch, SwitchGroup, SwitchLabel } from "@headlessui/vue";
import { ArrowPathIcon } from "@heroicons/vue/20/solid";
import { formatPrice } from "@/Utils/FormatPrice";

const props = defineProps({
  sales: {
    type: Array,
    required: true,
  },
});

const selectedStatus = ref(""); // Initialize selectedStatus ref
const selectedAgent = ref(""); // Initialize selectedAgent ref
const sortBy = ref(""); // Initialize sortBy ref
// Define reactive variable to track checkbox state
const showFees = ref(false);
const state = reactive({
  filterCriteria: {
    status: "",
    user_id: "",
    searchQuery: "",
    sort_by: "",
    pending_start_date: "",
    pending_end_date: "",
    close_start_date: "",
    close_end_date: "",
  },
  filteredSales: props.sales,
});

const handleChange = (filter, value) => {
  // Update the filter criteria in the state
  state.filterCriteria[filter] = value;

  // Apply all filters together
  state.filteredSales = props.sales
    .filter((sale) => {
      // Apply status filter
      if (
        state.filterCriteria.status &&
        sale.status !== state.filterCriteria.status
      ) {
        return false;
      }
      // Apply user_id filter
      if (
        state.filterCriteria.user_id &&
        sale.user_id !== parseInt(state.filterCriteria.user_id, 10)
      ) {
        return false;
      }
      return true;
    })
    .filter((sale) => {
      // Apply search query filter
      const query = state.filterCriteria.searchQuery.toLowerCase();
      return (
        sale.client_name.toLowerCase().includes(query) ||
        sale.address.toLowerCase().includes(query) ||
        sale.city.toLowerCase().includes(query) ||
        sale.state.toLowerCase().includes(query) ||
        sale.zip.toLowerCase().includes(query) ||
        (sale.user && sale.user.name.toLowerCase().includes(query))
      );
    })
    .filter((sale) => {
      // Apply pending start date filter
      if (state.filterCriteria.pending_start_date) {
        const pendingStartDate = new Date(
          state.filterCriteria.pending_start_date
        );
        const salePendingDate = new Date(sale.pending_date);
        if (salePendingDate < pendingStartDate) {
          return false;
        }
      }
      // Apply pending end date filter
      if (state.filterCriteria.pending_end_date) {
        const pendingEndDate = new Date(state.filterCriteria.pending_end_date);
        const salePendingDate = new Date(sale.pending_date);
        if (salePendingDate > pendingEndDate) {
          return false;
        }
      }
      // Apply close start date filter
      if (state.filterCriteria.close_start_date) {
        const closeStartDate = new Date(state.filterCriteria.close_start_date);
        const saleCloseDate = new Date(sale.closing_date);
        if (saleCloseDate < closeStartDate) {
          return false;
        }
      }
      // Apply close end date filter
      if (state.filterCriteria.close_end_date) {
        const closeEndDate = new Date(state.filterCriteria.close_end_date);
        const saleCloseDate = new Date(sale.closing_date);
        if (saleCloseDate > closeEndDate) {
          return false;
        }
      }
      return true;
    });

  // Apply sort filter
  if (state.filterCriteria.sort_by) {
    const value = state.filterCriteria.sort_by;
    state.filteredSales = state.filteredSales.slice().sort((a, b) => {
      const sortOrder = value.startsWith("-") ? 1 : -1;
      const field = value.replace(/^-/, "");

      // Determine the data type of the field
      const isNumeric = !isNaN(parseFloat(a[field])) && isFinite(a[field]);

      // Compare values based on data type
      if (isNumeric) {
        return sortOrder * (parseFloat(a[field]) - parseFloat(b[field]));
      } else {
        return sortOrder * a[field].localeCompare(b[field]);
      }
    });
  }
};

// Function to get a unique list of users (name and id) from sales - user is nullable so there might be none
const getUniqueAgents = () => {
  const agents = [];
  props.sales.forEach((sale) => {
    if (sale.user && !agents.some((agent) => agent.id === sale.user.id)) {
      agents.push({ id: sale.user.id, name: sale.user.name });
    }
  });
  // return agents sorted
  return agents.sort((a, b) => a.name.localeCompare(b.name));
};

const handleEdit = (id) => {
  window.location.href = `sales/edit/${id}`;
};

const handleReset = () => {
  state.searchQuery = "";
  state.filteredSales = props.sales;
};

// Function to export sales data using the current filter criteria
const exportSales = () => {
  // post to route sales.export with filter criteria
  axios
    .post(route("sales.export"), state.filterCriteria, { responseType: "blob" })
    .then((response) => {
      // Create a URL for the file blob and trigger download
      const url = window.URL.createObjectURL(new Blob([response.data]));
      const link = document.createElement("a");
      link.href = url;
      link.setAttribute("download", "sales.csv"); // Set the file name
      document.body.appendChild(link);
      link.click();
      document.body.removeChild(link); // Clean up
    })
    .catch((error) => {
      // handle error
      console.log(error);
    });
};
</script>
