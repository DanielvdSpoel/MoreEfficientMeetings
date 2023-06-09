<template>
    <BaseLayout>
        <template v-slot:content>
            <h4 class="sr-only">Status</h4>
            <h1 class="font-medium text-xl text-gray-900">Creating a new meeting</h1>
            <Steps :currentStep="step" @setStep="setStep"/>
            <div class="divide-y divide-gray-200 overflow-hidden rounded-lg bg-white shadow mt-6">
                <div class="px-4 py-3 sm:p-5 gap-2">
                    <div v-if="step === 1">
                        <InputField
                            label="Meeting title"
                            name="title"
                            v-model="form.title"
                            type="text"
                        />
                        <TextareaField
                            label="Meeting description"
                            v-model="form.description"
                            name="description"
                            type="text"/>
                        <div class="flex gap-2 mt-2">
                            <ArrowPathIcon v-if="shouldThisBeAMeetingData.loading"
                                           class="mt-1 text-indigo-500 h-4 w-4 animate-spin"/>
                            <button class="text-sm text-indigo-500 hover:text-indigo-700 underline cursor-pointer"
                                    :disabled="shouldThisBeAMeetingData.loading" @click="shouldThisBeAMeeting">Should
                                this be a meeting?
                            </button>
                        </div>
                        <p v-if="shouldThisBeAMeetingData.result" class="text-sm text-gray-500 italic cursor-pointer">
                            {{ shouldThisBeAMeetingData.result }}</p>

                    </div>
                    <div v-if="step === 2">
                        <Combobox as="div" v-model="form.attendees" multiple>
                            <ComboboxLabel class="block text-sm font-medium leading-6 text-gray-900">
                                Invite attendees
                            </ComboboxLabel>
                            <div class="relative mt-2">
                                <ComboboxInput
                                    class="w-full rounded-md border-0 bg-white py-1.5 pl-3 pr-12 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    @change="combobox.query = $event.target.value" :display-value="(user) => user?.name"
                                />
                                <ComboboxButton
                                    class="absolute inset-y-0 right-0 flex items-center rounded-r-md px-2 focus:outline-none">
                                    <ChevronUpDownIcon class="h-5 w-5 text-gray-400" aria-hidden="true"/>
                                </ComboboxButton>

                                <ComboboxOptions v-if="combobox.filteredUsers.length > 0"
                                                 class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm">
                                    <ComboboxOption v-for="user in combobox.filteredUsers" :key="user.username"
                                                    :value="user" as="template" v-slot="{ active, selected }">
                                        <li :class="['relative cursor-default select-none py-2 pl-3 pr-9', active ? 'bg-indigo-600 text-white' : 'text-gray-900']">
                                            <div class="flex">
                                                <span :class="['truncate', selected && 'font-semibold']">
                                                    {{ user.name }}
                                                </span>
                                                <span
                                                    :class="['ml-2 truncate text-gray-500', active ? 'text-indigo-200' : 'text-gray-500']">
                                                    {{ user.role }}
                                                </span>
                                            </div>

                                            <span v-if="selected"
                                                  :class="['absolute inset-y-0 right-0 flex items-center pr-4', active ? 'text-white' : 'text-indigo-600']">
                                                <CheckIcon class="h-5 w-5" aria-hidden="true"/>
                                            </span>
                                        </li>
                                    </ComboboxOption>
                                </ComboboxOptions>
                            </div>
                        </Combobox>
                        <div class="overflow-hidden mt-4 sm:rounded-md">
                            <p class="block text-lg font-medium leading-6 text-gray-900">
                                Selected attendees
                            </p>
                            <ul role="list" class="divide-y divide-gray-200">
                                <li v-for="attendee in form.attendees" :key="attendee.id">
                                    <div class="block group hover:bg-gray-50 cursor-pointer" @click="removeAttendee(attendee)">
                                        <div class="flex items-center px-4 py-4 sm:px-6">
                                            <div class="flex min-w-0 flex-1 items-center">
                                                <div class="flex-shrink-0">
                                                    <img class="h-12 w-12 rounded-full" :src="'https://ui-avatars.com/api/?name=' + attendee.name + '&background=e5e7eb'" alt="" />
                                                </div>
                                                <div class="min-w-0 flex-1 px-4 md:grid md:grid-cols-2 md:gap-4">
                                                    <div>
                                                        <p class="truncate text-sm font-medium text-indigo-600">{{ attendee.name }}</p>
                                                        <p class="mt-2 flex items-center text-sm text-gray-500">
                                                            <EnvelopeIcon class="mr-1.5 h-5 w-5 flex-shrink-0 text-gray-400" aria-hidden="true" />
                                                            <span class="truncate">{{ attendee.email }}</span>
                                                        </p>
                                                    </div>
                                                    <div class="hidden md:block">
                                                        <div>
                                                            <p class="text-sm text-gray-900">
                                                                {{ attendee.role}}
                                                            </p>
                                                            <p class="mt-2 flex items-center text-sm text-gray-500">
                                                                {{ getSkills(attendee) }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div>
                                                <TrashIcon class="h-5 w-5 text-red-600 hover:text-red-700 group-hover:text-red-700" aria-hidden="true" />
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="flex gap-2 mt-2">
                            <ArrowPathIcon v-if="attendeesSuggestionData.loading"
                                           class="mt-1 text-indigo-500 h-4 w-4 animate-spin"/>
                            <button class="text-sm text-indigo-500 hover:text-indigo-700 underline cursor-pointer"
                                    :disabled="attendeesSuggestionData.loading" @click="getAttendeeSuggestions">
                                {{ attendeesSuggestionData.text }}
                            </button>
                        </div>
                        <div v-if="attendeesSuggestionData.result.length > 0" class="overflow-hidden mt-4 sm:rounded-md">
                            <p class="block text-sm font-medium leading-6 text-gray-900">
                                Suggested attendees
                            </p>
                            <ul role="list" class="divide-y divide-gray-200">
                                <li v-for="attendee in attendeesSuggestionData.result" :key="attendee.id">
                                    <div class="block group hover:bg-gray-50 cursor-pointer" @click="addAttendee(attendee)">
                                        <div class="flex items-center px-4 py-4 sm:px-6">
                                            <div class="flex min-w-0 flex-1 items-center">
                                                <div class="flex-shrink-0">
                                                    <img class="h-12 w-12 rounded-full" :src="'https://ui-avatars.com/api/?name=' + attendee.name + '&background=e5e7eb'" alt="" />
                                                </div>
                                                <div class="min-w-0 flex-1 px-4 md:grid md:grid-cols-2 md:gap-4">
                                                    <div>
                                                        <p class="truncate text-sm font-medium text-indigo-600">{{ attendee.name }}</p>
                                                        <p class="mt-2 flex items-center text-sm text-gray-500">
                                                            <span>{{ attendee.reason }}</span>
                                                        </p>
                                                    </div>
                                                    <div class="hidden md:block">
                                                        <div>
                                                            <p class="text-sm text-gray-900">
                                                                {{ attendee.role }}
                                                            </p>
                                                            <p class="mt-2 flex items-center text-sm text-gray-500">
                                                                {{ attendee.email }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div>
                                                <PlusCircleIcon class="h-5 w-5 text-green-600 hover:text-green-700 group-hover:text-green-700" aria-hidden="true" />
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>

                    </div>
                    <div v-if="step === 3">
                        <InputField
                            label="Meeting duration (in minutes)"
                            type="number"
                            :min="0"
                            v-model="form.duration"
                            name="duration"

                        />
                        <div class="grid grid-cols-4 gap-4">
                            <div class="col-span-3">
                                <DateField
                                    label="Find availibility before"
                                    v-model="date_range"
                                    name="date"
                                />
                            </div>
                            <div class="mt-7">
                                <button @click="checkAvailability"
                                    class="flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                    Find best time and date
                                </button>
                            </div>
                        </div>

                        <div v-if="availability">
                            <h3 class="text-sm font-medium leading-6 text-gray-900 mt-4">Availability:</h3>
                            <div v-if="availability.length > 0" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 mt-4">
                                <AvailabilitySelectorCard v-for="availability in availability" :key="availability.id" @selected="selectAvailability"
                                                          :attendees="attendees" :requiredAttendees="form.attendees" :selectedAvailability="form.selectedAvailability" :availability="availability"/>
                            </div>
                            <div v-else>
                                <p class="text-sm text-red-500 cursor-pointer">
                                    No availability found
                                </p>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="px-4 py-3 sm:px-5 flex justify-between">
                    <button @click="step--" :disabled="step === 1"
                            class="flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gray-600 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                        Previous step
                    </button>
                    <button @click="step++" v-if="step !== 3" :disabled="step === 3"
                            class="flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        Next step
                    </button>
                    <button v-if="step === 3" @click="submitForm"
                            class="flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        Create meeting
                    </button>
                </div>
            </div>
        </template>
    </BaseLayout>
</template>

<script>
import BaseLayout from "@/layouts/BaseLayout.vue";
import InputField from "@/components/fields/InputField.vue";
import TextareaField from "@/components/fields/TextareaField.vue";
import {router, useForm} from "@inertiajs/vue3";
import {
    ArrowPathIcon,
    ChevronUpDownIcon,
    CheckIcon,
    TrashIcon,
    EnvelopeIcon,
    PlusCircleIcon
} from "@heroicons/vue/20/solid";
import {Combobox, ComboboxButton, ComboboxInput, ComboboxLabel, ComboboxOption, ComboboxOptions} from "@headlessui/vue";
import Steps from "@/components/Steps.vue";
import DateField from "@/components/fields/DateField.vue";
import AvailabilitySelectorCard from "@/components/AvailabilitySelectorCard.vue";

export default {
    name: "Create",
    components: {
        AvailabilitySelectorCard,
        DateField,
        Steps,
        ComboboxOption,
        ComboboxOptions,
        Combobox,
        ComboboxLabel,
        ComboboxButton,
        ComboboxInput,
        TextareaField,
        InputField,
        BaseLayout,
        ArrowPathIcon,
        CheckIcon,
        ChevronUpDownIcon,
        TrashIcon,
        EnvelopeIcon,
        PlusCircleIcon,
    },
    props: {
        attendees: {
            type: Array,
            required: true
        }
    },
    data() {
        return {
            form: useForm({
                title: '',
                description: '',
                date: '',
                duration: '',
                start_time: '',
                attendees: [],
                selectedAvailability: null,
            }),
            availability: null,
            date_range: null,
            shouldThisBeAMeetingData: {
                loading: false,
                result: null,
            },
            attendeesSuggestionData: {
                loading: false,
                result: [],
                text: "Want some suggestions for attendees?",
            },
            combobox: {
                query: '',
                filteredUsers: [],
            },
            step: 1,
        }
    },
    watch: {
        'combobox.query': function (newVal) {
            this.combobox.filteredUsers = this.attendees.filter(user => user.name.toLowerCase().includes(newVal.toLowerCase()))
        }
    },
    methods: {
        checkAvailability() {
            window.axios.get(route('meetings.availability-check') + '?date=' + this.date_range + '&duration=' + this.form.duration + '&attendees=' + this.form.attendees.map(a => a.id).join(','))
                .then(response => {
                    console.log(response.data)
                    this.availability = response.data
                })
                .catch(error => console.log(error))

        },
        setStep(step) {
            this.step = step
        },
        addAttendee(attendee) {
            if (!this.form.attendees.find(a => a.id === attendee.id)) {
                this.form.attendees.push(attendee)
                this.attendeesSuggestionData.result = this.attendeesSuggestionData.result.filter(a => a.id !== attendee.id)
            }
        },
        getSkills(attendee) {
            const skills = []
            attendee.skills.forEach(skill => {
                skills.push(skill.name)
            })
            return skills.join(', ')
        },
        removeAttendee(attendee) {
            this.form.attendees = this.form.attendees.filter(a => a.id !== attendee.id)
        },
        shouldThisBeAMeeting() {
            this.shouldThisBeAMeetingData.loading = true
            window.axios.get(route('ai.should-this-be-a-meeting') + '?title=' + this.form.title + '&description=' + this.form.description)
                .then(response => {
                    this.shouldThisBeAMeetingData.result = response.data.choices[0].text
                })
                .finally(() => {
                    this.shouldThisBeAMeetingData.loading = false
                });
        },
        getAttendeeSuggestions() {
            this.attendeesSuggestionData.loading = true
            window.axios.get(route('ai.suggest-attendees') + '?title=' + this.form.title + '&description=' + this.form.description)
                .then(response => {
                    const temp = response.data.choices[0].text
                    temp.replace(/(\r\n|\n|\r)/gm, " ")
                    console.log(temp)

                    const data = JSON.parse(temp)
                    Object.keys(data).forEach(key => {
                        const attendee = this.attendees.find(attendee => attendee.name === key)
                        attendee.reason = data[key]
                        this.attendeesSuggestionData.result.push(attendee);
                    })
                })
                .finally(() => {
                    this.attendeesSuggestionData.loading = false
                    this.attendeesSuggestionData.text = "Want some more suggestions for attendees?"
                });
        },
        selectAvailability(availability) {
            this.form.selectedAvailability = availability
        },
        submitForm() {
            this.form
                .transform((data) => ({
                    title: data.title,
                    description: data.description,
                    date: data.selectedAvailability.date,
                    duration: data.duration,
                    start_time: data.selectedAvailability.start_time,
                    attendees: data.attendees.map(a => a.id),
                }))
                .post(route('meetings.store'), {

            })
        }

    }
}
</script>

<style scoped>

</style>
