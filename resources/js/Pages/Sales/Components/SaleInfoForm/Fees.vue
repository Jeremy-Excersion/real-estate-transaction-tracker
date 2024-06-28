<template>
  <section class="w-[100%]">
    <div class="flex justify-start">
      <h2 class="text-2xl font-bold my-4">Fees</h2>
      <button
        type="button"
        @click="state.isAddingNewFee = !state.isAddingNewFee"
      >
        <PlusIcon
          class="text-black h-[25px] w-[25px] ml-4"
          aria-label="Add new fee"
        />
      </button>
    </div>
    <table class="min-w-full divide-y divide-gray-200 text-left">
      <!-- FEE ROWS & TABLE HEAD -->
      <thead class="bg-gray-50">
        <tr>
          <th
            scope="col"
            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
            v-for="field in fields"
          >
            {{ field }}
          </th>
        </tr>
      </thead>

      <!-- FEE DATA -->
      <tbody class="bg-white divide-y divide-gray-200">
        <template v-for="(fee, index) in fees" :key="fee.id">
          <tr>
            <td class="px-6 py-4 whitespace-nowrap">
              <div v-if="!fee.isEditing" class="text-sm text-gray-900">
                <div v-if="fee.split_type === 7">{{ fee.fee_amount }}%</div>
                <div v-else>${{ formatPrice(fee.fee_amount) }}</div>
              </div>
              <input
                v-else
                type="text"
                class="rounded-lg bg-[#f8f8f8]"
                v-model="fee.fee_amount"
                required
                @blur="validateInput('amount', fee)"
              />
              <p v-if="state.formError.amount" class="text-red-600">
                {{ state.formError.amount }}
              </p>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <div v-if="!fee.isEditing" class="text-sm text-gray-900">
                {{ fee.fee_name }}
              </div>
              <input
                v-else
                type="text"
                class="rounded-lg bg-[#f8f8f8]"
                v-model="fee.fee_name"
                required
              />
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <div v-if="!fee.isEditing" class="text-sm text-gray-900">
                {{ fee.split_type_name }}
              </div>
              <select
                v-else
                id="split_type"
                name="split_type"
                v-model="fee.split_type"
                class="block rounded-lg bg-[#f8f8f8]"
                required
              >
                <option
                  v-for="splitType in splitTypes"
                  :key="splitType.label"
                  :value="splitType.value"
                >
                  {{ splitType.label }}
                </option>
              </select>
            </td>
            <!-- ACTION BUTTONS -->
            <td
              class="px-6 py-4 whitespace-nowrap text-left text-sm font-medium"
            >
              <button
                type="button"
                v-if="fee.isEditing"
                @click="handleEdit(fee, index)"
              >
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

        <!-- ROW WHEN ADDING A NEW FEE -->
        <tr v-if="state.isAddingNewFee">
          <td class="px-6 py-4 whitespace-nowrap">
            <div class="text-sm text-gray-900">
              <label for="fee_amount" class="block"> Fee Amount </label>
              <input
                type="text"
                name="fee_amount"
                id="fee_amount"
                class="rounded-lg bg-[#f8f8f8]"
                v-model="state.newFee.fee_amount"
              />
            </div>
          </td>
          <td class="px-6 py-4 whitespace-nowrap">
            <div class="text-sm text-gray-900">
              <label for="fee_name" class="block"> Fee Name </label>
              <input
                type="text"
                name="fee_name"
                id="fee_name"
                class="rounded-lg bg-[#f8f8f8]"
                v-model="state.newFee.fee_name"
              />
            </div>
          </td>
          <td class="px-6 py-4 whitespace-nowrap">
            <div class="text-sm text-gray-900">
              <label for="split_type" class="block"> Split Type </label>
              <select
                id="split_type"
                name="split_type"
                class="rounded-lg bg-[#f8f8f8]"
                v-model="state.newFee.split_type"
              >
                <option
                  v-for="splitType in splitTypes"
                  :key="splitType.label"
                  :value="splitType.value"
                >
                  {{ splitType.label }}
                </option>
              </select>
            </div>
          </td>
          <td class="px-6 py-4 whitespace-nowrap">
            <div class="text-sm text-gray-900">
              <button type="button" @click="handleAddNewFee">
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
import { reactive } from "vue";
import {
  PlusIcon,
  PencilSquareIcon,
  TrashIcon,
  CheckCircleIcon,
} from "@heroicons/vue/20/solid";
import { splitTypes } from "../../index.data";
import { formatPrice } from "@/Utils/FormatPrice";
import { validatePrice } from "@/Utils/Validators";

export default {
  name: "Fees",
  components: {
    PlusIcon,
    PencilSquareIcon,
    TrashIcon,
    CheckCircleIcon,
  },
  props: {
    fees: Array,
    handleDelete: Function,
  },
  emits: ["handleUpdate"],
  setup(props, { emit }) {
    const state = reactive({
      newFee: {},
      isAddingNewFee: false,
      formError: {
        amount: "",
        name: "",
        splitType: "",
      },
    });

    const fields = ["Fee Amount", "Fee Name", "Split Type", "Actions"];

    const toggleEditing = (index) => {
      props.fees[index].isEditing = !props.fees[index].isEditing;
    };

    const handleEdit = (fee, index) => {
      const requiredFields = ["fee_amount", "fee_name", "split_type"];
      const missingField = requiredFields.find((field) => !fee[field]);

      if (missingField) {
        const fieldName = missingField.replace("_", " ");
        const formattedFieldName =
          fieldName.charAt(0).toUpperCase() + fieldName.substr(1).toLowerCase();

        alert(`${formattedFieldName} is required.`);
        return;
      }

      props.fees[index] = {
        ...fee,
        split_type_name: splitTypes[fee.split_type - 1].label,
      };
      props.fees[index].isEditing = !props.fees[index].isEditing;

      emit("handleUpdate", props.fees);
    };

    const handleDelete = (index) => {
      props.fees.splice(index, 1);
      emit("handleUpdate", props.fees);
    };

    const handleAddNewFee = () => {
      // omit isEditing key before sending up to parent for POST request
      const { isEditing, ...parsedNewFee } = state.newFee;
      const newFee = {
        ...parsedNewFee,
        split_type_name: splitTypes[state.newFee.split_type - 1].label,
      };

      props.fees.push(newFee);
      state.newFee = {};
      state.isAddingNewFee = false;

      emit("handleUpdate", props.fees);
    };

    const validateInput = (field, fee) => {
      switch (field) {
        case "amount": {
          if (!validatePrice(fee.fee_amount)) {
            state.formError[field] = "Please enter a valid amount";
          } else {
            // strip out commas and $ sign
            fee = fee.replace(/[$,]/g, "");
          }
        }
      }
    };

    return {
      state,
      fields,
      splitTypes,
      toggleEditing,
      handleDelete,
      handleEdit,
      handleAddNewFee,
      formatPrice,
      validateInput,
    };
  },
};
</script>
