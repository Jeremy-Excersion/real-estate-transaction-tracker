<template>
  <AuthenticatedLayout>
    <div class="container mx-auto">
      <h1 class="text-3xl font-bold my-8">Sales Dashboard</h1>
      <SalesDashboardFilters
        :sales="sales"
        :state="state"
        :handleChange="handleChange"
        :showFees="state.showFees"
        :exportSales="exportSales"
      />
      <!-- total sales, total sold price, total agent commission from current filtered table data -->
      <div class="flex justify-end gap-4">
        <div>
          <p class="text-sm font-medium text-gray-500">
            <strong>Total Sales:</strong>
            {{ state.filteredSales.length }}
          </p>
        </div>
        <div>
          <p class="text-sm font-medium text-gray-500">
            <strong>Total Sold Price: $</strong>
            {{
              formatPrice(
                state.filteredSales.reduce((acc, sale) => acc + parseFloat(sale.sold_price), 0)
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
                  .reduce((acc, sale) => acc + parseFloat(sale.agent_commission), 0)
                  .toFixed(2)
              )
            }}
          </p>
        </div>
      </div>
      <!-- Sales Table -->
      <SalesDesktop
        v-if="!isMobile"
        :sales="state.filteredSales"
        :handleEdit="handleEdit"
        :showFees="state.showFees"
      />
      <SalesMobile
        v-else
        :sales="state.filteredSales"
        :handleEdit="handleEdit"
        :showFees="state.showFees"
      />
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref, reactive, onMounted, onUnmounted } from 'vue';
import { formatPrice } from '@/Utils/FormatPrice';
import SalesDashboardFilters from './Components/SalesDashboardFilters.vue';
import SalesDesktop from './Components/SalesDashboardDesktop.vue';
import SalesMobile from './Components/SalesDashboardMobile.vue';

const props = defineProps({
  sales: {
    type: Array,
    required: true,
  },
});

// Define reactive variable to track checkbox state
const state = reactive({
  filterCriteria: {
    status: '',
    user_id: '',
    searchQuery: '',
    sort_by: '',
    pending_start_date: '',
    pending_end_date: '',
    close_start_date: '',
    close_end_date: '',
  },
  filteredSales: props.sales,
  showFees: false,
});

const handleChange = (filter, value) => {
  // Update the filter criteria in the state
  state.filterCriteria[filter] = value;

  // Apply all filters together
  state.filteredSales = props.sales
    .filter((sale) => {
      // Apply status filter
      if (state.filterCriteria.status && sale.status !== state.filterCriteria.status) {
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
        const pendingStartDate = new Date(state.filterCriteria.pending_start_date);
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
      const sortOrder = value.startsWith('-') ? 1 : -1;
      const field = value.replace(/^-/, '');

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

const handleEdit = (id) => {
  window.location.href = `sales/edit/${id}`;
};

// Function to export sales data using the current filter criteria
const exportSales = () => {
  // post to route sales.export with filter criteria
  axios
    .post(route('sales.export'), state.filterCriteria, { responseType: 'blob' })
    .then((response) => {
      // Create a URL for the file blob and trigger download
      const url = window.URL.createObjectURL(new Blob([response.data]));
      const link = document.createElement('a');
      link.href = url;
      link.setAttribute('download', 'sales.csv'); // Set the file name
      document.body.appendChild(link);
      link.click();
      document.body.removeChild(link); // Clean up
    })
    .catch((error) => {
      // handle error
      console.log(error);
    });
};

const isMobile = ref(window.innerWidth < 1050);

const handleResize = () => {
  isMobile.value = window.innerWidth < 1050;
};

onMounted(() => {
  window.addEventListener('resize', handleResize);
  handleResize();
});

onUnmounted(() => {
  window.removeEventListener('resize', handleResize);
});
</script>
