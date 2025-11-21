<script setup>
import { ref, provide } from 'vue'
import { useNavScroll } from '@/Composables/useNavScroll'
import Footer from '@/DaisyComponents/Front/Footer.vue';
import Nav from '@/DaisyComponents/Front/Nav.vue';

const heroRef = ref(null)

// 提供給「有 Hero 的頁面」使用
provide('heroRef', heroRef)

// 把 heroRef 丟進去，讓 useNavScroll 決定要不要監測 Hero
const { isInHero, isScrollingDown } = useNavScroll(heroRef)

</script>

<template>
    <div class="min-h-screen flex flex-col mx-auto py-4 px-4 lg:px-8 3xl:px-10">
        <Nav :is-in-hero="isInHero"
        :is-scrolling-down="isScrollingDown"/>
        <main class="flex-1">
            <slot />
        </main>
        <Footer />
    </div>
</template>
