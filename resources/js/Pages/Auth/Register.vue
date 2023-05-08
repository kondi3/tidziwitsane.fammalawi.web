<template>
  <main class="w-full min-h-screen bg-gray-100 flex justify-center items-center">
    <div class="bg-white rounded-md shadow-xl">
        <form @submit.prevent="register" class="p-10 text-center">
            <h4 class="font-bold text-xl tracking-wide uppercase text-gray-800">Administration</h4>
            <p class="text-sm text-gray-500 my-2">Register now to start managing <b>Tidziwitsane</b> platform.</p>

            <div class="mt-8">
                <TextInput placeholder="Email" type="email" class="block w-full read-only:bg-gray-200 read-only:text-gray-500" readonly v-model="form.email" required autocomplete="email" />
                <InputError class="text-left mt-2" :message="form.errors.email" />
            </div>

            <div class="mt-5">
                <TextInput placeholder="Full Name" type="text" class="block w-full" v-model="form.name" required autocomplete="name" />
                <InputError class="text-left mt-2" :message="form.errors.name" />
            </div>

            <div class="mt-5">
                <TextInput placeholder="Password" type="password" class="block w-full" v-model="form.password" required autocomplete="password" />
                <InputError class="text-left mt-2" :message="form.errors.password" />
            </div>

            <div class="mt-5">
                <TextInput placeholder="Confirm Password" type="password" class="block w-full" v-model="form.password_confirmation" required autocomplete="password" />
                <InputError class="text-left mt-2" :message="form.errors.password_confirmation" />
            </div>


            <div class="mt-14">
                <button type="submit" :disabled="form.processing" class="block w-full bg-gray-700 disabled:bg-gray-300 hover:bg-gray-800 transition-colors py-2.5 rounded text-white tracking-widest">Register</button>
            </div>
        </form>

        <div class="w-full bg-gray-200 rounded-b-md p-4 text-center">
            <p class="text-sm text-gray-500">
                Already have an account? <Link :href="route('auth.login')" class="text-sky-800 font-medium hover:text-gray-500 transition-colors">Login</Link>
            </p>
        </div>
    </div>
  </main>
</template>

<script setup>
import InputLabel from '@/Components/InputLabel.vue'
import TextInput from '@/Components/TextInput.vue'
import InputError from '@/Components/InputError.vue'
import Checkbox from '@/Components/Checkbox.vue'
import { useForm } from '@inertiajs/vue3'

const {invite} = defineProps({invite: Object})

const form = useForm({
    email: invite.email,
    name: null,
    password: null,
    password_confirmation: null,
    remember_me: false,
})

const register = () => {
    form.post(route('auth.store', {'invite': invite}))
}
</script>

<style>

</style>