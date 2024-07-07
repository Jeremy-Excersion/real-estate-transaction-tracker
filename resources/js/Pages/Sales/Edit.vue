<template>
  <AuthenticatedLayout>
    <div class="container mx-auto max-w-[75rem]">
      <SaleInfoForm :users="users" :sale="sale" :handleSubmit="handleSubmit" />
    </div>
  </AuthenticatedLayout>
</template>

<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import SaleInfoForm from './Components/SaleInfoForm/Index.vue';
import { toast } from 'vue3-toastify';

export default {
  name: 'SalesEditPage',
  components: {
    AuthenticatedLayout,
    SaleInfoForm,
  },
  props: {
    sale: Object,
    users: Array,
    sources: Object,
  },
  setup(props) {
    const handleSubmit = async (editedSale) => {
      const { sale } = props;
      try {
        const { status } = await axios.post(`/sales/edit/${sale.id}`, editedSale);
        if (status === 200) {
          toast('Sale successfully edited', {
            autoClose: 1000,
            position: 'top-center',
            hideProgressBar: true,
          });
          window.location.href = '/sales';
        }
      } catch (error) {
        console.error(error);
      }
    };

    return {
      handleSubmit,
    };
  },
};
</script>
