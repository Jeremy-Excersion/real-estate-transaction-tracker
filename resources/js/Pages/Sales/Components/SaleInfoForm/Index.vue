<template>
  <form @submit.prevent="handleSubmit">
    <div class="flex items-center">
      <h1 class="text-3xl font-bold my-4">Add Sale</h1>
      <ReturnToDashboardButton />
    </div>
    <div class="flex flex-wrap gap-4">
      <div>
        <h2 class="text-2xl font-bold mb-2">Agent Info</h2>
        <label for="user_id">Agent</label>
        <select
          id="user_id"
          name="user_id"
          :class="styleClasses.input"
          v-model="form.user_id"
        >
          <option v-for="user in users" :key="user.email" :value="user.id">
            {{ user.name }}
          </option>
        </select>
      </div>

      <!-- CLIENT INFO -->
      <div class="w-[100%]">
        <h2 class="text-2xl font-bold mb-2">Client Info</h2>
        <div class="flex gap-4">
          <div>
            <label for="client_name">Client Name</label>
            <input
              type="text"
              name="client_name"
              :class="styleClasses.input"
              v-model="form.client_name"
            />
          </div>
          <div class="w-[100%]">
            <label for="client_email">Client Email</label>
            <input
              type="text"
              name="client_email"
              :class="[styleClasses.input, 'w-[50%]']"
              v-model="form.client_email"
              @blur="validateInput('email')"
              @focusin="state.formError.email = ''"
            />
            <p v-if="state.formError.email" class="text-red-600">
              {{ state.formError.email }}
            </p>
          </div>
        </div>
      </div>

      <!-- ADDRESS INFO -->
      <div class="w-[100%]">
        <h2 class="text-2xl font-bold mb-2">Address Info</h2>
        <div class="flex gap-4">
          <div class="w-[50%]">
            <label for="address">Street</label>
            <input
              type="text"
              name="address"
              :class="[styleClasses.input, 'w-[100%]']"
              v-model="form.address"
            />
          </div>
          <div class="w-[50%]">
            <label for="city">City</label>
            <input
              type="text"
              name="city"
              :class="[styleClasses.input, 'w-[100%]']"
              v-model="form.city"
            />
          </div>
          <div>
            <label for="state"> State </label>
            <select id="state" name="state" v-model="form.state">
              <option
                v-for="state in stateOptions"
                :key="state.abbreviation"
                :value="state.abbreviation"
              >
                {{ state.name }}
              </option>
            </select>
          </div>
          <div>
            <label for="zip">Zip Code</label>
            <input
              type="text"
              name="zip"
              :class="styleClasses.input"
              v-model="form.zip"
              @blur="validateInput('zip')"
              @focusin="state.formError.zip = ''"
            />
            <p v-if="state.formError.zip" class="text-red-600">
              {{ state.formError.zip }}
            </p>
          </div>
        </div>
      </div>

      <!-- PRICE & SALE INFO -->
      <div class="w-[100%]">
        <h2 class="text-2xl font-bold mb-2">Price & Sale Info</h2>
        <div class="grid grid-cols-4">
          <div class="mb-4">
            <label for="asking_price">Asking Price</label>
            <input
              type="text"
              name="asking_price"
              :class="styleClasses.input"
              v-model="form.asking_price"
              @blur="validateInput('askingPrice')"
              @focusin="state.formError.askingPrice = ''"
            />
            <p v-if="state.formError.askingPrice" class="text-red-600">
              {{ state.formError.askingPrice }}
            </p>
          </div>
          <div class="mb-4">
            <label for="sold_price">Sold Price</label>
            <input
              type="text"
              name="sold_price"
              :class="styleClasses.input"
              v-model="form.sold_price"
              @blur="validateInput('soldPrice')"
              @focusin="state.formError.soldPrice = ''"
            />
            <p v-if="state.formError.soldPrice" class="text-red-600">
              {{ state.formError.soldPrice }}
            </p>
          </div>
          <div class="mb-4">
            <label for="percentage">Percentage</label>
            <input
              type="text"
              name="percentage"
              :class="styleClasses.input"
              v-model="form.percentage"
            />
          </div>
          <div class="mb-4">
            <label for="agent_split_percentage"> Agent Split Percentage </label>
            <input
              type="text"
              name="agent_split_percentage"
              :class="styleClasses.input"
              v-model="form.agent_split_percentage"
            />
          </div>
          <div class="mb-4">
            <label for="pending_date">Pending Date</label>
            <input
              type="date"
              name="pending_date"
              :class="styleClasses.input"
              v-model="form.pending_date"
              @change="handleDateChange"
            />
          </div>
          <div class="mb-4">
            <label for="closing_date">Closing Date</label>
            <input
              type="date"
              name="closing_date"
              :class="styleClasses.input"
              v-model="form.closing_date"
              @change="handleDateChange"
            />
          </div>
          <div class="mb-4">
            <label for="status"> Status </label>
            <select
              id="status"
              name="status"
              :class="styleClasses.input"
              v-model="form.status"
            >
              <option
                v-for="status in statusOptions"
                :key="status"
                :value="status"
              >
                {{ status }}
              </option>
            </select>
          </div>
          <div class="mb-4">
            <label for="buyer"> Type </label>
            <select
              id="buyer"
              name="buyer"
              :class="styleClasses.input"
              v-model="form.buyer"
            >
              <option
                v-for="buyerType in buyerOptions"
                :key="buyerType.label"
                :value="buyerType.value"
              >
                {{ buyerType.label }}
              </option>
            </select>
          </div>
        </div>

        <!-- SOURCES -->
        <div>
          <h2 class="text-2xl font-bold mb-2">Sources</h2>
          <div class="flex gap-8 mb-4">
            <div v-for="(source, index) in sources" :key="source.name">
              <label :for="source.name">
                {{ source.name }}
              </label>
              <input
                type="checkbox"
                :name="source.name"
                :checked="form.sources?.some((obj) => obj.name === source.name)"
                @change="handleSourceChange(source)"
              />
            </div>
            <div>
              <label for="other_source"> Other </label>
              <input
                name="other_source"
                type="checkbox"
                @change="
                  state.customSourceFieldVisible =
                    !state.customSourceFieldVisible
                "
              />
            </div>
          </div>
          <div v-if="state.customSourceFieldVisible">
            <label for="custom_source"> Custom Source </label>
            <input
              type="text"
              name="custom_source"
              :class="styleClasses.input"
              v-model="state.customSource"
              @change="handleCustomSourceChange"
            />
          </div>
        </div>

        <!-- CHECKS -->
        <Checks
          v-if="form.status === 'Paid'"
          :checks="form.checks"
          @checks-change="handleChecksUpdate"
        />

        <!-- FEES -->
        <Fees :fees="form.fees" @handleUpdate="handleFeesUpdate" />

        <!-- NOTES -->
        <div class="my-4">
          <label for="notes">Notes</label>
          <textarea
            type="text"
            name="notes"
            class="w-full h-36 p-3 border border-black rounded-lg bg-gray-100 text-base"
            v-model="form.notes"
          />
        </div>
      </div>
    </div>
    <!-- SUBMIT BUTTON -->
    <button
      type="submit"
      class="text-white p-2.5 my-4 bg-[#ba7b65] uppercase block rounded-lg"
    >
      Save
    </button>
  </form>
</template>

<script>
import { useForm } from "@inertiajs/vue3";
import Fees from "./Fees.vue";
import Checks from "./Checks.vue";
import { reactive, watch } from "vue";
import ReturnToDashboardButton from "@/Pages/Sales/Components/SaleInfoForm/ReturnToDashboardButton.vue";
import {
  stateOptions,
  statusOptions,
  buyerOptions,
  splitTypes,
} from "../../index.data";
import {
  validateEmail,
  validatePrice,
  validateZipcode,
} from "@/Utils/Validators";
import { toast } from "vue3-toastify";

export default {
  name: "SaleInfoForm",
  components: {
    Fees,
    Checks,
    ReturnToDashboardButton,
  },
  props: {
    users: Array,
    sources: Object,
    sale: Object,
    handleSubmit: Function,
  },
  setup(props) {
    const state = reactive({
      isAddingNewFee: false,
      newFee: {},
      newCheck: {},
      agentCommission: 0,
      customSourceFieldVisible: false,
      customSource: "",
      formError: {
        zip: "",
        email: "",
        askingPrice: "",
        closingPrice: "",
      },
    });

    const form = useForm({
      user_id: "",
      client_name: "",
      client_email: "",
      address: "",
      city: "",
      state: "",
      zip: "",
      asking_price: "",
      sold_price: "",
      percentage: "",
      agent_split_percentage: "",
      pending_date: "",
      closing_date: "",
      status: "Pending",
      sources: [],
      fees: [],
      checks: [],
      buyer: null,
      notes: "",
    });

    const styleClasses = {
      input: "rounded-lg block bg-[#f8f8f8]",
    };

    const handleDateChange = () => {
      const pendingDate = new Date(form["pending_date"]);
      const closingDate = new Date(form["closing_date"]);
      if (closingDate < pendingDate) {
        toast("Closing date cannot be before pending date", {
          autoClose: 1000,
          position: "top-center",
          hideProgressBar: true,
        });
        return;
      }
    };

    const validateInput = (field) => {
      switch (field) {
        case "email":
          if (!validateEmail(form.client_email)) {
            state.formError[field] = "Please enter a valid email";
          }
          break;
        case "zip":
          if (!validateZipcode(form.zip)) {
            state.formError[field] = "Please enter a valid zip code";
          }
          break;
        case "askingPrice":
          if (!validatePrice(form.asking_price)) {
            state.formError[field] = "Please enter a valid asking price";
          } else {
            // strip out commas and $ sign
            form.asking_price = form.asking_price.replace(/[$,]/g, "");
          }
          break;
        case "soldPrice":
          if (!validatePrice(form.sold_price)) {
            state.formError[field] = "Please enter a valid sold price";
          } else {
            // strip out commas and $ sign
            form.sold_price = form.sold_price.replace(/[$,]/g, "");
          }
          break;
        default:
          false;
      }
    };

    const handleSourceChange = (source) => {
      const sourceIndex = form.sources.findIndex(
        (obj) => obj.name === source.name
      );
      if (sourceIndex === -1) {
        form.sources.push({
          name: state.customSource || source.name,
        });
      } else {
        form.sources.splice(sourceIndex, 1);
      }
    };

    const handleCustomSourceChange = () => {
      if (state.customSource) {
        form.sources.push({
          name: state.customSource,
        });
      }
    };

    const handleFeesUpdate = (fees) => {
      form.fees = fees;
    };

    const handleSubmit = async () => {
      props.handleSubmit(form);
    };

    watch(
      () => props.sale,
      (newSale) => {
        if (newSale) {
          Object.keys(form).forEach((key) => {
            if (newSale[key] !== undefined) {
              form[key] = newSale[key];
            }
          });
        }
      },
      { immediate: true }
    );

    return {
      state,
      form,
      styleClasses,
      stateOptions,
      statusOptions,
      buyerOptions,
      splitTypes,
      validateEmail,
      validatePrice,
      validateZipcode,
      handleFeesUpdate,
      handleDateChange,
      validateInput,
      handleSourceChange,
      handleCustomSourceChange,
      handleSubmit,
    };
  },
};
</script>
