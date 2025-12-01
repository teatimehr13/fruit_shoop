<script setup>
import { ref, provide, watch } from 'vue'
import { useNavScroll } from '@/Composables/useNavScroll'
import Footer from '@/DaisyComponents/Front/Footer.vue';
import Nav from '@/DaisyComponents/Front/Nav.vue';
import CartDrawer from '@/DaisyComponents/Front/CartDrawer.vue';

const heroRef = ref(null)
const isCartOpen = ref(false)

// 提供給「有 Hero 的頁面」使用
provide('heroRef', heroRef)

// provide('isCartOpen', isCartOpen)

// 把 heroRef 丟進去，讓 useNavScroll 決定要不要監測 Hero
const { isInHero, isScrollingDown } = useNavScroll(heroRef)

watch(isCartOpen, (val) => {
        if (val) {
            document.body.style.overflow = 'hidden'
        } else {
            document.body.style.overflow = ''
        }
    },
    { immediate: true }
)

const openCart = () => { isCartOpen.value = true }
const closeCart = () => { isCartOpen.value = false }
provide('openCart', openCart)
</script>

<template>
    <div class="min-h-screen flex flex-col mx-auto py-4 px-4 lg:py-8 lg:px-8 3xl:px-10 3xl:py-10">
        <Nav :is-in-hero="isInHero" :is-scrolling-down="isScrollingDown" @open-cart="openCart"/>
        <main class="flex-1">
            <slot />
        </main>
        <Footer />
    </div>

    <CartDrawer :open="isCartOpen" @close="closeCart" @remove="handleRemove"/>
</template>
