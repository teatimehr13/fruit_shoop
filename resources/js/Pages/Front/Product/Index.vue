<script setup>
import FrontLayout from '@/Layouts/FrontLayout.vue';
import Hero from './_Hero.vue';
import Feature from './_Feature.vue';



defineOptions({
    layout: FrontLayout,
})

</script>

<template>
    <!-- hero -->
     <!-- <section> -->
         <Hero />
     <!-- </section> -->
    <!-- icon feature-->
        <Feature />

    <!-- category gallery -->
    <section></section>

    <!-- recommand -->
    <section></section>
</template>