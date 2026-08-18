<script setup>
import PrimaryButton from '@/DaisyComponents/Front/PrimaryButton.vue';
import { useForm } from '@inertiajs/vue3';
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
    <div class="mt-[var(--spacing-header-space)] lg:pt-8 3xl:pt-12 max-w-[460px] my-[50px] mx-auto p-5">
        <div class="border border-base-300 rounded-2xl bg-base-100 p-6">
            <h1 class="text-2xl md:text-3xl font-medium text-heading pb-4 mb-6 border-b border-base-300">
                忘記密碼?
            </h1>

            <p class="text-sm text-base-content/70 mb-4">
                我們會寄送一封電子郵件給你，用來重設密碼。
            </p>

            <form @submit.prevent="submit" class="grid gap-4">
                <div>
                    <label for="email" class="field-label">Email</label>
                    <input id="email" type="email" class="input input-primary w-full border-base-300 rounded-lg"
                        v-model="form.email" required autofocus autocomplete="username" placeholder="example@mail.com"
                        :class="{ 'input-error': form.errors.email }" />
                    <p v-if="form.errors.email" class="errors-hint">{{ form.errors.email }}</p>
                </div>

                <PrimaryButton type="submit" :disabled="form.processing">
                    提交
                </PrimaryButton>
            </form>

            <div v-if="demo_mode && reset_link" class="mt-4 p-3 text-sm text-base-content/60">
                <div class="font-medium text-heading">Demo 模式：重設連結</div>
                <a :href="reset_link" class="underline break-all">{{ reset_link }}</a>
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
