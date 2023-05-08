<template>
  <main class="max-w-7xl mx-auto">
    <div class="w-full bg-white p-5 rounded shadow">
        <p class="text-sm text-gray-500">Invite a new user to the platform.</p>

        <form @submit.prevent="handleInviteForm" class="w-full flex justify-start items-start space-x-4 mt-8">
            <div class="lg:w-1/4">
                <TextInput type="email" placeholder="Email" v-model="inviteForm.email" class="block w-full text-sm" />
                <InputError :message="inviteForm.errors.email" class="mt-2" />
            </div>
            
            <div class="lg:w-1/4">
                <select v-model="inviteForm.user_type" class="block w-full text-sm border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm">
                    <option value="">-- User Type --</option>
                    <option value="0">Administrator</option>
                    <option value="1">Manager</option>
                    <option value="2">Employee</option>
                </select>
                <InputError :message="inviteForm.errors.user_type" class="mt-2" />
            </div>

            <div>
                <PrimaryButton v-show="!alert" :disabled="inviteForm.processing" class="py-2.5 text-sm disabled:bg-gray-500">Send Invite</PrimaryButton>
            </div>
        </form>
    </div>

    <div class="w-full bg-white shadow rounded p-5 mt-10">
        <div class="flex justify-between items-center">
            <form @submit.prevent="handleSearchForm" class="w-1/4">
                <TextInput type="text" placeholder="Search" class="text-sm block w-full" />
            </form>

            <Dropdown>
                <template #trigger>
                    <button class="text-xs text-gray-600 p-2 rounded hover:text-gray-800 hover:bg-gray-100 transition-colors">
                        <i class="fa-solid fa-filter mr-2"></i>
                        <span>Filter</span>
                    </button>
                </template>

                <template #content>
                    <div>
                        
                    </div>
                </template>
            </Dropdown>
        </div>

        <div class="w-full mt-8">
            <table class="w-full">
                <thead>
                    <tr class="text-sm text-gray-500 border-b">
                        <td class="py-2">Name</td>
                        <td>Email</td>
                        <td>Type</td>
                        <td>Added</td>
                        <td>Actions</td>
                    </tr>
                </thead>

                <tbody>
                    <tr v-for="(user, index) in users.data" :key="index" class="text-sm text-gray-700 border-t">
                        <td class="py-2.5">{{ user.name }}</td>
                        <td class="py-2.5">{{ user.email }}</td>
                        <td class="py-2.5">
                            {{ parseUserType(user.type) }}
                        </td>
                        <td class="py-2.5">
                            {{ dayjs(user.created_at).fromNow() }}
                        </td>
                        <td class="py-2.5">
                            <Dropdown>
                                <template #trigger>
                                    <button class="px-4 py-2 rounded text-sm text-gray-600 hover:text-gray-800 hover:bg-gray-100 transition-colors">
                                        <i class="fa-solid fa-ellipsis-vertical"></i>
                                    </button>
                                </template>
                            

                                <template #content>
                                    <div>
                                        <p class="px-4 py-1.5 text-xs text-gray-400">Change Type To</p>
                                        <div class="">
                                            <DropdownLink as="button" v-show="user.type !== 0" :href="route('admin.users.update', user)" method="put" :data="{type: 0}">Administrator</DropdownLink>
                                            <DropdownLink as="button" v-show="user.type !== 1" :href="route('admin.users.update', user)" method="put" :data="{type: 1}">Manager</DropdownLink>
                                            <DropdownLink as="button" v-show="user.type !== 2" :href="route('admin.users.update', user)" method="put" :data="{type: 2}">Employee</DropdownLink>
                                        </div>
                                        <hr>          
                                        <!-- <DropdownLink as="button" :href="route('admin.users.update', user)" method="post" :data="{status: 0}">
                                            <i class="fa-solid fa-user-minus text-xs text-gray-400 mr-2"></i>
                                            <span>Deactivate</span>
                                        </DropdownLink> -->
                                        <DropdownLink as="button" :href="route('admin.users.destroy', user)" method="delete">
                                            <i class="fa-solid fa-trash text-xs text-gray-400 mr-3"></i>
                                            <span>Delete</span>
                                        </DropdownLink>
                                    </div>
                                </template>
                            </Dropdown>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
  </main>

  <!-- alert -->
  <div class="fixed top-10 right-10 flex w-full max-w-sm overflow-hidden bg-white rounded-lg shadow-md" v-show="alert">
        <div class="flex items-center justify-center w-12 bg-emerald-500">
            <svg class="w-6 h-6 text-white fill-current" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg">
                <path d="M20 3.33331C10.8 3.33331 3.33337 10.8 3.33337 20C3.33337 29.2 10.8 36.6666 20 36.6666C29.2 36.6666 36.6667 29.2 36.6667 20C36.6667 10.8 29.2 3.33331 20 3.33331ZM16.6667 28.3333L8.33337 20L10.6834 17.65L16.6667 23.6166L29.3167 10.9666L31.6667 13.3333L16.6667 28.3333Z" />
            </svg>
        </div>

        <div class="px-4 py-2 -mx-3">
            <div class="mx-3">
                <span class="font-semibold text-emerald-500">Success</span>
                <p class="text-sm text-gray-600">Invitation sent!</p>
            </div>
        </div>
    </div>
</template>

<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

export default {
    layout: AuthenticatedLayout
}
</script>

<script setup>
import { useForm } from '@inertiajs/vue3'
import dayjs from 'dayjs'
import relativeTime from 'dayjs/plugin/relativeTime'

import InputError from '@/Components/InputError.vue'
import TextInput from '@/Components/TextInput.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import Dropdown from '@/Components/Dropdown.vue'
import DropdownLink from '@/Components/DropdownLink.vue'
import { ref } from 'vue'

// initialize dayjs
dayjs.extend(relativeTime)

defineProps({users: Object})

const inviteForm = useForm({
    email: null,
    user_type: ''
})

const alert = ref(false)
const handleInviteForm = () => {
    inviteForm.post(route('auth.invite'), {
        onSuccess: () => {
            alert.value = true
            setTimeout(() => alert.value = false, 3000)
            inviteForm.reset().clearErrors()
        }
    })
}

const parseUserType = (user_type) => {
    let parsed
    switch (user_type) {
        case -1:
            parsed = 'Super User'
            break
        case 0:
            parsed = 'Administrator'
            break
        case 1:
            parsed = 'Manager'
            break
        case 2:
            parsed = 'Employee'
            break
    }

    return parsed
}
</script>

<style>

</style>