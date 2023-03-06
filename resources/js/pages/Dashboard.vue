import FullPageLayout from './FullPageLayout.vue';

<template>
    <FullPageLayout>
        <div class="bg-white rounded-lg shadow">
            <div class="px-4 sm:px-6 lg:mx-auto lg:px-8">
                <div class="py-6 md:flex md:items-center md:justify-between">
                    <div class="flex-1 min-w-0">
                        <!-- Profile -->
                        <div class="flex items-center">
                            <img
                                class="hidden w-16 h-16 rounded-full sm:block"
                                src="https://cdn.vectorstock.com/i/1000x1000/92/94/bearded-man-in-glasses-showing-emotion-winking-vector-45159294.webp"
                                alt=""
                            />
                            <div>
                                <div class="flex items-center">
                                    <img
                                        class="w-16 h-16 rounded-full sm:hidden"
                                        src="https://cdn.vectorstock.com/i/1000x1000/92/94/bearded-man-in-glasses-showing-emotion-winking-vector-45159294.webp"
                                        alt=""
                                    />
                                    <h1
                                        class="ml-3 text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:leading-9"
                                    >
                                        {{ greeting }}, Sahil
                                    </h1>
                                </div>
                                <dl
                                    class="flex flex-col mt-6 sm:ml-3 sm:mt-1 sm:flex-row sm:flex-wrap"
                                >
                                    <dt class="sr-only">Company</dt>
                                    <dd
                                        class="flex items-center text-sm font-medium text-gray-500 capitalize sm:mr-6"
                                    >
                                        <BuildingOfficeIcon
                                            class="mr-1.5 h-5 w-5 flex-shrink-0 text-gray-400"
                                            aria-hidden="true"
                                        />
                                        Web Developer
                                    </dd>
                                    <dt class="sr-only">Account status</dt>
                                    <dd
                                        class="flex items-center mt-3 text-sm font-medium text-gray-500 capitalize sm:mr-6 sm:mt-0"
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
                    <div class="flex gap-2 mt-6 md:mt-0 md:w-2/5">
                        <img
                            src="https://cdn-icons-png.flaticon.com/512/7350/7350737.png"
                            alt=""
                            class="w-6 h-6"
                        />
                        <div>
                            {{ thoughts }}
                        </div>
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
                    class="grid grid-cols-1 gap-5 mt-2 sm:grid-cols-2 lg:grid-cols-3"
                >
                    <!-- Card -->
                    <div
                        v-for="card in cards"
                        :key="card.name"
                        class="overflow-hidden bg-white rounded-lg shadow"
                    >
                        <div class="p-5">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <component
                                        :is="card.icon"
                                        class="w-6 h-6 text-gray-400"
                                        aria-hidden="true"
                                    />
                                </div>
                                <div class="flex-1 w-0 ml-5">
                                    <dl>
                                        <dt
                                            class="text-sm font-medium text-gray-500 truncate"
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
                        <div class="px-5 py-3 bg-gray-50">
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
                class="px-4 mx-auto mt-8 text-lg font-medium leading-6 text-gray-900 sm:px-6 lg:px-8"
            >
                Recent activity
            </h2>

            <!-- Activity list (smallest breakpoint only) -->
            <div class="shadow sm:hidden">
                <ul
                    role="list"
                    class="mt-2 overflow-hidden divide-y divide-gray-200 shadow sm:hidden"
                >
                    <li
                        v-for="transaction in transactions"
                        :key="transaction.id"
                    >
                        <a
                            :href="transaction.href"
                            class="block px-4 py-4 bg-white hover:bg-gray-50"
                        >
                            <span class="flex items-center space-x-4">
                                <span class="flex flex-1 space-x-2 truncate">
                                    <BanknotesIcon
                                        class="flex-shrink-0 w-5 h-5 text-gray-400"
                                        aria-hidden="true"
                                    />
                                    <span
                                        class="flex flex-col text-sm text-gray-500 truncate"
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
                                    class="flex-shrink-0 w-5 h-5 text-gray-400"
                                    aria-hidden="true"
                                />
                            </span>
                        </a>
                    </li>
                </ul>

                <nav
                    class="flex items-center justify-between px-4 py-3 bg-white border-t border-gray-200"
                    aria-label="Pagination"
                >
                    <div class="flex justify-between flex-1">
                        <a
                            href="#"
                            class="relative inline-flex items-center px-3 py-2 text-sm font-semibold text-gray-900 bg-white rounded-md ring-1 ring-inset ring-gray-300 hover:bg-gray-50"
                            >Previous</a
                        >
                        <a
                            href="#"
                            class="relative inline-flex items-center px-3 py-2 ml-3 text-sm font-semibold text-gray-900 bg-white rounded-md ring-1 ring-inset ring-gray-300 hover:bg-gray-50"
                            >Next</a
                        >
                    </div>
                </nav>
            </div>

            <!-- Activity table (small breakpoint and up) -->
            <div class="hidden sm:block">
                <div class="mx-auto">
                    <div class="flex flex-col mt-2">
                        <div
                            class="min-w-full overflow-hidden overflow-x-auto align-middle shadow sm:rounded-lg"
                        >
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-sm font-semibold text-left text-gray-900 bg-gray-50"
                                            scope="col"
                                        >
                                            Transaction
                                        </th>
                                        <th
                                            class="px-6 py-3 text-sm font-semibold text-right text-gray-900 bg-gray-50"
                                            scope="col"
                                        >
                                            Amount
                                        </th>
                                        <th
                                            class="hidden px-6 py-3 text-sm font-semibold text-left text-gray-900 bg-gray-50 md:block"
                                            scope="col"
                                        >
                                            Status
                                        </th>
                                        <th
                                            class="px-6 py-3 text-sm font-semibold text-right text-gray-900 bg-gray-50"
                                            scope="col"
                                        >
                                            Date
                                        </th>
                                    </tr>
                                </thead>
                                <tbody
                                    class="bg-white divide-y divide-gray-200"
                                >
                                    <tr
                                        v-for="transaction in transactions"
                                        :key="transaction.id"
                                        class="bg-white"
                                    >
                                        <td
                                            class="w-full px-6 py-4 text-sm text-gray-900 max-w-0 whitespace-nowrap"
                                        >
                                            <div class="flex">
                                                <a
                                                    :href="transaction.href"
                                                    class="inline-flex space-x-2 text-sm truncate group"
                                                >
                                                    <BanknotesIcon
                                                        class="flex-shrink-0 w-5 h-5 text-gray-400 group-hover:text-gray-500"
                                                        aria-hidden="true"
                                                    />
                                                    <p
                                                        class="text-gray-500 truncate group-hover:text-gray-900"
                                                    >
                                                        {{ transaction.name }}
                                                    </p>
                                                </a>
                                            </div>
                                        </td>
                                        <td
                                            class="px-6 py-4 text-sm text-right text-gray-500 whitespace-nowrap"
                                        >
                                            <span
                                                class="font-medium text-gray-900"
                                                >{{ transaction.amount }}</span
                                            >
                                            {{ transaction.currency }}
                                        </td>
                                        <td
                                            class="hidden px-6 py-4 text-sm text-gray-500 whitespace-nowrap md:block"
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
                                            class="px-6 py-4 text-sm text-right text-gray-500 whitespace-nowrap"
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
                                class="flex items-center justify-between px-4 py-3 bg-white border-t border-gray-200 sm:px-6"
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
                                    class="flex justify-between flex-1 gap-x-3 sm:justify-end"
                                >
                                    <a
                                        href="#"
                                        class="relative inline-flex items-center px-3 py-2 text-sm font-semibold text-gray-900 bg-white rounded-md ring-1 ring-inset ring-gray-300 hover:ring-gray-400"
                                        >Previous</a
                                    >
                                    <a
                                        href="#"
                                        class="relative inline-flex items-center px-3 py-2 text-sm font-semibold text-gray-900 bg-white rounded-md ring-1 ring-inset ring-gray-300 hover:ring-gray-400"
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
import { ScaleIcon, ChatBubbleLeftRightIcon } from "@heroicons/vue/24/outline";
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
<script>
import { mapActions, mapGetters } from "vuex";
import { usePage } from "@inertiajs/vue3";
import axios from "axios";
export default {
    components: { mapActions, mapGetters, usePage },
    data() {
        return {
            userName: "",
            userPassword: "",
            thoughts: "",
        };
    },
    computed: {
        ...mapGetters("greeting", ["greeting"]),
    },
    methods: {
        ...mapActions("greeting", ["SetGreeting"]),
        getThoughts() {
            axios.get("https://api.quotable.io/random").then((response) => {
                this.thoughts = response.data.content;
            });
            // axios
            //     .get("https://hindi-quotes.vercel.app/random")
            //     .then((response) => {
            //         this.thoughts = response.data.quote;
            //     });
        },
    },
    mounted() {
        this.SetGreeting();
        this.getThoughts();
    },
    created() {
        setInterval(() => {
            this.getThoughts();
        }, 6000);
    },
};
</script>
