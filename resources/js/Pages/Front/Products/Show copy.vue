<script setup>
import FrontLayout from '@/Layouts/FrontLayout.vue';
import { inject, ref, onMounted, nextTick, onUnmounted, reactive, watch } from 'vue'
import axios from 'axios';
import { Swiper, SwiperSlide } from 'swiper/vue';
import { Navigation, Thumbs, FreeMode, Pagination } from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/navigation'
import 'swiper/css/pagination'
import 'swiper/css/thumbs'
import 'swiper/css/free-mode'
import QuantityStepper_Product from '@/DaisyComponents/Front/QuantityStepper_Product.vue';

const modules = [Navigation, Thumbs, FreeMode, Pagination];

const images = [
    {
        id: 1,
        url: 'https://picsum.photos/600/900?random=1',
        alt: '春季新鮮草莓',
        subtitle: '產地直送・當日現採',
        link: '/products/strawberry',
    },
    {
        id: 2,
        url: 'https://picsum.photos/600/900?random=2',
        alt: '有機蔬菜箱',
        subtitle: '本週限時 8 折',
        link: '/products/veggie-box',
    },
    {
        id: 3,
        url: 'https://picsum.photos/600/900?random=3',
        alt: '有機蔬菜箱',
        subtitle: '本週限時 8 折',
        link: '/products/veggie-box',
    },
    {
        id: 4,
        url: 'https://picsum.photos/600/900?random=4',
        alt: '有機蔬菜箱',
        subtitle: '本週限時 8 折',
        link: '/products/veggie-box',
    },
    {
        id: 5,
        url: 'https://picsum.photos/600/900?random=5',
        alt: '有機蔬菜箱',
        subtitle: '本週限時 8 折',
        link: '/products/veggie-box',
    },
    {
        id: 6,
        url: 'https://picsum.photos/600/900?random=6',
        alt: '有機蔬菜箱',
        subtitle: '本週限時 8 折',
        link: '/products/veggie-box',
    },
    {
        id: 7,
        url: 'https://picsum.photos/600/900?random=6',
        alt: '有機蔬菜箱',
        subtitle: '本週限時 8 折',
        link: '/products/veggie-box',
    },
    {
        id: 8,
        url: 'https://picsum.photos/600/900?random=6',
        alt: '有機蔬菜箱',
        subtitle: '本週限時 8 折',
        link: '/products/veggie-box',
    },
    {
        id: 9,
        url: 'https://picsum.photos/600/900?random=6',
        alt: '有機蔬菜箱',
        subtitle: '本週限時 8 折',
        link: '/products/veggie-box',
    },

]

defineOptions({
    layout: FrontLayout,
})

const props = defineProps({
    product: Object
})

console.log(props.product);


// swiper圖片區
// 縮圖 Swiper 實例
const thumbsSwiper = ref(null)
// 主 Swiper 實例
const mainSwiper = ref(null)

// 設定縮圖 Swiper
const setThumbsSwiper = (swiper) => {
    thumbsSwiper.value = swiper
}

// 設定主 Swiper
const setMainSwiper = (swiper) => {
    mainSwiper.value = swiper
}

const thumbsMaxHeight = ref('600px')

// 更新縮圖容器高度
const updateThumbsHeight = () => {
    const isMd = window.innerWidth >= 768

    if (!isMd) {
        thumbsMaxHeight.value = 'none'
        return
    }

    nextTick(() => {
        const mainCarousel = document.querySelector('.pdp-media__carousel')
        if (mainCarousel) {
            const height = mainCarousel.offsetHeight
            if (height > 0) {
                thumbsMaxHeight.value = `${height}px`
                // console.log('Updated thumbs height to:', height)
            }
        }
    })
}

// 監聽視窗大小變化
const handleResize = () => {
    updateThumbsHeight()
}

onMounted(() => {
    // 多次嘗試更新高度，確保圖片載入完成
    setTimeout(updateThumbsHeight, 100)
    setTimeout(updateThumbsHeight, 300)
    setTimeout(updateThumbsHeight, 500)
    setTimeout(updateThumbsHeight, 1000)

    window.addEventListener('resize', handleResize)
})

onUnmounted(() => {
    window.removeEventListener('resize', handleResize)
})

//文字區
// console.log(props.cheapest_option_id);

const selectedOptionForm = reactive({
    id: props.product.cheapest_option_id,
    price: props.product.cheapest_price,
    qty: 1
})

const formatTwd = (price) => {
    return `$ ${price?.toLocaleString() || 0}`
}

const handleOptionChange = (opt) => {
    selectedOptionForm.price = opt.price;
} 

watch(() => selectedOptionForm.id, (newId, oldId) => {
    // console.log('新選中的 ID:', newId)
    // const selectedOpt = props.product.product_options.find(opt => opt.id === newId)
    // console.log('選中的完整選項:', selectedOpt)
})


</script>

<template>
    <section class="mb-8">
        <div class="mt-header">
            <div class="grid md:gap-8 md:grid-cols-[minmax(0,1.1fr)_minmax(0,1fr)] lg:gap-12 h-full">
                <div class="grid gap-4 md:gap-6 relative 
                            grid-cols-1 md:grid-cols-[100px_minmax(0,1fr)]  h-full md:grid-flow-col-dense">

                    <!-- 縮圖區域 -->
                    <div class="order-2 md:order-1" :style="{ maxHeight: thumbsMaxHeight }">
                        <Swiper class="swiper--thumbs !h-full w-full" :modules="modules" :space-between="8"
                            :free-mode="true" :watch-slides-progress="true" :breakpoints="{
                                0: {
                                    direction: 'horizontal',
                                    slidesPerView: 6
                                },
                                // 768: {
                                //     direction: 'vertical',
                                //     slidesPerView: 'auto'
                                // },
                            }" @swiper="setThumbsSwiper">
                            <SwiperSlide v-for="(image, index) in images" :key="image.id || index"
                                class="cursor-pointer !h-auto">
                                <div
                                    class="aspect-square overflow-hidden border-2 border-gray-200 rounded-lg hover:border-[#82ae46] transition-colors">
                                    <img :src="image.url" :alt="image.alt || `產品圖片 ${index + 1}`"
                                        class="w-full h-full object-cover">
                                </div>
                            </SwiperSlide>
                        </Swiper>
                    </div>

                    <!-- 主圖區域 -->
                    <div class="order-1 md:order-2">
                        <div class="relative md:h-full">
                            <Swiper class="pdp-media__carousel" :modules="modules" :space-between="0"
                                :thumbs="{ swiper: thumbsSwiper }" :navigation="{
                                    nextEl: '.swiper__navigation--next',
                                    prevEl: '.swiper__navigation--prev',
                                }" :pagination="{
                                    el: '.pdp-media__pagination',
                                    clickable: true,
                                }" @swiper="setMainSwiper">
                                <SwiperSlide v-for="(image, index) in images" :key="image.id || index">
                                    <div class="aspect-3/2 bg-gray-100 rounded-lg overflow-hidden"
                                        style="max-height: clamp(200px, 60vh, 500px);">
                                        <img :src="image.url" :alt="image.alt || `產品圖片 ${index + 1}`"
                                            class="w-full h-full object-cover" @load="updateThumbsHeight">
                                    </div>
                                </SwiperSlide>
                            </Swiper>

                            <div class="pdp-media__pagination flex justify-center mt-4 absolute bottom-2 z-10"></div>
                        </div>
                    </div>
                </div>

                <div class="mt-8 md:mt-0 p-2 bg-">
                    <div class="flex flex-col">
                        <div>
                            <h1 style="font-size: clamp(24px,3vw,48px);" class="font-semibold">
                                {{ product.name }}
                            </h1>
                        </div>
                        <div class="mt-4">
                            <h1 class="text-base md:text-lg" v-html="product.description"></h1>
                        </div>

                        <div class="flex flex-col mt-8 gap-4">
                            <span>規格</span>
                            <div class="flex flex-wrap gap-2">
                                <div class="w-[calc(50%-0.5rem)] md:w-auto inline-flex"
                                    v-for="opt in product.product_options" :key="opt.id">
                                    <label class="inline-flex items-center cursor-pointer w-full">
                                        <input type="radio" class="peer hidden" name="gift" :value="opt.id"
                                            v-model="selectedOptionForm.id" @change="handleOptionChange(opt)">
                                        <span class="text-center w-full px-6 py-2 rounded-full text-sm border-1 border-[#f1f0ed] text-[#67645e]
                                        peer-checked:bg-[#82ae46] peer-checked:text-[#fff]">
                                            {{ opt.option_text }}
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </div>


                        <!-- <div class="mt-8 text-[#82ae46]" style="font-size: clamp(24px,1.75vw,36px);">
                            {{ formatTwd(product.cheapest_price) }}
                        </div>

                        <QuantityStepper_Product v-model="selectedOptionForm.qty" :min="1" :max="50"
                            @update:modelValue="val => handleQtyChange(item, val)" class="mt-6" /> -->

                        <div class="mt-6 w-full">
                            <button type="button"
                                class="btn w-full btn-lg mt-4 mb-2 w-fullpy-3 bg-transparent border text-[#82ae46] border-[#82ae46] rounded-[40px] hover:bg-[#82ae46] hover:text-[#fff] transition-colors">
                                加入購物車 - {{ formatTwd(selectedOptionForm.price) }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<style>
.pdp-media__pagination {
    --swiper-pagination-color: #fff;
}

/* 確保 Swiper 縮圖在垂直模式下正確顯示 */
.swiper--thumbs {
    overflow: hidden !important;
}

.swiper--thumbs .swiper-wrapper {
    height: 100% !important;
}

/* 選中的縮圖高亮 */
.swiper--thumbs .swiper-slide-thumb-active div {
    border-color: #82ae46 !important;
}
</style>