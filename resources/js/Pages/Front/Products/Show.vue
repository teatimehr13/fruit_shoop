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
import { Link, router } from '@inertiajs/vue3';

const modules = [Navigation, Thumbs, FreeMode, Pagination];

const images = [
    {
        id: 1,
        url: 'https://picsum.photos/700/700?random=1',
        alt: '春季新鮮草莓',
        subtitle: '產地直送・當日現採',
        link: '/products/strawberry',
    },
    {
        id: 2,
        url: 'https://picsum.photos/700/700?random=2',
        alt: '有機蔬菜箱',
        subtitle: '本週限時 8 折',
        link: '/products/veggie-box',
    },
    {
        id: 3,
        url: 'https://picsum.photos/700/700?random=3',
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

const thumbsMaxHeight = ref('0px')

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

const openCart = inject('openCart')
const submitToCart = async () => {
    const payload = {
        product_option_id: selectedOptionForm.id,
        qty: selectedOptionForm.qty
    }

    const res = await axios.post(route('cart.store'), payload)
    console.log(res.data)

    await openCart()
    await router.reload({
        only: ['cartItems'],
        preserveScroll: true,
    })
}


</script>

<template>
    <section class="py-8 px-4 mt-[var(--spacing-header-space)] max-w-[var(--max-w-layout-normal)] mx-auto">
        <div class="mt-header">
            <div class="mx-auto h-full">
                <div class="flex flex-col md:flex-row gap-6 md:gap-8 lg:gap-12">
                    <!-- 左側：圖片區域 -->
                    <div class="w-full md:w-[55%]" >
                        <div class="flex flex-col-reverse md:flex-row gap-4 h-auto">
                            <!-- 縮圖列表 -->
                            <div class="w-full md:w-[60px] shrink-0" :style="{ maxHeight: thumbsMaxHeight }">
                                <Swiper class="swiper--thumbs !h-full w-full" :modules="modules" :space-between="8"
                                    :free-mode="true" :watch-slides-progress="true" :breakpoints="{
                                        0: {
                                            direction: 'horizontal',
                                            slidesPerView: 8
                                        },
                                        768: {
                                            direction: 'vertical',
                                            slidesPerView: 'auto'
                                        },
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
                            <div class="min-w-0 ">
                                <div class="relative w-full">
                                    <Swiper class="pdp-media__carousel absolute inset-0  "
                                        :modules="modules" :space-between="0" :thumbs="{ swiper: thumbsSwiper }"
                                        :navigation="{
                                            nextEl: '.swiper__navigation--next',
                                            prevEl: '.swiper__navigation--prev'
                                        }" :pagination="{
                                            el: '.pdp-media__pagination',
                                            clickable: true
                                        }" @swiper="setMainSwiper">
                                        <SwiperSlide v-for="(image, index) in images" :key="image.id || index">
                                            <div class="aspect-square h-full bg-gray-100 rounded-lg overflow-hidden pdp-media__carousel_img">
                                                <img :src="image.url" :alt="image.alt"
                                                    class="w-full h-full object-cover" @load="updateThumbsHeight">
                                            </div>
                                        </SwiperSlide>

                                        <div
                                            class="pdp-media__pagination absolute left-0 right-0 z-10 flex justify-center">
                                        </div>
                                    </Swiper>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 右側：商品資訊 -->
                    <div class="w-full md:[45%] md:pl-4">
                        <div class="flex flex-col">
                            <!-- 商品標題 -->
                            <div>
                                <h1 style="font-size: clamp(24px,3vw,48px);" class="font-semibold">
                                    {{ product.name }}
                                </h1>
                            </div>

                            <!-- 商品描述 -->
                            <div class="mt-4">
                                <div class="text-base md:text-lg" v-html="product.description"></div>
                            </div>

                            <!-- 規格選擇 -->
                            <div class="flex-col mt-8 gap-4">
                                <span class="text-sm font-semibold">規格</span>
                                <div class="flex flex-wrap gap-2 mt-2">
                                    <div class="w-[calc(50%-0.25rem)] md:w-auto inline-flex"
                                        v-for="opt in product.product_options" :key="opt.id">
                                        <label class="inline-flex items-center cursor-pointer w-full">
                                            <input type="radio" class="peer hidden" name="gift" :value="opt.id"
                                                v-model="selectedOptionForm.id" @change="handleOptionChange(opt)">
                                            <span class="text-center w-full px-6 py-2 rounded-full text-sm border border-[#f1f0ed] text-[#67645e]
                                peer-checked:bg-[#82ae46] peer-checked:text-white transition-colors">
                                                {{ opt.option_text }}
                                            </span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-8 text-[#82ae46]" style="font-size: clamp(24px, 1.75vw, 32px);">
                                {{ formatTwd(selectedOptionForm.price) }}
                            </div>                           

                            <!-- 加入購物車按鈕 -->
                            <div class="mt-8 w-full">
                                <button type="button" class="btn w-full py-2 bg-transparent border-1 text-[#82ae46] border-[#82ae46] rounded-full 
                        hover:bg-[#82ae46] hover:text-white transition-colors text-lg font-semibold" @click="submitToCart">
                                    加入購物車 
                                </button>
                            </div>
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