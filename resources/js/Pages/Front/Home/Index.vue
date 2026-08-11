<script setup>
import FrontLayout from '@/Layouts/FrontLayout.vue';
import Hero from './_Hero.vue';
import Features from './_Features.vue';
import Promo from './_Promo.vue';
import TopProducts from './_TopProducts.vue';
import Cta from './_Cta.vue';

defineOptions({
    layout: FrontLayout,
})

defineProps({
    popularProducts: Object,
});
</script>

<template>
    <Hero />
    <Features />
    <Promo />
    <TopProducts :popular-products="popularProducts" />
    <Cta />
</template>
