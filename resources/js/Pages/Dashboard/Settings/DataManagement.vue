<template>
  <section class="mt-10 max-w-7xl mx-auto px-5">
    <p class="text-sm text-gray-500 max-w-lg">
      Import and export platform data here. Please note data being imported must
      be in a comma separated values (CSV) file, and data being exported will
      also be in a comma separated values (CSV) file.
    </p>

    <div class="mt-14">
      <h4 class="text-lg text-gray-500">Export</h4>
      <hr class="my-3" />
      <div class="flex justify-start items-center space-x-10 py-5">
        <Link
          :href="route('admin.settings.data.export.myths')"
          class="text-sm text-gray-600 hover:text-gray-800 transition-colors"
        >
          <i class="fa-solid fa-file-export mr-2"></i>
          <span>Myths & Facts</span>
        </Link>

        <Link
          :href="route('admin.settings.data.export.providers')"
          class="text-sm text-gray-600 hover:text-gray-800 transition-colors"
        >
          <i class="fa-solid fa-file-export mr-2"></i>
          <span>Service Providers</span>
        </Link>

        <Link
          :href="route('admin.settings.data.export.faqs')"
          class="text-sm text-gray-600 hover:text-gray-800 transition-colors"
        >
          <i class="fa-solid fa-file-export mr-2"></i>
          <span>FAQs</span>
        </Link>
      </div>
    </div>

    <div class="mt-14">
      <h4 class="text-lg text-gray-500">Import</h4>
      <hr class="my-3" />
      <form @submit.prevent="handleImportForm" class="">
        <div class="flex flex-col justify-start items-start space-y-7 py-5">
          <div class="lg:w-1/3">
            <InputLabel value="CSV File" />

            <input
              type="file"
              name="csv_file"
              accept=".csv"
              class="block w-full px-3 py-2 mt-2 text-sm text-gray-600 bg-white border border-gray-200 rounded-lg file:bg-gray-200 file:text-gray-700 file:text-sm file:px-4 file:py-1 file:border-none file:rounded-full placeholder-gray-400/70 focus:border-blue-400 focus:outline-none focus:ring focus:ring-blue-300 focus:ring-opacity-40"
            />
            <InputError :message="dataImportForm.errors.csv_file" class="mt-2" />
          </div>

          <div class="lg:w-1/3">
            <InputLabel value="Import Into" />
            <select
                v-model="dataImportForm.belongs_to"
                class="block w-full mt-2 border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm"
            >
                <option value="myths">Myths & Facts</option>
                <option value="providers">Service Providers</option>
                <option value="faqs">FAQs</option>
            </select>
            <InputError :message="dataImportForm.errors.belongs_to" class="mt-2" />
          </div>
        </div>

        <div class="mt-4">
            <PrimaryButton :disable="dataImportForm.processing" class="py-3 disabled:bg-gray-500">
                <span>Import File</span>
                <div class="fa-solid fa-file-import ml-2"></div>
            </PrimaryButton>
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
        <p class="text-sm text-gray-600">File was imported!</p>
      </div>
    </div>
  </div>
</template>

<script>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

export default {
  layout: AuthenticatedLayout,
};
</script>

<script setup>
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { useForm } from "@inertiajs/vue3";
import { ref } from 'vue';

const alert = ref(false)

let dataImportForm = useForm({
  csv_file: null,
  belongs_to: null,
});

const handleImportForm = (e) => {
    const data = new FormData(e.target)

    dataImportForm.csv_file = data.get('csv_file')
    dataImportForm.post(route('admin.settings.data.import'), {
        onSuccess: () => {
            alert.value = true

            setTimeout(() => {
                alert.value = false
            }, 3000)

            e.target.reset()
            dataImportForm.reset().clearErrors()
        }
    })
}
</script>

<style>
</style>