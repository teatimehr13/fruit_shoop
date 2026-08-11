<script setup>
import { inject } from 'vue';
import CtaButton from '@/DaisyComponents/Front/CtaButton.vue';

// 接上 FrontLayout 提供的 heroRef,讓 Nav 知道現在是不是還在 hero 區塊內
// (在 hero 內:透明背景+白字;離開 hero:白底+深色字樣式)
const heroRef = inject('heroRef', null)
const setHeroRef = (el) => {
    if (heroRef) {
        heroRef.value = el
    }
}

const scrollToFeatures = () => {
    document.getElementById('features')?.scrollIntoView({ behavior: 'smooth' })
}
</script>

<template>
    <section :ref="setHeroRef" class="relative w-full aspect-[4/3] md:h-screen overflow-hidden">
        <video autoplay muted loop playsinline poster="/videos/hero-fruit-poster.jpg"
            class="absolute inset-0 w-full h-full object-cover">
            <source src="/videos/hero-fruit.mp4" type="video/mp4">
        </video>
        <div class="absolute inset-0 bg-gradient-to-b from-black/10 via-black/20 to-black/40"></div>

        <div class="relative h-full max-w-layout-wide mx-auto px-4 pb-8 md:pb-16 flex flex-col items-center justify-end text-center">
            <h1 class="hero-fade-up text-2xl md:text-3xl font-medium text-base-100 mb-6" style="animation-delay: 0.1s">
                嚴選新鮮蔬果，讓日常採買更簡單。
            </h1>

            <CtaButton @click="scrollToFeatures" style="animation-delay: 0.4s"
                class="hero-fade-up !bg-transparent !text-[#fff] !border !border-[#fff] hover:!bg-primary hover:!text-primary-content hover:!border-primary">
                探索更多 →
            </CtaButton>
        </div>
    </section>
</template>

<style scoped>
.hero-fade-up {
    opacity: 0;
    animation: hero-fade-up 0.8s ease-out forwards;
}

@keyframes hero-fade-up {
    from {
        opacity: 0;
        transform: translateY(24px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
