<template>
    <BaseLayout>
        <div>
            <h4 class="sr-only">Status</h4>
            <h1 class="font-medium text-xl text-gray-900">Creating a new meeting</h1>
            <div class="mt-6" aria-hidden="true">
                <div class="overflow-hidden rounded-full bg-gray-200">
                    <div class="h-2 rounded-full bg-indigo-600" style="width: 37.5%" />
                </div>
                <div class="mt-2 hidden grid-cols-3 text-sm font-medium text-gray-600 sm:grid">
                    <div class="text-indigo-600">Meeting details</div>
                    <div class="text-center text-indigo-600">Attendees</div>
                    <div class="text-center">Date and time</div>
                </div>
            </div>

            <form class="divide-y divide-gray-200 overflow-hidden rounded-lg bg-white shadow mt-6">
                <div class="px-4 py-3 sm:p-5 gap-2">
                    <div v-if="step === 1">
                        <InputField
                            label="Meeting title"
                            name="title"
                            type="text"
                        />
                        <TextareaField
                            label="Meeting description"
                            name="description"
                            type="text"/>
                    </div>
                </div>
                <div class="px-4 py-3 sm:px-5 flex justify-between">
                    <button @click="step--" :disabled="step === 1"
                            class="flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gray-600 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                        Previous step
                    </button>
                    <button @click="step++" :disabled="step === 3"
                            class="flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        Next step
                    </button>
                </div>
            </form>

        </div>
    </BaseLayout>
</template>

<script>
import BaseLayout from "@/layouts/BaseLayout.vue";
import InputField from "@/components/fields/InputField.vue";
import TextareaField from "@/components/fields/TextareaField.vue";

export default {
    name: "Create",
    components: {TextareaField, InputField, BaseLayout},
    data() {
        return {
            form: useForm({
                title: '',
                description: '',
            }),
            step: 1,

        }
    },
}
</script>

<style scoped>

</style>
