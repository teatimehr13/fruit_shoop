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
    <div class="md:hidden grid gap-4 px-2 md:px-10 pt-6">
        <div>
            <h1 class="text-2xl font-semibold ">
                Hi, {{ user.name }}
            </h1>
        </div>

        <div class="grid space-y-2 info border-t border-[#c4c4c4] pt-4">

            <div>
                <span class="text-lg font-semibold ">
                    個人資訊
                </span>
                <span class="member-cache ml-2 text-xs">一般會員</span>
            </div>

            <div class="mt-2">
                <label class="font-semibold">姓名</label>
                <span class="block mt-1">{{ user.name }}</span>
            </div>

            <div>
                <label class="font-semibold">Email</label>
                <span class="block mt-1">{{ user.email }}</span>
            </div>

            <div>
                <label class="font-semibold">電話</label>
                <span class="block mt-1">{{ user.phone }}</span>
            </div>

            <div>
                <label class="font-semibold">地址</label>
                <span class="block mt-1">{{ fullShippingAddress }}</span>
            </div>

            <div>
                <Link :href="route('account.profile')" :preserve-state="false"
                    class="tracking-wide btn btn-sm mt-4 mb-2 w-full py-3 border-[#82ae46] text-[#82ae46] hover:text-white rounded-[40px] hover:bg-[#82ae46] transition-colors w-full">
                    編輯個人資訊
                </Link>

            </div>
        </div>

        <div class="grid space-y-2 order border-t border-[#c4c4c4] pt-4">
            <div>
                <span class="text-lg font-semibold">
                    訂單
                </span>
            </div>

            <div>
                <!-- <button
                    class="tracking-wide btn mt-4 mb-2 w-full py-3 border-[#82ae46] text-[#82ae46] hover:text-white rounded-[40px] hover:bg-[#82ae46] transition-colors">
                    查看所有訂單
                </button> -->
                <Link :href="route('account.orders')" :preserve-state="false"
                    class="tracking-wide btn btn-sm mt-4 mb-2 w-full py-3 border-[#82ae46] text-[#82ae46] hover:text-white rounded-[40px] hover:bg-[#82ae46] transition-colors w-full">
                    查看所有訂單</Link>
            </div>
        </div>

        <div class="border-t border-[#c4c4c4]">
            <Link v-if="$page.props.auth.user" :href="route('logout')" method="post" as="button"
                class="tracking-wide btn btn-sm mt-8 mb-2 w-full py-3 border-[#82ae46] text-[#82ae46] hover:text-white rounded-[40px] hover:bg-[#82ae46] transition-colors bg-white"
                :class="{ 'text-[#fff]': isInHeroState }">
                登出
            </Link>
        </div>
    </div>

    <div class="hidden md:block px-10 pt-6">
        <h1 class="text-3xl font-semibold mb-4">Hi, {{ user.name }}</h1>

        <div class="grid space-y-2 info border-t border-[#c4c4c4] pt-6">
            <p class="member-cache">一般會員</p>

            <div class="mt-4">
                <label class="font-semibold">姓名</label>
                <span class="block mt-1">{{ user.name }}</span>
            </div>

            <div class="mt-2">
                <label class="font-semibold">Email</label>
                <span class="block mt-1">{{ user.email }}</span>
            </div>
            <div class="mt-2">
                <label class="font-semibold">加入日期</label>
                <span class="block mt-1">{{ createdDate }}</span>
            </div>
        </div>
    </div>

</template>
