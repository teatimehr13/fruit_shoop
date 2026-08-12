<script setup>
import Breadcrumb from '@/DaisyComponents/Front/Breadcrumb.vue';

defineProps({
    breadcrumbItems: {
        type: Array,
        required: true,
    },
})
</script>

<template>
    <section class="w-full mt-[var(--spacing-header-space)] bg-base-200 overflow-hidden">
        <div class="max-w-layout-wide mx-auto px-4 relative">
            <div class="relative z-10 py-10 md:py-16 md:max-w-[55%]">
                <h1 class="text-3xl md:text-4xl font-medium text-heading mb-3">所有產品</h1>
                <Breadcrumb :items="breadcrumbItems" bare />
            </div>

            <img src="/images/promo/veggie-box.webp" alt=""
                class="hidden md:block absolute right-0 top-8 bottom-0 w-[300px] lg:w-[360px] h-[calc(100%-2rem)] object-cover rounded-t-[28px]" />
        </div>
    </section>
</template>
