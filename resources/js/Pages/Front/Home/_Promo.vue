<script setup>
import CtaButton from '@/DaisyComponents/Front/CtaButton.vue';
</script>

<template>
    <!-- 不是特定商品,是分類層級的促銷入口(蔬菜類/果汁類目前有什麼活動),手動指定 2 個分類 -->
    <section class="px-4 py-10 md:py-14 max-w-layout-wide mx-auto">
        <div class="grid md:grid-cols-2 gap-[30px]">
            <a :href="route('categories.products', { category: '蔬菜' })"
                class="group relative rounded-[20px] overflow-hidden aspect-16/9 block">
                <img src="/images/promo/veggie-box.webp" alt="蔬菜嚴選"
                    class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                <div class="absolute inset-0 flex flex-col justify-center items-start px-8">
                    <h3 class="text-2xl md:text-3xl font-medium text-heading mb-2">蔬菜嚴選</h3>
                    <p class="text-base text-heading/70 mb-4">當季現採，每日到貨</p>
                    <CtaButton as="span"
                        class="!bg-transparent !text-primary !border !border-primary hover:!bg-primary hover:!text-primary-content">
                        選購去 →
                    </CtaButton>
                </div>
            </a>

            <a :href="route('categories.products', { category: '果汁' })"
                class="group relative rounded-[20px] overflow-hidden aspect-16/9 block">
                <img src="/images/promo/cold-pressed-juice.webp" alt="鮮榨果汁"
                    class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                <div class="absolute inset-0 flex flex-col justify-center items-start px-8">
                    <h3 class="text-2xl md:text-3xl font-medium text-heading mb-2">鮮榨果汁</h3>
                    <p class="text-base text-heading/70 mb-4">冷壓工法，鎖住營養</p>
                    <CtaButton as="span"
                        class="!bg-[#fff] !text-primary !border !border-[#fff] hover:!bg-primary hover:!text-primary-content hover:!border-primary">
                        選購去 →
                    </CtaButton>
                </div>
            </a>
        </div>
    </section>
</template>
