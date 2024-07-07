<template>
  <AuthenticatedLayout>
    <div class="container mx-auto max-w-[75rem]">
      <SaleInfoForm :users="users" :handleSubmit="handleSubmit" />
    </div>
  </AuthenticatedLayout>
</template>

<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import SaleInfoForm from './Components/SaleInfoForm/Index.vue';
import { toast } from 'vue3-toastify';

export default {
  name: 'CreateSalePage',
  components: {
    AuthenticatedLayout,
    SaleInfoForm,
  },
  props: {
    users: Array,
    sources: Object,
  },
  setup() {
    const handleSubmit = async (newSale) => {
      try {
        const { data, status } = await axios.post('/sales/create', newSale);
        if (status === 200) {
          toast('Sale successfully created', {
            autoClose: 1000,
            position: 'top-center',
            hideProgressBar: true,
          });
          window.location.href = `/sales/edit/${data.sale.id}`;
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

<style scoped>
label {
  display: block;
}

input:not([type='checkbox']),
select {
  border-radius: 0.5rem;
  background-color: #f8f8f8;
}

textarea {
  width: 100%;
  height: 150px;
  padding: 12px 20px;
  box-sizing: border-box;
  border: 1px solid black;
  border-radius: 4px;
  background-color: #f8f8f8;
  font-size: 16px;
}
</style>
