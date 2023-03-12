<template>
    <BaseLayout>
        <template v-slot:content>
            <h1 class="block text-xl font-medium leading-6 text-gray-900">
                Upcoming meetings
            </h1>
            <div class="mt-5 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
                <div v-for="meeting in meetings" :key="meeting.id"  class="bg-white rounded-md p-2 gap-y-2">
                    <div class="flex justify-center">
                        <h2 class="leading font-medium text-sm">{{ meeting.title }}</h2>
                    </div>
                    <p class="text-sm">Date: {{ meeting.date }}</p>
                    <p class="text-sm">Time: {{ meeting.start_time }}</p>
                    <p class="text-sm">Invited users: {{ meeting.attendees.map(a => a.name).join(", ") }}</p>
                </div>
            </div>
        </template>
    </BaseLayout>
</template>

<script>
import BaseLayout from "@/layouts/BaseLayout.vue";

export default {
    name: "Index",
    components: {BaseLayout},
    props: {
        meetings: {
            type: Array,
            required: true
        }
    }
}
</script>

<style scoped>

</style>
