import FullPageLayout from './FullPageLayout.vue';

<template>
    <FullPageLayout>
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 sm:px-6 lg:mx-auto lg:max-w-6xl lg:px-8">
                <div class="py-6 md:flex md:items-center md:justify-between">
                    <div class="min-w-0 flex-1">
                        <!-- Profile -->
                        <div class="flex items-center">
                            <img
                                class="hidden h-16 w-16 rounded-full sm:block"
                                src="https://cdn.vectorstock.com/i/1000x1000/92/94/bearded-man-in-glasses-showing-emotion-winking-vector-45159294.webp"
                                alt=""
                            />
                            <div>
                                <div class="flex items-center">
                                    <img
                                        class="h-16 w-16 rounded-full sm:hidden"
                                        src="https://cdn.vectorstock.com/i/1000x1000/92/94/bearded-man-in-glasses-showing-emotion-winking-vector-45159294.webp"
                                        alt=""
                                    />
                                    <h1
                                        class="ml-3 text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:leading-9"
                                    >
                                        Good morning, Sahil
                                    </h1>
                                </div>
                                <dl
                                    class="mt-6 flex flex-col sm:ml-3 sm:mt-1 sm:flex-row sm:flex-wrap"
                                >
                                    <dt class="sr-only">Company</dt>
                                    <dd
                                        class="flex items-center text-sm font-medium capitalize text-gray-500 sm:mr-6"
                                    >
                                        <BuildingOfficeIcon
                                            class="mr-1.5 h-5 w-5 flex-shrink-0 text-gray-400"
                                            aria-hidden="true"
                                        />
                                        Web Developer
                                    </dd>
                                    <dt class="sr-only">Account status</dt>
                                    <dd
                                        class="mt-3 flex items-center text-sm font-medium capitalize text-gray-500 sm:mr-6 sm:mt-0"
                                    >
                                        <CheckCircleIcon
                                            class="mr-1.5 h-5 w-5 flex-shrink-0 text-green-400"
                                            aria-hidden="true"
                                        />
                                        Verified account
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                    <div class="mt-6 flex space-x-3 md:mt-0">
                        <button
                            type="button"
                            class="inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50"
                        >
                            Add money
                        </button>
                        <button
                            type="button"
                            class="inline-flex items-center rounded-md bg-cyan-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-cyan-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-cyan-600"
                        >
                            Send money
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-8">
            <div class="mx-auto">
                <h2 class="text-lg font-medium leading-6 text-gray-900">
                    Overview
                </h2>
                <div
                    class="mt-2 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3"
                >
                    <!-- Card -->
                    <div
                        v-for="card in cards"
                        :key="card.name"
                        class="overflow-hidden rounded-lg bg-white shadow"
                    >
                        <div class="p-5">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <component
                                        :is="card.icon"
                                        class="h-6 w-6 text-gray-400"
                                        aria-hidden="true"
                                    />
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt
                                            class="truncate text-sm font-medium text-gray-500"
                                        >
                                            {{ card.name }}
                                        </dt>
                                        <dd>
                                            <div
                                                class="text-lg font-medium text-gray-900"
                                            >
                                                {{ card.amount }}
                                            </div>
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-5 py-3">
                            <div class="text-sm">
                                <a
                                    :href="card.href"
                                    class="font-medium text-cyan-700 hover:text-cyan-900"
                                    >View all</a
                                >
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <h2
                class="mx-auto mt-8 px-4 text-lg font-medium leading-6 text-gray-900 sm:px-6 lg:px-8"
            >
                Recent activity
            </h2>

            <!-- Activity list (smallest breakpoint only) -->
            <div class="shadow sm:hidden">
                <ul
                    role="list"
                    class="mt-2 divide-y divide-gray-200 overflow-hidden shadow sm:hidden"
                >
                    <li
                        v-for="transaction in transactions"
                        :key="transaction.id"
                    >
                        <a
                            :href="transaction.href"
                            class="block bg-white px-4 py-4 hover:bg-gray-50"
                        >
                            <span class="flex items-center space-x-4">
                                <span class="flex flex-1 space-x-2 truncate">
                                    <BanknotesIcon
                                        class="h-5 w-5 flex-shrink-0 text-gray-400"
                                        aria-hidden="true"
                                    />
                                    <span
                                        class="flex flex-col truncate text-sm text-gray-500"
                                    >
                                        <span class="truncate">{{
                                            transaction.name
                                        }}</span>
                                        <span
                                            ><span
                                                class="font-medium text-gray-900"
                                                >{{ transaction.amount }}</span
                                            >
                                            {{ transaction.currency }}</span
                                        >
                                        <time
                                            :datetime="transaction.datetime"
                                            >{{ transaction.date }}</time
                                        >
                                    </span>
                                </span>
                                <ChevronRightIcon
                                    class="h-5 w-5 flex-shrink-0 text-gray-400"
                                    aria-hidden="true"
                                />
                            </span>
                        </a>
                    </li>
                </ul>

                <nav
                    class="flex items-center justify-between border-t border-gray-200 bg-white px-4 py-3"
                    aria-label="Pagination"
                >
                    <div class="flex flex-1 justify-between">
                        <a
                            href="#"
                            class="relative inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50"
                            >Previous</a
                        >
                        <a
                            href="#"
                            class="relative ml-3 inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50"
                            >Next</a
                        >
                    </div>
                </nav>
            </div>

            <!-- Activity table (small breakpoint and up) -->
            <div class="hidden sm:block">
                <div class="mx-auto">
                    <div class="mt-2 flex flex-col">
                        <div
                            class="min-w-full overflow-hidden overflow-x-auto align-middle shadow sm:rounded-lg"
                        >
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <th
                                            class="bg-gray-50 px-6 py-3 text-left text-sm font-semibold text-gray-900"
                                            scope="col"
                                        >
                                            Transaction
                                        </th>
                                        <th
                                            class="bg-gray-50 px-6 py-3 text-right text-sm font-semibold text-gray-900"
                                            scope="col"
                                        >
                                            Amount
                                        </th>
                                        <th
                                            class="hidden bg-gray-50 px-6 py-3 text-left text-sm font-semibold text-gray-900 md:block"
                                            scope="col"
                                        >
                                            Status
                                        </th>
                                        <th
                                            class="bg-gray-50 px-6 py-3 text-right text-sm font-semibold text-gray-900"
                                            scope="col"
                                        >
                                            Date
                                        </th>
                                    </tr>
                                </thead>
                                <tbody
                                    class="divide-y divide-gray-200 bg-white"
                                >
                                    <tr
                                        v-for="transaction in transactions"
                                        :key="transaction.id"
                                        class="bg-white"
                                    >
                                        <td
                                            class="w-full max-w-0 whitespace-nowrap px-6 py-4 text-sm text-gray-900"
                                        >
                                            <div class="flex">
                                                <a
                                                    :href="transaction.href"
                                                    class="group inline-flex space-x-2 truncate text-sm"
                                                >
                                                    <BanknotesIcon
                                                        class="h-5 w-5 flex-shrink-0 text-gray-400 group-hover:text-gray-500"
                                                        aria-hidden="true"
                                                    />
                                                    <p
                                                        class="truncate text-gray-500 group-hover:text-gray-900"
                                                    >
                                                        {{ transaction.name }}
                                                    </p>
                                                </a>
                                            </div>
                                        </td>
                                        <td
                                            class="whitespace-nowrap px-6 py-4 text-right text-sm text-gray-500"
                                        >
                                            <span
                                                class="font-medium text-gray-900"
                                                >{{ transaction.amount }}</span
                                            >
                                            {{ transaction.currency }}
                                        </td>
                                        <td
                                            class="hidden whitespace-nowrap px-6 py-4 text-sm text-gray-500 md:block"
                                        >
                                            <span
                                                :class="[
                                                    statusStyles[
                                                        transaction.status
                                                    ],
                                                    'inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium capitalize',
                                                ]"
                                                >{{ transaction.status }}</span
                                            >
                                        </td>
                                        <td
                                            class="whitespace-nowrap px-6 py-4 text-right text-sm text-gray-500"
                                        >
                                            <time
                                                :datetime="transaction.datetime"
                                                >{{ transaction.date }}</time
                                            >
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <!-- Pagination -->
                            <nav
                                class="flex items-center justify-between border-t border-gray-200 bg-white px-4 py-3 sm:px-6"
                                aria-label="Pagination"
                            >
                                <div class="hidden sm:block">
                                    <p class="text-sm text-gray-700">
                                        Showing
                                        {{ " " }}
                                        <span class="font-medium">1</span>
                                        {{ " " }}
                                        to
                                        {{ " " }}
                                        <span class="font-medium">10</span>
                                        {{ " " }}
                                        of
                                        {{ " " }}
                                        <span class="font-medium">20</span>
                                        {{ " " }}
                                        results
                                    </p>
                                </div>
                                <div
                                    class="flex flex-1 justify-between gap-x-3 sm:justify-end"
                                >
                                    <a
                                        href="#"
                                        class="relative inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:ring-gray-400"
                                        >Previous</a
                                    >
                                    <a
                                        href="#"
                                        class="relative inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:ring-gray-400"
                                        >Next</a
                                    >
                                </div>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </FullPageLayout>
</template>
<script setup>
import { ScaleIcon } from "@heroicons/vue/24/outline";
import {
    BanknotesIcon,
    BuildingOfficeIcon,
    CheckCircleIcon,
    ChevronRightIcon,
} from "@heroicons/vue/24/solid";
const cards = [
    {
        name: "Account balance",
        href: "#",
        icon: ScaleIcon,
        amount: "$30,659.45",
    },
    {
        name: "Account balance",
        href: "#",
        icon: ScaleIcon,
        amount: "$30,659.45",
    },
    {
        name: "Account balance",
        href: "#",
        icon: ScaleIcon,
        amount: "$30,659.45",
    },
    // More items...
];
const transactions = [
    {
        id: 1,
        name: "Payment to Molly Sanders",
        href: "#",
        amount: "$20,000",
        currency: "USD",
        status: "success",
        date: "July 11, 2020",
        datetime: "2020-07-11",
    },
    {
        id: 2,
        name: "Payment to Molly Sanders",
        href: "#",
        amount: "$20,000",
        currency: "USD",
        status: "success",
        date: "July 11, 2020",
        datetime: "2020-07-11",
    },
    {
        id: 3,
        name: "Payment to Molly Sanders",
        href: "#",
        amount: "$20,000",
        currency: "USD",
        status: "success",
        date: "July 11, 2020",
        datetime: "2020-07-11",
    },
    // More transactions...
];
const statusStyles = {
    success: "bg-green-100 text-green-800",
    processing: "bg-yellow-100 text-yellow-800",
    failed: "bg-gray-100 text-gray-800",
};
</script>
