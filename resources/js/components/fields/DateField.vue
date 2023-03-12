<template>
  <div v-click-away="onClickAway">
    <div class="flex mt-1">
      <label :for="name" class="block text-sm font-medium text-gray-700 mt-1">{{
        label
      }}</label>
      <span class="text-red-600 ml-0.5" v-if="required">*</span>
    </div>
    <input
      type="text"
      :name="name"
      :id="name"
      v-model="displayValue"
      readonly
      @click="handleInputClick"
      class="block w-full pr-10 border-gray-300 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm rounded-md"
    />
    <div v-show="isSelectorOpen" class="mt-2">
      <DateSelector
        v-if="view === 'days'"
        :year="year"
        :month="month"
        @switch-view="switchView"
        :disabledDates="disabledDates"
        @update-year="updateYear"
        @update-month="updateMonth"
        @update-date="updateDate"
        :allowMultipleDates="allowMultipleDates"
        :format="format"
        v-on:input="$emit('update:modelValue', $event.target.value)"
        v-bind:value="modelValue"
      />
      <MonthSelector
        v-else-if="view === 'months'"
        :year="year"
        :month="month"
        :month-year="monthYear"
        @switch-view="switchView"
        @update-year="updateYear"
        @update-month="updateMonth"
      />
    </div>
    <p v-if="error" class="mt-2 text-sm text-red-600" id="email-error">
      {{ error }}
    </p>
  </div>
</template>

<script>
import { DateTime } from "luxon";
import MonthSelector from "@/components/fields/DateField/MonthSelector.vue";
import DateSelector from "@/components/fields/DateField/DateSelector.vue";

export default {
  name: "DateField",
  components: { MonthSelector, DateSelector },
  props: {
    // eslint-disable-next-line vue/require-prop-type-constructor
    modelValue: String | Array,
    name: String,
    label: String,
    required: Boolean,
    disabledDates: {
      type: Array,
      // eslint-disable-next-line vue/require-valid-default-prop
      default: [],
    },
    allowMultipleDates: {
      type: Boolean,
      default: false,
    },
    format: {
      type: String,
      default: "dd-MM-yyyy",
    },
  },
  data() {
    return {
      isSelectorOpen: false,
      view: "days",
      year: null,
      monthYear: null,
      month: null,
      falseEvent: false,
    };
  },
  mounted() {
    if (this.modelValue) {
      let date;
      if (typeof this.modelValue === "string") {
        date = DateTime.fromFormat(this.modelValue, this.format);
      } else if (Array.isArray(this.modelValue)) {
        date = DateTime.fromFormat(
          this.modelValue[this.modelValue.length - 1],
          this.format
        );
      }
      this.year = date.year;
      this.monthYear = date.year;
      this.month = date.month;
    } else {
      const now = DateTime.now().setZone("Europe/Amsterdam");
      this.year = now.year;
      this.monthYear = now.year;
      this.month = now.month;
    }
  },
  computed: {
    displayValue() {
      if (this.modelValue) {
        if (typeof this.modelValue === "string") {
          return this.modelValue;
        } else if (Array.isArray(this.modelValue)) {
          return this.modelValue.join(", ");
        }
      }
      return "";
    },
      error() {
          if (this.form) {
              if (this.form.errors[this.name]) {
                  return this.form.errors[this.name];
              }
              return null
          }
          if (this.$page.props.errors[this.name]) {
              return this.$page.props.errors[this.name]
          }
          return null
      }
  },
  methods: {
    switchView(view) {
      this.falseEvent = true;
      this.view = view;

      const that = this;
      setTimeout(function () {
        that.falseEvent = false;
      }, 1000);
    },
    updateYear(year) {
      this.year = year;
    },
    updateMonth(month, year = null) {
      this.falseEvent = true;

      if (year) {
        this.monthYear = year;
      }
      this.month = month;

      const that = this;
      setTimeout(function () {
        that.falseEvent = false;
      }, 1000);
    },
    updateDate(dates) {
      if (!this.allowMultipleDates) {
        this.isSelectorOpen = false;
      }
      this.$emit("update:modelValue", dates);
    },
    handleInputClick() {
      if (this.isSelectorOpen) {
        this.isSelectorOpen = false;
      } else {
        this.isSelectorOpen = true;
        this.isOpening = true;
      }
    },
    onClickAway() {
      const that = this;
      setTimeout(function () {
        if (!that.falseEvent) {
          that.isSelectorOpen = false;
        }
      }, 100);
      console.log("Clicking away");
    },
  },
};
</script>

<style scoped></style>
