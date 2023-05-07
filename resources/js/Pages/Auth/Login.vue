<template>
  <main class="w-full min-h-screen bg-gray-100 flex justify-center items-center">
    <form @submit.prevent="authenticate" class="bg-white shadow-xl rounded-md p-10 text-center">
        <h4 class="font-bold text-xl tracking-wide uppercase text-gray-800">Administration</h4>
        <p class="text-sm text-gray-500 my-2">Login to continue managing <b>Tidziwitsane</b> platform.</p>

        <div class="mt-8">
            <TextInput placeholder="Email" type="email" class="block w-full" v-model="form.email" required autocomplete="email" />
            <InputError class="text-left mt-2" :message="form.errors.email" />
        </div>

        <div class="mt-5">
            <TextInput placeholder="Password" type="password" class="block w-full" v-model="form.password" required autocomplete="password" />
            <InputError class="text-left mt-2" :message="form.errors.password" />
        </div>

        <div class="w-full flex justify-between items-center mt-4">
            <div class="flex justify-start space-x-2">
                <Checkbox v-model="form.remember_me" />
                <InputLabel value="Remember me" class="text-sm font-normal" />
            </div>

            <Link class="text-sm text-gray-500 hover:text-gray-800 transition-colors">Forgot Password?</Link>
        </div>

        <div class="mt-14">
            <button type="submit" :disabled="form.processing" class="block w-full bg-gray-700 disabled:bg-gray-300 hover:bg-gray-800 transition-colors py-2.5 rounded text-white tracking-widest">Login</button>
        </div>
    </form>
  </main>
</template>

<script setup>
import InputLabel from '@/Components/InputLabel.vue'
import TextInput from '@/Components/TextInput.vue'
import InputError from '@/Components/InputError.vue'
import Checkbox from '@/Components/Checkbox.vue'
import { useForm } from '@inertiajs/vue3'

const form = useForm({
    email: null,
    password: null,
    remember_me: false,
})

const authenticate = () => {
    form.post(route('auth.authenticate'))
}
</script>

<style>

</style>