<script setup>
import FrontLayout from '@/Layouts/FrontLayout.vue';
import HomeHero from './_HomeHero.vue';
import Feature from './_Feature.vue';
import Category from './_Category.vue';
import TopPicks from './_TopPicks.vue';
import PageHero from './_PageHero.vue';



defineOptions({
    layout: FrontLayout,
})

</script>

<template>
    <!-- <PageHero /> -->
     <!-- <section> -->
         <HomeHero />
     <!-- </section> -->
    <!-- icon feature-->
        <Feature />

    <!-- category gallery -->
    <!-- <section></section>
      -->
     <Category />
    <!-- recommand -->
    <!-- <section></section> -->
     <TopPicks />
</template>