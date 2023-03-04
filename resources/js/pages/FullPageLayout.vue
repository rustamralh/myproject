<!--
  This example requires some changes to your config:
  
  ```
  // tailwind.config.js
  module.exports = {
    // ...
    plugins: [
      // ...
      require('@tailwindcss/forms'),
    ],
  }
  ```
-->
<template>
    <!--
      This example requires updating your template:
  
      ```
      <html class="h-full bg-gray-100">
      <body class="h-full">
      ```
    -->
    <div class="min-h-full">
        <TransitionRoot as="template" :show="sidebarOpen">
            <Dialog
                as="div"
                class="relative z-40 lg:hidden"
                @close="sidebarOpen = false"
            >
                <TransitionChild
                    as="template"
                    enter="transition-opacity ease-linear duration-300"
                    enter-from="opacity-0"
                    enter-to="opacity-100"
                    leave="transition-opacity ease-linear duration-300"
                    leave-from="opacity-100"
                    leave-to="opacity-0"
                >
                    <div class="fixed inset-0 bg-gray-600 bg-opacity-75" />
                </TransitionChild>

                <div class="fixed inset-0 z-40 flex">
                    <TransitionChild
                        as="template"
                        enter="transition ease-in-out duration-300 transform"
                        enter-from="-translate-x-full"
                        enter-to="translate-x-0"
                        leave="transition ease-in-out duration-300 transform"
                        leave-from="translate-x-0"
                        leave-to="-translate-x-full"
                    >
                        <DialogPanel
                            class="relative flex w-full max-w-xs flex-1 flex-col bg-cyan-700 pt-5 pb-4"
                        >
                            <TransitionChild
                                as="template"
                                enter="ease-in-out duration-300"
                                enter-from="opacity-0"
                                enter-to="opacity-100"
                                leave="ease-in-out duration-300"
                                leave-from="opacity-100"
                                leave-to="opacity-0"
                            >
                                <div class="absolute top-0 right-0 -mr-12 pt-2">
                                    <button
                                        type="button"
                                        class="ml-1 flex h-10 w-10 items-center justify-center rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white"
                                        @click="sidebarOpen = false"
                                    >
                                        <span class="sr-only"
                                            >Close sidebar</span
                                        >
                                        <XMarkIcon
                                            class="h-6 w-6 text-white"
                                            aria-hidden="true"
                                        />
                                    </button>
                                </div>
                            </TransitionChild>
                            <div class="flex flex-shrink-0 items-center px-4">
                                <img
                                    class="h-8 w-auto"
                                    src="https://tailwindui.com/img/logos/mark.svg?color=cyan&shade=300"
                                    alt="Easywire logo"
                                />
                            </div>
                            <nav
                                class="mt-5 h-full flex-shrink-0 divide-y divide-cyan-800 overflow-y-auto"
                                aria-label="Sidebar"
                            >
                                <div class="space-y-1 px-2">
                                    <a
                                        v-for="item in navigation"
                                        :key="item.name"
                                        :href="item.href"
                                        :class="[
                                            item.current
                                                ? 'bg-cyan-800 text-white'
                                                : 'text-cyan-100 hover:bg-cyan-600 hover:text-white',
                                            'group flex items-center rounded-md px-2 py-2 text-base font-medium',
                                        ]"
                                        :aria-current="
                                            item.current ? 'page' : undefined
                                        "
                                    >
                                        <component
                                            :is="item.icon"
                                            class="mr-4 h-6 w-6 flex-shrink-0 text-cyan-200"
                                            aria-hidden="true"
                                        />
                                        {{ item.name }}
                                    </a>
                                </div>
                                <div class="mt-6 pt-6">
                                    <div class="space-y-1 px-2">
                                        <a
                                            v-for="item in secondaryNavigation"
                                            :key="item.name"
                                            :href="item.href"
                                            class="group flex items-center rounded-md px-2 py-2 text-base font-medium text-cyan-100 hover:bg-cyan-600 hover:text-white"
                                        >
                                            <component
                                                :is="item.icon"
                                                class="mr-4 h-6 w-6 text-cyan-200"
                                                aria-hidden="true"
                                            />
                                            {{ item.name }}
                                        </a>
                                    </div>
                                </div>
                            </nav>
                        </DialogPanel>
                    </TransitionChild>
                    <div class="w-14 flex-shrink-0" aria-hidden="true">
                        <!-- Dummy element to force sidebar to shrink to fit close icon -->
                    </div>
                </div>
            </Dialog>
        </TransitionRoot>

        <!-- Static sidebar for desktop -->
        <div class="hidden lg:fixed lg:inset-y-0 lg:flex lg:w-64 lg:flex-col">
            <!-- Sidebar component, swap this element with another sidebar if you like -->
            <div
                class="flex flex-grow flex-col overflow-y-auto bg-cyan-700 pt-5 pb-4"
            >
                <div class="flex flex-shrink-0 items-center px-4">
                    <img
                        class="h-8 w-auto"
                        src="https://tailwindui.com/img/logos/mark.svg?color=cyan&shade=300"
                        alt="Easywire logo"
                    />
                </div>
                <nav
                    class="mt-5 flex flex-1 flex-col divide-y divide-cyan-800 overflow-y-auto"
                    aria-label="Sidebar"
                >
                    <div class="space-y-1 px-2">
                        <a
                            v-for="item in navigation"
                            :key="item.name"
                            :href="item.href"
                            :class="[
                                item.current
                                    ? 'bg-cyan-800 text-white'
                                    : 'text-cyan-100 hover:bg-cyan-600 hover:text-white',
                                'group flex items-center rounded-md px-2 py-2 text-sm font-medium leading-6',
                            ]"
                            :aria-current="item.current ? 'page' : undefined"
                        >
                            <component
                                :is="item.icon"
                                class="mr-4 h-6 w-6 flex-shrink-0 text-cyan-200"
                                aria-hidden="true"
                            />
                            {{ item.name }}
                        </a>
                    </div>
                    <div class="mt-6 pt-6">
                        <div class="space-y-1 px-2">
                            <a
                                v-for="item in secondaryNavigation"
                                :key="item.name"
                                :href="item.href"
                                class="group flex items-center rounded-md px-2 py-2 text-sm font-medium leading-6 text-cyan-100 hover:bg-cyan-600 hover:text-white"
                            >
                                <component
                                    :is="item.icon"
                                    class="mr-4 h-6 w-6 text-cyan-200"
                                    aria-hidden="true"
                                />
                                {{ item.name }}
                            </a>
                        </div>
                    </div>
                </nav>
            </div>
        </div>

        <div class="flex flex-1 flex-col lg:pl-64">
            <div
                class="flex h-16 flex-shrink-0 border-b border-gray-200 bg-white lg:border-none"
            >
                <button
                    type="button"
                    class="border-r border-gray-200 px-4 text-gray-400 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-cyan-500 lg:hidden"
                    @click="sidebarOpen = true"
                >
                    <span class="sr-only">Open sidebar</span>
                    <Bars3CenterLeftIcon class="h-6 w-6" aria-hidden="true" />
                </button>
                <!-- Search bar -->
                <div
                    class="flex flex-1 justify-between px-4 sm:px-6 lg:mx-auto lg:px-8"
                >
                    <div class="flex flex-1">
                        <form
                            class="flex w-full md:ml-0"
                            action="#"
                            method="GET"
                        >
                            <label for="search-field" class="sr-only"
                                >Search</label
                            >
                            <div
                                class="relative w-full text-gray-400 focus-within:text-gray-600"
                            >
                                <div
                                    class="pointer-events-none absolute inset-y-0 left-0 flex items-center"
                                    aria-hidden="true"
                                >
                                    <MagnifyingGlassIcon
                                        class="h-5 w-5"
                                        aria-hidden="true"
                                    />
                                </div>
                                <input
                                    id="search-field"
                                    name="search-field"
                                    class="block h-full w-full border-transparent py-2 pl-8 pr-3 text-gray-900 focus:border-transparent focus:outline-none focus:ring-0 sm:text-sm"
                                    placeholder="Search transactions"
                                    type="search"
                                />
                            </div>
                        </form>
                    </div>
                    <div class="ml-4 flex items-center md:ml-6">
                        <button
                            type="button"
                            class="rounded-full bg-white p-1 text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2"
                        >
                            <span class="sr-only">View notifications</span>
                            <BellIcon class="h-6 w-6" aria-hidden="true" />
                        </button>

                        <!-- Profile dropdown -->
                        <Menu as="div" class="relative ml-3">
                            <div>
                                <MenuButton
                                    class="flex max-w-xs items-center rounded-full bg-white text-sm focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2 lg:rounded-md lg:p-2 lg:hover:bg-gray-50"
                                >
                                    <img
                                        class="h-8 w-8 rounded-full"
                                        src="https://cdn.vectorstock.com/i/1000x1000/92/94/bearded-man-in-glasses-showing-emotion-winking-vector-45159294.webp"
                                        alt=""
                                    />
                                    <span
                                        class="ml-3 hidden text-sm font-medium text-gray-700 lg:block"
                                        ><span class="sr-only"
                                            >Open user menu for </span
                                        >Sahil</span
                                    >
                                    <ChevronDownIcon
                                        class="ml-1 hidden h-5 w-5 flex-shrink-0 text-gray-400 lg:block"
                                        aria-hidden="true"
                                    />
                                </MenuButton>
                            </div>
                            <transition
                                enter-active-class="transition ease-out duration-100"
                                enter-from-class="transform opacity-0 scale-95"
                                enter-to-class="transform opacity-100 scale-100"
                                leave-active-class="transition ease-in duration-75"
                                leave-from-class="transform opacity-100 scale-100"
                                leave-to-class="transform opacity-0 scale-95"
                            >
                                <MenuItems
                                    class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                                >
                                    <MenuItem v-slot="{ active }">
                                        <a
                                            href="#"
                                            :class="[
                                                active ? 'bg-gray-100' : '',
                                                'block px-4 py-2 text-sm text-gray-700',
                                            ]"
                                            >Your Profile</a
                                        >
                                    </MenuItem>
                                    <MenuItem v-slot="{ active }">
                                        <a
                                            href="#"
                                            :class="[
                                                active ? 'bg-gray-100' : '',
                                                'block px-4 py-2 text-sm text-gray-700',
                                            ]"
                                            >Settings</a
                                        >
                                    </MenuItem>
                                    <MenuItem v-slot="{ active }">
                                        <a
                                            href="#"
                                            :class="[
                                                active ? 'bg-gray-100' : '',
                                                'block px-4 py-2 text-sm text-gray-700',
                                            ]"
                                            >Logout</a
                                        >
                                    </MenuItem>
                                </MenuItems>
                            </transition>
                        </Menu>
                    </div>
                </div>
            </div>
            <main class="flex-1 p-7">
                <slot></slot>
            </main>
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";
import {
    Dialog,
    DialogPanel,
    Menu,
    MenuButton,
    MenuItem,
    MenuItems,
    TransitionChild,
    TransitionRoot,
} from "@headlessui/vue";
import {
    Bars3CenterLeftIcon,
    BellIcon,
    ClockIcon,
    CogIcon,
    CreditCardIcon,
    DocumentChartBarIcon,
    HomeIcon,
    QuestionMarkCircleIcon,
    ScaleIcon,
    ShieldCheckIcon,
    UserGroupIcon,
    XMarkIcon,
} from "@heroicons/vue/24/outline";
import {
    BanknotesIcon,
    BuildingOfficeIcon,
    CheckCircleIcon,
    ChevronDownIcon,
    ChevronRightIcon,
    MagnifyingGlassIcon,
} from "@heroicons/vue/20/solid";

const navigation = [
    { name: "Home", href: "#", icon: HomeIcon, current: true },
    { name: "History", href: "#", icon: ClockIcon, current: false },
    { name: "Balances", href: "#", icon: ScaleIcon, current: false },
    { name: "Cards", href: "#", icon: CreditCardIcon, current: false },
    { name: "Recipients", href: "#", icon: UserGroupIcon, current: false },
    { name: "Reports", href: "#", icon: DocumentChartBarIcon, current: false },
];
const secondaryNavigation = [
    { name: "Settings", href: "#", icon: CogIcon },
    { name: "Help", href: "#", icon: QuestionMarkCircleIcon },
    { name: "Privacy", href: "#", icon: ShieldCheckIcon },
];

const sidebarOpen = ref(false);
</script>
