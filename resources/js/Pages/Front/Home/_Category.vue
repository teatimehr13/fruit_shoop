<script setup>
const props = defineProps({
    categories: Object,
})

// 目前 Category 資料表沒有圖片欄位，先用固定的裝飾圖循環當底圖
const tileImages = [
    '/images/categories/unnamed.webp',
    '/images/categories/thought-catalog-EMX1eJ1BcgU-unsplash.webp',
    '/images/categories/Gemini_Generated_Image_1b8lf61b8lf61b8l.webp',
    '/images/categories/Gemini_Generated_Image_5wt40w5wt40w5wt4.webp',
]
</script>
<template>
    <section class="px-4 py-16 md:py-20 max-w-layout-wide mx-auto">
        <h2 class="text-3xl md:text-5xl font-semibold tracking-wide text-base-content mb-8 md:mb-12">
            熱門分類
        </h2>

        <div class="grid grid-cols-2 md:grid-cols-3 gap-[30px]">
            <a v-for="(category, index) in props.categories" :key="category.id"
                :href="route('categories.products', { category: category.name })"
                class="group relative aspect-square md:aspect-4/3 rounded-[20px] overflow-hidden block">
                <img :src="tileImages[index % tileImages.length]" :alt="category.name"
                    class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-black/0 to-transparent"></div>
                <span
                    class="absolute left-4 bottom-4 md:left-6 md:bottom-6 text-xl md:text-2xl font-semibold text-base-100">
                    {{ category.name }}
                </span>
            </a>
        </div>
    </section>
</template>
