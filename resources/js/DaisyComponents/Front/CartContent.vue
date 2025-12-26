<template>
    <template v-if="items.length">
        <section class="flex-1 overflow-y-auto px-2 space-y-3 py-4 bg-[#fafafa]">
            <div v-for="item in items" :key="item.id ?? item.product_option_id"
                class="grid grid-cols-[auto_minmax(0,1fr)_auto_auto] gap-3 p-2 bg-white border border-[#f1f0ed] rounded-xl">
                <div class="w-20 h-20 flex-shrink-0 rounded bg-gray-100 overflow-hidden">
                    <div v-if="item.img_url">
                        <img :src="item.img_url" alt="" class="w-full h-full object-cover" />
                    </div>
                    <div v-else class="w-full h-full flex items-center justify-center text-xs text-gray-400">
                        圖片
                    </div>
                </div>

                <div class="flex flex-col justify-center">
                    <div>
                        <div class="text-sm font-semibold text-[#67645e] line-clamp-2">
                            {{ item.product_name }}
                        </div>
                        <div class="text-sm font-medium text-[#67645e] line-clamp-2">
                            {{ item.option_text }}
                        </div>
                    </div>

                    <div class="text-xs text-[#67645e] mt-2">
                        {{ item.qty }} x
                        {{ item.price < item.original_price ? formatTwd(item.price) : formatTwd(item.original_price) }}
                    </div>
                </div>

                <QuantityStepper :modelValue="item.qty" :min="1" :max="50"
                    @update:modelValue="val => handleQtyChange(item, val)" />

                <div>
                    <button type="button" class="btn btn-ghost btn-circle btn-xs text-xs"
                        @click="delCartItem(item.product_option_id)">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6 text-[#67645e]">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 12H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                    </button>
                </div>
            </div>
        </section>

        <footer class="border-t border-stone-300 px-4 py-3 space-y-2 bg-white">
            <div class="flex items-center justify-between text-sm">
                <span class="text-gray-600">小計</span>
                <span class="font-semibold text-[#333]">{{ formatTwd(subtotal) }}</span>
            </div>

            <button type="button" @click="emit('checkout')"
                class="btn btn-lg mt-4 mb-2 w-full py-3 border-[#82ae46] text-[#82ae46] hover:text-white rounded-[40px] hover:bg-[#82ae46] transition-colors">
                前往結帳
            </button>
        </footer>
    </template>

    <div v-else class="flex-1 flex flex-col items-center justify-center px-6 py-10 text-center bg-[#fafafa]">
        <div class="text-lg font-semibold text-[#67645e]">你的購物車為空</div>
        <button type="button" @click="emit('continue')"
            class="btn mt-6 border-[#82ae46] text-[#82ae46] hover:bg-[#82ae46] hover:text-white rounded-[40px] w-30">
            去逛逛
        </button>
    </div>
</template>

<script setup>
import axios from 'axios'
import { computed } from 'vue'
import { router } from '@inertiajs/vue3'
import { useSharedCart } from '@/Composables/useSharedCart'
import QuantityStepper from '@/DaisyComponents/Front/QuantityStepper.vue'

const emit = defineEmits(['checkout', 'continue'])

const { cartItems } = useSharedCart()

const items = computed(() => cartItems.value?.items ?? [])
const subtotal = computed(() => cartItems.value?.subtotal ?? 0)

const handleQtyChange = async (item, newQty) => {
    await axios.patch(route('cart.update'), {
        product_option_id: item.product_option_id,
        qty: newQty,
    })

    await router.reload({
        only: ['cartItems'],
        preserveScroll: true,
    })
}

const delCartItem = async (productOptionId) => {
    await axios.delete(route('cart.destroy'), {
        data: { product_option_id: productOptionId },
    })

    await router.reload({
        only: ['cartItems'],
        preserveScroll: true,
    })
}

const formatTwd = (price) => {
    return `$ ${Number(price || 0).toLocaleString()}`
}
</script>
