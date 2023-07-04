<template>
    <form>
        <div id="card-element">
            <label>
                Card Number
                <input id="card-number-element" v-model="form.card_number" />
            </label>
            <label>
                Expiration Date
                <input
                    id="card-number-element"
                    v-model="form.expiration_date"
                />
            </label>
            <label>
                CVC
                <input id="card-number-element" v-model="form.cvc" />
            </label>
        </div>
    </form>
    <div>
        <div id="card-element"></div>
        <button @click="createCardPaymentMethod">Add Card</button>
    </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import Button from "primevue/button";
import axios from "axios";
import { defineProps } from "vue";
import { loadStripe } from "@stripe/stripe-js";
import { useForm } from "@inertiajs/vue3";

// Define props using defineProps

const getSubsciptionLink = (productId) => {
    console.log(productId);
    axios
        .post(route("subscription.link"), { product_id: productId })
        .then((response) => {
            console.log(response);
        });
};
const form = useForm({
    card_number: null,
    expiration_date: null,
    cvc: null,
});
const stripe = ref();
const initializeStripe = async () => {
    stripe.value = await loadStripe(
        "pk_test_51Mm9L0SGZDhPpFnTfFlj7OqhPvgkRJSzd5wqz7oGsZy0OWoUysUXKQJvfngSOlWJ2hapUxfPmGdnfE7iFFiTe7gK00tG02kBQn"
    );
    // this.stripe = stripe;
    // const elements = stripe.elements();
    // const cardElement = elements.create("card");
    // cardElement.mount("#card-element");
    // this.cardElement = cardElement;
};
const createCardPaymentMethod = async () => {
    const { error, paymentMethod } = await stripe.createPaymentMethod({
        type: "card",
        card: form,
    });
    if (error) {
        // Handle error
        console.error(error.message);
    } else {
        // Send payment method to server for further processing
        sendPaymentMethod(paymentMethod.id);
    }
};
const sendPaymentMethod = (paymentMethodId) => {
    // Make an API request to your Laravel backend
    axios
        .post("/api/payment-methods", { paymentMethodId })
        .then((response) => {
            // Handle the response
            console.log(response.data);
        })
        .catch((error) => {
            // Handle the error
            console.error(error.response.data.message);
        });
};
const mounted = () => {
    initializeStripe();
};
</script>
<!-- <script>
export default {
    data() {
        return {
            stripe: null,
            cardElement: null,
        };
    },
    mounted() {
        this.initializeStripe();
    },
    methods: {
        async initializeStripe() {
            const stripe = await loadStripe("your_stripe_publishable_key");
            this.stripe = stripe;
            const elements = stripe.elements();
            const cardElement = elements.create("card");
            cardElement.mount("#card-element");
            this.cardElement = cardElement;
        },
        async createCardPaymentMethod() {
            const { error, paymentMethod } =
                await this.stripe.createPaymentMethod({
                    type: "card",
                    card: this.cardElement,
                });

            if (error) {
                // Handle error
                console.error(error.message);
            } else {
                // Send payment method to server for further processing
                this.sendPaymentMethod(paymentMethod.id);
            }
        },
        sendPaymentMethod(paymentMethodId) {
            // Make an API request to your Laravel backend
            axios
                .post("/api/payment-methods", { paymentMethodId })
                .then((response) => {
                    // Handle the response
                    console.log(response.data);
                })
                .catch((error) => {
                    // Handle the error
                    console.error(error.response.data.message);
                });
        },
    },
};
</script> -->
