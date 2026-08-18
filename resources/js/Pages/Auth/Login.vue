<script setup>
import PrimaryButton from '@/DaisyComponents/Front/PrimaryButton.vue';
import { Link, useForm } from '@inertiajs/vue3';
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
    login: 'demo123@gmail.com',
    password: 'demo12345678',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <div class="mt-[var(--spacing-header-space)] lg:pt-8 3xl:pt-12 max-w-[460px] my-[50px] mx-auto p-5">
        <div class="border border-base-300 rounded-2xl bg-base-100 p-6">
            <h1 class="text-2xl md:text-3xl font-medium text-heading pb-4 mb-6 border-b border-base-300">
                登入
            </h1>

            <form @submit.prevent="submit" class="grid gap-4">
                <div>
                    <label for="login" class="field-label">Email 或手機號碼</label>
                    <input id="login" type="text"
                        class="input input-primary w-full border-base-300 rounded-lg" v-model="form.login"
                        required autofocus autocomplete="username" placeholder="example@mail.com"
                        :class="{ 'input-error': form.errors.login }" />
                    <p v-if="form.errors.login" class="errors-hint">{{ form.errors.login }}</p>
                </div>

                <div>
                    <label for="password" class="field-label">密碼</label>
                    <input id="password" type="password"
                        class="input input-primary w-full border-base-300 rounded-lg" v-model="form.password"
                        required autocomplete="current-password" placeholder="請輸入密碼"
                        :class="{ 'input-error': form.errors.password }" />
                    <p v-if="form.errors.password" class="errors-hint">{{ form.errors.password }}</p>
                </div>

                <label class="flex items-center gap-2 text-sm text-base-content cursor-pointer select-none">
                    <input type="checkbox" v-model="form.remember" class="checkbox checkbox-sm checkbox-primary">
                    記住我
                </label>

                <PrimaryButton type="submit" :disabled="form.processing">
                    登入
                </PrimaryButton>
            </form>

            <div class="mt-6 grid gap-3 text-center text-sm">
                <Link v-if="canResetPassword" :href="route('password.request')" class="text-primary hover:underline">
                    忘記密碼?
                </Link>

                <p class="text-base-content/70">
                    還不是會員?
                    <Link :href="route('register')" class="text-primary hover:underline">前往註冊</Link>
                </p>
            </div>
        </div>
    </div>
</template>

<style scoped>
.field-label {
    display: block;
    margin-bottom: .375rem;
    font-size: .875rem;
    font-weight: 500;
    color: var(--color-heading);
}

.errors-hint {
    margin-left: .25rem;
    margin-top: .25rem;
    font-size: .75rem;
    color: oklch(71% .194 13.428);
}
</style>
