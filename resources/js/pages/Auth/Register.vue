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
                    <img :src="'/images/logo.png'" width="150" alt="Logo" />
                    <h1 class="mb-2 text-2xl">Xyrin Technologies</h1>
                    <!-- <span class="text-gray-300">Enter Login Details</span> -->
                </div>
                <form action="#" @submit.prevent="registerClicked()">
                    <div class="mb-4 text-lg">
                        <input
                            class="px-6 py-2 text-center bg-transparent bg-opacity-50 border-none shadow-lg outline-none rounded-3xl text-inherit placeholder-slate-200 backdrop-blur-md"
                            type="text"
                            name="name"
                            placeholder="Name"
                            v-model="form.name"
                        />
                    </div>
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
                    <div class="mb-4 text-lg">
                        <input
                            class="px-6 py-2 text-center bg-transparent bg-opacity-50 border-none shadow-lg outline-none rounded-3xl text-inherit placeholder-slate-200 backdrop-blur-md"
                            type="Password"
                            name="Confirm password"
                            placeholder="confirm password"
                            v-model="form.password_confirmation"
                        />
                    </div>
                    <div class="flex justify-center mt-8 text-lg text-black">
                        <button
                            type="submit"
                            class="px-10 py-2 text-white transition-colors duration-300 bg-transparent bg-opacity-50 shadow-xl rounded-3xl backdrop-blur-md hover:bg-white/10"
                        >
                            Register
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
export default {
    components: { mapActions, mapGetters, usePage },

    setup() {
        const form = useForm({
            name: null,
            email: null,
            password: null,
            password_confirmation: null,
        });

        function registerClicked() {
            form.post(route("register.store"));
        }
        return {
            form,
            registerClicked,
        };
    },
};
</script>
