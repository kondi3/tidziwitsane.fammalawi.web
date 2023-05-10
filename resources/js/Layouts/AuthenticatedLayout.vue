<template>
  <main class="w-full h-screen flex justify-start items-start">
    <!-- navigation -->
    <aside
      class="flex flex-col w-64 h-full px-8 py-8 overflow-y-auto bg-white border-r"
    >
      <Link :href="route('site.index')">
        <h4 class="font-bold text-lg text-gray-800">tidziwitsane</h4>
      </Link>

      <div class="flex flex-col justify-between flex-1 mt-14">
        <div class="flex-1">
          <nav class="-mx-3 space-y-3">
            <Link
              v-for="(nav_link, index) in nav_links"
              :key="index"
              class="flex items-center px-4 py-3 text-sm text-gray-600 transition-colors duration-300 transform rounded-lg hover:bg-gray-100 hover:text-gray-700"
              :class="{
                'bg-gray-700 text-white hover:bg-gray-700 hover:text-white cursor-default':
                  route().current(nav_link.route),
              }"
              :href="route(nav_link.route)"
            >
              <i :class="`fa-solid fa-${nav_link.icon}`"></i>
              <span class="mx-2 text-sm font-medium">{{ nav_link.name }}</span>
            </Link>
          </nav>

          <nav class="mt-5 -mx-3 space-y-3">
            <p class="text-xs text-gray-500 p-2 rounded">
              Content Management
            </p>

            <Link
              v-for="(nav_link, index) in content_nav_links"
              :key="index"
              class="flex items-center px-4 py-3 text-sm text-gray-600 transition-colors duration-300 transform rounded-lg hover:bg-gray-100 hover:text-gray-700"
              :class="{
                'bg-gray-700 text-white hover:bg-gray-700 hover:text-white cursor-default':
                  route().current(nav_link.route),
              }"
              :href="route(nav_link.route)"
            >
              <i :class="`fa-solid fa-${nav_link.icon}`"></i>
              <span class="mx-2 text-sm font-medium">{{ nav_link.name }}</span>
            </Link>
          </nav>

          <nav class="mt-5 -mx-3 space-y-3">
            <p class="text-xs text-gray-500 p-2 rounded">
              Settings
            </p>

            <Link
              v-for="(nav_link, index) in settings_nav_links"
              :key="index"
              class="flex items-center px-4 py-3 text-sm text-gray-600 transition-colors duration-300 transform rounded-lg hover:bg-gray-100 hover:text-gray-700"
              :class="{
                'bg-gray-700 text-white hover:bg-gray-700 hover:text-white cursor-default':
                  route().current(nav_link.route),
              }"
              :href="route(nav_link.route)"
            >
              <i :class="`fa-solid fa-${nav_link.icon}`"></i>
              <span class="mx-2 text-sm font-medium">{{ nav_link.name }}</span>
            </Link>
          </nav>
        </div>

        <!-- footer -->
        <div class="mt-6">
          <!-- <div class="p-3 bg-gray-100 rounded-lg">
            <h2 class="text-sm font-medium text-gray-800">
              New feature availabel!
            </h2>

            <p class="mt-1 text-xs text-gray-500">
              Lorem ipsum dolor sit amet consectetur adipisicing elit. Natus
              harum officia eligendi velit.
            </p>

            <img
              class="object-cover w-full h-32 mt-2 rounded-lg"
              src="https://images.unsplash.com/photo-1658953229664-e8d5ebd039ba?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1374&h=1374&q=80"
              alt=""
            />
          </div> -->

          <div class="w-full flex items-center justify-between space-x-5 mt-6">
            <!-- <p class="flex-1 text-sm font-medium text-gray-700 truncate">{{ page.props.auth.user.name }}</p> -->

            <Link
              :href="route('auth.logout')"
              method="post"
              :preserve-state="false"
              as="button"
              class="text-gray-500 transition-colors duration-200 hover:text-gray-900 flex justify-start space-x-3"
            >
              <span>Logout</span>
              <span class="rotate-180">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke-width="1.5"
                  stroke="currentColor"
                  class="w-5 h-5"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75"
                  />
                </svg>
              </span>
            </Link>

            
          </div>
        </div>
      </div>
    </aside>

    <!-- content -->
    <section class="flex-1 w-full h-full py-8 bg-gray-50 overflow-y-auto">
      <div class="max-w-7xl mx-auto p-5">
        <div class="flex justify-between items-center">
          <div>
            <h4 class="tracking-wider text-sm font-bold">{{ page.props.auth.user.name }}</h4>
            <p class="text-xs text-gray-500">{{ page.props.date_today }}</p>
          </div>

          <div>
            <button class="bg-gray-100 px-2.5 py-1.5 rounded-full text-gray-600 hover:text-gray-800 hover:bg-gray-200 transition-colors">
              <i class="fa-solid fa-bell"></i>
            </button>
          </div>
        </div>
      </div>

      <slot></slot>
    </section>
  </main>
</template>

<script setup>
import { usePage } from "@inertiajs/vue3";

const page = usePage();

const nav_links = [
  {
    name: "Dashboard",
    route: "admin.dashboard",
    icon: "chart-simple",
  },
  {
    name: "Messages",
    route: "admin.chats",
    icon: "comments",
  },
];

const content_nav_links = [
  {
    name: "Myths & Facts",
    route: "admin.myths",
    icon: "book",
  },
  {
    name: "Service Providers",
    route: "admin.providers",
    icon: "circle-info",
  },
  {
    name: "FAQs",
    route: "admin.faqs",
    icon: "circle-question",
  },
];

const settings_nav_links = [
  {
    name: "Profile",
    route: "admin.settings.profile",
    icon: "user",
  },
  {
    name: "Data Management",
    route: "admin.settings.data",
    icon: "file-import",
  },
  {
    name: "Users",
    route: "admin.users",
    icon: "user-group",
  },
];
</script>

<style>
</style>