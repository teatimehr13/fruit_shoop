<script setup>
import { useSharedCart } from '@/Composables/useSharedCart';
import axios from 'axios';
import { computed, inject } from 'vue';
import { router } from '@inertiajs/vue3'

const props = defineProps({

})

const { cartItems } = useSharedCart()
console.log(cartItems.value);

const formatTwd = (price) => {
    const n = Number(price ?? 0)
    return `$ ${n.toLocaleString()}`
}

const singleItemSubtotal = (qty, itemPrice) => {
    return Number(qty) * Number(itemPrice);
}
</script>

<template>
    <div class="relative w-full flex flex-col">
        <section class="flex-1 overflow-y-auto space-y-3">
            <div class="space-y-3">
                <div class="block md:hidden">
                    <h1 class="text-xl font-semibold text-heading">訂單商品</h1>
                </div>
                <div v-for="item in cartItems.items" :key="item.id"
                    class="flex gap-4 p-3 bg-base-100 rounded-lg border border-base-200 hover:border-base-300 transition-colors">
                    <!-- 商品圖片 + 數量標記 -->
                    <div class="relative w-16 h-16 flex-shrink-0 self-center">
                        <div class="w-full h-full rounded overflow-hidden bg-base-200">
                            <img v-if="item.img_url" :src="item.img_url" :alt="item.product_name"
                                class="w-full h-full object-cover">
                            <div v-else class="w-full h-full flex items-center justify-center text-xs text-base-content">
                                無圖片
                            </div>
                        </div>

                        <!-- 數量標記 -->
                        <div
                            class="absolute -right-2 -top-2 min-w-[22px] h-[22px] px-1.5 bg-primary text-white text-xs font-bold rounded-md flex items-center justify-center shadow-md border-1 border-base-100">
                            {{ item.qty }}
                        </div>
                    </div>

                    <!-- 商品資訊 -->
                    <div class="flex-1 min-w-0 self-center">
                        <h3 class="text-base font-semibold text-heading line-clamp-1 mb-0.5">
                            {{ item.product_name }}
                        </h3>
                        <p class="text-xs text-base-content">
                            {{ item.option_text }}
                        </p>
                    </div>

                    <!-- 價格 -->
                    <div class="self-center text-base font-semibold text-heading whitespace-nowrap">
                        {{ formatTwd(item.subtotal) }}
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-y-1.5 gap-x-4 mt-8">
                    <div>
                        <span class="text-sm text-base-content">小計</span>
                    </div>
                    <div class="justify-self text-right">
                        <span v-if="cartItems.subtotal" class="text-base text-heading">
                            {{ formatTwd(cartItems.subtotal) }}
                        </span>
                    </div>
                    <div>
                        <span class="text-sm text-base-content">運費</span>
                    </div>
                    <div class="justify-self text-right">
                        <span v-if="cartItems.subtotal" class="text-base text-heading">
                            {{ formatTwd(cartItems.shipping_fee) }}
                        </span>
                    </div>
                    <div>
                        <span class="text-base font-bold text-heading">合計</span>
                    </div>
                    <div class="justify-self text-right">
                        <span class="text-base font-bold text-primary">
                            {{ cartItems.subtotal ? formatTwd(cartItems.subtotal + cartItems.shipping_fee) : null }}
                        </span>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>