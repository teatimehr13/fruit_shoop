<script setup>
import AccountLayout from '@/Layouts/AccountLayout.vue'
defineOptions({ layout: AccountLayout })

import { computed, onMounted } from 'vue'
import { Link, usePage, router } from '@inertiajs/vue3'
import OutlineButton from '@/DaisyComponents/Front/OutlineButton.vue'




const user = computed(() => usePage().props.auth.user)

const props = defineProps({
  orders: Object
});

const { data } = props.orders;

//載入頁面時刷新資料
onMounted(() => {
  let times = 0
  const timer = setInterval(() => {
    router.reload({ only: ['orders'], preserveScroll: true })
    times++
    if (times >= 2) clearInterval(timer)
  }, 2000)
})


const formatDate = (v) => (v ? String(v).slice(0, 10) : '');

const formatTwd = (price) => {
  return `$ ${price?.toLocaleString() || 0}`
}


function retryPayment(orderNumber) {
  window.location.href = `/payment/retry/${orderNumber}`
}
</script>


<template>
  <div class="order-info px-2 md:px-0 pt-6  items-center grid gap-4">
    <!-- desktop -->
    <div class="mb-2 md:hidden ">
      <Link :href="route('account.index')" class="inline-flex text-heading">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4 self-center mr-1">
          <path fill-rule="evenodd"
            d="M9.53 2.47a.75.75 0 0 1 0 1.06L4.81 8.25H15a6.75 6.75 0 0 1 0 13.5h-3a.75.75 0 0 1 0-1.5h3a5.25 5.25 0 1 0 0-10.5H4.81l4.72 4.72a.75.75 0 1 1-1.06 1.06l-6-6a.75.75 0 0 1 0-1.06l6-6a.75.75 0 0 1 1.06 0Z"
            clip-rule="evenodd" />
        </svg>
        <span>
          返回會員中心
        </span>
      </Link>
    </div>
    <h1 class="text-2xl md:text-3xl font-medium text-heading">訂單</h1>
    <div class="hidden md:grid grid-cols-[2fr_1fr_1fr_1.5fr_.75fr] border-t border-base-300 pt-6">
      <div class="contents font-medium text-heading">
        <div class="p-3">訂單號碼</div>
        <div class="p-3">日期</div>
        <div class="p-3">合計</div>
        <div class="p-3">訂單狀態</div>
        <div class="p-3"></div>

        <div class="col-span-5 h-px bg-base-300"></div>
      </div>

      <div v-for="row in data" :key="row.id" class="contents">
        <div class="p-3">{{ row.order_number }}</div>
        <div class="p-3">{{ formatDate(row.created_at) }}</div>
        <div class="p-3">{{ formatTwd(row.amount) }}</div>
        <div class="p-3">{{ row.order_status_label }}
          <div v-if="row.is_payment_pending" class="inline">
            <div v-if="!row.is_payment_expired" class="inline">
              <a :href="route('payment.retry', row.order_number)" class="text-primary text-sm underline ">重新付款</a>
              <div v-if="row.payment_expire_at_label" class="text-sm text-base-content/50">
                (繳費期限：{{ row.payment_expire_at_label }})
              </div>
            </div>
            <div v-else class="text-sm text-base-content/50">
              (訂單已取消)
            </div>
          </div>
        </div>
        <div class="p-3 text-center">
          <OutlineButton tag="a" :href="route('order.show', row.order_number)" width="w-16" size="text-xs">
            查看
          </OutlineButton>
        </div>
      </div>
    </div>

    <!-- mobile -->
    <div class="space-y-4 md:hidden">
      <div class="border-t border-base-300 pt-4"></div>
      <div v-for="row in data" :key="row.id" class="border border-base-300 rounded-xl p-4 bg-base-100">
        <div class="space-y-2 text-sm">
          <div class="flex justify-between gap-3">
            <div class="text-heading font-medium">訂單號碼</div>
            <div class=" text-right break-all">{{ row.order_number }}</div>
          </div>

          <div class="flex justify-between gap-3">
            <div class="text-heading font-medium">日期</div>
            <div class="">{{ formatDate(row.created_at) }}</div>
          </div>

          <div class="flex justify-between gap-3">
            <div class="text-heading font-medium">合計</div>
            <div class="">{{ formatTwd(row.amount) }}</div>
          </div>

          <div class="flex justify-between gap-3">
            <div class="text-heading font-medium">訂單狀態</div>
            <div class="">
              <div class="flex flex-col items-end text-right">
                <div class="text-sm">
                  {{ row.order_status_label }}
                </div>

                <div v-if="row.is_payment_pending" class="mt-1 flex flex-col items-end gap-0.5">
                  <template v-if="!row.is_payment_expired">
                    <a :href="route('payment.retry', row.order_number)"
                      class="text-sm text-primary underline underline-offset-2 hover:opacity-80">
                      重新付款
                    </a>

                    <div v-if="row.payment_expire_at_label" class="text-xs text-base-content/50">
                      (繳費期限：{{ row.payment_expire_at_label }})
                    </div>
                  </template>

                  <div v-else class="text-xs text-base-content/50">
                    (訂單已取消)
                  </div>
                </div>
              </div>

            </div>

          </div>
        </div>

        <div class="mt-4">
          <OutlineButton tag="a" :href="route('order.show', row.order_number)">
            查看
          </OutlineButton>
        </div>
      </div>
    </div>

    <div class="mx-auto mt-4 text-sm tracking-wider">
      僅顯示2年內訂單
    </div>
  </div>
</template>
