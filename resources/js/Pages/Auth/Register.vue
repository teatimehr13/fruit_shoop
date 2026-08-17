<script setup>
import PrimaryButton from '@/DaisyComponents/Front/PrimaryButton.vue';
import { Link, useForm } from '@inertiajs/vue3';
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
    <div class="mt-[var(--spacing-header-space)] lg:pt-8 3xl:pt-12 max-w-[460px] my-[50px] mx-auto p-5">
        <div class="border border-base-300 rounded-2xl bg-base-100 p-6">
            <h1 class="text-2xl md:text-3xl font-medium text-heading pb-4 mb-6 border-b border-base-300">
                註冊會員
            </h1>

            <form @submit.prevent="submit" class="grid gap-4">
                <div>
                    <label for="name" class="field-label">姓名</label>
                    <input id="name" type="text" class="input input-primary w-full border-base-300 rounded-lg"
                        v-model="form.name" required autofocus autocomplete="name" placeholder="請輸入姓名"
                        :class="{ 'input-error': form.errors.name }" />
                    <p v-if="form.errors.name" class="errors-hint">{{ form.errors.name }}</p>
                </div>

                <div>
                    <label for="email" class="field-label">Email</label>
                    <input id="email" type="email" class="input input-primary w-full border-base-300 rounded-lg"
                        v-model="form.email" required autocomplete="username" placeholder="example@mail.com"
                        :class="{ 'input-error': form.errors.email }" />
                    <p v-if="form.errors.email" class="errors-hint">{{ form.errors.email }}</p>
                </div>

                <div>
                    <label for="password" class="field-label">密碼</label>
                    <input id="password" type="password" class="input input-primary w-full border-base-300 rounded-lg"
                        v-model="form.password" required autocomplete="new-password" placeholder="請輸入密碼"
                        :class="{ 'input-error': form.errors.password }" />
                    <p v-if="form.errors.password" class="errors-hint">{{ form.errors.password }}</p>
                </div>

                <div>
                    <label for="password_confirmation" class="field-label">密碼確認</label>
                    <input id="password_confirmation" type="password"
                        class="input input-primary w-full border-base-300 rounded-lg"
                        v-model="form.password_confirmation" required autocomplete="new-password" placeholder="請再次輸入密碼"
                        :class="{ 'input-error': form.errors.password_confirmation }" />
                    <p v-if="form.errors.password_confirmation" class="errors-hint">{{ form.errors.password_confirmation }}</p>
                </div>

                <PrimaryButton type="submit" :disabled="form.processing">
                    註冊
                </PrimaryButton>
            </form>

            <div class="mt-6 text-center text-sm text-base-content/70">
                已經擁有帳號?
                <Link :href="route('login')" class="text-primary hover:underline">登入</Link>
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
