<script setup>
const heroSlides = [
    {
        id: 1,
        image: '/images/hero/Gemini_Generated_Image_xut2fvxut2fvxut2.webp',
        title: '所有產品',
        subtitle: '嚴選在地小農-高品質國產、進口蔬果',
    },
];
</script>

<template>
    <section class="w-full h-[220px] md:h-[320px]">
        <div v-if="heroSlides.length" class="relative h-full overflow-hidden">
            <img :src="heroSlides[0].image" :alt="heroSlides[0].title" class="w-full h-full object-cover" />

            <div class="absolute inset-0 bg-gradient-to-r from-black/65 via-black/35 to-transparent">
                <div class="max-w-layout-wide mx-auto h-full flex items-center px-4">
                    <div class="text-center md:text-left">
                        <h3 class="text-2xl md:text-4xl font-medium tracking-[0.02em] text-base-100 mb-2 md:mb-3">
                            {{ heroSlides[0].title }}
                        </h3>

                        <p class="text-sm md:text-base text-base-100/85">
                            {{ heroSlides[0].subtitle }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
