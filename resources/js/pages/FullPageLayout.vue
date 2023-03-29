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
                            class="relative flex flex-col flex-1 w-full max-w-xs pt-5 pb-4 bg-cyan-700"
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
                                <div class="absolute top-0 right-0 pt-2 -mr-12">
                                    <button
                                        type="button"
                                        class="flex items-center justify-center w-10 h-10 ml-1 rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white"
                                        @click="sidebarOpen = false"
                                    >
                                        <span class="sr-only"
                                            >Close sidebar</span
                                        >
                                        <XMarkIcon
                                            class="w-6 h-6 text-white"
                                            aria-hidden="true"
                                        />
                                    </button>
                                </div>
                            </TransitionChild>
                            <div class="flex items-center flex-shrink-0 px-4">
                                <img
                                    class="w-auto h-8"
                                    src="https://tailwindui.com/img/logos/mark.svg?color=cyan&shade=300"
                                    alt="Easywire logo"
                                />
                            </div>
                            <nav
                                class="flex-shrink-0 h-full mt-5 overflow-y-auto divide-y divide-cyan-800"
                                aria-label="Sidebar"
                            >
                                <div class="px-2 space-y-1">
                                    <a
                                        v-for="item in navigation"
                                        :key="item"
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
                                            class="flex-shrink-0 w-6 h-6 mr-4 text-cyan-200"
                                            aria-hidden="true"
                                        />
                                        {{ item.name }}
                                    </a>
                                </div>
                                <div class="pt-6 mt-6">
                                    <div class="px-2 space-y-1">
                                        <a
                                            v-for="item in secondaryNavigation"
                                            :key="item.name"
                                            :href="item.href"
                                            :class="[
                                                item.current
                                                    ? 'bg-cyan-800 text-white'
                                                    : 'text-cyan-100 hover:bg-cyan-600 hover:text-white',
                                                'group flex items-center rounded-md px-2 py-2 text-base font-medium',
                                            ]"
                                        >
                                            <component
                                                :is="item.icon"
                                                class="w-6 h-6 mr-4 text-cyan-200"
                                                aria-hidden="true"
                                            />
                                            {{ item.name }}
                                        </a>
                                    </div>
                                </div>
                            </nav>
                        </DialogPanel>
                    </TransitionChild>
                    <div class="flex-shrink-0 w-14" aria-hidden="true">
                        <!-- Dummy element to force sidebar to shrink to fit close icon -->
                    </div>
                </div>
            </Dialog>
        </TransitionRoot>

        <!-- Static sidebar for desktop -->
        <div class="hidden lg:fixed lg:inset-y-0 lg:flex lg:w-64 lg:flex-col">
            <!-- Sidebar component, swap this element with another sidebar if you like -->
            <div
                class="flex flex-col flex-grow pt-5 pb-4 overflow-y-auto bg-cyan-700"
            >
                <div class="flex items-center flex-shrink-0 px-4">
                    <img
                        class="w-auto h-8"
                        src="https://tailwindui.com/img/logos/mark.svg?color=cyan&shade=300"
                        alt="Easywire logo"
                    />
                </div>
                <nav
                    class="flex flex-col flex-1 mt-5 overflow-y-auto divide-y divide-cyan-800"
                    aria-label="Sidebar"
                >
                    <div class="px-2 space-y-1">
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
                                class="flex-shrink-0 w-6 h-6 mr-4 text-cyan-200"
                                aria-hidden="true"
                            />
                            {{ item.name }}
                        </a>
                    </div>
                    <div class="pt-6 mt-6">
                        <div class="px-2 space-y-1">
                            <a
                                v-for="item in secondaryNavigation"
                                :key="item.name"
                                :href="item.href"
                                :class="[
                                    item.current
                                        ? 'bg-cyan-800 text-white'
                                        : 'text-cyan-100 hover:bg-cyan-600 hover:text-white',
                                    'group flex items-center rounded-md px-2 py-2 text-base font-medium',
                                ]"
                            >
                                <component
                                    :is="item.icon"
                                    class="w-6 h-6 mr-4 text-cyan-200"
                                    aria-hidden="true"
                                />
                                {{ item.name }}
                            </a>
                        </div>
                    </div>
                </nav>
            </div>
        </div>

        <div class="flex flex-col flex-1 lg:pl-64">
            <div
                class="flex flex-shrink-0 h-16 bg-white border-b border-gray-200 lg:border-none"
            >
                <button
                    type="button"
                    class="px-4 text-gray-400 border-r border-gray-200 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-cyan-500 lg:hidden"
                    @click="sidebarOpen = true"
                >
                    <span class="sr-only">Open sidebar</span>
                    <Bars3CenterLeftIcon class="w-6 h-6" aria-hidden="true" />
                </button>
                <!-- Search bar -->
                <div
                    class="flex justify-between flex-1 px-4 sm:px-6 lg:mx-auto lg:px-8"
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
                                    class="absolute inset-y-0 left-0 flex items-center pointer-events-none"
                                    aria-hidden="true"
                                >
                                    <MagnifyingGlassIcon
                                        class="w-5 h-5"
                                        aria-hidden="true"
                                    />
                                </div>
                                <input
                                    id="search-field"
                                    name="search-field"
                                    class="block w-full h-full py-2 pl-8 pr-3 text-gray-900 border-transparent focus:border-transparent focus:outline-none focus:ring-0 sm:text-sm"
                                    placeholder="Search transactions"
                                    type="search"
                                />
                            </div>
                        </form>
                    </div>
                    <div class="flex items-center ml-4 md:ml-6">
                        <button
                            type="button"
                            class="p-1 text-gray-400 bg-white rounded-full hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2"
                        >
                            <span class="sr-only">View notifications</span>
                            <BellIcon class="w-6 h-6" aria-hidden="true" />
                        </button>

                        <!-- Profile dropdown -->
                        <Menu as="div" class="relative ml-3">
                            <div>
                                <MenuButton
                                    class="flex items-center max-w-xs text-sm bg-white rounded-full focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2 lg:rounded-md lg:p-2 lg:hover:bg-gray-50"
                                >
                                    <img
                                        class="w-8 h-8 rounded-full"
                                        src="https://cdn.vectorstock.com/i/1000x1000/92/94/bearded-man-in-glasses-showing-emotion-winking-vector-45159294.webp"
                                        alt=""
                                    />
                                    <span
                                        class="hidden ml-3 text-sm font-medium text-gray-700 lg:block"
                                        ><span class="sr-only"
                                            >Open user menu for </span
                                        >Sahil</span
                                    >
                                    <ChevronDownIcon
                                        class="flex-shrink-0 hidden w-5 h-5 ml-1 text-gray-400 lg:block"
                                        aria-hidden="true"
                                    />
                                </MenuButton>
                            </div>
                            <transition
                                enter-active-class="transition duration-100 ease-out"
                                enter-from-class="transform scale-95 opacity-0"
                                enter-to-class="transform scale-100 opacity-100"
                                leave-active-class="transition duration-75 ease-in"
                                leave-from-class="transform scale-100 opacity-100"
                                leave-to-class="transform scale-95 opacity-0"
                            >
                                <MenuItems
                                    class="absolute right-0 z-10 w-48 py-1 mt-2 origin-top-right bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
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
                                            href="auth/logout"
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
import { ref } from "vue";
const navigation = [
    {
        name: "Home",
        href: "/dashboard",
        icon: HomeIcon,
        current:
            "/dashboard" === window.location.pathname ||
            "/" === window.location.pathname,
    },
    {
        name: "Photos",
        href: "/photos",
        icon: ClockIcon,
        current: "/photos" === window.location.pathname,
    },
    {
        name: "Teams",
        href: "/teams",
        icon: ScaleIcon,
        current: "/teams" === window.location.pathname,
    },
    { name: "Projects", href: "#", icon: CreditCardIcon, current: false },
    {
        name: "Calender",
        href: "/calender",
        icon: UserGroupIcon,
        current: "/calender" === window.location.pathname,
    },
    { name: "Reports", href: "#", icon: DocumentChartBarIcon, current: false },
];
const secondaryNavigation = [
    {
        name: "Settings",
        href: "/setting",
        icon: CogIcon,
        current: "/setting" === window.location.pathname,
    },
    { name: "Help", href: "#", icon: QuestionMarkCircleIcon },
    { name: "Privacy", href: "#", icon: ShieldCheckIcon },
];

const sidebarOpen = ref(false);
</script>
