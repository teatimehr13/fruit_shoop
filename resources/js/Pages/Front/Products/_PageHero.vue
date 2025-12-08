<script setup>
import { inject, onMounted, ref } from 'vue'

const heroRef = inject('heroRef', null)

// const setHeroRef = (el) => {
//   if (heroRef) {
//     heroRef.value = el
//   }
// }

const heroSlides = [
    {
        id: 1,
        image: '/images/hero/Gemini_Generated_Image_xut2fvxut2fvxut2.png',
        title: '所有產品',
        subtitle: '嚴選在地小農-高品質國產、進口蔬果',
        link: '/products/strawberry',
    },
];
</script>

<template>

    <section :ref="setHeroRef" class="w-full aspect-[16/9] md:aspect-auto md:h-[500px] mt-[88px]">
        <div v-if="heroSlides.length" class="relative h-full  overflow-hidden">
            <img :src="heroSlides[0].image" :alt="heroSlides[0].title" class="w-full h-full object-cover" />

            <div class="absolute inset-0 bg-gradient-to-r from-black/50 to-transparent">
                <div class="w-full h-full flex items-center justify-center px-6">
                    <div class="text-center">
                        <h3 class="text-xl sm:text-2xl md:text-3xl lg:text-4xl 3xl:text-5xl font-bold text-white mb-4">
                            {{ heroSlides[0].title }}
                        </h3>

                        <p class="text-sm sm:text-base md:text-lg lg:text-xl 3xl:text-2xl text-white mb-6">
                            {{ heroSlides[0].subtitle }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

</template>


<style>

</style>