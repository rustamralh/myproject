<template>
    <div>
        <NavBar v-if="isAuth" />
        <Login :name="userName" :password="userPassword" v-if="!isAuth" />
    </div>
</template>
<script setup>
import { ref } from "vue";
import NavBar from "./NavBar.vue";
import Login from "./Auth/Login.vue";
const isAuth = ref(false);
const userDetails = ref({
    name: 'sahil',
    password: '123456'
})
const isAuthenticated= computed({
    if(userName===userDetails.value.name)

})
</script>
