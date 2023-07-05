<template>
    <div class="px-4 py-8 surface-ground md:px-6 lg:px-8">
        <div class="mb-4 text-6xl font-bold text-center text-900">
            Pricing Plans
        </div>
        <div class="mb-6 text-xl text-center text-700 line-height-3">
            Lorem ipsum dolor sit, amet consectetur adipisicing elit. Velit
            numquam eligendi quos.
        </div>
        <div class="grid">
            <div
                class="col-12 lg:col-4"
                v-for="product in products"
                :key="index"
            >
                {{ product }}
                <div class="h-full p-3">
                    <div
                        class="flex h-full p-3 shadow-2 flex-column surface-card"
                        style="border-radius: 6px"
                    >
                        <div class="mb-2 text-xl font-medium text-900">
                            {{ product?.name }}
                        </div>
                        <div class="text-600">Plan description</div>
                        <hr
                            class="mx-0 my-3 border-none border-top-1 surface-border"
                        />
                        <div class="flex align-items-center">
                            <span class="text-2xl font-bold text-900"
                                >${{ product?.default_price }}</span
                            >
                            <span class="ml-2 font-medium text-600"
                                >per month</span
                            >
                        </div>
                        <hr
                            class="mx-0 my-3 border-none border-top-1 surface-border"
                        />
                        <ul class="p-0 m-0 list-none flex-grow-1">
                            <li class="flex mb-3 align-items-center">
                                <i
                                    class="mr-2 text-green-500 pi pi-check-circle"
                                ></i>
                                <span class="text-900"
                                    >Arcu vitae elementum</span
                                >
                            </li>
                            <li class="flex mb-3 align-items-center">
                                <i
                                    class="mr-2 text-green-500 pi pi-check-circle"
                                ></i>
                                <span class="text-900"
                                    >Dui faucibus in ornare</span
                                >
                            </li>
                            <li class="flex mb-3 align-items-center">
                                <i
                                    class="mr-2 text-green-500 pi pi-check-circle"
                                ></i>
                                <span class="text-900"
                                    >Morbi tincidunt augue</span
                                >
                            </li>
                        </ul>
                        <hr
                            class="mx-0 mt-auto mb-3 border-none border-top-1 surface-border"
                        />
                        <Button
                            label="Buy Now"
                            @click="paymentLinkRedirect(product.id)"
                            class="w-full p-3 mt-auto"
                        ></Button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
import { ref, onMounted } from "vue";
import Button from "primevue/button";
import axios from "axios";
import { Inertia } from "@inertiajs/inertia";
import { defineProps } from "vue";

// Define props using defineProps
const props = defineProps({
    products: {
        type: Object,
        required: true,
    },
});

const paymentLinkRedirect = (productId) => {
    Inertia.post(route("stripe.get-payment-link", productId));
};

const products = ref(props?.products?.data);
</script>
