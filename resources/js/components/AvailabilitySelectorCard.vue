<template>
    <div class="bg-gray-50 rounded-md p-2 gap-y-2">
        <div class="flex justify-center">
            <h2 class="leading font-medium text-sm">{{ availability.date }}</h2>
        </div>
        <p class="text-sm">Time: {{ availability.start_time }}</p>
        <p class="text-sm">Available users: {{ availableAttendees.map(a => a.name).join(", ") }}</p>
        <p class="text-sm">Unavailable users: {{ unavailableAttendees.map(a => a.name).join(", ") }}</p>

        <div class="flex justify-center">
            <button @click="$emit('selected', availability)"
                    class="inline-flex mt-2 justify-center rounded-md bg-primary-700 py-1 px-2 text-sm font-medium text-white shadow-sm hover:bg-primary-800 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-900">
                {{ selectedAvailability !== null && selectedAvailability.id === availability.id ? 'Selected' : 'Select' }}
            </button>
        </div>
    </div>
</template>

<script>
export default {
    name: "AvailabilitySelectorCard",
    props: {
        selectedAvailability: {
            type: Object,
            required: true
        },
        availability: {
            type: Object,
            required: true
        },
        attendees: {
            type: Array,
            required: true
        },
        requiredAttendees: {
            type: Array,
            required: true
        }
    },
    computed: {
        availableAttendees() {
            return this.attendees.filter(attendee => this.availability.available_users.includes(attendee.id))
        },
        unavailableAttendees() {
            return this.attendees.filter(attendee => this.requiredAttendees.map(a => a.id).includes(attendee.id) && !this.availability.available_users.includes(attendee.id));
        }
    }
}
</script>

<style scoped>

</style>
