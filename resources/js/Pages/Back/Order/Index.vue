<script setup>
import Pagination from '@/DaisyComponents/Pagination.vue';
import BackLayout from '@/Layouts/BackLayout.vue';
import { Link, router, useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
defineOptions({ layout: BackLayout })



const props = defineProps({
    orders: Object,
    statusOptions: Object,
    filters: Object
})

console.log(props.orders);

const filterForm = useForm({
    q: props.filters?.q ?? '',
    status: props.filters?.status ?? '',
    range: props.filters?.range ?? '', // 你有做日期篩選就加
})

const orders = computed(() => {
    return props.orders.data
})

const handlePageChange = (page) => {
    const cleanFilters = Object.fromEntries(Object.entries(filterForm.data()).filter(([_, v]) => v !== '' && v !== null));
    // router.get(route('back.products.index'), {
    //     page: page,
    //     ...cleanFilters
    // }, {
    //     // preserveState: true,
    //     preserveScroll: true
    // })

    // console.log(filterForm.data());

    // return

    router.get(route('back.orders.index'), {
        page,
        ...cleanFilters
    }, {
        // preserveState: true,
        preserveScroll: true,
        replace: true
    })
}

const columns = [
    { key: 'order_number', label: '訂單號碼', width: 'w-[16%]' },
    { key: 'created_at', label: '訂單日期', width: 'w-[12%]', format: v => formatDate(v) },
    { key: 'recipient_name', label: '姓名', width: 'w-[10%]' },
    { key: 'shipping_email', label: 'Email', width: 'w-[18%]' },
    { key: 'recipient_phone', label: '電話號碼', width: 'w-[14%]' },
    { key: 'order_status_label', label: '訂單狀態', width: 'w-[10%]' },
    { key: 'amount', label: '合計', width: 'w-[10%]', format: v => formatTwd(v), align: 'text-right', wrap: 'whitespace-nowrap' },
    { key: '__actions', label: '', width: 'w-[10%]', type: 'actions', wrap: 'whitespace-nowrap' },
    // { label: '地址', value: (o) => `${o.shipping_city}${o.shipping_district}${o.shipping_address_detail}` }
]
const selectedTr = ref(null);

const formatDate = (v) => (v ? String(v).slice(0, 10) : '')
const formatTwd = (price) => {
    return `$ ${price?.toLocaleString() || 0}`
}
</script>

<template>
    <div class="relative">
        <div>
            <p class="text-[#1E2328] text-lg font-semibold">
                訂單
            </p>

            <div class="shadow bg-base-100 mt-6 px-6 py-5">
                <div class="my-4 flex gap-4 flex-wrap">
                    <label class="input input-sm w-full sm:w-60 md:w-68 lg:w-76">
                        <svg class="h-[1em] opacity-50" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <g stroke-linejoin="round" stroke-linecap="round" stroke-width="2.5" fill="none"
                                stroke="currentColor">
                                <circle cx="11" cy="11" r="8"></circle>
                                <path d="m21 21-4.3-4.3"></path>
                            </g>
                        </svg>
                        <input type="search" required placeholder="訂單號 / Email / 姓名 / 電話" v-model="filterForm.q"
                            @change="handlePageChange()" />
                    </label>

                    <select v-model="filterForm.range" @change="handlePageChange()"
                        class="select select-sm w-full sm:w-40 md:w-48 lg:w-56">
                        <option value="">全部時間</option>
                        <option value="today">今天</option>
                        <option value="7d">近 7 天</option>
                        <option value="30d">近 30 天</option>
                    </select>

                    <select v-model="filterForm.status" @change="handlePageChange()"
                        class="select select-sm w-full sm:w-40 md:w-48 lg:w-56">
                        <option value="">付款狀態</option>
                        <option v-for="(label, value) in statusOptions" :key="value" :value="value">
                            {{ label }}
                        </option>
                    </select>
                </div>
                <div class="overflow-x-auto rounded-box border border-base-content/5 bg-base-100 mt-8">
                    <table class="table w-full">
                        <colgroup>
                            <col v-for="c in columns" :key="c.key" :class="c.width" />
                        </colgroup>

                        <thead class="bg-[#fafbfc]">
                            <tr>
                                <th v-for="column in columns" :class="column.align">
                                    {{ column.label }}
                                </th>
                            </tr>
                        </thead>

                        <tbody v-if="orders.length">
                            <tr v-for="order in orders" :key="order.id"
                                :class="{ 'bg-stone-100': selectedTr === order.id }">
                                <td v-for="col in columns" :key="col.key" :class="[col.align, col.wrap]">
                                    <!-- {{ col.format ? col.format(order[col.key], order) : order[col.key] }} -->
                                    <template v-if="col.type === 'actions'">
                                        <Link :href="route('back.orders.show', order.order_number)"
                                            class="btn btn-sm">
                                        查看
                                        </Link>
                                    </template>

                                    <template v-else>
                                        {{ col.format ? col.format(order[col.key], order) : order[col.key] }}
                                    </template>
                                </td>
                            </tr>
                        </tbody>

                        <tbody v-else>
                            <tr>
                                <td :colspan="columns.length + 1" class="text-center text-sm text-base-content/60 py-8">
                                    沒有資料
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-4">
                <Pagination :pagination="props.orders" @change="handlePageChange" />
            </div>
        </div>
    </div>
</template>