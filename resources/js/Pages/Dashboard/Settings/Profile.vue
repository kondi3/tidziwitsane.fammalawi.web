<template>
  <section class="max-w-7xl mx-auto px-5 py-14">

    <p class="text-sm text-gray-500">
        Securely update your profile here.
    </p>

    <div class="mt-14">
        <p class="text-lg text-gray-700">User Information</p>

        <form @submit.prevent="handleSubmitBasic" class="mt-7 max-w-lg">
            <div class="">
                <InputLabel value="Full Name" /> 
                <TextInput type="text" v-model="userInfoForm.name" class="mt-2 block w-full" />
                <InputError :message="userInfoForm.errors.name" class="mt-2" />
            </div>
            <div class="mt-5">
                <InputLabel value="Email" /> 
                <TextInput type="email" v-model="userInfoForm.email" class="mt-2 block w-full" />
                <InputError :message="userInfoForm.errors.email" class="mt-2" />
            </div>

            <div class="mt-5">
                <PrimaryButtonVue :disabled="userInfoForm.processing" class="disabled:bg-gray-500" >
                    <span>Save</span>
                    <i class="fa-solid fa-save ml-2"></i>
                </PrimaryButtonVue>
            </div>
        </form>
    </div>

    <div class="mt-14">
        <p class="text-lg text-gray-700">Security Information</p>

        <form @submit.prevent="handleSubmitSecurity" class="mt-7 max-w-lg">
            <div class="">
                <InputLabel value="Old Password" /> 
                <TextInput type="password" v-model="securityInfoForm.old_password" class="mt-2 block w-full" />
                <InputError :message="securityInfoForm.errors.old_password" class="mt-2" />
            </div>
            <div class="mt-5">
                <InputLabel value="New Password" /> 
                <TextInput type="password" v-model="securityInfoForm.password" class="mt-2 block w-full" />
                <InputError :message="securityInfoForm.errors.password" class="mt-2" />
            </div>
            <div class="mt-5">
                <InputLabel value="Confirm Password" /> 
                <TextInput type="password" v-model="securityInfoForm.password_confirmation" class="mt-2 block w-full" />
                <InputError :message="securityInfoForm.errors.password_confirmation" class="mt-2" />
            </div>

            <div class="mt-5">
                <PrimaryButtonVue :disabled="securityInfoForm.processing" class="disabled:bg-gray-500" >
                    <span>Save</span>
                    <i class="fa-solid fa-save ml-2"></i>
                </PrimaryButtonVue>
            </div>
        </form>
    </div>
  </section>

  <!-- alert -->
  <div
    class="fixed top-10 right-10 flex w-full max-w-sm overflow-hidden bg-white rounded-lg shadow-xl"
    v-show="alert"
  >
    <div class="flex items-center justify-center w-12 bg-emerald-500">
      <svg
        class="w-6 h-6 text-white fill-current"
        viewBox="0 0 40 40"
        xmlns="http://www.w3.org/2000/svg"
      >
        <path
          d="M20 3.33331C10.8 3.33331 3.33337 10.8 3.33337 20C3.33337 29.2 10.8 36.6666 20 36.6666C29.2 36.6666 36.6667 29.2 36.6667 20C36.6667 10.8 29.2 3.33331 20 3.33331ZM16.6667 28.3333L8.33337 20L10.6834 17.65L16.6667 23.6166L29.3167 10.9666L31.6667 13.3333L16.6667 28.3333Z"
        />
      </svg>
    </div>

    <div class="px-4 py-2 -mx-3">
      <div class="mx-3">
        <span class="font-semibold text-emerald-500">Success</span>
        <p class="text-sm text-gray-600">Profile updated!</p>
      </div>
    </div>
  </div>
</template>

<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

export default {
    layout: AuthenticatedLayout,
}
</script>

<script setup>
import InputError from '@/Components/InputError.vue'
import InputLabel from '@/Components/InputLabel.vue'
import TextInput from '@/Components/TextInput.vue'
import PrimaryButtonVue from '@/Components/PrimaryButton.vue'
import { useForm, usePage } from '@inertiajs/vue3'
import { computed, ref } from 'vue'

const user = computed(() => usePage().props.auth.user)
const alert = ref(false)

const userInfoForm = useForm({
    name: user.value.name,
    email: user.value.email,
})

const securityInfoForm = useForm({
    old_password: null,
    password: null,
    password_confirmation: null,
})

const handleSubmitBasic = () => {
    if (userInfoForm.isDirty) {
        userInfoForm.put(route('admin.settings.profile.update.basic', user.value.id), {
            onSuccess: on_success,
        })
    }
}

const handleSubmitSecurity = () => {
    if (securityInfoForm.isDirty) {
        securityInfoForm.put(route('admin.settings.profile.update.security', user.value.id), {
            onSuccess: on_success,
        })
    }
}

const on_success = () => {
    alert.value = true
    setTimeout(() => {
        alert.value = false
    }, 3000);
    userInfoForm.clearErrors()
    securityInfoForm.reset().clearErrors()
}
</script>

<style>

</style>