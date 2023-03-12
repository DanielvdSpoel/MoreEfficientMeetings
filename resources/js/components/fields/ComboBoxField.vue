<template>
    <div class="mt-2">
        <Combobox as="div" v-model="selectedRecord" :nullable="nullable" :multiple="multiple">
            <ComboboxLabel class="block text-sm font-medium text-gray-700">{{ label }}</ComboboxLabel>
            <div class="relative mt-1">
                <ComboboxInput autocomplete="off"
                               class="w-full rounded-md border border-gray-300 bg-white py-2 pl-3 pr-10 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 sm:text-sm"
                               @change="query = $event.target.value" :display-value="(value) => formatInput(value)"/>
                <ComboboxButton
                    class="absolute inset-y-0 right-0 flex items-center rounded-r-md px-2 focus:outline-none"
                    v-bind:class="{'border-red-300 text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500': error}">
                    <ChevronUpDownIcon class="h-5 w-5 text-gray-400" aria-hidden="true"/>
                </ComboboxButton>

                <ComboboxOptions v-if="filteredRecords.length > 0"
                                 class="absolute z-50 mt-1 max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm">
                    <ComboboxOption v-for="record in filteredRecords" :key="record.id" :value="record" as="template"
                                    v-slot="{ active, selected }">
                        <li :class="['relative cursor-default select-none py-2 pl-3 pr-9', active ? 'bg-indigo-600 text-white' : 'text-gray-900']">
                            <span :class="['block truncate', selected && 'font-semibold']">
                                {{ record[recordNameAttribute] }}
                            </span>

                            <span v-if="selected"
                                  :class="['absolute inset-y-0 right-0 flex items-center pr-4', active ? 'text-white' : 'text-indigo-600']">
                                <CheckIcon class="h-5 w-5" aria-hidden="true"/>
                             </span>
                        </li>
                    </ComboboxOption>
                </ComboboxOptions>
            </div>
        </Combobox>
        <div v-if="nullable" @click="clearInput"
            class="mt-1 ml-1 text-sm text-gray-500 hover:text-gray-700 flex gap-2 cursor-pointer">
            <i class="fa-solid fa-trash-can mt-1"></i>
            <p>Clear</p>
        </div>
        <p v-if="error" class="mt-2 text-sm text-red-600" id="email-error">{{ error }}</p>

    </div>
</template>

<script>
import {computed, ref, watch} from 'vue'
import {CheckIcon, ChevronUpDownIcon} from '@heroicons/vue/20/solid'
import {
    Combobox,
    ComboboxButton,
    ComboboxInput,
    ComboboxLabel,
    ComboboxOption,
    ComboboxOptions,
} from '@headlessui/vue'


export default {
    props: {
        modelValue: String | Number | Array | undefined,
        form: Object,
        name: String,
        label: String,
        recordNameAttribute: {
            type: String,
            default: 'name',
        },
        nullable: {
            type: Boolean,
            default: true,
        },
        multiple: {
            type: Boolean,
            default: false,
        },
        records: Array,
    },
    components: {
        CheckIcon,
        Combobox,
        ComboboxButton,
        ComboboxInput,
        ComboboxLabel,
        ComboboxOption,
        ComboboxOptions,
        ChevronUpDownIcon,
    },
    setup(props) {
        console.log("Setup")
        const records = props.records
        const query = ref('')
        const selectedRecord = ref()
        if (props.modelValue) {
            if (Array.isArray(props.modelValue)) {
                selectedRecord.value = []
                props.modelValue.forEach(id => {
                    selectedRecord.value.push(records.find(record => parseInt(record.id) === parseInt(id)))
                })
            } else {
                selectedRecord.value = records.find(record => parseInt(record.id) === parseInt(props.modelValue)) //do not replace with ==, record.id is a number and modelValue is a string (sometimes)
            }
        } else {
            selectedRecord.value = null;
        }
        return {
            query,
            selectedRecord,
        }
    },
    watch: {
        selectedRecord(newValue) {
            if (Array.isArray(newValue)) {
                this.$emit('update:modelValue', newValue.map(record => record.id))
            } else {
                this.$emit('update:modelValue', newValue ? newValue.id : null)
            }
        },
    },
    methods: {
        clearInput() {
            this.query = '';
            this.selectedRecord = null;
        },
        formatInput(input) {
            if (!input) {
                return null
            }
            if (input[this.recordNameAttribute]) {
                return input[this.recordNameAttribute]
            } else {
                if (input.length > 0) {
                    console.log(input)
                    return input.map(object => object[this.recordNameAttribute])
                } else {
                    return null
                }
            }
        }
    },
    computed: {
        filteredRecords() {
            if (this.query === '') {
                console.log("Returning all records")
                return this.records
            } else {
                return this.records.filter(record => {
                    return record.name.toLowerCase().includes(this.query.toLowerCase())
                })
            }

        },
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
    }
}
</script>
