<script setup>
import { inject, ref, watch } from 'vue';
import { router } from '@inertiajs/vue3';
import axios from 'axios';

const props = defineProps({
    popularProducts: Object,
})

const openCart = inject('openCart')

const formatTwd = (price) => {
    return `$ ${price?.toLocaleString() || 0}`
}

// 選項面板:每個商品目前選中的 option id,預設用最低價的規格(productOptions 關聯本身已經照 price asc 排序)
const selectedOptions = ref({})
watch(() => props.popularProducts, (products) => {
    products?.forEach(p => {
        selectedOptions.value[p.id] = p.product_options?.[0]?.id
    })
}, { immediate: true })

const getSelectedOption = (p) => {
    return p.product_options?.find(opt => opt.id === selectedOptions.value[p.id])
}

const handleOptionChange = (p) => {
    getSelectedOption(p)
}

const selectToCartId = ref(null)
const toggleSelectCard = (id) => {
    selectToCartId.value = id || null
}

const submitToCart = async (optionId) => {
    await axios.post(route('cart.store'), {
        product_option_id: optionId,
        qty: 1,
    })

    await openCart()
    await router.reload({
        only: ['cartItems'],
        preserveScroll: true,
    })
}

const quickAddToCart = async (p) => {
    if (p.product_options?.length > 1) {
        // 有多個規格,顯示選擇面板
        toggleSelectCard(p.id)
    } else {
        await submitToCart(getSelectedOption(p)?.id)
    }
}

const confirmAddToCart = async (p) => {
    await submitToCart(getSelectedOption(p)?.id)
}
</script>

<template>
    <!-- 跟「我們的承諾」同一套標題樣式(英文小標+葉子裝飾 → 中文主標) -->
    <section class="px-4 pt-10 md:pt-14 pb-12 md:pb-14 lg:pb-20 max-w-layout-wide mx-auto text-center">
        <p class="flex items-center justify-center gap-2 text-sm text-primary my-3">
            <img src="/images/econis-ref/hero-leaf-1.png" alt="" class="w-4 h-4 object-contain">
            Top products
            <img src="/images/econis-ref/hero-leaf-2.png" alt="" class="w-4 h-4 object-contain">
        </p>
        <h2 class="text-2xl md:text-4xl font-medium tracking-wide text-heading mb-8 md:mb-12">
            人氣商品
        </h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6 text-left">
            <div v-for="p in popularProducts" :key="p.id"
                class="group relative overflow-hidden bg-base-100 border border-base-300 rounded-[20px] p-4">
                <!-- 圖片:連到商品頁 -->
                <a :href="route('products.show', p.slug)" class="aspect-square flex items-center justify-center mb-4">
                    <img :src="p.image" :alt="p.name"
                        class="max-w-[75%] max-h-[75%] object-contain transition-transform duration-500 group-hover:scale-105">
                </a>

                <h3 class="text-base md:text-lg font-medium mb-1 text-heading">{{ p.name }}</h3>
                <div class="flex items-center justify-between">
                    <p class="text-primary font-semibold">{{ formatTwd(getSelectedOption(p)?.price) }}</p>

                    <!-- 快速加入購物車 -->
                    <button type="button" @click="quickAddToCart(p)"
                        class="w-10 h-10 rounded-full flex items-center justify-center cursor-pointer transition-colors hover:bg-primary hover:text-primary-content">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007Z" />
                        </svg>
                    </button>
                </div>

                <!-- 規格選擇滑出面板 -->
                <div class="absolute left-0 bottom-0 w-full rounded-b-[20px] p-4 bg-base-100/95 transition-transform duration-500 z-10"
                    :class="selectToCartId === p.id ? 'translate-y-0' : 'translate-y-full'">
                    <div class="flex flex-col gap-3">
                        <div class="flex items-start justify-between gap-2">
                            <div class="text-sm font-medium leading-snug line-clamp-2 text-heading">{{ p.name }}</div>
                            <button type="button" class="w-5 h-5 options-close" @click="toggleSelectCard(null)">
                                <span></span>
                            </button>
                        </div>
                        <div>
                            <label class="text-xs text-base-content/60">選項</label>
                            <select
                                class="select-md border border-base-300 rounded-lg bg-base-100 px-3 py-2 w-full outline-none focus:border-primary appearance-none bg-select-arrow bg-no-repeat bg-right text-sm"
                                v-model="selectedOptions[p.id]" @change="handleOptionChange(p)">
                                <option v-for="opt in p.product_options" :key="opt.id" :value="opt.id">
                                    {{ opt.option_text }}
                                </option>
                            </select>
                        </div>
                        <button type="button" @click="confirmAddToCart(p)"
                            class="btn btn-sm w-full border-primary bg-transparent text-primary rounded-full hover:bg-primary hover:text-primary-content hover:border-primary transition-colors">
                            加入購物車 - {{ formatTwd(getSelectedOption(p)?.price) }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <a :href="route('products.index')"
            class="mt-10 md:mt-12 btn btn-outline border-primary text-primary rounded-full px-8 font-medium tracking-[0.06em] hover:bg-primary hover:text-primary-content transition-colors">
            查看全部商品 →
        </a>
    </section>
</template>

<style>
.bg-select-arrow {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='%236B7280'%3E%3Cpath fill-rule='evenodd' d='M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z' clip-rule='evenodd' /%3E%3C/svg%3E");
    background-size: 1.25rem;
    padding-right: 1.5rem;
}

.options-close {
    position: relative;
    cursor: pointer;
    border-radius: 50%;
    background: var(--color-primary);
    width: 1.25rem;
    height: 1.25rem;
    min-width: 1.25rem;
    flex-shrink: 0;
}

.options-close span {
    width: 55%;
    height: 2px;
    background-color: var(--color-base-100);
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}
</style>
