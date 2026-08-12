<script setup>
import AccountLayout from '@/Layouts/AccountLayout.vue'
defineOptions({ layout: AccountLayout })

import { computed, onMounted } from 'vue'
import { Link, usePage, router } from '@inertiajs/vue3'

const user = computed(() => usePage().props.auth.user);

onMounted(() => {
    if (sessionStorage.getItem('users_dirty') === '1') {
        sessionStorage.removeItem('users_dirty')
        router.reload({ only: ['auth'] })
    }
})

const createdDate = computed(() => {
    const v = user.value?.created_at
    if (!v) return ''
    return String(v).slice(0, 10) // "2025-12-16"
})

const fullShippingAddress = computed(() => {
    const city = user.value?.city ?? ''
    const district = user.value?.district ?? ''
    const detail = user.value?.address_detail ?? ''
    return [city, district, detail].filter(Boolean).join('')
})
</script>

<template>
    <!-- mobile -->
    <div class="md:hidden grid gap-4 px-2 pt-6">
        <div>
            <h1 class="text-2xl font-medium text-heading">
                Hi, {{ user.name }}
            </h1>
        </div>

        <div class="grid space-y-2 info border border-base-300 rounded-2xl bg-base-100 p-4">

            <div>
                <span class="text-lg font-medium text-heading">
                    個人資訊
                </span>
                <span class="member-cache ml-2 text-xs">一般會員</span>
            </div>

            <div class="mt-2">
                <label class="font-medium text-heading">姓名</label>
                <span class="block mt-1 text-base-content/80">{{ user.name }}</span>
            </div>

            <div>
                <label class="font-medium text-heading">Email</label>
                <span class="block mt-1 text-base-content/80">{{ user.email }}</span>
            </div>

            <div>
                <label class="font-medium text-heading">電話</label>
                <span class="block mt-1 text-base-content/80">{{ user.phone }}</span>
            </div>

            <div>
                <label class="font-medium text-heading">地址</label>
                <span class="block mt-1 text-base-content/80">{{ fullShippingAddress }}</span>
            </div>

            <div>
                <Link :href="route('account.profile')" :preserve-state="false"
                    class="tracking-wide btn btn-sm mt-4 mb-2 w-full py-3 border-primary text-primary hover:text-white rounded-[4px] hover:bg-primary transition-colors w-full">
                    編輯個人資訊
                </Link>

            </div>
        </div>

        <div class="grid space-y-2 order border border-base-300 rounded-2xl bg-base-100 p-4">
            <div>
                <span class="text-lg font-medium text-heading">
                    訂單
                </span>
            </div>

            <div>
                <Link :href="route('account.orders')" :preserve-state="false"
                    class="tracking-wide btn btn-sm mt-4 mb-2 w-full py-3 border-primary text-primary hover:text-white rounded-[4px] hover:bg-primary transition-colors w-full">
                    查看所有訂單</Link>
            </div>
        </div>

        <div>
            <Link v-if="$page.props.auth.user" :href="route('logout')" method="post" as="button"
                class="tracking-wide btn btn-sm w-full py-3 border-primary text-primary hover:text-white rounded-[4px] hover:bg-primary transition-colors bg-white">
                登出
            </Link>
        </div>
    </div>

    <div class="hidden md:block">
        <h1 class="text-3xl font-medium text-heading mb-4">Hi, {{ user.name }}</h1>

        <div class="grid space-y-2 info border border-base-300 rounded-2xl bg-base-100 p-6">
            <p class="member-cache">一般會員</p>

            <div class="mt-4">
                <label class="font-medium text-heading">姓名</label>
                <span class="block mt-1 text-base-content/80">{{ user.name }}</span>
            </div>

            <div class="mt-2">
                <label class="font-medium text-heading">Email</label>
                <span class="block mt-1 text-base-content/80">{{ user.email }}</span>
            </div>
            <div class="mt-2">
                <label class="font-medium text-heading">加入日期</label>
                <span class="block mt-1 text-base-content/80">{{ createdDate }}</span>
            </div>
        </div>
    </div>

</template>
