<template>
  <main class="max-w-7xl mx-auto flex flex-col justify-start">
    <div class="w-full p-5 mt-8">
      <p class="text-sm text-gray-500 lg:max-w-md">
        Create and manage service providers here. You can also import a CSV
        (Comma Separated Values) file from settings.
      </p>
      <div class="w-full mt-8 flex justify-between items-center">
        <form @submit.prevent="" class="w-1/4">
          <TextInput type="text" placeholder="Search" class="w-full text-sm" />
        </form>

        <div>
          <PrimaryButton @click="modal = true" class="text-sm py-2.5">
            <i class="fa-solid fa-plus mr-2"></i>
            <span>Add New Entry</span>
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
              <td class="py-2">Service Provider</td>
              <td>Action</td>
              <td>Data</td>
              <td>Added</td>
              <td>Actions</td>
            </tr>
          </thead>

          <tbody v-if="providers.data.length">
            <tr
              v-for="(provider, index) in providers.data"
              :key="index"
              class="text-sm text-gray-700 border-t"
            >
              <td class="py-2.5">{{ provider.provider }}</td>
              <td class="py-2.5 uppercase">{{ provider.action }}</td>
              <td class="py-2.5">{{ provider.args }}</td>
              <td class="py-2.5">
                {{ dayjs(provider.created_at).fromNow() }}
              </td>
              <td class="py-2.5">
                <Dropdown>
                  <template #trigger>
                    <button
                      class="px-4 py-2 rounded text-sm text-gray-600 hover:text-gray-800 hover:bg-gray-200 transition-colors"
                    >
                      <i class="fa-solid fa-ellipsis-vertical"></i>
                    </button>
                  </template>

                  <template #content>
                    <div>
                      <button
                        @click="update(provider)"
                        class="block w-full px-4 py-2 text-left text-gray-600 hover:bg-gray-100"
                      >
                        <i class="fa-solid fa-edit mr-2 text-gray-400"></i>
                        <span>Edit</span>
                      </button>
                      <DropdownLink
                        as="button"
                        :href="route('admin.providers.destroy', provider.id)"
                        method="delete"
                      >
                        <i
                          class="fa-solid fa-trash text-xs text-gray-400 mr-3"
                        ></i>
                        <span>Delete</span>
                      </DropdownLink>
                    </div>
                  </template>
                </Dropdown>
              </td>
            </tr>
          </tbody>

          <tbody v-else>
            <tr class="text-gray-500 text-sm">
              <td class="py-4">Nothing to show yet.</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- pagination -->
    <div class="mt-10 flex justify-between px-4">
      <p
        class="text-sm text-gray-500"
        v-if="providers.prev_page_url || providers.next_page_url"
      >
        Showing Page {{ providers.current_page }} of
        {{
          Math.round(
            providers.total / providers.per_page < 1.5
              ? providers.total / providers.per_page + 0.5
              : providers.total / providers.per_page
          )
        }}
      </p>

      <div class="flex justify-end space-x-4 text-sm">
        <Link
          as="button"
          :href="providers.prev_page_url"
          class="bg-white px-2.5 py-1.5 shadow rounded hover:bg-sky-500 hover:text-white transition-colors"
          v-if="providers.prev_page_url"
        >
          <i class="fa-solid fa-chevron-left"></i>
        </Link>

        <Link
          v-for="(page_url, index) in page_urls"
          :key="index"
          as="button"
          :href="page_url.url"
          class="bg-white px-2.5 py-1.5 shadow rounded hover:bg-sky-500 hover:text-white transition-colors"
          :class="{
            'bg-sky-600 text-white': page_url.active,
          }"
          v-show="providers.prev_page_url || providers.next_page_url"
        >
          {{ page_url.label }}
        </Link>

        <Link
          as="button"
          :href="providers.next_page_url"
          class="bg-white px-2.5 py-1.5 shadow rounded hover:bg-sky-500 hover:text-white transition-colors"
          v-if="providers.next_page_url"
        >
          <i class="fa-solid fa-chevron-right"></i>
        </Link>
      </div>
    </div>
  </main>

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
        <p class="text-sm text-gray-600">Saved your entry!</p>
      </div>
    </div>
  </div>

  <!-- side modal -->
  <section
    class="absolute top-0 left-0 w-full h-screen bg-black bg-opacity-40 backdrop-blur-sm flex justify-end transition-all"
    v-show="modal"
  >
    <div
      class="w-1/4 h-full bg-white flex flex-col justify-between items-start"
    >
      <form @submit.prevent="handleNewEntryForm" class="flex-1 w-full">
        <div class="px-8 py-6 flex justify-between items-center">
          <button
            type="button"
            @click="
              (modal = false),
                newEntryForm.clearErrors().reset(),
                (update_entry = null)
            "
            class="bg-gray-100 px-2.5 py-1 rounded text-gray-600 hover:text-gray-800 hover:bg-gray-200 transition-colors"
          >
            <i class="fa-solid fa-close"></i>
          </button>

          <PrimaryButton
            :disabled="newEntryForm.processing"
            class="disabled:bg-gray-500"
          >
            <span v-if="update_entry">Update</span>
            <span v-else>Save</span>
            <i class="fa-solid fa-save text-xs ml-2"></i>
          </PrimaryButton>
        </div>

        <div class="px-8 mt-10">
          <InputLabel value="Provider" />
          <TextInput
            type="text"
            class="mt-2 block w-full"
            v-model="newEntryForm.provider"
          />
          <InputError :message="newEntryForm.errors.provider" class="mt-2" />
        </div>

        <div class="px-8 mt-10">
          <InputLabel value="Action" />
          <select
            v-model="newEntryForm.action"
            class="block w-full mt-2 border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm"
          >
            <option value="geo">Map / Geo</option>
            <option value="phone">Phone</option>
            <option value="sms">SMS</option>
            <option value="email">Email</option>
            <option value="web">Web</option>
          </select>
          <InputError :message="newEntryForm.errors.action" class="mt-2" />
        </div>

        <div class="px-8 mt-10">
          <InputLabel value="Data" />
          <TextAreaInput
            type="text"
            class="mt-2 block w-full"
            v-model="newEntryForm.args"
          />
          <InputError :message="newEntryForm.errors.args" class="mt-2" />
        </div>
      </form>

      <div class="px-8 py-8">
        <Link
          :href="route('admin.settings.data')"
          class="text-sm text-gray-500 hover:text-gray-800"
        >
          <i class="fa-solid fa-file-import mr-2"></i>
          <span>Import Batch File (CSV)</span>
        </Link>
      </div>
    </div>
  </section>
</template>

<script>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

export default {
  layout: AuthenticatedLayout,
};
</script>

<script setup>
import { useForm } from "@inertiajs/vue3";
import dayjs from "dayjs";
import relativeTime from "dayjs/plugin/relativeTime";

import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import TextInput from "@/Components/TextInput.vue";
import TextAreaInput from "@/Components/TextAreaInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import { computed, ref } from "vue";

// initialize dayjs
dayjs.extend(relativeTime);

const { providers } = defineProps({ providers: Object });

const page_urls = computed(() =>
  providers.links.filter(
    (_, index) => index !== 0 && index !== providers.links.length - 1
  )
);

const modal = ref(false);
const update_entry = ref(null);

const newEntryForm = useForm({
  provider: null,
  action: null,
  args: null,
});

const alert = ref(false);
const handleNewEntryForm = () => {
  if (update_entry.value) {
    newEntryForm.put(route("admin.providers.update", update_entry.value.id), {
      onSuccess: () => {
        on_success(), (update_entry.value = null);
      },
    });
  } else {
    newEntryForm.post(route("admin.providers.store"), {
      onSuccess: on_success,
    });
  }
};

const update = (provider) => {
  update_entry.value = provider;
  newEntryForm.provider = provider.provider;
  newEntryForm.action = provider.action;
  newEntryForm.args = provider.args;

  modal.value = true;
};

const on_success = () => {
  modal.value = false;
  alert.value = true;
  setTimeout(() => (alert.value = false), 3000);
  newEntryForm.reset().clearErrors();
};
</script>

<style>
</style>