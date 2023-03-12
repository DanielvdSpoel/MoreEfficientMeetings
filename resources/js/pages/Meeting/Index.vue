<template>
    <BaseLayout>
        <template v-slot:content>
            <h1 class="block text-xl font-medium leading-6 text-gray-900">
                Upcoming meetings
            </h1>
            <div class="mt-5 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
                <div v-for="meeting in meetings" :key="meeting.id"  class="bg-white rounded-md p-4">
                    <h2 class="leading font-medium text-lg">{{ meeting.title }}</h2>
                    <p class="italic text-gray-500">{{ meeting.description }}</p>
                    <div class="mt-2">
                        <div class="flex gap-2 mt-1">
                            <i class="fa-regular fa-calendar-days text-primary-600 mt-0.5"></i>
                            <p class="text-sm">{{ meeting.date }}</p>
                        </div>
                        <div class="flex gap-2 mt-1">
                            <i class="fa-regular fa-clock text-primary-600 mt-0.5"></i>
                            <p class="text-sm">{{ meeting.start_time }}</p>
                        </div>
                        <div class="flex gap-2 mt-1">
                            <i class="fa-regular fa-hourglass text-primary-600 mt-0.5"></i>
                            <p class="text-sm">{{ meeting.duration }} minutes</p>
                        </div>
                        <div class="flex gap-2 mt-1">
                            <i class="fa-solid fa-users text-primary-600 mt-0.5"></i>
                            <p class="text-sm">{{ meeting.attendees.length > 0 ? meeting.attendees.map(a => a.name).join(", ") : "No attendees"}}</p>
                        </div>
                    </div>
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
