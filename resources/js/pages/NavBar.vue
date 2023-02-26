<template>
    <div>
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
                            class="relative flex w-full max-w-xs flex-1 flex-col bg-gray-800"
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
                            <div class="h-0 flex-1 overflow-y-auto pt-5 pb-4">
                                <div
                                    class="flex flex-shrink-0 items-center px-4"
                                >
                                    <img
                                        class="h-8 w-auto"
                                        src="https://tailwindui.com/img/logos/mark.svg?color=indigo&shade=500"
                                        alt="Your Company"
                                    />
                                </div>
                                <nav class="mt-5 space-y-1 px-2">
                                    <router-link
                                        v-for="item in navigation"
                                        :key="item.name"
                                        :to="item.href"
                                        :class="[
                                            item.current
                                                ? 'bg-gray-900 text-white'
                                                : 'text-gray-300 hover:bg-gray-700 hover:text-white',
                                            'group flex items-center px-2 py-2 text-base font-medium rounded-md',
                                        ]"
                                    >
                                        <component
                                            :is="item.icon"
                                            :class="[
                                                item.current
                                                    ? 'text-gray-300'
                                                    : 'text-gray-400 group-hover:text-gray-300',
                                                'mr-4 flex-shrink-0 h-6 w-6',
                                            ]"
                                            aria-hidden="true"
                                        />
                                        {{ item.name }}
                                    </router-link>
                                </nav>
                            </div>
                            <div class="flex flex-shrink-0 bg-gray-700 p-4">
                                <a href="#" class="group block flex-shrink-0">
                                    <div class="flex items-center">
                                        <div>
                                            <img
                                                class="inline-block h-10 w-10 rounded-full"
                                                src="/images/sahil.jpg"
                                                alt=""
                                            />
                                        </div>
                                        <div class="ml-3">
                                            <p
                                                class="text-base font-medium text-white capitalize"
                                            >
                                                {{ currentUser }}
                                            </p>
                                            <p
                                                class="text-sm font-medium text-gray-400 group-hover:text-gray-300"
                                            >
                                                View profile
                                            </p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </DialogPanel>
                    </TransitionChild>
                    <div class="w-14 flex-shrink-0">
                        <!-- Force sidebar to shrink to fit close icon -->
                    </div>
                </div>
            </Dialog>
        </TransitionRoot>

        <!-- Static sidebar for desktop -->
        <div class="hidden lg:fixed lg:inset-y-0 lg:flex lg:w-64 lg:flex-col">
            <!-- Sidebar component, swap this element with another sidebar if you like -->
            <div class="flex min-h-0 flex-1 flex-col bg-gray-800">
                <div class="flex flex-1 flex-col overflow-y-auto pt-5 pb-4">
                    <div class="flex flex-shrink-0 items-center px-4">
                        <img
                            class="h-8 w-auto"
                            src="https://tailwindui.com/img/logos/mark.svg?color=indigo&shade=500"
                            alt="Your Company"
                        />
                    </div>
                    <nav class="mt-5 flex-1 space-y-1 px-2">
                        <router-link
                            to="/login"
                            v-for="item in navigation"
                            :key="item.name"
                            :href="item.href"
                            :class="[
                                item.current
                                    ? 'bg-gray-900 text-white'
                                    : 'text-gray-300 hover:bg-gray-700 hover:text-white',
                                'group flex items-center px-2 py-2 text-sm font-medium rounded-md',
                            ]"
                        >
                            <component
                                :is="item.icon"
                                :class="[
                                    item.current
                                        ? 'text-gray-300'
                                        : 'text-gray-400 group-hover:text-gray-300',
                                    'mr-3 flex-shrink-0 h-6 w-6',
                                ]"
                                aria-hidden="true"
                            />
                            {{ item.name }}
                        </router-link>
                    </nav>
                </div>
                <div class="flex flex-shrink-0 bg-gray-700 p-4">
                    <a href="#" class="group block w-full flex-shrink-0">
                        <div class="flex items-center">
                            <div>
                                <img
                                    class="inline-block h-9 w-9 rounded-full"
                                    src="/images/sahil.jpg"
                                    alt=""
                                />
                            </div>
                            <div class="ml-3">
                                <p
                                    class="text-sm font-medium text-white capitalize"
                                >
                                    {{ currentUser }}
                                </p>
                                <p
                                    class="text-xs font-medium text-gray-300 group-hover:text-gray-200"
                                >
                                    View profile
                                </p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        <div class="flex flex-1 flex-col lg:pl-64">
            <!-- <div
                class="sticky top-0 z-10 bg-gray-100 pl-1 pt-1 sm:pl-3 sm:pt-3 lg:hidden"
            >
                <button
                    type="button"
                    class="-ml-0.5 -mt-0.5 inline-flex h-12 w-12 items-center justify-center rounded-md text-gray-500 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500"
                    @click="sidebarOpen = true"
                >
                    <span class="sr-only">Open sidebar</span>
                    <Bars3Icon class="h-6 w-6" aria-hidden="true" />
                </button>
            </div> -->
            <main class="flex-1 h-full">
                <div class="">
                    <div class="mx-auto max-w-7xl">
                        <!-- Replace with your content -->
                        <div class="">
                            <div
                                class="flex h-16 flex-shrink-0 border-b border-gray-200 bg-white"
                            >
                                <button
                                    type="button"
                                    class="border-r border-gray-200 px-4 text-gray-400 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-cyan-500 lg:hidden"
                                    @click="sidebarOpen = true"
                                >
                                    <span class="sr-only">Open sidebar</span>
                                    <Bars3CenterLeftIcon
                                        class="h-6 w-6"
                                        aria-hidden="true"
                                    />
                                </button>
                                <!-- Search bar -->
                                <div
                                    class="flex flex-1 justify-between px-5 lg:mx-auto lg:max-w-6xl"
                                >
                                    <div class="flex flex-1">
                                        <form
                                            class="flex w-full md:ml-0"
                                            action="#"
                                            method="GET"
                                        >
                                            <label
                                                for="search-field"
                                                class="sr-only"
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
                                                    class="block h-full w-full border-transparent py-2 pl-8 pr-3 text-gray-900 placeholder-gray-500 focus:border-transparent focus:outline-none focus:ring-0 sm:text-sm"
                                                    placeholder="Search Details"
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
                                            <span class="sr-only"
                                                >View notifications</span
                                            >
                                            <BellIcon
                                                class="h-6 w-6"
                                                aria-hidden="true"
                                            />
                                        </button>

                                        <!-- Profile dropdown -->
                                        <Menu as="div" class="relative ml-3">
                                            <div>
                                                <MenuButton
                                                    class="flex max-w-xs items-center rounded-full bg-white text-sm focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2 lg:rounded-md lg:p-2 lg:hover:bg-gray-50"
                                                >
                                                    <img
                                                        class="h-8 w-8 rounded-full"
                                                        src="\images\sahil.jpg"
                                                        alt=""
                                                    />
                                                    <span
                                                        class="ml-3 hidden text-sm font-medium text-gray-700 lg:block capitalize"
                                                    >
                                                        {{ currentUser }}
                                                    </span>
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
                                                    <MenuItem
                                                        v-slot="{ active }"
                                                    >
                                                        <a
                                                            href="#"
                                                            :class="[
                                                                active
                                                                    ? 'bg-gray-100'
                                                                    : '',
                                                                'block px-4 py-2 text-sm text-gray-700',
                                                            ]"
                                                            >Your Profile</a
                                                        >
                                                    </MenuItem>
                                                    <MenuItem
                                                        v-slot="{ active }"
                                                    >
                                                        <a
                                                            href="#"
                                                            :class="[
                                                                active
                                                                    ? 'bg-gray-100'
                                                                    : '',
                                                                'block px-4 py-2 text-sm text-gray-700',
                                                            ]"
                                                            >Settings</a
                                                        >
                                                    </MenuItem>
                                                    <MenuItem
                                                        v-slot="{ active }"
                                                    >
                                                        <a
                                                            href="#"
                                                            @click="
                                                                isAuthLogout
                                                            "
                                                            :class="[
                                                                active
                                                                    ? 'bg-gray-100'
                                                                    : '',
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
                            <MainBody />
                        </div>
                        <!-- /End replace -->
                    </div>
                </div>
            </main>
        </div>
    </div>
</template>
<script>
import { computed } from "vue";
export default {
    computed: {
        currentUser() {
            return this.$store.getters["auth/currentLogedUser"];
        },
    },
    methods: {
        isAuthLogout() {
            this.$store.dispatch("auth/isAuthLogout");
        },
    },
};
</script>
<script setup>
import { ref } from "vue";
import MainBody from "./MainBody.vue";
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
    CalendarIcon,
    ChartBarIcon,
    FolderIcon,
    HomeIcon,
    InboxIcon,
    UsersIcon,
    XMarkIcon,
    Bars3CenterLeftIcon,
    BellIcon,
    MagnifyingGlassIcon,
} from "@heroicons/vue/24/outline";

const navigation = [
    { name: "Dashboard", href: "/", icon: HomeIcon, current: true },
    { name: "Team", href: "/login", icon: UsersIcon, current: false },
    { name: "Photos", href: "/photos", icon: FolderIcon, current: false },
    { name: "Calendar", href: "#", icon: CalendarIcon, current: false },
    { name: "Documents", href: "#", icon: InboxIcon, current: false },
    { name: "Reports", href: "#", icon: ChartBarIcon, current: false },
];

const sidebarOpen = ref(false);
</script>
