<template>
  <main class="max-w-7xl mx-auto flex flex-col justify-start">
    <div class="w-full p-5 mt-8">
        <p class="text-sm text-gray-500">Create and manage platform users.</p>
        <div class="w-full mt-8 flex justify-between items-center">
            <form @submit.prevent="" class="w-1/4">
                <TextInput type="text" placeholder="Search" class="w-full text-sm" />
            </form>

            <div>
                <PrimaryButton @click="modal = true" class="text-sm py-2.5">
                    <i class="fa-solid fa-plus mr-2"></i>
                    <span>Invite User</span>
                </PrimaryButton>
            </div>
        </div>
    </div>

    <!-- table -->
    <div class="w-full flex-1 p-5 mt-10">
        <div class="w-full">
            <table class="w-full">
                <thead>
                    <tr class="text-sm text-gray-500 border-b font-medium">
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
                                    <button class="px-4 py-2 rounded text-sm text-gray-600 hover:text-gray-800 hover:bg-gray-200 transition-colors">
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

    <!-- pagination -->
    <div class="mt-10 flex justify-between px-4">
        <p class="text-sm text-gray-500">
            Showing Page {{ users.current_page }} of {{ (users.total / users.per_page) < 1 ? 1 : Math.round((users.total / users.per_page))}}
        </p>

        <div class="flex justify-end space-x-4 text-sm">
            <Link as="button" :href="users.prev_page_url" class="bg-white px-2.5 py-1.5 shadow rounded hover:bg-sky-500 hover:text-white transition-colors" v-if="users.prev_page_url">
                <i class="fa-solid fa-chevron-left"></i>
            </Link>

            <Link v-for="(page_url, index) in page_urls" 
                :key="index" as="button"
                :href="page_url.url"  
                class="bg-white px-2.5 py-1.5 shadow rounded hover:bg-sky-500 hover:text-white transition-colors"
                :class="{
                    'bg-sky-500 text-white': page_url.active
                }" 
                v-if="users.prev_page_url || users.next_page_url"
            >
                {{ page_url.label }}
            </Link>

            <Link as="button" :href="users.next_page_url" class="bg-white px-2.5 py-1.5 shadow rounded hover:bg-sky-500 hover:text-white transition-colors" v-if="users.next_page_url">
                <i class="fa-solid fa-chevron-right"></i>
            </Link>
        </div>
    </div>
  </main>

  <!-- alert -->
  <div class="fixed top-10 right-10 flex w-full max-w-sm overflow-hidden bg-white rounded-lg shadow-xl" v-show="alert">
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

    <!-- side modal -->
    <section class="absolute top-0 left-0 w-full h-screen bg-black bg-opacity-40 backdrop-blur-sm flex justify-end transition-all" v-show="modal">
        <div class="w-1/4 h-full bg-white">
            <form @submit.prevent="handleInviteForm">
                <div class="px-8 py-6 flex justify-between items-center">
                    <button type="button" @click="modal = false" class="bg-gray-100 px-2.5 py-1 rounded text-gray-600 hover:text-gray-800 hover:bg-gray-200 transition-colors">
                        <i class="fa-solid fa-close"></i>
                    </button>

                    <PrimaryButton :disabled="inviteForm.processing" class="disabled:bg-gray-500">
                        <span>Send Invite</span>
                        <i class="fa-solid fa-paper-plane text-xs ml-2"></i>
                    </PrimaryButton>
                </div>

                <div class="px-8 mt-10">
                    <InputLabel value="Email" />
                    <TextInput type="text" class="mt-2 block w-full" v-model="inviteForm.email" />
                    <InputError :message="inviteForm.errors.email" class="mt-2" />
                </div>

                <div class="px-8 mt-7">
                    <InputLabel value="User Type" />
                    <select v-model="inviteForm.user_type" class="block w-full mt-2 border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm">
                        <option value="0">Administrator</option>
                        <option value="1">Manager</option>
                        <option value="2">Employee</option>
                    </select>
                    <InputError :message="inviteForm.errors.user_type" class="mt-2" />
                </div>
            </form>
        </div>
    </section>
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

import InputLabel from '@/Components/InputLabel.vue'
import InputError from '@/Components/InputError.vue'
import TextInput from '@/Components/TextInput.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import Dropdown from '@/Components/Dropdown.vue'
import DropdownLink from '@/Components/DropdownLink.vue'
import { computed, ref } from 'vue'

// initialize dayjs
dayjs.extend(relativeTime)

const { users } = defineProps({users: Object})
const page_urls = computed(() => users.links.filter((_, index) => index !== 0 && index !== users.links.length-1))

const modal = ref(false)

const inviteForm = useForm({
    email: null,
    user_type: null,
})

const alert = ref(false)
const handleInviteForm = () => {
    inviteForm.post(route('auth.invite'), {
        onSuccess: () => {
            modal.value = false
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