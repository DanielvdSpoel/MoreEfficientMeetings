<template>
  <div
    class="bg-white rounded-md text-center lg:col-start-8 lg:col-end-13 lg:row-start-1 xl:col-start-9"
  >
    <div class="flex items-center text-gray-900 px-4 pt-4">
      <button
        type="button"
        @click="previousYear"
        class="-m-1.5 flex flex-none items-center justify-center p-1.5 text-gray-400 hover:text-gray-500"
      >
        <span class="sr-only">Previous year</span>
        <svg
          xmlns="http://www.w3.org/2000/svg"
          class="h-5 w-5"
          fill="none"
          viewBox="0 0 24 24"
          stroke="currentColor"
          stroke-width="2"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            d="M15 19l-7-7 7-7"
          />
        </svg>
      </button>
      <div class="flex-auto font-semibold cursor-pointer py-2 px-4">
        {{ year }}
      </div>
      <button
        type="button"
        @click="nextYear"
        class="-m-1.5 flex flex-none items-center justify-center p-1.5 text-gray-400 hover:text-gray-500"
      >
        <span class="sr-only">Next year</span>
        <svg
          xmlns="http://www.w3.org/2000/svg"
          class="h-5 w-5"
          fill="none"
          viewBox="0 0 24 24"
          stroke="currentColor"
          stroke-width="2"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            d="M9 5l7 7-7 7"
          />
        </svg>
      </button>
    </div>
    <div
      class="isolate mt-2 grid grid-cols-4 gap-px rounded-lg bg-gray-200 text-sm shadow ring-1 ring-gray-200"
    >
      <button
        v-for="(day, dayIdx) in months"
        :key="day.monthLong"
        type="button"
        @click="handleClick(day)"
        :class="[
          'py-1.5 hover:bg-gray-100 focus:z-10 bg-white',
          (isSelected(day) || monthIsCurrentMonth(day)) && 'font-semibold',
          isSelected(day) && 'text-white',
          !isSelected(day) && 'text-gray-900',
          monthIsCurrentMonth(day) && !isSelected(day) && 'text-primary-600',
          dayIdx === 0 && 'rounded-tl-lg',
          dayIdx === 3 && 'rounded-tr-lg',
          dayIdx === months.length - 4 && 'rounded-bl-lg',
          dayIdx === months.length - 1 && 'rounded-br-lg',
        ]"
      >
        <time
          :datetime="day.monthLong"
          :class="[
            'mx-auto flex h-7 w-[5rem] items-center justify-center rounded-full',
            isSelected(day) && monthIsCurrentMonth(day) && 'bg-primary-600',
            isSelected(day) && !monthIsCurrentMonth(day) && 'bg-gray-900',
          ]"
        >
          {{ day.monthLong }}
        </time>
      </button>
    </div>
  </div>
</template>

<script>
import { DateTime } from "luxon";

export default {
  name: "MonthSelector",
  props: {
    monthYear: Number,
    month: Number,
    year: Number,
  },
  data() {
    return {
      months: [],
    };
  },
  watch: {
    year() {
      this.generateMonths();
    },
  },
  mounted() {
    this.generateMonths();
  },
  methods: {
    isSelected(date) {
      return this.monthYear === date.year && this.month === date.month;
    },
    nextYear() {
      this.$emit("updateYear", this.year + 1);
    },
    previousYear() {
      this.$emit("updateYear", this.year - 1);
    },
    capitalize(s) {
      return s[0].toUpperCase() + s.slice(1);
    },
    handleClick(date) {
      this.$emit("updateMonth", date.month, date.year);
      this.$emit("switchView", "days");
    },
    monthIsCurrentMonth(date) {
      const today = DateTime.now();
      today.setZone("Europe/Amsterdam");
      return date.month === today.month && this.year === today.year;
    },

    generateMonths() {
      if (!this.year) return;
      this.months = [];
      let calculationDate = DateTime.local(this.year, 1, 1);
      calculationDate.setZone("Europe/Amsterdam");
      for (let i = 0; i < 12; i++) {
        this.months.push(calculationDate);
        calculationDate = calculationDate.plus({ months: 1 });
      }
    },
  },
};
</script>

<style scoped></style>
