<template>
    <BaseField
        :name="name"
        :label="label"
        :description="description"
        :required="required"
        :form="form"
    >
    <textarea
        :id="name"
        :name="name"
        :rows="rows"
        v-on:input="$emit('update:modelValue', $event.target.value)"
        v-bind:value="modelValue"
        class="block mt-1 w-full pr-10 border-gray-300 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm rounded-md"
        :placeholder="placeholder"
        v-bind:class="{
        'border-red-300 text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500':
          error,
      }"
    >
    </textarea>
    </BaseField>
</template>

<script>
import BaseField from "@/components/Fields/BaseField.vue";

export default {
    name: "TextareaField",
    components: {BaseField},
    props: {
        modelValue: String,
        form: Object,
        name: String,
        label: String,
        placeholder: String,
        description: String,
        required: Boolean,
        type: String,
        disabled: Boolean,
        autocomplete: String,

        rows: {
            type: Number,
            default: 5,
        },
    },
    computed: {
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
};
</script>

<style scoped></style>
