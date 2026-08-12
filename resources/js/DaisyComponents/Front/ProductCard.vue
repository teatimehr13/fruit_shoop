<script setup>
import { inject, computed, ref } from 'vue';
import { router } from '@inertiajs/vue3';
import axios from 'axios';

const props = defineProps({
    product: Object,
})

const openCart = inject('openCart')

const formatTwd = (price) => {
    return `$ ${price?.toLocaleString() || 0}`
}

// Home 的 home() 回傳扁平 image 欄位，Products 的 index() 回傳 primary_image.img_url
const imageUrl = computed(() => props.product.image || props.product.primary_image?.img_url)

// product_options 關聯本身已經照 price asc 排序，預設選中最低價的規格
const selectedOptionId = ref(props.product.product_options?.[0]?.id)

const selectedOption = computed(() => {
    return props.product.product_options?.find(opt => opt.id === selectedOptionId.value)
})

const hasDiscount = computed(() => {
    const opt = selectedOption.value
    return !!opt?.original_price && opt.original_price > opt.price
})

const isPanelOpen = ref(false)
const togglePanel = (open) => {
    isPanelOpen.value = open
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

const quickAddToCart = async () => {
    if (props.product.product_options?.length > 1) {
        togglePanel(true)
    } else {
        await submitToCart(selectedOption.value?.id)
    }
}

const confirmAddToCart = async () => {
    await submitToCart(selectedOption.value?.id)
    togglePanel(false)
}
</script>

<template>
    <div class="group relative overflow-hidden bg-base-100 border border-base-300 rounded-[20px] p-4">
        <a :href="route('products.show', product.slug)" class="aspect-square flex items-center justify-center mb-4">
            <img :src="imageUrl" :alt="product.name"
                class="max-w-[75%] max-h-[75%] object-contain transition-transform duration-500 group-hover:scale-105">
        </a>

        <h3 class="text-base md:text-lg font-medium mb-1 text-heading">{{ product.name }}</h3>
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
                <p class="text-primary font-semibold">{{ formatTwd(selectedOption?.price) }}</p>
                <p v-if="hasDiscount" class="line-through text-base-content/50 text-xs sm:text-sm">
                    {{ formatTwd(selectedOption?.original_price) }}
                </p>
            </div>

            <!-- 快速加入購物車 -->
            <button type="button" @click="quickAddToCart"
                class="w-10 h-10 rounded-full flex items-center justify-center cursor-pointer transition-colors hover:bg-primary hover:text-primary-content">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007Z" />
                </svg>
            </button>
        </div>

        <!-- 規格選擇滑出面板:手機是貼螢幕底部的 fixed 選單,桌機以上才卡在卡片自己底部 -->
        <div class="fixed left-0 md:absolute bottom-0 w-full rounded-t-[20px] md:rounded-t-none md:rounded-b-[20px] px-4 pt-5 pb-6 md:p-4 bg-base-100 border border-base-300 md:border-0 transition-transform duration-500 z-10"
            :class="isPanelOpen ? 'translate-y-0' : 'translate-y-full'">
            <div class="flex flex-col gap-4 md:gap-3">
                <div class="flex items-start gap-3">
                    <div class="w-14 h-14 flex-shrink-0 rounded-lg overflow-hidden bg-base-200">
                        <img :src="imageUrl" :alt="product.name" class="w-full h-full object-cover">
                    </div>
                    <div class="flex-1 min-w-0 text-sm font-medium leading-snug line-clamp-2 text-heading pt-1">
                        {{ product.name }}
                    </div>
                    <button type="button" class="w-5 h-5 options-close flex-shrink-0 mt-1"
                        @click="togglePanel(false)">
                        <span></span>
                    </button>
                </div>
                <div>
                    <label class="text-xs text-base-content/60">選項</label>
                    <select
                        class="select-md border border-base-300 rounded-lg bg-base-100 px-3 py-2 w-full outline-none focus:border-primary appearance-none bg-select-arrow bg-no-repeat bg-right text-sm"
                        v-model="selectedOptionId">
                        <option v-for="opt in product.product_options" :key="opt.id" :value="opt.id">
                            {{ opt.option_text }}
                        </option>
                    </select>
                </div>
                <button type="button" @click="confirmAddToCart"
                    class="btn btn-sm w-full border-primary bg-transparent text-primary rounded-full hover:bg-primary hover:text-primary-content hover:border-primary transition-colors">
                    加入購物車 - {{ formatTwd(selectedOption?.price) }}
                </button>
            </div>
        </div>
    </div>

    <!-- 手機規格選單的背景遮罩,桌機是卡在卡片內的面板不需要 -->
    <Transition enter-active-class="transition-opacity duration-300 ease-out" enter-from-class="opacity-0"
        leave-active-class="transition-opacity duration-200 ease-in" leave-to-class="opacity-0">
        <div v-if="isPanelOpen" class="fixed inset-0 z-[5] bg-black/40 md:hidden" @click="togglePanel(false)"></div>
    </Transition>
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
