<script setup>
import FrontLayout from '@/Layouts/FrontLayout.vue';
import HomeHero from './_HomeHero.vue';
import Feature from './_Feature.vue';
import Category from './_Category.vue';
import Promo from './_Promo.vue';
import TopPicks from './_TopPicks.vue';

defineOptions({
    layout: FrontLayout,
})

const props = defineProps({
    featured: Object,
    categories: Object,
});
</script>

<template>
    <HomeHero />
    <Feature />
    <Category :categories="categories" />
    <Promo />
    <TopPicks :featured="featured" />
</template>