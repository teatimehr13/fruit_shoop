<template>
    <PageHero />

    <section class="py-8 px-4  max-w-layout-wide mx-auto">
        <div class="mt-header">
            <div class="mx-auto h-full">
                <div class="flex flex-col md:flex-row gap-6 md:gap-8 lg:gap-12">
                    <!-- 左側：圖片區域 -->
                    <div class="w-full md:w-[50%]">
                        <div class="aspect-[3/2] bg-gray-100 rounded-lg overflow-hidden">
                            <img :src="props.about.img_url" class="w-full h-full object-cover block">
                        </div>
                    </div>

                    <!-- 右側：商品資訊 -->
                    <div class="w-full md:w-[50%] md:pl-4">
                        <div class="flex flex-col">
                            <div class="md:pt-10 mb-6 ">
                                <h1 class="text-2xl md:text-4xl font-semibold tracking-wide text-base-content">
                                    {{ props.about.title }}
                                </h1>
                            </div>

                            <p class="tracking-wide text-sm md:text-lg text-base-content" v-html="props.about.content">
                               
                            </p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
</template>

<script setup>
import FrontLayout from '@/Layouts/FrontLayout.vue';
import PageHero from './_PageHero.vue';

defineOptions({
    layout: FrontLayout
})

const props = defineProps({
    about: Object
})

console.log(props.about);


</script>