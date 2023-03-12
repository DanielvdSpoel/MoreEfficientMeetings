<template>
    <BaseLayout>
        <template v-slot:content>
            <div class="w-full flex justify-center">
                <div class="space-y-6 sm:px-6 lg:col-span-9 lg:px-0">
                    <section aria-labelledby="payment-details-heading">
                        <form @submit.prevent="profile.put(route('profile.update'))">
                            <div class="shadow sm:overflow-hidden sm:rounded-md">
                                <div class="bg-white py-6 px-4 sm:p-6">
                                    <div>
                                        <h2 id="payment-details-heading"
                                            class="text-lg font-medium leading-6 text-gray-900">Update account details</h2>
                                        <p class="mt-1 text-sm text-gray-500">
                                            Update your username and email
                                        </p>
                                    </div>

                                    <div class="mt-6 grid grid-cols-4 gap-6">
                                        <div class="col-span-4 sm:col-span-2">
                                            <InputField
                                                label="Name:"
                                                type="text"
                                                name="Name"
                                                v-model="profile.name"/>
                                        </div>

                                        <div class="col-span-4 sm:col-span-2">
                                            <InputField
                                                label="Email:"
                                                name="email"
                                                type="email"
                                                v-model="profile.email"/>
                                        </div>

                                    </div>
                                </div>
                                <div class="bg-gray-50 px-4 py-3 text-right sm:px-6">
                                    <button type="submit" :disabled="profile.processing"
                                            class="inline-flex justify-center rounded-md bg-gray-900 py-2 px-3 text-sm font-semibold text-white shadow-sm hover:bg-gray-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-900">
                                        Save
                                    </button>
                                </div>
                            </div>
                        </form>
                    </section>
                    <section aria-labelledby="payment-details-heading">
                        <form @submit.prevent="connections.put(route('profile.update'))">
                            <div class="shadow sm:rounded-md">
                                <div class="bg-white py-6 px-4 sm:p-6">
                                    <div>
                                        <h2 id="payment-details-heading"
                                            class="text-lg font-medium leading-6 text-gray-900">Update departments and skills</h2>
                                        <p class="mt-1 text-sm text-gray-500">
                                            These will be used for the planning of meetings!
                                        </p>
                                    </div>

                                    <div class="mt-6 grid grid-cols-4 gap-6">
                                        <div class="col-span-4 sm:col-span-2">
                                            <ComboBoxField
                                                label="Departments:"
                                                :multiple="true"
                                                name="departments"
                                                v-model="connections.departments"
                                                :records="departments"/>
                                        </div>

                                        <div class="col-span-4 sm:col-span-2">
                                            <ComboBoxField
                                                label="Skills:"
                                                name="skills"
                                                :multiple="true"
                                                v-model="connections.skills"
                                                :records="skills"/>
                                        </div>

                                    </div>
                                </div>
                                <div class="bg-gray-50 px-4 py-3 text-right sm:px-6">
                                    <button type="submit" :disabled="connections.processing"
                                            class="inline-flex justify-center rounded-md bg-gray-900 py-2 px-3 text-sm font-semibold text-white shadow-sm hover:bg-gray-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-900">
                                        Save
                                    </button>
                                </div>
                            </div>
                        </form>
                    </section>
                    <section aria-labelledby="payment-details-heading">
                        <div class="shadow sm:rounded-md">
                            <div class="bg-white py-6 px-4 sm:p-6">
                                <div>
                                    <h2 id="payment-details-heading"
                                        class="text-lg font-medium leading-6 text-gray-900">Availability</h2>
                                    <p class="mt-1 text-sm text-gray-500">
                                        These will be used for the planning of meetings!
                                    </p>
                                </div>

                                <h3 class="text-sm font-medium leading-6 text-gray-900 mt-4">Current availability</h3>
                                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 mt-4">
                                    <AvailibilityCard v-for="availability in availabilities" :availability="availability" :key="availability.id"/>
                                </div>
                            </div>
                            <form @submit.prevent="submitAvailibility" class="bg-gray-50 px-4 py-3 text-right sm:px-6">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <InputField
                                        label="Start time:"
                                        name="start_time"
                                        type="time"
                                        v-model="availability.start_time"/>
                                    <InputField
                                        label="End time:"
                                        name="end_time"
                                        type="time"
                                        v-model="availability.end_time"/>
                                </div>
                                <div class="grid grid-cols-4 gap-4">
                                    <div class="col-span-3">
                                        <DateField label="Date:"
                                                   v-model="availability.date"
                                        />
                                    </div>
                                    <div class="mt-2">
                                        <button type="submit" :disabled="availability.processing"
                                                class="inline-flex mt-5 justify-center rounded-md bg-gray-900 py-2 px-3 text-sm font-semibold text-white shadow-sm hover:bg-gray-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-900">
                                            Submit
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>

                    </section>
                </div>
            </div>

        </template>
    </BaseLayout>
</template>

<script>
import BaseLayout from "@/layouts/BaseLayout.vue";
import InputField from "@/components/fields/InputField.vue";
import ComboBoxField from "@/components/fields/ComboBoxField.vue";
import {useForm} from "@inertiajs/vue3";
import DateField from "@/components/fields/DateField.vue";
import AvailibilityCard from "@/components/AvailibilityCard.vue";

export default {
    name: "Profile",
    components: {AvailibilityCard, DateField, ComboBoxField, InputField, BaseLayout},
    props: {
        departments: {
            type: Array,
            required: true
        },
        skills: {
            type: Array,
            required: true
        },
        user: {
            type: Object,
            required: true
        },
        availabilities: {
            type: Array,
            required: true
        }
    },
    mounted() {
        this.profile.name = this.user.name;
        this.profile.email = this.user.email;

        this.user.departments.forEach(department => {
            this.connections.departments.push(department.id);
        });

        this.user.skills.forEach(skill => {
            this.connections.skills.push(skill.id);
        });
    },
    data() {
        return {
            profile: useForm({
                name: '',
                email: '',
            }),
            availability: useForm({
                date: null,
                start_time: null,
                end_time: null,
            }),
            connections: useForm({
                departments: [],
                skills: [],
            })
        }
    },
    methods: {
        submitAvailibility() {
            this.availability.post(route('profile.availability.add'), {
                preserveScroll: true,
                onSuccess: () => {
                    this.availability.reset();
                }
            });
        }
    }
}
</script>

<style scoped>

</style>
