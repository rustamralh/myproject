<template>
    <div
        class="flex h-screen w-full items-center justify-center bg-gray-900 bg-cover bg-no-repeat"
        style="
            background-image: url('https://thumbs.gfycat.com/AbandonedNecessaryCapeghostfrog-mobile.mp4');
        "
    >
        <div
            class="rounded-xl bg-white bg-opacity-10 px-16 py-10 shadow-lg backdrop-blur-md max-sm:px-8"
        >
            <div class="text-white">
                <div class="mb-8 flex flex-col items-center">
                    <img src="\images\logo.png" width="150" alt="" srcset="" />
                    <h1 class="mb-2 text-2xl">Xyrin Technologies</h1>
                    <!-- <span class="text-gray-300">Enter Login Details</span> -->
                </div>
                <form action="#" @submit.prevent="isAuthLogin">
                    <div class="mb-4 text-lg">
                        <input
                            class="rounded-3xl border-none bg-transparent bg-opacity-50 px-6 py-2 text-center text-inherit placeholder-slate-200 shadow-lg outline-none backdrop-blur-md"
                            type="text"
                            name="name"
                            placeholder="Name"
                            v-model="userName"
                        />
                    </div>

                    <div class="mb-4 text-lg">
                        <input
                            class="rounded-3xl border-none bg-transparent bg-opacity-50 px-6 py-2 text-center text-inherit placeholder-slate-200 shadow-lg outline-none backdrop-blur-md"
                            type="Password"
                            name="name"
                            placeholder="*********"
                            v-model="userPassword"
                        />
                    </div>
                    <div class="mt-8 flex justify-center text-lg text-black">
                        <button
                            type="submit"
                            class="rounded-3xl bg-transparent bg-opacity-50 px-10 py-2 text-white shadow-xl backdrop-blur-md transition-colors duration-300 hover:bg-white/10"
                        >
                            Login
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
<script>
import { mapActions } from "vuex";
export default {
    data() {
        return {
            userName: "",
            userPassword: "",
        };
    },
    methods: {
        isAuthLogin() {
            this.$store.dispatch("auth/isAuthLogin", {
                name: this.userName,
                password: this.userPassword,
            });
        },
    },
};
</script>
