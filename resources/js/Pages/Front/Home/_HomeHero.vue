<script setup>
import { inject, onMounted, ref } from 'vue'
import { Swiper, SwiperSlide } from 'swiper/vue';
import { Autoplay, Pagination, EffectFade } from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/pagination';
import 'swiper/css/effect-fade';

// const heroRef = inject('heroRef', null)

// const setHeroRef = (el) => {
//   if (heroRef) {
//     heroRef.value = el
//   }
// }

const modules = [Autoplay, Pagination, EffectFade];

const heroSlides = [
    {
        id: 1,
        image: '/images/hero/engin-akyurt-Y5n8mCpvlZU-unsplash.webp',
        title: '春季新鮮草莓',
        subtitle: '產地直送・當日現採',
        link: '/products/strawberry',
    },
    {
        id: 2,
        image: '/images/hero/8bce9e29-97f7-43f9-822f-d1108e792ff2.webp',
        title: '鮮榨果汁',
        subtitle: '新鮮喝的到',
        link: '/products/veggie-box',
    },
    {
        id: 3,
        image: '/images/hero/bfd4942a-1c19-402d-80c8-a63eb93b1b11.webp',
        title: '有機蔬菜箱',
        subtitle: '本週限時 8 折',
        link: '/products/veggie-box',
    },
];
</script>

<template>
    <section :ref="setHeroRef" class="w-full aspect-[16/9] md:aspect-auto md:h-[600px] mt-[var(--spacing-header-space)]">
        <Swiper :modules="modules" :slides-per-view="1" :loop="true" :effect="'fade'" :autoplay="{
            delay: 4000,
            disableOnInteraction: false,
        }" :pagination="{ clickable: true }" class="h-full my-swiper">
            <SwiperSlide v-for="slide in heroSlides" :key="slide.id">
                <div class="relative h-full ">
                    <img :src="slide.image" :alt="slide.title" class="w-full h-full object-cover" />
                    <div class="absolute inset-0 bg-gradient-to-r from-black/50 to-transparent">
                        <div class="max-w-layout-wide mx-auto h-full flex items-center px-4">
                            <div>
                                <h1
                                    class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl 3xl:text-8xl font-bold text-base-100 mb-6">
                                    {{ slide.title }}
                                </h1>
                                <p class="text-base sm:text-lg md:text-xl lg:text-2xl 3xl:text-3xl text-base-100 mb-8">
                                    {{ slide.subtitle }}
                                </p>

                                <!-- <a :href="slide.link" class="
                                    inline-block bg-green-600 text-base-100 rounded-lg 
                                    hover:bg-green-700 transition-colors
                                    px-6 py-2.5 md:px-8 md:py-3 lg:px-10 lg:py-4
                                    text-sm md:text-base lg:text-lg">
                                    立即選購 →
                                </a> -->
                            </div>
                        </div>
                    </div>
                </div>
            </SwiperSlide>
        </Swiper>
    </section>
</template>


<style>
.my-swiper {
    /* 分頁器顏色 */
    --swiper-pagination-color: var(--color-base-100);
}
</style>