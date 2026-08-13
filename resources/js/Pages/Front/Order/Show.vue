<template>
    <section class="mt-[88px] mb-8 md:py-8 max-w-layout-wide mx-auto px-4">
        <h1 class="text-2xl md:text-3xl font-medium py-3 text-heading">訂單內容</h1>

        <div class="grid gap-4">

            <!-- 狀態橫幅 -->
            <div class="rounded-2xl px-6 py-5 flex flex-wrap items-center justify-between gap-4"
                :class="statusVariant.bg">
                <div>
                    <div class="text-xs font-bold uppercase tracking-wide" :class="statusVariant.text">訂單狀態</div>
                    <div class="text-xl font-bold text-heading mt-0.5">{{ order.order_status_label }}</div>
                </div>
                <div class="text-sm text-base-content text-right">
                    訂單號碼 {{ order.order_number }}<br>
                    {{ createdDate }}
                </div>
            </div>

            <!-- 商品明細 -->
            <div class="border border-base-300 rounded-2xl bg-base-100 p-6">
                <div class="text-base font-bold tracking-wide text-heading uppercase mb-4">商品明細</div>

                <div v-for="item in items" :key="item.id"
                    class="grid grid-cols-[80px_1fr_auto] gap-4 items-center py-3 border-b border-base-300 last:border-b-0">
                    <div class="w-20 h-20 rounded-lg bg-base-200 overflow-hidden flex-shrink-0">
                        <img v-if="item.img_url" :src="item.img_url" class="w-full h-full object-cover" alt="" />
                    </div>
                    <div class="min-w-0">
                        <div class="text-base font-medium text-heading line-clamp-2">{{ item.name }}</div>
                        <div class="text-sm text-base-content mt-0.5">{{ item.option_text }}</div>
                        <div class="text-sm text-base-content mt-0.5">{{ formatTwd(item.price) }} x {{ item.qty }}</div>
                    </div>
                    <div class="text-base text-right whitespace-nowrap">
                        {{ formatTwd(item.line_total) }}
                    </div>
                </div>
            </div>

            <!-- 金額摘要 -->
            <div class="border border-base-300 rounded-2xl bg-base-100 p-6 grid gap-1.5">
                <div class="flex justify-between">
                    <span class="text-sm">小計</span>
                    <span class="text-base">{{ formatTwd(subtotal) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm">運費</span>
                    <span class="text-base">{{ formatTwd(shippingFee) }}</span>
                </div>
                <div class="flex justify-between mt-1 pt-2 border-t border-base-300">
                    <span class="text-base font-bold text-heading">合計</span>
                    <span class="text-base font-bold text-primary">{{ formatTwd(total) }}</span>
                </div>
            </div>

            <!-- 訂單資訊 + 收件資訊 -->
            <div class="border border-base-300 rounded-2xl bg-base-100 p-6 grid md:grid-cols-2 gap-6 md:gap-12 lg:gap-18">
                <div>
                    <div class="text-base font-bold tracking-wide text-heading uppercase mb-4">訂單資訊</div>
                    <div class="grid gap-2.5">
                        <div class="flex justify-between gap-3">
                            <span class="text-[14px]">訂單 Email</span>
                            <span class="text-base text-right break-all">{{ order.shipping_email }}</span>
                        </div>
                        <div class="flex justify-between gap-3">
                            <span class="text-[14px]">付款方式</span>
                            <span class="text-base">{{ order.payment_method || '-' }}</span>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="text-base font-bold tracking-wide text-heading uppercase mb-4">收件資訊</div>
                    <div class="grid gap-2.5">
                        <div class="flex justify-between gap-3">
                            <span class="text-[14px]">收件人</span>
                            <span class="text-base">{{ order.recipient_name }}</span>
                        </div>
                        <div class="flex justify-between gap-3">
                            <span class="text-[14px]">電話</span>
                            <span class="text-base">{{ order.recipient_phone }}</span>
                        </div>
                        <div class="flex justify-between gap-3">
                            <span class="text-[14px]">地址</span>
                            <span class="text-base text-right break-words">{{ fullShippingAddress }}</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
</template>

<script setup>
import FrontLayout from '@/Layouts/FrontLayout.vue';
import { computed } from 'vue';

const props = defineProps({
    order: Object,
    items: Object,
    subtotal: Number,
    shippingFee: Number,
    total: Number,
});

defineOptions({
    layout: FrontLayout,
})

const order = computed(() => props.order)
const items = computed(() => props.items)
const subtotal = computed(() => props.subtotal)
const shippingFee = computed(() => props.shippingFee)
const total = computed(() => props.total)

const fullShippingAddress = computed(() => {
    const city = props.order?.shipping_city ?? ''
    const district = props.order?.shipping_district ?? ''
    const detail = props.order?.shipping_address_detail ?? ''
    return [city, district, detail].filter(Boolean).join('')
})

const createdDate = computed(() => {
    const v = props.order?.created_at
    if (!v) return ''
    return String(v).slice(0, 10) // "2025-12-16"
})

// 訂單狀態只有付款狀態，沒有出貨/配送追蹤，狀態橫幅顏色依付款狀態分三類：已付款/待處理/已取消
const statusVariant = computed(() => {
    const variants = {
        paid: { bg: 'bg-primary/10', text: 'text-primary' },
        waiting_for_the_transfer: { bg: 'bg-warning/20', text: 'text-warning-content' },
        not_selected_payment: { bg: 'bg-warning/20', text: 'text-warning-content' },
        cancelled: { bg: 'bg-error/10', text: 'text-error' },
    }
    return variants[props.order?.order_status] ?? { bg: 'bg-base-200', text: 'text-base-content' }
})

const formatTwd = (price) => {
    return `$ ${price?.toLocaleString() || 0}`
}
</script>
