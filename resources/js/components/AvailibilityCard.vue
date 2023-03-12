<template>
    <div class="bg-gray-50 rounded-md p-2 gap-y-2">
        <div class="flex justify-center">
            <h2 class="leading font-medium text-sm">{{ availability.date }}</h2>
        </div>
        <p class="italic text-sm">from {{ availability.start_time }} until {{ availability.end_time }}</p>
        <div class="flex justify-center">
            <button @click="deleteAvailability"
                    class="inline-flex mt-2 justify-center rounded-md bg-red-700 py-1 px-2 text-sm font-medium text-white shadow-sm hover:bg-red-800 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-900">
                Delete
            </button>
        </div>
    </div>
</template>

<script>
import {router} from "@inertiajs/vue3";

export default {
    name: "AvailabilityCard",
    props: {
        availability: {
            type: Object,
            required: true
        }
    },
    methods: {
        deleteAvailability() {
            router.delete(route('profile.availability.delete', this.availability.id))
        }
    }
}
</script>

<style scoped>

</style>
