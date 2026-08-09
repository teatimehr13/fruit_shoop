<script setup>
import { ref } from 'vue'
import { Swiper, SwiperSlide } from 'swiper/vue';
import { Scrollbar, EffectFade, Pagination, Navigation } from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/effect-fade';
// import 'swiper/css/scrollbar';
import 'swiper/css/pagination';
import 'swiper/css/navigation';

const modules = [Scrollbar, EffectFade, Pagination, Navigation];

const props = defineProps({
    featured: Object
});

</script>

<template>
    <section class="px-4 pb-16 md:pb-18 max-w-[var(--max-w-layout-wide)] mx-auto">
        <div class="border border-base-200 rounded-[12px] py-6 px-4 pr-0 lg:pr-0 lg:px-6">
            <div class="md:mb-8 mb-4 md:pt-4">
                <h2 class="text-2xl md:text-4xl font-semibold tracking-wide text-base-content">
                    精選好物 +
                </h2>
            </div>
            <div class="relative">
                <swiper :slidesPerView="1" :spaceBetween="14" :pagination="{
                    el: '.custom-progressbar',
                    type: 'progressbar'
                }" :navigation="{
                    nextEl: '.custom-button-next',
                    prevEl: '.custom-button-prev'
                }" :modules="modules" :breakpoints="{
                    '640': {
                        slidesPerView: 2,
                        spaceBetween: 16,
                    },
                    '768': {
                        slidesPerView: 3,
                        spaceBetween: 16,
                    },
                    '1024': {
                        slidesPerView: 3,
                        spaceBetween: 16,
                    },
                    '1280': {
                        slidesPerView: 4,
                        spaceBetween: 16,
                    },
                    '1536': {
                        slidesPerView: 5,
                        spaceBetween: 18,
                    }

                }" class="mySwiper">
                        <swiper-slide v-for="featured in props.featured">
                        <a :href="route('products.show', featured.slug)">
                            <img :src="featured.image"
                                class="aspect-square object-cover rounded-[12px]">
                        </a>
                    </swiper-slide>
                </swiper>

                <div class="flex gap-8 mt-8 mr-2 lg:mr-4">
                    <!-- 客製化 Progressbar -->
                    <div class="custom-progressbar mt-6 rounded-full w-full"></div>
                    <div class="flex gap-4">
                        <!-- 客製化左右按鈕 -->
                        <button class="custom-button-prev">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 19l-7-7 7-7" />
                            </svg>
                        </button>

                        <button class="custom-button-next">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<style>
.custom-button-prev,
.custom-button-next {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s;
}


/* Hover 效果 */
.custom-button-prev:hover,
.custom-button-next:hover {
    background: white;
}

/* 禁用狀態 */
.custom-button-prev:disabled,
.custom-button-next:disabled {
    opacity: 0.3;
    cursor: not-allowed;
}

.custom-progressbar{
    position:  relative !important;
    height: 2px !important;
    background: var(--color-neutral) !important;

}

.custom-progressbar .swiper-pagination-progressbar-fill {
    background: var(--color-base-content) !important;
    height: 2px !important;
}
</style>