<template>
    <BaseLayout>
        <template v-slot:content>
            <h4 class="sr-only">Status</h4>
            <h1 class="font-medium text-xl text-gray-900">Creating a new meeting</h1>
            <div class="mt-6" aria-hidden="true">
                <div class="overflow-hidden rounded-full bg-gray-200">
                    <div class="h-2 rounded-full bg-indigo-600" style="width: 37.5%"/>
                </div>
                <div class="mt-2 hidden grid-cols-3 text-sm font-medium text-gray-600 sm:grid">
                    <div class="text-indigo-600">Meeting details</div>
                    <div class="text-center text-indigo-600">Attendees</div>
                    <div class="text-center">Date and time</div>
                </div>
            </div>

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
                                                                {{ attendee.skills.join(', ') }}
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
            </div>
        </template>
    </BaseLayout>
</template>

<script>
import BaseLayout from "@/layouts/BaseLayout.vue";
import InputField from "@/components/fields/InputField.vue";
import TextareaField from "@/components/fields/TextareaField.vue";
import {useForm} from "@inertiajs/vue3";
import {ArrowPathIcon, ChevronUpDownIcon, CheckIcon, TrashIcon, EnvelopeIcon} from "@heroicons/vue/20/solid";
import {Combobox, ComboboxButton, ComboboxInput, ComboboxLabel, ComboboxOption, ComboboxOptions} from "@headlessui/vue";

export default {
    name: "Create",
    components: {
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
                attendees: [],
            }),
            shouldThisBeAMeetingData: {
                loading: false,
                result: null,
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
        removeAttendee(attendee) {
            this.form.attendees = this.form.attendees.filter(a => a.id !== attendee.id)
        },
        shouldThisBeAMeeting() {
            this.shouldThisBeAMeetingData.loading = true
            window.axios.get(route('ai.should-this-be-a-meeting') + '?title=' + this.form.title + '&description=' + this.form.description)
                .then(response => {
                    this.shouldThisBeAMeetingData.result = response.data.choices[0].text
                    this.shouldThisBeAMeetingData.loading = false
                })
                .finally(() => {
                    this.shouldThisBeAMeetingData.loading = false
                });
        }
    }
}
</script>

<style scoped>

</style>
