<template>
    <div class="flex h-full">
        <!-- Content area -->
        <div class="flex flex-1 flex-col overflow-hidden">
            <!-- Main content -->
            <div class="flex flex-1 items-stretch overflow-hidden">
                <main class="flex-1 overflow-y-auto">
                    <div class="mx-auto max-w-7xl px-4 pt-8 sm:px-6 lg:px-8">
                        <div class="flex">
                            <h1 class="flex-1 text-2xl font-bold text-gray-900">
                                Photos
                            </h1>
                            <div
                                class="ml-6 flex items-center rounded-lg bg-gray-100 p-0.5 sm:hidden"
                            >
                                <button
                                    type="button"
                                    class="rounded-md p-1.5 text-gray-400 hover:bg-white hover:shadow-sm focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500"
                                >
                                    <Bars4Icon
                                        class="h-5 w-5"
                                        aria-hidden="true"
                                    />
                                    <span class="sr-only">Use list view</span>
                                </button>
                                <button
                                    type="button"
                                    class="ml-0.5 rounded-md bg-white p-1.5 text-gray-400 shadow-sm focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500"
                                >
                                    <Squares2X2IconMini
                                        class="h-5 w-5"
                                        aria-hidden="true"
                                    />
                                    <span class="sr-only">Use grid view</span>
                                </button>
                            </div>
                        </div>

                        <!-- Tabs -->
                        <div class="mt-3 sm:mt-2">
                            <div class="sm:hidden">
                                <label for="tabs" class="sr-only"
                                    >Select a tab</label
                                >
                                <!-- Use an "onChange" listener to redirect the user to the selected tab URL. -->
                                <select
                                    id="tabs"
                                    name="tabs"
                                    class="block w-full rounded-md border-gray-300 py-2 pl-3 pr-10 text-base focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm"
                                >
                                    <option selected="">Recently Viewed</option>
                                    <option>Recently Added</option>
                                    <option>Favorited</option>
                                </select>
                            </div>
                            <div class="hidden sm:block">
                                <div
                                    class="flex items-center border-b border-gray-200"
                                >
                                    <nav
                                        class="-mb-px flex flex-1 space-x-6 xl:space-x-8"
                                        aria-label="Tabs"
                                    >
                                        <a
                                            v-for="tab in tabs"
                                            :key="tab.name"
                                            :href="tab.href"
                                            :aria-current="
                                                tab.current ? 'page' : undefined
                                            "
                                            :class="[
                                                tab.current
                                                    ? 'border-indigo-500 text-indigo-600'
                                                    : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300',
                                                'whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm',
                                            ]"
                                            >{{ tab.name }}</a
                                        >
                                    </nav>
                                    <div
                                        class="ml-6 hidden items-center rounded-lg bg-gray-100 p-0.5 sm:flex"
                                    >
                                        <button
                                            type="button"
                                            class="rounded-md p-1.5 text-gray-400 hover:bg-white hover:shadow-sm focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500"
                                        >
                                            <Bars4Icon
                                                class="h-5 w-5"
                                                aria-hidden="true"
                                            />
                                            <span class="sr-only"
                                                >Use list view</span
                                            >
                                        </button>
                                        <button
                                            type="button"
                                            class="ml-0.5 rounded-md bg-white p-1.5 text-gray-400 shadow-sm focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500"
                                        >
                                            <Squares2X2IconMini
                                                class="h-5 w-5"
                                                aria-hidden="true"
                                            />
                                            <span class="sr-only"
                                                >Use grid view</span
                                            >
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Gallery -->
                        <section
                            class="mt-8 pb-16"
                            aria-labelledby="gallery-heading"
                        >
                            <h2 id="gallery-heading" class="sr-only">
                                Recently viewed
                            </h2>
                            <ul
                                role="list"
                                class="grid grid-cols-2 gap-x-4 gap-y-8 sm:grid-cols-3 sm:gap-x-6 md:grid-cols-4 lg:grid-cols-3 xl:grid-cols-4 xl:gap-x-8"
                            >
                                <li
                                    v-for="file in files"
                                    :key="file.name"
                                    class="relative"
                                >
                                    <div
                                        :class="[
                                            file.current
                                                ? 'ring-2 ring-offset-2 ring-indigo-500'
                                                : 'focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-offset-gray-100 focus-within:ring-indigo-500',
                                            'group block w-full aspect-w-10 aspect-h-7 rounded-lg bg-gray-100 overflow-hidden',
                                        ]"
                                    >
                                        <img
                                            :src="file.source"
                                            alt=""
                                            :class="[
                                                file.current
                                                    ? ''
                                                    : 'group-hover:opacity-75',
                                                'object-cover pointer-events-none aspect-square',
                                            ]"
                                        />
                                        <button
                                            type="button"
                                            class="absolute inset-0 focus:outline-none"
                                        >
                                            <span class="sr-only"
                                                >View details for
                                                {{ file.name }}</span
                                            >
                                        </button>
                                    </div>
                                    <p
                                        class="pointer-events-none mt-2 block truncate text-sm font-medium text-gray-900"
                                    >
                                        {{ file.name }}
                                    </p>
                                    <p
                                        class="pointer-events-none block text-sm font-medium text-gray-500"
                                    >
                                        {{ file.size }}
                                    </p>
                                </li>
                            </ul>
                        </section>
                    </div>
                </main>

                <!-- Details sidebar -->
                <aside
                    class="hidden w-96 overflow-y-auto border-l border-gray-200 bg-white p-8 lg:block"
                >
                    <div class="space-y-6 pb-16">
                        <div>
                            <div
                                class="aspect-w-10 aspect-h-7 block w-full overflow-hidden rounded-lg"
                            >
                                <img
                                    :src="currentFile.source"
                                    alt=""
                                    class="object-cover"
                                />
                            </div>
                            <div class="mt-4 flex items-start justify-between">
                                <div>
                                    <h2
                                        class="text-lg font-medium text-gray-900"
                                    >
                                        <span class="sr-only">Details for </span
                                        >{{ currentFile.name }}
                                    </h2>
                                    <p
                                        class="text-sm font-medium text-gray-500"
                                    >
                                        {{ currentFile.size }}
                                    </p>
                                </div>
                                <button
                                    type="button"
                                    class="ml-4 flex h-8 w-8 items-center justify-center rounded-full bg-white text-gray-400 hover:bg-gray-100 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                >
                                    <HeartIcon
                                        class="h-6 w-6"
                                        aria-hidden="true"
                                    />
                                    <span class="sr-only">Favorite</span>
                                </button>
                            </div>
                        </div>
                        <div>
                            <h3 class="font-medium text-gray-900">
                                Information
                            </h3>
                            <dl
                                class="mt-2 divide-y divide-gray-200 border-t border-b border-gray-200"
                            >
                                <div
                                    v-for="key in Object.keys(
                                        currentFile.information
                                    )"
                                    :key="key"
                                    class="flex justify-between py-3 text-sm font-medium"
                                >
                                    <dt class="text-gray-500">{{ key }}</dt>
                                    <dd class="whitespace-nowrap text-gray-900">
                                        {{ currentFile.information[key] }}
                                    </dd>
                                </div>
                            </dl>
                        </div>
                        <div>
                            <h3 class="font-medium text-gray-900">
                                Description
                            </h3>
                            <div class="mt-2 flex items-center justify-between">
                                <p class="text-sm italic text-gray-500">
                                    Add a description to this image.
                                </p>
                                <button
                                    type="button"
                                    class="flex h-8 w-8 items-center justify-center rounded-full bg-white text-gray-400 hover:bg-gray-100 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                >
                                    <PencilIcon
                                        class="h-5 w-5"
                                        aria-hidden="true"
                                    />
                                    <span class="sr-only">Add description</span>
                                </button>
                            </div>
                        </div>
                        <div>
                            <h3 class="font-medium text-gray-900">
                                Shared with
                            </h3>
                            <ul
                                role="list"
                                class="mt-2 divide-y divide-gray-200 border-t border-b border-gray-200"
                            >
                                <li
                                    v-for="person in currentFile.sharedWith"
                                    :key="person.id"
                                    class="flex items-center justify-between py-3"
                                >
                                    <div class="flex items-center">
                                        <img
                                            :src="person.imageUrl"
                                            alt=""
                                            class="h-8 w-8 rounded-full"
                                        />
                                        <p
                                            class="ml-4 text-sm font-medium text-gray-900"
                                        >
                                            {{ person.name }}
                                        </p>
                                    </div>
                                    <button
                                        type="button"
                                        class="ml-6 rounded-md bg-white text-sm font-medium text-indigo-600 hover:text-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                                    >
                                        Remove<span class="sr-only">
                                            {{ person.name }}</span
                                        >
                                    </button>
                                </li>
                                <li
                                    class="flex items-center justify-between py-2"
                                >
                                    <button
                                        type="button"
                                        class="group -ml-1 flex items-center rounded-md bg-white p-1 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                    >
                                        <span
                                            class="flex h-8 w-8 items-center justify-center rounded-full border-2 border-dashed border-gray-300 text-gray-400"
                                        >
                                            <PlusIconMini
                                                class="h-5 w-5"
                                                aria-hidden="true"
                                            />
                                        </span>
                                        <span
                                            class="ml-4 text-sm font-medium text-indigo-600 group-hover:text-indigo-500"
                                            >Share</span
                                        >
                                    </button>
                                </li>
                            </ul>
                        </div>
                        <div class="flex">
                            <button
                                type="button"
                                class="flex-1 rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                            >
                                Download
                            </button>
                            <button
                                type="button"
                                class="ml-3 flex-1 rounded-md border border-gray-300 bg-white py-2 px-4 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                            >
                                Delete
                            </button>
                        </div>
                    </div>
                </aside>
            </div>
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
    Bars3BottomLeftIcon,
    CogIcon,
    HeartIcon,
    HomeIcon,
    PhotoIcon,
    PlusIcon as PlusIconOutline,
    RectangleStackIcon,
    Squares2X2Icon as Squares2X2IconOutline,
    UserGroupIcon,
    XMarkIcon,
} from "@heroicons/vue/24/outline";
import {
    Bars4Icon,
    MagnifyingGlassIcon,
    PencilIcon,
    PlusIcon as PlusIconMini,
    Squares2X2Icon as Squares2X2IconMini,
} from "@heroicons/vue/20/solid";
const tabs = [
    { name: "Recently Viewed", href: "#", current: true },
    { name: "Recently Added", href: "#", current: false },
    { name: "Favorited", href: "#", current: false },
];
const files = [
    {
        name: "IMG_4985.HEIC",
        size: "3.9 MB",
        source: "https://images.unsplash.com/photo-1582053433976-25c00369fc93?ixid=MXwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHw%3D&ixlib=rb-1.2.1&auto=format&fit=crop&w=512&q=80",
        current: true,
    },
    {
        name: "IMG_4985.HEIC",
        size: "3.9 MB",
        source: "https://images.unsplash.com/photo-1614705827065-62c3dc488f40?ixid=MXwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHw%3D&ixlib=rb-1.2.1&auto=format&fit=crop&w=512&q=80",
        current: false,
    },
    {
        name: "IMG_4985.HEIC",
        size: "4 MB",
        source: "https://images.unsplash.com/photo-1614926857083-7be149266cda?ixlib=rb-1.2.1&ixid=MXwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHw%3D&auto=format&fit=crop&w=512&q=80",
        current: false,
    },
    {
        name: "IMG_4985.HEIC",
        size: "4 MB",
        source: "https://images.unsplash.com/photo-1441974231531-c6227db76b6e?ixid=MXwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHw%3D&ixlib=rb-1.2.1&auto=format&fit=crop&w=512&q=80",
        current: false,
    },
    {
        name: "IMG_4985.HEIC",
        size: "4 MB",
        source: "https://images.unsplash.com/photo-1586348943529-beaae6c28db9?ixid=MXwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHw%3D&ixlib=rb-1.2.1&auto=format&fit=crop&w=512&q=80",
        current: false,
    },
    {
        name: "IMG_4985.HEIC",
        size: "4 MB",
        source: "https://images.unsplash.com/photo-1497436072909-60f360e1d4b1?ixlib=rb-1.2.1&ixid=MXwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHw%3D&auto=format&fit=crop&w=512&q=80",
        current: false,
    },
    {
        name: "IMG_4985.HEIC",
        size: "4 MB",
        source: "https://images.unsplash.com/photo-1677297680174-63fea98df131?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxlZGl0b3JpYWwtZmVlZHw2OXx8fGVufDB8fHx8&auto=format&fit=crop&w=500&q=60",
        current: false,
    },
];
const currentFile = {
    name: "IMG_4985.HEIC",
    size: "3.9 MB",
    source: "https://images.unsplash.com/photo-1582053433976-25c00369fc93?ixid=MXwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHw%3D&ixlib=rb-1.2.1&auto=format&fit=crop&w=512&q=80",
    information: {
        "Uploaded by": "Marie Culver",
        Created: "June 8, 2020",
        "Last modified": "June 8, 2020",
        Dimensions: "4032 x 3024",
        Resolution: "72 x 72",
    },
    sharedWith: [
        {
            id: 1,
            name: "Aimee Douglas",
            imageUrl:
                "https://images.unsplash.com/photo-1502685104226-ee32379fefbe?ixlib=rb-=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=3&w=1024&h=1024&q=80",
        },
        {
            id: 2,
            name: "Andrea McMillan",
            imageUrl:
                "https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-1.2.1&ixqx=oilqXxSqey&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80",
        },
    ],
};

const mobileMenuOpen = ref(false);
</script>
