<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';
import FrontLayout from '@/Layouts/FrontLayout.vue';
defineOptions({ layout: FrontLayout })

const props = defineProps({
    email: {
        type: String,
        required: true,
    },
    token: {
        type: String,
        required: true,
    },
});

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('password.store'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <div class="mt-[108px] lg:pt-8 3xl:pt-12 max-w-[460px] my-[50px] mx-auto p-5">
        <p class="text-3xl leading-[2] mb-4 font-semibold">
            密碼重置
        </p>
        <form @submit.prevent="submit" class="grid gap-4">
            <div>
                <input id="email" type="text"
                    class="mt-1 block w-full rounded-[12px] border border-[#cccccc80] py-2 px-4" v-model="form.email"
                    required autofocus autocomplete="username" placeholder="Email">
                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div>
                <input id="password" type="password"
                    class="mt-1 block w-full rounded-[12px] border border-[#cccccc80] py-2 px-4" v-model="form.password"
                    required autofocus autocomplete="username" placeholder="密碼">
                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div>
                <input id="password" type="password"
                    class="mt-1 block w-full rounded-[12px] border border-[#cccccc80] py-2 px-4"
                    v-model="form.password_confirmation" required autofocus autocomplete="username" placeholder="密碼確認">
                <InputError class="mt-2" :message="form.errors.password_confirmation" />
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
                cursor-pointer" role="button" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    重設密碼
                </button>
            </div>
        </form>
    </div>
</template>
