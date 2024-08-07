<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue';
import { EllipsisVerticalIcon } from '@heroicons/vue/20/solid';

const defaultImage = 'https://via.placeholder.com/150';

defineProps({
  users: {
    type: Object,
    required: true,
  },
});
</script>

<template>
  <Head title="Admin Dashboard" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Admin Dashboard</h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <ul role="list" class="divide-y divide-gray-100">
          <li
            v-for="(user, index) in users"
            :key="user.email"
            class="flex justify-between gap-x-6 p-5"
            :class="index == 1 ? 'bg-slate-100' : 'bg-slate-300'"
          >
            <!-- alternate color for odd index -->
            <div class="flex min-w-0 gap-x-4">
              <img
                class="h-12 w-12 flex-none rounded-full bg-gray-50"
                :src="user.imageUrl || defaultImage"
                alt=""
              />
              <div class="min-w-0 flex-auto">
                <p class="text-sm font-semibold leading-6 text-gray-900">
                  <a :href="'/users/' + user.id" class="hover:underline">{{ user.name }}</a>
                </p>
                <p class="mt-1 flex text-xs leading-5 text-gray-500">
                  <a :href="'mailto:' + user.email" class="truncate hover:underline">
                    {{ user.email }}
                  </a>
                </p>
              </div>
            </div>
            <div class="flex shrink-0 items-center gap-x-6">
              <div class="hidden sm:flex sm:flex-col sm:items-end">
                <p class="text-sm leading-6 text-gray-900">
                  {{ user.roles[0]?.name }}
                </p>
              </div>
              <Menu as="div" class="relative flex-none">
                <MenuButton class="-m-2.5 block p-2.5 text-gray-500 hover:text-gray-900">
                  <span class="sr-only">Open options</span>
                  <EllipsisVerticalIcon class="h-5 w-5" aria-hidden="true" />
                </MenuButton>
                <transition
                  enter-active-class="transition ease-out duration-100"
                  enter-from-class="transform opacity-0 scale-95"
                  enter-to-class="transform opacity-100 scale-100"
                  leave-active-class="transition ease-in duration-75"
                  leave-from-class="transform opacity-100 scale-100"
                  leave-to-class="transform opacity-0 scale-95"
                >
                  <MenuItems
                    class="absolute right-0 z-10 mt-2 w-32 origin-top-right rounded-md bg-white py-2 shadow-lg ring-1 ring-gray-900/5 focus:outline-none"
                  >
                    <MenuItem v-slot="{ active }">
                      <a
                        href="#"
                        :class="[
                          active ? 'bg-gray-50' : '',
                          'block px-3 py-1 text-sm leading-6 text-gray-900',
                        ]"
                      >
                        View profile
                        <span class="sr-only">, {{ user.name }}</span>
                      </a>
                    </MenuItem>
                    <!-- TODO: implement edit functionality for users -->
                    <MenuItem v-slot="{ active }">
                      <a
                        href="#"
                        :class="[
                          active ? 'bg-gray-50' : '',
                          'block px-3 py-1 text-sm leading-6 text-gray-900',
                        ]"
                      >
                        Edit
                        <span class="sr-only">, {{ user.name }}</span>
                      </a>
                    </MenuItem>
                  </MenuItems>
                </transition>
              </Menu>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
