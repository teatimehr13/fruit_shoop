<script setup>
import Footer from '@/DaisyComponents/Front/Footer.vue';
import Nav from '@/DaisyComponents/Front/Nav.vue';


</script>

<template>
    <div class="min-h-screen flex flex-col">
        <Nav />
        <main class="flex-1">
            <slot />
        </main>
        <Footer />
    </div>
</template>