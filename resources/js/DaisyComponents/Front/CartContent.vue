<template>
    <div v-if="!items.length" class="flex-1 flex flex-col items-center justify-center px-6 py-10 text-center gap-4"
        :class="layoutMode === 'drawer' ? 'bg-surface-muted' : ''">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.25"
            stroke="currentColor" class="w-16 h-16 text-base-content/30">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
        </svg>
        <div class="text-lg font-semibold text-heading">你的購物車為空</div>
        <button type="button" @click="continueShop"
            class="btn border-primary bg-primary text-primary-content hover:bg-primary/90 rounded-full w-32">
            去逛逛
        </button>
    </div>

    <div v-else :class="containerClass">

        <section :class="listSectionClass">
            <div v-for="item in items" :key="item.id ?? item.product_option_id"
                class="grid gap-3 p-3 bg-base-100 rounded-2xl shadow-soft items-center" :class="itemGridClass">
                <div class="flex-shrink-0 rounded-xl bg-base-200 overflow-hidden" :class="imageSizeClass">
                    <div v-if="item.img_url" class="w-full h-full">
                        <img :src="item.img_url" alt="" class="w-full h-full object-cover" />
                    </div>
                    <div v-else class="w-full h-full flex items-center justify-center text-xs text-base-content/50">
                        圖片
                    </div>
                </div>

                <div class="flex flex-col justify-center min-w-0">
                    <div>
                        <div class="text-md font-semibold text-heading line-clamp-2">
                            {{ item.product_name }}
                        </div>
                        <div class="text-xs font-medium text-base-content line-clamp-2">
                            {{ item.option_text }}
                        </div>
                    </div>
                    <div class="text-sm font-semibold text-primary mt-2 md:mt-4">
                        {{ item.price < item.original_price ? formatTwd(item.price) : formatTwd(item.original_price) }}
                    </div>
                </div>

                <div class="flex justify-center">
                    <QuantityStepper :modelValue="item.qty" :min="1" :max="50"
                        @update:modelValue="val => handleQtyChange(item, val)" />
                </div>

                <div class="flex justify-end py-2 self-baseline">
                    <button type="button"
                        class="btn btn-ghost btn-circle btn-xs text-base-content/50 hover:text-error hover:bg-error/10"
                        @click="delCartItem(item.product_option_id)">
                        <span class="sr-only">移除商品</span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                        </svg>
                    </button>
                </div>
            </div>
        </section>

        <footer :class="summaryContainerClass">
            <div :class="summaryCardClass">

                <h2 v-if="layoutMode === 'page'" class="text-lg font-bold text-heading mb-4 hidden lg:block">
                    訂單摘要
                </h2>

                <div class="flex items-center justify-between mb-4">
                    <span class="text-sm text-base-content">小計</span>
                    <span class="text-base font-semibold text-heading">{{ formatTwd(subtotal) }}</span>
                </div>

                <div v-if="layoutMode === 'page'" class="text-xs text-base-content/50 mb-4">
                    運費與優惠代碼將於結帳步驟計算。
                </div>

                <PrimaryButton @click="handleButtonClick" :class="layoutMode === 'drawer' ? 'mt-4 mb-2' : ''">
                    {{ layoutMode === 'drawer' ? '查看購物車' : '結帳' }}
                </PrimaryButton>
            </div>
        </footer>

    </div>
</template>

<script setup>
import axios from 'axios'
import { computed, inject } from 'vue'
import { router } from '@inertiajs/vue3'
import { useSharedCart } from '@/Composables/useSharedCart'
import QuantityStepper from '@/DaisyComponents/Front/QuantityStepper.vue'
import PrimaryButton from '@/DaisyComponents/Front/PrimaryButton.vue'



// 定義 Props，接收 layoutMode
const props = defineProps({
    layoutMode: {
        type: String,
        default: 'drawer', // 預設為側欄模式
        validator: (val) => ['drawer', 'page'].includes(val)
    }
})

const emit = defineEmits(['checkout', 'continue'])

const { cartItems } = useSharedCart()
const items = computed(() => cartItems.value?.items ?? [])
const subtotal = computed(() => cartItems.value?.subtotal ?? 0)

const handleQtyChange = async (item, newQty) => {
    await axios.patch(route('cart.update'), {
        product_option_id: item.product_option_id,
        qty: newQty,
    })
    await router.reload({ only: ['cartItems'], preserveScroll: true })
}

const delCartItem = async (productOptionId) => {
    await axios.delete(route('cart.destroy'), {
        data: { product_option_id: productOptionId },
    })
    await router.reload({ only: ['cartItems'], preserveScroll: true })
}

const formatTwd = (price) => `$ ${Number(price || 0).toLocaleString()}`



// 1. 最外層容器佈局
const containerClass = computed(() => {
    if (props.layoutMode === 'page') {
        return 'flex flex-col lg:grid lg:grid-cols-12 lg:gap-8 lg:items-start'
    }
    // Drawer 模式：這個 div 本身是 CartDrawer.vue 那個 <aside flex flex-col> 底下的一個 flex item，
    // 沒有 flex-1 的話它只會照內容自然高度撐開，商品一多整塊就會超出 aside 的 100vh 邊界，
    // 把 footer 一起頂到畫面外。flex-1 讓它吃滿剩餘高度，min-h-0 解除 flexbox 預設的
    // min-height:auto（否則裡面的 overflow-y-auto 商品列表沒辦法真正捲動，一樣會把整體撐高）
    return 'flex flex-col flex-1 min-h-0'
})

// 2. 列表區塊樣式
const listSectionClass = computed(() => {
    if (props.layoutMode === 'page') {
        return 'lg:col-span-8 space-y-3'
    }
    // Drawer 模式:佔滿剩餘空間並可滾動,footer 走一般 flex 排版貼在底部,不用 fixed
    // (fixed 定位曾經意外依賴 aside 本身常駐 translate-x-0 造成的 containing block 才會位置正確,
    // 換掉那個寫法後 fixed 會直接跳出去對齊整個 viewport,改成單純 flex-col 排版更穩)
    return 'flex-1 overflow-y-auto px-2 space-y-3 py-4 bg-surface-muted'
})

// 3. 摘要區塊容器樣式
const summaryContainerClass = computed(() => {
    if (props.layoutMode === 'page') {
        return 'lg:col-span-4 lg:sticky lg:top-24 mt-4 lg:mt-0'
    }
    // Drawer 模式:flex-col 容器裡的最後一塊,自然貼底,有上邊框
    return 'border-t border-base-300 px-4 py-3 bg-base-100'
})

// 4. 摘要區塊內部卡片樣式 (讓 Page 版比較像一張卡片)
const summaryCardClass = computed(() => {
    if (props.layoutMode === 'page') {
        return 'bg-surface-muted border border-base-300 rounded-xl p-6'
    }
    return 'space-y-2'
})

// 5. 單個商品 Grid 排版
const itemGridClass = computed(() => {
    return 'grid-cols-[auto_minmax(0,1fr)_auto_auto]'
})

// 6. 圖片大小控制
const imageSizeClass = computed(() => {
    if (props.layoutMode === 'page') {
        return 'w-20 h-20 sm:w-28 sm:h-28'
    }
    return 'w-20 h-20'
})

const handleButtonClick = () => {
    if (props.layoutMode === 'drawer') {
        emit('continue');
        router.visit(route('cart.index'))
    } else {
        emit('checkout')
    }
}

const continueShop = () => {
    if (props.layoutMode === 'drawer') {
        emit('continue');
    } else {
        window.location.href = route('products.index');
        // router.visit(route('products.index'))
    }
}
</script>