<template>
    <div v-if="!items.length" class="flex-1 flex flex-col items-center justify-center px-6 py-10 text-center"
        :class="layoutMode === 'drawer' ? 'bg-[#fafafa]' : ''">
        <div class="text-lg font-semibold text-[#67645e]">你的購物車為空</div>
        <button type="button" @click="continueShop"
            class="btn mt-6 border-[#82ae46] text-[#82ae46] hover:bg-[#82ae46] hover:text-white rounded-[40px] w-30">
            去逛逛
        </button>
    </div>

    <div v-else :class="containerClass">

        <section :class="listSectionClass">
            <div v-for="item in items" :key="item.id ?? item.product_option_id"
                class="grid gap-3 p-3 bg-white border border-[#f1f0ed] rounded-xl items-center" :class="itemGridClass">
                <div class="flex-shrink-0 rounded bg-gray-100 overflow-hidden" :class="imageSizeClass">
                    <div v-if="item.img_url" class="w-full h-full">
                        <img :src="item.img_url" alt="" class="w-full h-full object-cover" />
                    </div>
                    <div v-else class="w-full h-full flex items-center justify-center text-xs text-gray-400">
                        圖片
                    </div>
                </div>

                <div class="flex flex-col justify-center min-w-0">
                    <div>
                        <div class="text-md font-semibold text-[#67645e] line-clamp-2">
                            {{ item.product_name }}
                        </div>
                        <div class="text-xs font-medium text-[#67645e] line-clamp-2">
                            {{ item.option_text }}
                        </div>
                    </div>
                    <!-- <div class="text-xs text-[#67645e] mt-2 block">
             {{ item.qty }} x {{ item.price < item.original_price ? formatTwd(item.price) : formatTwd(item.original_price) }}
          </div> -->
                    <div class="text-sm text-[#67645e] mt-2 md:mt-4">
                        {{ item.price < item.original_price ? formatTwd(item.price) : formatTwd(item.original_price) }}
                            </div>
                    </div>

                    <div class="flex justify-center">
                        <QuantityStepper :modelValue="item.qty" :min="1" :max="50"
                            @update:modelValue="val => handleQtyChange(item, val)" />
                    </div>

                    <div class="flex justify-end py-2 self-baseline">
                        <button type="button" class="btn btn-ghost btn-circle btn-xs text-xs"
                            @click="delCartItem(item.product_option_id)">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-5 text-[#67645e]">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 12H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                        </button>
                    </div>
                </div>
        </section>

        <footer :class="summaryContainerClass">
            <div :class="summaryCardClass">

                <h2 v-if="layoutMode === 'page'" class="text-lg font-bold text-[#67645e] mb-4 hidden lg:block">
                    訂單摘要
                </h2>

                <div class="flex items-center justify-between text-sm mb-4">
                    <span class="text-gray-600">小計</span>
                    <span class="font-semibold text-[#333]">{{ formatTwd(subtotal) }}</span>
                </div>

                <div v-if="layoutMode === 'page'" class="text-xs text-gray-500 mb-4">
                    運費與優惠代碼將於結帳步驟計算。
                </div>

                <button type="button" @click="handleButtonClick"
                    class="btn  w-full py-3 border-[#82ae46] text-[#82ae46] hover:text-white rounded-[40px] hover:bg-[#82ae46] transition-colors"
                    :class="layoutMode === 'drawer' ? 'mt-4 mb-2' : ''">
                    {{ layoutMode === 'drawer' ? '查看購物車' : '結帳' }}
                </button>
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
    // Drawer 模式：維持原本的 Flex Column
    return 'flex flex-col h-full'
})

// 2. 列表區塊樣式
const listSectionClass = computed(() => {
    if (props.layoutMode === 'page') {
        return 'lg:col-span-8 space-y-3'
    }
    // Drawer 模式：佔滿剩餘空間並可滾動
    return 'flex-1 overflow-y-auto px-2 space-y-3 py-4 bg-[#fafafa] mb-[190px]'
})

// 3. 摘要區塊容器樣式
const summaryContainerClass = computed(() => {
    if (props.layoutMode === 'page') {
        return 'lg:col-span-4 lg:sticky lg:top-24 mt-4 lg:mt-0'
    }
    // Drawer 模式：固定在底部，有上邊框
    return 'border-t border-stone-300 px-4 py-3 bg-white fixed w-full bottom-0'
})

// 4. 摘要區塊內部卡片樣式 (讓 Page 版比較像一張卡片)
const summaryCardClass = computed(() => {
    if (props.layoutMode === 'page') {
        return 'bg-gray-50 border border-gray-200 rounded-xl p-6'
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