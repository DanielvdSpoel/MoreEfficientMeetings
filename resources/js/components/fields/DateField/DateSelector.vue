<template>
  <div
    class="bg-white rounded-md text-center lg:col-start-8 lg:col-end-13 lg:row-start-1 xl:col-start-9"
  >
    <div class="flex items-center text-gray-900 px-4 pt-4">
      <button
        type="button"
        @click="previousMonth"
        class="-m-1.5 flex flex-none items-center justify-center p-1.5 text-gray-400 hover:text-gray-500"
      >
        <span class="sr-only">Previous month</span>
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
      <button
        class="flex-auto font-semibold cursor-pointer hover:bg-gray-50 py-2 px-4"
        @click="$emit('switchView', 'months')"
      >
        {{ capitalize(translatedMonth) }} {{ year }}
      </button>
      <button
        type="button"
        @click="nextMonth"
        class="-m-1.5 flex flex-none items-center justify-center p-1.5 text-gray-400 hover:text-gray-500"
      >
        <span class="sr-only">Next month</span>
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
    <div class="mt-6 grid grid-cols-7 text-xs leading-6 text-gray-500">
      <div v-for="header in headers" :key="header">{{ header }}</div>
    </div>
    <div
      class="isolate mt-2 grid grid-cols-7 gap-px rounded-lg bg-gray-200 text-sm shadow ring-1 ring-gray-200"
    >
      <button
        v-for="(day, dayIdx) in dates"
        :key="day.date"
        type="button"
        @click="handleClick(day)"
        :class="[
          'py-1.5 hover:bg-gray-100 focus:z-10',
          dayInCurrentMonth(day)
            ? isDateDisabled(day)
              ? 'bg-gray-100 cursor-not-allowed'
              : 'bg-white'
            : 'bg-gray-50',
          (isSelected(day) || dayIsToday(day)) && 'font-semibold',
          isSelected(day) && 'text-white',
          !isSelected(day) &&
            dayInCurrentMonth(day) &&
            !dayIsToday(day) &&
            'text-gray-900',
          !isSelected(day) &&
            !dayInCurrentMonth(day) &&
            !dayIsToday(day) &&
            'text-gray-400',
          dayIsToday(day) && !isSelected(day) && 'text-primary-600',
          dayIdx === 0 && 'rounded-tl-lg',
          dayIdx === 6 && 'rounded-tr-lg',
          dayIdx === dates.length - 7 && 'rounded-bl-lg',
          dayIdx === dates.length - 1 && 'rounded-br-lg',
        ]"
      >
        <time
          :datetime="day.toISODate()"
          :class="[
            'mx-auto flex h-7 w-7 items-center justify-center rounded-full',
            isSelected(day) && dayIsToday(day) && 'bg-primary-600',
            isSelected(day) && !dayIsToday(day) && 'bg-gray-900',
          ]"
        >
          {{ day.day }}
        </time>
      </button>
    </div>
  </div>
</template>

<script>
import { DateTime } from "luxon";

export default {
  name: "DateSelector",
  props: {
    format: {
      type: String,
      default: "dd-MM-yyyy",
    },
    // eslint-disable-next-line vue/require-prop-type-constructor
    modelValue: String | Array,
    allowMultipleDates: {
      type: Boolean,
      default: false,
    },
    disabledDates: {
      type: Array,
      default: () => [],
    },
    year: Number,
    month: Number,
  },
  data() {
    return {
      dates: [],
      selectedDates: [],
    };
  },
  mounted() {
    this.generateDates();
  },
  watch: {
    year() {
      this.generateDates();
    },
    month() {
      this.generateDates();
    },
  },
  computed: {
    translatedMonth() {
      let date = DateTime.now();
      date.setZone("Europe/Amsterdam");
      date = date.set({ month: this.month });
      return date.monthLong;
    },
    headers() {
      const headers = [];
      let date = DateTime.now();
      date.setZone("Europe/Amsterdam");
      date = date.startOf("week");
      for (let i = 0; i < 7; i++) {
        headers.push(date.weekdayShort.toLocaleUpperCase("nl-NL"));
        date = date.plus({ days: 1 });
      }
      return headers;
    },
  },
  methods: {
    isDateDisabled(day) {
      if (this.disabledDates.length === 0) {
        return false;
      }
      let result = false;
      this.disabledDates.forEach((disabledDate) => {
        switch (typeof disabledDate) {
          case "string":
            if (day.toISODate() === disabledDate) {
              result = true;
            }
            break;
          case "object":
            if (day.toISODate() === disabledDate.toISODate()) {
              result = true;
            }
            break;
          case "function":
            if (disabledDate(day.toJSDate())) {
              result = true;
            }
            break;
        }
      });
      return result;
    },
    isSelected(day) {
      if (this.dayInCurrentMonth(day) && this.modelValue) {
        if (this.allowMultipleDates) {
          return this.modelValue.includes(day.toFormat(this.format));
        } else {
          const date = DateTime.fromFormat(this.modelValue, this.format);
          return date.toISODate() === day.toISODate();
        }
      }
      return false;
    },
    nextMonth() {
      if (this.month === 12) {
        this.$emit("updateMonth", 1);
        this.$emit("updateYear", this.year + 1);
        this.generateDates(1, this.year + 1);
      } else {
        this.$emit("updateMonth", this.month + 1);
        this.generateDates(this.month + 1, this.year);
      }
    },
    previousMonth() {
      if (this.month === 1) {
        this.$emit("updateMonth", 12);
        this.$emit("updateYear", this.year - 1);
        this.generateDates(12, this.year - 1);
      } else {
        this.$emit("updateMonth", this.month - 1);
        this.generateDates(this.month - 1, this.year);
      }
    },
    capitalize(s) {
      return s[0].toUpperCase() + s.slice(1);
    },
    handleClick(day) {
      if (day.month !== this.month) {
        this.$emit("updateMonth", day.month);
        this.$emit("updateYear", day.year);
        return;
      }
      if (this.isDateDisabled(day)) return;

      const formattedDate = day.toFormat(this.format);
      if (this.allowMultipleDates) {
        if (this.modelValue.includes(formattedDate)) {
          this.$emit(
            "updateDate",
            this.modelValue.filter((date) => date !== formattedDate)
          );
        } else {
          this.$emit("updateDate", [...this.modelValue, formattedDate]);
        }
      } else {
        this.$emit("updateDate", formattedDate);
      }
    },
    dayIsToday(day) {
      const today = DateTime.now();
      today.setZone("Europe/Amsterdam");
      return day.toISODate() === today.toISODate();
    },
    dayInCurrentMonth(day) {
      return day.month === this.month;
    },
    generateDates() {
      this.dates = [];
      if (!this.year && !this.month) {
        return;
      }
      console.log(this.month);
      console.log(this.year);

      let calculationDate = DateTime.now();
      calculationDate.setZone("Europe/Amsterdam");
      calculationDate = calculationDate.set({
        month: this.month,
        year: this.year,
      });

      //Go to the starting point
      const firstDayOfMonth = calculationDate.startOf("month");

      //Add all the days of past month
      calculationDate = firstDayOfMonth.minus({
        days: firstDayOfMonth.weekday - 1,
      });

      //Also prevent out of memory in case this is infinite loop
      let cycles = 0;
      while (cycles < 30 && calculationDate.month !== this.month) {
        this.dates.push(calculationDate);
        calculationDate = calculationDate.plus({ days: 1 });
        cycles++;
      }

      //Add all the days of this month
      calculationDate = firstDayOfMonth;
      while (calculationDate.month === this.month) {
        this.dates.push(calculationDate);
        calculationDate = calculationDate.plus({ days: 1 });
      }

      //Add all the days of next month
      while (this.dates.length < 42) {
        this.dates.push(calculationDate);
        calculationDate = calculationDate.plus({ days: 1 });
      }
    },
  },
};
</script>

<style scoped></style>
