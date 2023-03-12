<template>
    <nav aria-label="Progress" class="mt-6">
        <ol role="list" class="divide-y divide-gray-300 rounded-md border border-gray-300 md:flex md:divide-y-0">
            <li class="relative md:flex md:flex-1" @click="$emit('setStep', 1)">
                <a v-if="currentStep > 1" class="group flex w-full items-center">
                    <span class="flex items-center px-6 py-4 text-sm font-medium">
                        <span class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-indigo-600 group-hover:bg-indigo-800">
                            <CheckIcon class="h-6 w-6 text-white" aria-hidden="true"/>
                        </span>
                        <span class="ml-4 text-sm font-medium text-gray-900">Meeting details</span>
                    </span>
                </a>
                <a v-else-if="currentStep === 1" class="flex items-center px-6 py-4 text-sm font-medium" aria-current="step">
                    <span class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full border-2 border-indigo-600">
                        <span class="text-indigo-600">1</span>
                    </span>
                    <span class="ml-4 text-sm font-medium text-indigo-600">Meeting details</span>
                </a>
                <a v-else class="group flex items-center">
                    <span class="flex items-center px-6 py-4 text-sm font-medium">
                        <span class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full border-2 border-gray-300 group-hover:border-gray-400">
                            <span class="text-gray-500 group-hover:text-gray-900">1</span>
                        </span>
                        <span class="ml-4 text-sm font-medium text-gray-500 group-hover:text-gray-900">Meeting details</span>
                    </span>
                </a>
                <div class="absolute top-0 right-0 hidden h-full w-5 md:block" aria-hidden="true">
                    <svg class="h-full w-full text-gray-300" viewBox="0 0 22 80" fill="none"
                         preserveAspectRatio="none">
                        <path d="M0 -2L20 40L0 82" vector-effect="non-scaling-stroke" stroke="currentcolor"
                              stroke-linejoin="round"/>
                    </svg>
                </div>
            </li>
            <li class="relative md:flex md:flex-1" @click="$emit('setStep', 2)">
                <a v-if="currentStep > 2" class="group flex w-full items-center">
                    <span class="flex items-center px-6 py-4 text-sm font-medium">
                        <span class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-indigo-600 group-hover:bg-indigo-800">
                            <CheckIcon class="h-6 w-6 text-white" aria-hidden="true"/>
                        </span>
                        <span class="ml-4 text-sm font-medium text-gray-900">Meeting details</span>
                    </span>
                </a>
                <a v-else-if="currentStep === 2" class="flex items-center px-6 py-4 text-sm font-medium" aria-current="step">
                    <span class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full border-2 border-indigo-600">
                        <span class="text-indigo-600">2</span>
                    </span>
                    <span class="ml-4 text-sm font-medium text-indigo-600">Attendees</span>
                </a>
                <a v-else class="group flex items-center">
                    <span class="flex items-center px-6 py-4 text-sm font-medium">
                        <span class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full border-2 border-gray-300 group-hover:border-gray-400">
                            <span class="text-gray-500 group-hover:text-gray-900">2</span>
                        </span>
                        <span class="ml-4 text-sm font-medium text-gray-500 group-hover:text-gray-900">Attendees</span>
                    </span>
                </a>
                <!-- Arrow separator for lg screens and up -->
                <div class="absolute top-0 right-0 hidden h-full w-5 md:block" aria-hidden="true">
                    <svg class="h-full w-full text-gray-300" viewBox="0 0 22 80" fill="none"
                         preserveAspectRatio="none">
                        <path d="M0 -2L20 40L0 82" vector-effect="non-scaling-stroke" stroke="currentcolor"
                              stroke-linejoin="round"/>
                    </svg>
                </div>
            </li>
            <li class="relative md:flex md:flex-1" @click="$emit('setStep', 3)">
                <a v-if="currentStep > 3" class="group flex w-full items-center">
                    <span class="flex items-center px-6 py-4 text-sm font-medium">
                        <span class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-indigo-600 group-hover:bg-indigo-800">
                            <CheckIcon class="h-6 w-6 text-white" aria-hidden="true"/>
                        </span>
                        <span class="ml-4 text-sm font-medium text-gray-900">Date and time</span>
                    </span>
                </a>
                <a v-else-if="currentStep === 3" class="flex items-center px-6 py-4 text-sm font-medium" aria-current="step">
                    <span class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full border-2 border-indigo-600">
                        <span class="text-indigo-600">3</span>
                    </span>
                    <span class="ml-4 text-sm font-medium text-indigo-600">Date and time</span>
                </a>
                <a v-else class="group flex items-center">
                    <span class="flex items-center px-6 py-4 text-sm font-medium">
                        <span class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full border-2 border-gray-300 group-hover:border-gray-400">
                            <span class="text-gray-500 group-hover:text-gray-900">3</span>
                        </span>
                        <span class="ml-4 text-sm font-medium text-gray-500 group-hover:text-gray-900">Date and time</span>
                    </span>
                </a>
            </li>
        </ol>
    </nav>
</template>

<script>
import {CheckIcon} from "@heroicons/vue/20/solid";

export default {
    name: "Steps",
    components: {CheckIcon},
    props: {
        currentStep: {
            type: Number,
            required: true
        }
    },
}
</script>

<style scoped>

</style>
