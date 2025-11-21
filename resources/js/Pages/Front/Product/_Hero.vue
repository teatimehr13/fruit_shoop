<script setup>
import { inject, onMounted, ref } from 'vue'
import { Swiper, SwiperSlide } from 'swiper/vue';
import { Autoplay, Pagination, EffectFade } from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/pagination';
import 'swiper/css/effect-fade';

const parentHeroRef = inject('heroRef', null)
const localHeroRef = ref(null)

onMounted(() => {
    if (parentHeroRef && localHeroRef.value) {
        parentHeroRef.value = localHeroRef.value
    }
})

const modules = [Autoplay, Pagination, EffectFade];

const heroSlides = [
    {
        id: 1,
        image: '/images/hero/engin-akyurt-Y5n8mCpvlZU-unsplash.jpg',
        title: '春季新鮮草莓',
        subtitle: '產地直送・當日現採',
        link: '/products/strawberry',
    },
    {
        id: 2,
        image: '/images/hero/messageImage_1763705363499.jpg',
        title: '有機蔬菜箱',
        subtitle: '本週限時 8 折',
        link: '/products/veggie-box',
    },
       {
        id: 3,
        image: '/images/hero/messageImage_1763705600822.jpg',
        title: '有機蔬菜箱',
        subtitle: '本週限時 8 折',
        link: '/products/veggie-box',
    },
];
</script>

<template>
    <section ref="localHeroRef">
        <Swiper :modules="modules" :slides-per-view="1" :loop="true" :effect="'fade'" :autoplay="{
            delay: 4000,
            disableOnInteraction: false,
        }" :pagination="{ clickable: true }" class="h-[500px] md:h-[600px] my-swiper rounded-t-[12px]">
            <SwiperSlide v-for="slide in heroSlides" :key="slide.id">
                <div class="relative h-full">
                    <img :src="slide.image" :alt="slide.title" class="w-full h-full object-cover" />
                    <div class="absolute inset-0 bg-gradient-to-r from-black/50 to-transparent">
                        <div class="max-w-8xl mx-auto h-full flex items-center px-6">
                            <div>
                                <h1 class="text-5xl font-bold text-white mb-4">
                                    {{ slide.title }}
                                </h1>
                                <p class="text-xl text-white mb-6">
                                    {{ slide.subtitle }}
                                </p>
    
                                <a :href="slide.link"
                                class="inline-block bg-green-600 text-white px-8 py-3 rounded-lg hover:bg-green-700"
                                >
                                立即選購 →
                                </a>
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
  --swiper-pagination-color: #fff;
}
</style>