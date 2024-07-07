<template>
  <section class="w-[100%]">
    <div class="flex justify-start">
      <h2 class="text-2xl font-bold my-4">Checks</h2>
      <button type="button" @click="state.isAddingNewCheck = !state.isAddingNewCheck">
        <PlusIcon class="text-black h-[25px] w-[25px] ml-4" aria-label="Add a new check" />
      </button>
    </div>
    <table class="min-w-full divide-y divide-gray-200 text-left">
      <!-- CHECK ROWS & TABLE HEAD -->
      <thead class="bg-gray-50">
        <tr>
          <th
            scope="col"
            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
            v-for="field in fields"
            :key="field"
          >
            {{ field }}
          </th>
        </tr>
      </thead>

      <!-- CHECK DATA -->
      <tbody class="bg-white divide-y divide-gray-200">
        <template v-for="(check, index) in checks" :key="check.id">
          <tr>
            <td class="px-6 py-4 whitespace-nowrap">
              <div v-if="!check.isEditing" class="text-sm text-gray-900">
                {{ check.number }}
              </div>
              <input v-else type="text" v-model="check.number" class="rounded-lg" />
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <div v-if="!check.isEditing" class="text-sm text-gray-900">
                ${{ formatPrice(check.amount) }}
              </div>
              <input v-else type="text" v-model="check.amount" class="rounded-lg" />
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <div v-if="!check.isEditing" class="text-sm text-gray-900">
                {{ check.date }}
              </div>
              <input v-else v-model="check.date" class="rounded-lg" type="date" />
            </td>
            <!-- ACTION BUTTONS -->
            <td class="px-6 py-4 whitespace-nowrap text-left text-sm font-medium">
              <button type="button" v-if="check.isEditing" @click="handleEdit(check, index)">
                <CheckCircleIcon class="text-black h-[25px] w-[25px] ml-4" />
              </button>
              <button
                class="text-indigo-600 hover:text-indigo-900"
                type="button"
                @click="toggleEditing(index)"
              >
                <PencilSquareIcon class="text-black h-[25px] w-[25px] ml-4" />
              </button>
              <button
                class="text-indigo-600 hover:text-indigo-900"
                type="button"
                @click="handleDelete(index)"
              >
                <TrashIcon class="text-black h-[25px] w-[25px] ml-4" />
              </button>
            </td>
          </tr>
        </template>

        <!-- ROW WHEN ADDING A NEW CHECK -->
        <tr v-if="state.isAddingNewCheck">
          <td class="px-6 py-4 whitespace-nowrap">
            <div class="text-sm text-gray-900">
              <label for="check-number" class="block">Check Number</label>
              <input
                type="number"
                name="check-number"
                id="check-number"
                v-model="state.newCheck.number"
                class="rounded-lg"
              />
            </div>
          </td>
          <td class="px-6 py-4 whitespace-nowrap">
            <div class="text-sm text-gray-900">
              <label for="amount" class="block">Check Amount</label>
              <input
                type="number"
                name="amount"
                id="amount"
                v-model="state.newCheck.amount"
                class="rounded-lg"
              />
            </div>
          </td>
          <td class="px-6 py-4 whitespace-nowrap">
            <div class="text-sm text-gray-900">
              <label for="date" class="block">Check Date</label>
              <input
                id="date"
                name="date"
                v-model="state.newCheck.date"
                type="date"
                class="rounded-lg"
              />
            </div>
          </td>
          <td class="px-6 py-4 whitespace-nowrap">
            <div class="text-sm text-gray-900">
              <button type="button" @click="handleAddNewCheck">
                <CheckCircleIcon class="text-black h-[25px] w-[25px] ml-4" />
              </button>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
  </section>
</template>

<script>
import { reactive } from 'vue';
import { PlusIcon, PencilSquareIcon, TrashIcon, CheckCircleIcon } from '@heroicons/vue/20/solid';
import { formatPrice } from '@/Utils/FormatPrice';

export default {
  name: 'Checks',
  emits: ['checks-change'],
  components: {
    PlusIcon,
    PencilSquareIcon,
    TrashIcon,
    CheckCircleIcon,
  },
  props: {
    checks: Array,
    handleDelete: Function,
  },
  setup(props, { emit }) {
    const state = reactive({
      newCheck: {},
      isAddingNewCheck: false,
    });

    const fields = ['Check Number', 'Check Amount', 'Check Date', 'Actions'];

    const toggleEditing = (index) => {
      props.checks[index].isEditing = !props.checks[index].isEditing;
    };

    const handleEdit = (check, index) => {
      props.checks[index] = {
        ...check,
      };
      props.checks[index].isEditing = !props.checks[index].isEditing;
      emit('checks-change', props.checks);
    };

    const handleDelete = (index) => {
      props.checks.splice(index, 1);
      emit('checks-change', props.checks);
    };

    const handleAddNewCheck = () => {
      // omit isEditing key before sending up to parent for POST request
      const { isEditing, ...newCheck } = state.newCheck;

      props.checks.push(newCheck);
      state.newCheck = {};
      state.isAddingNewCheck = false;
      emit('checks-change', props.checks);
    };

    return {
      state,
      fields,
      toggleEditing,
      handleDelete,
      handleEdit,
      handleAddNewCheck,
      formatPrice,
    };
  },
};
</script>

<style scoped>
input {
  background-color: #f8f8f8;
}
</style>
