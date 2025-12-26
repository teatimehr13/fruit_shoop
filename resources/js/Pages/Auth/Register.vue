<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import FrontLayout from '@/Layouts/FrontLayout.vue';
defineOptions({ layout: FrontLayout })

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <!-- <GuestLayout>

        <Head title="Register" />

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="name" value="Name" />

                <TextInput id="name" type="text" class="mt-1 block w-full" v-model="form.name" required autofocus
                    autocomplete="name" />

                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <div class="mt-4">
                <InputLabel for="email" value="Email" />

                <TextInput id="email" type="email" class="mt-1 block w-full" v-model="form.email" required
                    autocomplete="username" />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="mt-4">
                <InputLabel for="password" value="Password" />

                <TextInput id="password" type="password" class="mt-1 block w-full" v-model="form.password" required
                    autocomplete="new-password" />

                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="mt-4">
                <InputLabel for="password_confirmation" value="Confirm Password" />

                <TextInput id="password_confirmation" type="password" class="mt-1 block w-full"
                    v-model="form.password_confirmation" required autocomplete="new-password" />

                <InputError class="mt-2" :message="form.errors.password_confirmation" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <Link :href="route('login')"
                    class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Already registered?
                </Link>

                <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Register
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout> -->


    <div class="mt-[108px] lg:pt-8 3xl:pt-12 max-w-[460px] my-[50px] mx-auto p-5">
        <p class="text-3xl leading-[2] mb-4 font-semibold">
            註冊會員
        </p>
        <form @submit.prevent="submit" class="grid gap-4">

            <div>
                <input id="email" type="text"
                    class="mt-1 block w-full rounded-[12px] border border-[#cccccc80] py-2 px-4" v-model="form.name"
                    required autofocus autocomplete="username" placeholder="姓名">
                <InputError class="mt-2" :message="form.errors.name" />
            </div>

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
                    class="mt-1 block w-full rounded-[12px] border border-[#cccccc80] py-2 px-4" v-model="form.password_confirmation"
                    required autofocus autocomplete="username" placeholder="密碼確認">
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
                    註冊
                </button>
            </div>
        </form>

        <div class="mt-8 grid gap-4">
            <div class="text-center">
                已經擁有帳號?
                <Link :href="route('login')"
                    class="tracking-wider underline text-sm text-[#82ae46] hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    登入
                </Link>
            </div>

        </div>
    </div>
</template>
