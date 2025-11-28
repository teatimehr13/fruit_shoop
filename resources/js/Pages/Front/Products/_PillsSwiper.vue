<script setup>
import { ref } from 'vue'
import { Swiper, SwiperSlide } from 'swiper/vue'
import { FreeMode } from 'swiper/modules' // 1. 引入 FreeMode 模組
import 'swiper/css'
import 'swiper/css/free-mode' // 2. 引入 FreeMode 樣式
import { Link } from '@inertiajs/vue3'

const props = defineProps({
    categories: Array,
    initialActive: [String, Number, null]
})
const emit = defineEmits(['change'])
const activeHandle = ref(props.initialActive ?? props.categories[0]?.handle)

// 3. 設定模組
const modules = [FreeMode]

const handleClick = (cat) => {
    // Swiper 在拖曳時會抑制 click，通常不需要額外處理，
    // 但保險起見可以使用 @click.stop 或依賴 Swiper 內建的 touchMove 判斷
    const handle = cat.handle ?? cat.slug ?? cat.id
    activeHandle.value = handle
    emit('change', handle)
}
</script>

<template>
    <div>
        <swiper :slides-per-view="'auto'" :space-between="10" :modules="modules" :free-mode="true" :grabCursor="true"
            class="w-full h-full">
            <swiper-slide v-for="cat in categories" :key="cat.id"
                class="!w-auto px-4 py-2 rounded-full border text-sm whitespace-nowrap transition-colors duration-200"
                :class="activeHandle === (cat.handle ?? cat.slug ?? cat.id)
                    ? 'bg-black text-white border-black'
                    : 'bg-white text-gray-800 border-gray-300 hover:bg-gray-100'">
                <Link :href="route('front.home.index')" class="">
                {{ cat.name }}
                </Link>
            </swiper-slide>
        </swiper>
    </div>
</template>