<template>
    <AuthLayout>
        <template v-slot:title>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">Log into your MEM-account</h2>

        </template>
        <template v-slot:subtitle>
            <p class="mt-2 text-center text-sm text-gray-600">
                Of
                <Link href="/register" class="font-medium text-primary-600 hover:text-primary-500">Create a new account</Link>
            </p>
        </template>

        <template v-slot:form>
            <form class="space-y-6" @submit.prevent="form.post(route('login'))">
                <InputField
                    name="email"
                    type="email"
                    label="E-mail"
                    v-model="form.email"
                    autocomplete="email"
                    :required="true"
                    :form="form"
                />

                <InputField
                    name="password"
                    type="password"
                    label="Password"
                    autocomplete="current-password"
                    v-model="form.password"
                    :required="true"
                    :form="form"
                />

                <div class="flex items-center justify-between">

                    <div class="text-sm">
                        <Link href="/register" class="font-medium text-primary-600 hover:text-primary-500">
                            No account yet?
                        </Link>
                    </div>
                </div>
                <div>
                    <button type="submit" :disabled="form.processing"
                            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        Login
                    </button>
                </div>
            </form>
        </template>
    </AuthLayout>
</template>

<script>
import InputField from "@/components/fields/InputField.vue";
import { Link } from '@inertiajs/vue3'
import { useForm } from '@inertiajs/vue3'
import AuthLayout from "@/layouts/AuthLayout.vue";

export default {
    name: "Login",
    components: {AuthLayout, InputField, Link},
    data() {
        return {
            form: useForm({
                email: null,
                password: null,
            }),
        }
    }
}
</script>

<style scoped>

</style>
