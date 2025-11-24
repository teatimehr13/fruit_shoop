<script setup>
import FrontLayout from '@/Layouts/FrontLayout.vue';
import Hero from './_Hero.vue';
import Feature from './_Feature.vue';
import Category from './_Category.vue';



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
    <!-- <section></section>
      -->
     <Category />
    <!-- recommand -->
    <section></section>
</template>