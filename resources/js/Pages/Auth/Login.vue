<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import FrontLayout from '@/Layouts/FrontLayout.vue';
defineOptions({ layout: FrontLayout })

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    // email: '',
    login: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <div class="mt-[108px] lg:pt-8 3xl:pt-12 max-w-[460px] my-[50px] mx-auto p-5">
        <p class="text-3xl leading-[2] mb-4 font-semibold">
            登入
        </p>
        <form @submit.prevent="submit" class="grid gap-4">
            <div>
                <input id="email" type="text"
                    class="mt-1 block w-full rounded-[12px] border border-[#cccccc80] py-2 px-4" v-model="form.login"
                    required autofocus autocomplete="username" placeholder="Email或手機號碼">
                <InputError class="mt-2" :message="form.errors.login" />
            </div>

            <div>
                <input id="password" type="password"
                    class="mt-1 block w-full rounded-[12px] border border-[#cccccc80] py-2 px-4" v-model="form.password"
                    required autofocus autocomplete="username" placeholder="密碼">
                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="text-center">
                <button class="rounded-[40px] 
                text-[#82ae46] 
                shadow-[inset_0_0_0_1px_#82ae46] 
                px-12 
                py-1.5 
                font-semibold 
                transition-all 
                duration-500 
                ease-in-out
                hover:bg-[#82ae46] 
                hover:text-white
                cursor-pointer" role="button">
                    登入
                </button>
            </div>
        </form>

        <div class="mt-8 grid gap-4">
            <div class="text-center">
                <Link v-if="canResetPassword" :href="route('password.request')"
                    class="tracking-wider underline text-sm text-[#82ae46] hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    忘記密碼?
                </Link>
            </div>

            <div class="text-center">
                <span>還不是會員? </span>
                <Link v-if="canResetPassword" :href="route('register')"
                    class="tracking-wider underline text-sm text-[#82ae46] hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    前往註冊
                </Link>
            </div>
        </div>
    </div>
</template>
