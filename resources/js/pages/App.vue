<template>
    <div>
        <Login v-if="!userAuthentication" />
        <Dashboard v-if="userAuthentication" />
    </div>
</template>
<script>
import { computed } from "vue";
import NavBar from "./NavBar.vue";
import Login from "./Auth/Login.vue";
export default {
    components: { NavBar, Login },
    computed: {
        userAuthentication() {
            return this.$store.getters["userAuthentication"];
        },
    },
};
</script>
