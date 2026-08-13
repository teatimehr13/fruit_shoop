<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import FrontLayout from '@/Layouts/FrontLayout.vue';
defineOptions({ layout: FrontLayout })

defineProps({
    status: {
        type: String,
    },
    reset_link: String,
    demo_mode: Boolean,
});

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
};
</script>

<template>
    <div class="mt-[108px] lg:pt-8 3xl:pt-12 max-w-[460px] my-[50px] mx-auto p-5">
        <p class="text-3xl leading-[2] mb-4 font-semibold">
            忘記密碼 ?
        </p>
        <div class="mb-4 text-sm text-gray-600">
            我們會寄送一封電子郵件給你，用來重設密碼。
        </div>
        <form @submit.prevent="submit" class="grid gap-4">
            <div>
                <input id="email" type="text"
                    class="mt-1 block w-full rounded-[12px] border border-neutral/50 py-2 px-4" v-model="form.email"
                    required autofocus autocomplete="username" placeholder="Email">
                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="text-center">
                <button class="btn btn-sm w-full border-primary text-primary hover:text-white rounded-[4px] hover:bg-primary transition-colors bg-white text-[14px]
                cursor-pointer" role="button" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    提交
                </button>
            </div>
        </form>


        <div v-if="demo_mode && reset_link" class="mt-4 p-3 text-gray-400">
            <div class="font-semibold">Demo 模式：重設連結</div>
            <a :href="reset_link" class="underline break-all">{{ reset_link }}</a>
        </div>
    </div>
</template>
