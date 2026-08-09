<template>
    <!-- <PageHero /> -->

    <section class="mt-[88px] mb-8 md:py-8 max-w-[var(--max-w-layout-wide)] mx-auto px-4">
        <!-- <h1 class="font-semibold text-xl">
            訂單詳情
        </h1> -->
        <div class="font-semibold text-lg px-4 py-3">訂單內容</div>
        <!-- Cart Items -->
        <div class="cart-items mt-2 md:mt-4">
            <div class="cart-items-wrapper">
                <!-- Header (md+) -->
                <div
                    class="hidden md:grid md:grid-cols-12 gap-4 px-4 py-3 text-sm text-gray-600 border-b border-base-300">
                    <div class="md:col-span-5">商品資料</div>
                    <div class="md:col-span-3 text-center">單件價格</div>
                    <div class="md:col-span-2 text-center">數量</div>
                    <div class="md:col-span-2 text-right">小計</div>
                </div>

                <div class="border-b border-base-300 px-4 py-4 md:py-5">
                    <div class="grid md:grid-cols-12 md:gap-4 border-b border-base-300 last:border-b-0 md:border-none py-4"
                        v-for="item in props.items" :key="item.id">

                        <!-- 商品資料 -->
                        <div class="col-span-12 md:col-span-5 flex gap-3 ">
                            <a href="/products/677f86d64710e0000c807154" target="_blank"
                                class="shrink-0 w-20 h-20 rounded-lg bg-gray-100 overflow-hidden">
                                <img :src="item.img_url" class="w-full h-full object-cover">
                            </a>

                            <div class="min-w-0">
                                <!-- promotion labels (可選) -->
                                <div class="mb-1 flex flex-wrap gap-2">
                                    <!-- <span class="inline-flex items-center rounded-full bg-gray-100 px-2 py-0.5 text-xs text-gray-700">加購品</span> -->
                                    <!-- <span class="inline-flex items-center rounded-full bg-gray-100 px-2 py-0.5 text-xs text-gray-700">贈品</span> -->
                                </div>

                                <div class="text-sm font-medium text-gray-900 line-clamp-2">
                                    {{ item.name }}
                                </div>

                                <div class="mt-1 text-xs text-gray-500">
                                    {{ item.option_text }}
                                </div>
                            </div>
                        </div>

                        <!-- 單件價格 -->
                        <div class="col-span-12 mt-3 text-right md:mt-0 md:col-span-3 md:text-center">
                            <!-- <div class="md:hidden text-xs text-gray-500 mb-1">單件價格</div> -->
                            <div class="text-sm font-semibold text-gray-900">
                                {{ formatTwd(item.price) }}
                            </div>
                            <!-- <div class="text-xs text-gray-400 line-through">NT$4,420</div> -->
                        </div>

                        <!-- 數量 -->
                        <div class="col-span-6 mt-3 flex md:mt-0 md:col-span-2 md:block md:text-center">
                            <div class="pr-1 text-sm text-gray-500 md:hidden">數量:</div>
                            <div class="text-sm text-gray-900">
                                {{ item.qty }}
                            </div>
                        </div>

                        <!-- 小計 -->
                        <div class="col-span-6 mt-3 text-right md:mt-0 md:col-span-2">
                            <!-- <div class="md:hidden text-xs text-gray-500 mb-1">小計</div> -->
                            <div class="text-sm font-semibold text-gray-900">
                                {{ formatTwd(item.line_total) }}
                            </div>
                        </div>

                    </div>
                </div>

            </div>

            <div class="border-b border-base-300 px-4 py-4 md:py-5">
                <div class="flex justify-end">
                    <div class="w-full md:w-[420px] space-y-3">
                        <div class="flex justify-between text-gray-700">
                            <span>小計</span>
                            <span>{{ formatTwd(subtotal) }}</span>
                        </div>
                        <div class="flex justify-between text-gray-700">
                            <span>運費</span>
                            <span>{{ formatTwd(shippingFee) }}</span>
                        </div>
                        <div class="flex justify-between font-bold">
                            <span>合計</span>
                            <span class="text-primary">{{ formatTwd(total) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="order-info mt-4 px-4 py-3">
            <div>
                <!-- <div class="grid md:grid-cols-2 gap-4">
                    <div class="">
                        <div class="font-semibold text-lg">訂單資訊</div>
                        <div class="mt-2 md:mt-4 grid grid-cols-[40%_60%]">
                            <div>訂單號碼</div>
                            <div>{{ props.order.order_number }}</div>
                        </div>
                        <div class="grid grid-cols-[40%_60%]">
                            <div>訂單Email</div>
                            <div>{{ props.order.shipping_email }}</div>
                        </div>
                        <div class="grid grid-cols-[40%_60%]">
                            <div>訂單日期</div>
                            <div>{{ createdDate }}</div>
                        </div>
                        <div class="grid grid-cols-[40%_60%]">
                            <div>訂單狀態</div>
                            <div>{{ props.order.order_status_label }}</div>
                        </div>
                    </div>
                    <div>
                        <div class="font-semibold text-lg">
                            顧客資訊
                        </div>
                        <div class="mt-2 md:mt-4 grid grid-cols-[40%_60%]">
                            <div>姓名</div>
                            <div>{{ props.order.recipient_name }}</div>
                        </div>
                        <div class="grid grid-cols-[40%_60%]">
                            <div>電話號碼</div>
                            <div>{{ props.order.recipient_phone }}</div>
                        </div>
                        <div class="grid grid-cols-[40%_60%]">
                            <div>送貨地址</div>
                            <div> {{ fullShippingAddress }} </div>
                        </div>
                    </div>
                </div> -->

                <div class="grid md:grid-cols-2">
                    <div class="border-b md:border-none border-base-300 pb-6 mb-6 md:border-b-0 md:border-r md:mb-0 md:pb-0 md:pr-6">
                        <div class="font-semibold text-lg">訂單資訊</div>

                        <div class="mt-2 md:mt-4 grid grid-cols-[40%_60%]">
                            <div class="text-gray-500">訂單號碼</div>
                            <div>{{ props.order.order_number }}</div>
                        </div>
                        <div class="mt-2 grid grid-cols-[40%_60%]">
                            <div class="text-gray-500">訂單Email</div>
                            <div class="break-all">{{ props.order.shipping_email }}</div>
                        </div>
                        <div class="mt-2 grid grid-cols-[40%_60%]">
                            <div class="text-gray-500">訂單日期</div>
                            <div>{{ createdDate }}</div>
                        </div>
                        <div class="mt-2 grid grid-cols-[40%_60%]">
                            <div class="text-gray-500">訂單狀態</div>
                            <div>{{ props.order.order_status_label }}</div>
                        </div>
                    </div>

                    <div class="md:pl-6">
                        <div class="font-semibold text-lg">顧客資訊</div>

                        <div class="mt-2 md:mt-4 grid grid-cols-[40%_60%]">
                            <div class="text-gray-500">姓名</div>
                            <div>{{ props.order.recipient_name }}</div>
                        </div>
                        <div class="mt-2 grid grid-cols-[40%_60%]">
                            <div class="text-gray-500">電話號碼</div>
                            <div>{{ props.order.recipient_phone }}</div>
                        </div>
                        <div class="mt-2 grid grid-cols-[40%_60%]">
                            <div class="text-gray-500">送貨地址</div>
                            <div>{{ fullShippingAddress }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</template>

<script setup>
import FrontLayout from '@/Layouts/FrontLayout.vue';
import PageHero from './_PageHero.vue';
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

const formatTwd = (price) => {
    return `$ ${price?.toLocaleString() || 0}`
}
// const 


console.log(props.order);
console.log(props.items);
console.log(props.subtotal);
console.log(props.shippingFee);
console.log(props.total);



</script>