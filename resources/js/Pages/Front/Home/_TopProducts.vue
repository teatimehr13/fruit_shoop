<script setup>
import ProductCard from '@/DaisyComponents/Front/ProductCard.vue';

defineProps({
    popularProducts: Object,
})
</script>

<template>
    <!-- 跟「我們的承諾」同一套標題樣式(英文小標+葉子裝飾 → 中文主標) -->
    <section class="px-4 pt-10 md:pt-14 pb-12 md:pb-14 lg:pb-20 max-w-layout-wide mx-auto text-center">
        <p class="flex items-center justify-center gap-2 text-sm text-primary my-3">
            <img src="/images/econis-ref/hero-leaf-1.png" alt="" class="w-4 h-4 object-contain">
            Top products
            <img src="/images/econis-ref/hero-leaf-2.png" alt="" class="w-4 h-4 object-contain">
        </p>
        <h2 class="text-2xl md:text-4xl font-medium tracking-wide text-heading mb-8 md:mb-12">
            人氣商品
        </h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6 text-left">
            <ProductCard v-for="p in popularProducts" :key="p.id" :product="p" />
        </div>

        <a :href="route('products.index')"
            class="mt-10 md:mt-12 btn btn-outline border-primary text-primary rounded-full px-8 font-medium tracking-[0.06em] hover:bg-primary hover:text-primary-content transition-colors">
            查看全部商品 →
        </a>
    </section>
</template>
