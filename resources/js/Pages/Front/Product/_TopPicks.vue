<script setup>
import { ref } from 'vue'
import { Swiper, SwiperSlide } from 'swiper/vue';
import { Scrollbar, EffectFade } from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/effect-fade';
import 'swiper/css/scrollbar';


const modules = [Scrollbar, EffectFade];

</script>

<template>
    <section class="pb-16">
        <div class="bg-[#f1f0ed] rounded-[12px] pr-0 md:pr-0 p-4 md:p-8">
            <div class="md:mb-8 mb-4 md:pt-4">
                <h2 class="text-2xl md:text-4xl font-semibold tracking-wide text-[#67645e]">
                    精選好物 +
                </h2>
            </div>
            <div>
                <swiper :scrollbar="{
                    hide: true,
                }" :slidesPerView="1.5" :spaceBetween="25" :pagination="{
                        clickable: true,
                    }" :modules="modules" :breakpoints="{
                        '640': {
                            slidesPerView: 2,
                            spaceBetween: 12,
                        },
                        '768': {
                            slidesPerView: 2.5,
                            spaceBetween: 14,
                        },
                        '1024': {
                            slidesPerView: 3,
                            spaceBetween: 18,
                        },
                        '1280': {
                            slidesPerView: 3.5,
                            spaceBetween: 22,
                        },
                        '1536': {
                            slidesPerView: 4.5,
                            spaceBetween: 24,
                        }

                    }" class="mySwiper">
                    <swiper-slide>
                        <img src="https://picsum.photos/600/900?random=1"
                            class="aspect-square object-cover rounded-[12px]">
                    </swiper-slide>
                    <swiper-slide>
                        <img src="https://picsum.photos/600/900?random=2"
                            class="aspect-square object-cover rounded-[12px]">
                    </swiper-slide>
                    <swiper-slide>
                        <img src="https://picsum.photos/600/900?random=3"
                            class="aspect-square object-cover rounded-[12px]">
                    </swiper-slide>
                    <swiper-slide>
                        <img src="https://picsum.photos/600/900?random=4"
                            class="aspect-square object-cover rounded-[12px]">
                    </swiper-slide>
                    <swiper-slide>
                        <img src="https://picsum.photos/600/900?random=5"
                            class="aspect-square object-cover rounded-[12px]">
                    </swiper-slide>
                    <swiper-slide>
                        <img src="https://picsum.photos/600/900?random=6"
                            class="aspect-square object-cover rounded-[12px]">
                    </swiper-slide>
                    <swiper-slide>
                        <img src="https://picsum.photos/600/900?random=7"
                            class="aspect-square object-cover rounded-[12px]">
                    </swiper-slide>
                    <swiper-slide>
                        <img src="https://picsum.photos/600/900?random=8"
                            class="aspect-square object-cover rounded-[12px]">
                    </swiper-slide>
                    <swiper-slide>
                        <img src="https://picsum.photos/600/900?random=9"
                            class="aspect-square object-cover rounded-[12px]">
                    </swiper-slide>
                </swiper>
            </div>
        </div>
    </section>
</template>