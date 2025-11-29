<script setup>
import { ref } from 'vue'
import { Swiper, SwiperSlide } from 'swiper/vue'
import { FreeMode } from 'swiper/modules' // 1. 引入 FreeMode 模組
import 'swiper/css'
import 'swiper/css/free-mode' // 2. 引入 FreeMode 樣式
import { Link } from '@inertiajs/vue3'

const props = defineProps({
    categories: Array,
    // initialActive: [String, Number, null],
    activeCategory: String
})

const activeHandle = ref(props.initialActive ?? props.categories[0]?.handle)

// 3. 設定模組
const modules = [FreeMode]

const emit = defineEmits(['select-category'])

const handleClickCategory = (name) => {
    emit('select-category', name)
}

console.log('test=>', props.activeCategory);
console.log('test2=>', props.categories);
</script>

<template>
    <div>
        <swiper :slides-per-view="'auto'" :space-between="10" :modules="modules" :free-mode="true" :grabCursor="true"
            class="w-full h-full">
            <swiper-slide
                class="!w-auto  rounded-full border text-sm whitespace-nowrap transition-colors duration-200" :class="props.activeCategory == 'ALL'
                    ? 'bg-[#67645e] text-white border-green-600'
                    : 'bg-white text-gray-800 border-gray-300 hover:bg-gray-100'">
                <a href="#" class="block w-full px-4 py-2" @click.prevent="handleClickCategory('ALL')">
                    全部
                </a>
            </swiper-slide>

            <swiper-slide v-for="cat in categories" :key="cat.id"
                class="!w-auto  rounded-full border text-sm whitespace-nowrap transition-colors duration-200" :class="props.activeCategory == cat.name
                    ? 'bg-[#67645e] text-white border-green-600'
                    : 'bg-white text-gray-800 border-gray-300 hover:bg-gray-100'">
                <a href="#" class="block w-full px-4 py-2" @click.prevent="handleClickCategory(cat.name)">
                    {{ cat.name }}
                </a>
            </swiper-slide>
        </swiper>
    </div>
</template>

<!-- :class="{'bg-[#67645e] text-base-100' : value.name == props.activeCategory}" -->