<template>
    <div
        class="flex items-center justify-center w-full h-screen bg-gray-900 bg-no-repeat bg-cover"
        style="
            background-image: url('https://thumbs.gfycat.com/AbandonedNecessaryCapeghostfrog-mobile.mp4');
        "
    >
        <div
            class="px-16 py-10 bg-white shadow-lg rounded-xl bg-opacity-10 backdrop-blur-md max-sm:px-8"
        >
            <div class="text-white">
                <div class="flex flex-col items-center mb-8">
                    <img src="\images\logo.png" width="150" alt="" srcset="" />
                    <h1 class="mb-2 text-2xl">Xyrin Technologies</h1>
                    <!-- <span class="text-gray-300">Enter Login Details</span> -->
                </div>
                <div class="flex flex-col items-center mb-8">
                    <img
                        src="\images\google.png"
                        width="60"
                        alt=""
                        srcset=""
                        @click="loginWithSocialNetworkClicked('google')"
                    />
                    <h1 class="mb-2 text-2xl">Xyrin Technologies</h1>
                    <!-- <span class="text-gray-300">Enter Login Details</span> -->
                </div>
                <form action="#" @submit.prevent="loginClicked()">
                    <div class="mb-4 text-lg">
                        <input
                            class="px-6 py-2 text-center bg-transparent bg-opacity-50 border-none shadow-lg outline-none rounded-3xl text-inherit placeholder-slate-200 backdrop-blur-md"
                            type="email"
                            name="email"
                            placeholder="Email"
                            v-model="form.email"
                        />
                    </div>

                    <div class="mb-4 text-lg">
                        <input
                            class="px-6 py-2 text-center bg-transparent bg-opacity-50 border-none shadow-lg outline-none rounded-3xl text-inherit placeholder-slate-200 backdrop-blur-md"
                            type="Password"
                            name="password"
                            placeholder="*********"
                            v-model="form.password"
                        />
                    </div>
                    <div class="flex justify-center mt-8 text-lg text-black">
                        <button
                            type="submit"
                            class="px-10 py-2 text-white transition-colors duration-300 bg-transparent bg-opacity-50 shadow-xl rounded-3xl backdrop-blur-md hover:bg-white/10"
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
import { mapActions, mapGetters } from "vuex";
import { useForm, usePage } from "@inertiajs/vue3";
import { Inertia } from "@inertiajs/inertia";
export default {
    components: { mapActions, mapGetters, usePage },
    setup() {
        const form = useForm({
            email: null,
            password: null,
        });

        function loginClicked() {
            form.post(route("login.store"));
        }
        function loginWithSocialNetworkClicked(socialNetwork) {
            Inertia.get(route("social.redirect", socialNetwork));
        }
        return {
            form,
            loginClicked,
            loginWithSocialNetworkClicked,
        };
    },
};
</script>
