<template>
  <!-- navigation -->
  <main class="w-full">
    <div class="container mx-auto flex justify-between items-center py-5">
      <!-- brand -->
      <h4 class="font-bold tracking-wider">Tidziwitsane</h4>

      <!-- nav links -->
      <ul class="flex justify-end space-x-5">
        <li v-for="(nav_link, index) in nav_links" :key="index">
          <NavLink
            class="tracking-widest"
            :href="route(nav_link.route)"
            :active="route().current(nav_link.route)"
          >
            {{ nav_link.name }}
          </NavLink>
        </li>

        <li>
            <Link :href="route('auth.login')" class="text-sm text-white bg-gray-700 hover:bg-gray-800 transition-colors px-4 py-2 rounded">Login</Link>
        </li>
      </ul>
    </div>
  </main>

  <!-- content -->
  <slot></slot>

  <!-- footer -->
</template>

<script setup>
import NavLink from "@/Components/NavLink.vue";

const nav_links = [
  {
    name: "Home",
    route: "site.index",
  },
  {
    name: "About",
    route: "site.about",
  },
  {
    name: "Policies",
    route: "site.policies",
  },
  {
    name: 'Contacts',
    route: 'site.contacts'
  }
];
</script>

<style>
</style>