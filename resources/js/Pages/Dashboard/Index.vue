<template>
    <section class="max-w-7xl mx-auto py-4 px-5">
        <!-- <p class="text-sm text-gray-500">
            View and interract with platform analytics here.
        </p> -->

        <div class="flex justify-between items-start py-8 space-x-20">
            <!-- app downloads -->
            <div class="w-1/3 p-8 bg-sky-100 rounded-xl">
                <div class="w-full flex justify-start items-center space-x-4">
                    <span class="text-2xl text-white bg-sky-500 px-3.5 py-3 rounded-xl">
                        <i class="fa-solid fa-download"></i>
                    </span>

                    <div>
                        <p class="text-sm font-bold">Application Downloads</p>
                        <p class="text-xs text-gray-500">Total Downloads</p>
                    </div>
                </div>

                <p class="mt-8 text-5xl text-gray-700">
                    {{ numeral(totals.downloads).format('0a') }}
                </p>
            </div>

            <!-- app installations -->
            <div class="w-1/3 p-8 bg-orange-100 rounded-xl">
                <div class="w-full flex justify-start items-center space-x-4">
                    <span class="text-2xl text-white bg-orange-400 px-3.5 py-3 rounded-xl">
                        <i class="fa-solid fa-mobile"></i>
                    </span>

                    <div>
                        <p class="text-sm font-bold">Application Installations</p>
                        <p class="text-xs text-gray-500">Total Installations</p>
                    </div>
                </div>

                <p class="mt-8 text-5xl text-gray-700">
                    {{ numeral(totals.installations).format('0a') }}
                </p>
            </div>

            <!-- messages received -->
            <div class="w-1/3 p-8 bg-blue-100 rounded-xl">
                <div class="w-full flex justify-start items-center space-x-4">
                    <span class="text-2xl text-white bg-blue-500 px-3.5 py-3 rounded-xl">
                        <i class="fa-solid fa-comments"></i>
                    </span>

                    <div>
                        <p class="text-sm font-bold">Messages Recieved</p>
                        <p class="text-xs text-gray-500">Total Messages</p>
                    </div>
                </div>

                <p class="mt-8 text-5xl text-gray-700">
                    {{ numeral(totals.messages).format('0a') }}
                </p>
            </div>
        </div>

        <div class="w-full mt-14">
            <div class="flex justify-between items-center">
                <p>Annual Summary</p>

                <!-- <TextInput type="number" class="text-sm" min="1900" :max="(new Date()).getFullYear()" :value="(new Date()).getFullYear()" /> -->
            </div>
            <div class="mt-5">
                <BarChart :chart-data="data" :options="chart_options" />
            </div>
        </div>
    </section>
</template>

<script>
import AuthenticatedLayoutVue from '@/Layouts/AuthenticatedLayout.vue'

export default {
    layout: AuthenticatedLayoutVue
}
</script>

<script setup>
import { BarChart } from 'vue-chart-3'
import { Chart, registerables } from 'chart.js'
import { computed } from 'vue'
import numeral from 'numeral'

import TextInput from '@/Components/TextInput.vue'

Chart.register(...registerables)

const { totals, annual } = defineProps({totals: Object, annual: Object})

const data = computed(() => ({
    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
    datasets: [
        {
            label: 'Downloads',
            backgroundColor: 'rgba(14, 165, 233, .5)',
            data: [(annual.downloads.filter(x => (new Date(x.created_at)).getMonth() === 1)).length, 84, 45, 36, 47, 206, 95, 785, 52, 364, 54, 145]
        },
        {
            label: 'Installations',
            backgroundColor: 'rgba(251, 146, 60, .6)',
            data: [70, 104, 405, 136, 47, 306, 195, 585, 520, 264, 154, 150]
        },
        {
            label: 'Messages',
            backgroundColor: 'rgba(59, 130, 246, .6)',
            data: [100, 78, 305, 536, 147, 206, 695, 385, 220, 464, 754, 550]
        },
    ],
}))

const chart_options = {
    plugins: {
        legend: {
            position: 'bottom',
            align: 'center'
        }
    }
}
</script>

<style>

</style>