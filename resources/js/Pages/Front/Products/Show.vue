<script setup>
import FrontLayout from '@/Layouts/FrontLayout.vue';
import { inject, ref, computed } from 'vue';
import axios from 'axios';
import { router } from '@inertiajs/vue3';
import Breadcrumb from '@/DaisyComponents/Front/Breadcrumb.vue';
import QuantityStepper from '@/DaisyComponents/Front/QuantityStepper.vue';
import ProductCard from '@/DaisyComponents/Front/ProductCard.vue';

defineOptions({
    layout: FrontLayout,
})

const props = defineProps({
    product: Object,
    relatedProducts: Array,
    categoryName: String,
})

const formatTwd = (price) => {
    return `$ ${price?.toLocaleString() || 0}`
}

const breadcrumbItems = computed(() => {
    const items = [
        { label: '首頁', href: route('front.home.index') },
        { label: '所有商品', href: route('products.index') },
    ]

    if (props.categoryName) {
        items.push({ label: props.categoryName, href: route('categories.products', { category: props.categoryName }) })
    }

    items.push({ label: props.product.name })

    return items
})

// 圖片區：主圖 + 圓點指示器 + 箭頭，不用縮圖列
const images = computed(() => {
    if (props.product.product_images?.length) return props.product.product_images
    if (props.product.primary_image) return [props.product.primary_image]
    return []
})

const currentIndex = ref(0)
const currentImage = computed(() => images.value[currentIndex.value])

const prevImage = () => {
    currentIndex.value = (currentIndex.value - 1 + images.value.length) % images.value.length
}

const nextImage = () => {
    currentIndex.value = (currentIndex.value + 1) % images.value.length
}

// 規格 + 數量
const selectedOptionId = ref(props.product.product_options?.[0]?.id)

const selectedOption = computed(() => {
    return props.product.product_options?.find(opt => opt.id === selectedOptionId.value)
})

const hasDiscount = computed(() => {
    const opt = selectedOption.value
    return !!opt?.original_price && opt.original_price > opt.price
})

const qty = ref(1)

const openCart = inject('openCart')
const submitToCart = async () => {
    await axios.post(route('cart.store'), {
        product_option_id: selectedOptionId.value,
        qty: qty.value,
    })

    await openCart()
    await router.reload({
        only: ['cartItems'],
        preserveScroll: true,
    })
}
</script>

<template>
    <div class="max-w-layout-wide mx-auto px-4 mt-[var(--spacing-header-space)] pt-6">
        <Breadcrumb :items="breadcrumbItems" bare />
    </div>

    <section class="py-8 px-4 max-w-layout-wide mx-auto">
        <div class="flex flex-col md:flex-row gap-8 md:gap-12">
            <!-- 圖片區 -->
            <div class="w-full md:w-1/2">
                <div class="relative w-full aspect-square bg-base-100 rounded-2xl overflow-hidden">
                    <img v-if="currentImage" :src="currentImage.img_url" :alt="product.name"
                        class="w-full h-full object-contain" />

                    <template v-if="images.length > 1">
                        <button type="button" @click="prevImage"
                            class="absolute left-3 top-1/2 -translate-y-1/2 w-9 h-9 rounded-full bg-base-100/90 shadow flex items-center justify-center hover:bg-base-100 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75"
                                stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                            </svg>
                        </button>
                        <button type="button" @click="nextImage"
                            class="absolute right-3 top-1/2 -translate-y-1/2 w-9 h-9 rounded-full bg-base-100/90 shadow flex items-center justify-center hover:bg-base-100 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75"
                                stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                            </svg>
                        </button>

                        <div class="absolute bottom-4 left-0 right-0 flex justify-center gap-2">
                            <button v-for="(img, idx) in images" :key="img.id || idx" type="button"
                                @click="currentIndex = idx" class="h-2 rounded-full transition-all"
                                :class="idx === currentIndex ? 'w-6 bg-primary' : 'w-2 bg-base-100/70 hover:bg-base-100'">
                            </button>
                        </div>
                    </template>
                </div>
            </div>

            <!-- 商品資訊 -->
            <div class="w-full md:w-1/2">
                <h1 class="text-2xl md:text-3xl font-medium text-heading">{{ product.name }}</h1>

                <p class="mt-4 flex items-center gap-2">
                    <span class="text-2xl font-semibold text-primary">{{ formatTwd(selectedOption?.price) }}</span>
                    <span v-if="hasDiscount" class="text-base line-through text-base-content/40">
                        {{ formatTwd(selectedOption?.original_price) }}
                    </span>
                </p>

                <div class="mt-6">
                    <span class="text-sm font-medium text-heading">規格</span>
                    <div class="flex flex-wrap gap-2 mt-2">
                        <button v-for="opt in product.product_options" :key="opt.id" type="button"
                            @click="selectedOptionId = opt.id"
                            class="px-4 py-2 border rounded-[4px] text-sm transition-colors"
                            :class="opt.id === selectedOptionId
                                ? 'border-primary text-primary bg-primary/5'
                                : 'border-base-300 text-heading hover:border-primary'">
                            {{ opt.option_text }}
                        </button>
                    </div>
                </div>

                <div class="mt-6">
                    <span class="text-sm font-medium text-heading">數量</span>
                    <div class="mt-2">
                        <QuantityStepper v-model="qty" />
                    </div>
                </div>

                <button type="button" @click="submitToCart"
                    class="mt-8 w-full btn py-3 bg-transparent border border-primary text-primary rounded-[4px] hover:bg-primary hover:text-primary-content transition-colors text-base font-medium">
                    加入購物車
                </button>

                <div class="mt-10 pt-6 border-t border-base-300">
                    <h2 class="text-[18px] font-medium text-heading mb-2">商品介紹</h2>
                    <div class="text-[16px] text-base-content/80 leading-relaxed" v-html="product.description"></div>
                </div>
            </div>
        </div>
    </section>

    <section v-if="relatedProducts?.length" class="mt-8 mb-16 md:mb-24 max-w-layout-wide mx-auto px-4">
        <h2 class="text-xl md:text-2xl font-medium text-heading mb-6">你可能也喜歡</h2>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 md:gap-6">
            <ProductCard v-for="p in relatedProducts" :key="p.id" :product="p" />
        </div>
    </section>
</template>
