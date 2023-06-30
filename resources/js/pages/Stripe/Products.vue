<template>
    <div>
        <div v-for="product in products" :key="index">
            <label>{{ product }}</label>
        </div>
    </div>
</template>
<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";
import { defineProps } from "vue";

// Define props using defineProps
const props = defineProps({
    products: {
        type: Object,
        required: true,
    },
});

const products = ref(props.products);
</script>
