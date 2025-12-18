<script setup>
import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
import FrontLayout from '@/Layouts/FrontLayout.vue';

defineOptions({
    layout: FrontLayout,
})

const page = usePage()
const user = computed(() => page.props.auth?.user)
console.log();

const props = defineProps({
    orders: Object
})

console.log(props.orders.data);

const fullShippingAddress = computed(() => {
    const city = user.value?.city ?? ''
    const district = user.value?.district ?? ''
    const detail = user.value?.address_detail ?? ''
    return [city, district, detail].filter(Boolean).join('')
})

const createdDate = computed(() => {
  const v = user.value?.created_at
  if (!v) return ''
  return String(v).slice(0, 10) // "2025-12-16"
})


</script>

<template>

    <section class="mt-[88px] py-8 max-w-[var(--max-w-layout-wide)] mx-auto px-4">
        <!-- mobile -->
        <div class="md:hidden">
            <div>
                <h1 class="text-2xl font-semibold mb-4">
                    Hi, {{ user.name }}
                </h1>
            </div>

            <div class="grid space-y-2 info border-t border-[#c4c4c4] py-8">

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
                    <button
                        class="tracking-wide btn mt-4 mb-2 w-full py-3 border-[#82ae46] text-[#82ae46] hover:text-white rounded-[40px] hover:bg-[#82ae46] transition-colors">
                        編輯個人資訊
                    </button>
                </div>
            </div>

            <div class="grid space-y-2 order border-t border-[#c4c4c4] pt-8">
                <div>
                    <span class="text-lg font-semibold">
                        訂單
                    </span>
                </div>

                <div>
                    <button
                        class="tracking-wide btn mt-4 mb-2 w-full py-3 border-[#82ae46] text-[#82ae46] hover:text-white rounded-[40px] hover:bg-[#82ae46] transition-colors">
                        查看所有訂單
                    </button>
                </div>
            </div>
        </div>

        <!-- desktop -->
        <div class="hidden md:grid grid-cols-[25rem_1fr] items-start">
            <div>
                <aside class="mr-8 p-4 ">
                    <ul class="accountSection-navigation">
                        <li
                            class="text-[#67645e] rounded-sm accountSection-navigation-item accountSection-navigation-item-active">
                            <a href="#" class="block w-full ">帳號總覽</a>
                        </li>
                        <li class="text-[#67645e] rounded-sm border-t border-[#c4c4c4] accountSection-navigation-item">
                            <a href="#" class="block w-full">個人資訊</a>
                        </li>
                        <li
                            class="text-[#67645e] rounded-sm border-t border-b border-[#c4c4c4] accountSection-navigation-item">
                            <a href="#" class="block w-full">訂單</a>
                        </li>
                    </ul>
                </aside>

            </div>
            <div>
                <div class="hidden md:block px-10 pt-6">
                    <div>
                        <h1 class="text-3xl font-semibold mb-4">
                            Hi, {{ user.name }}
                        </h1>
                    </div>

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

                        <!-- <div>
                            <label class="font-semibold">電話</label>
                            <span class="block mt-1">{{ user.phone }}</span>
                        </div> -->

                        <!-- <div>
                            <label class="font-semibold">地址</label>
                            <span class="block mt-1">{{ fullShippingAddress }}</span>
                        </div> -->
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<style>
.accountSection-navigation a {
    text-decoration: none;
    white-space: nowrap;
    padding: 1rem 1rem 1rem 0;
    width: 100%;
    display: block;
}

.accountSection-navigation a:after {
    content: "";
    position: absolute;
    border: 1px solid #c4c4c4;
    border-radius: 50%;
    width: 1.25rem;
    height: 1.25rem;
    top: 50%;
    right: 2rem;
    transform: translateY(-50%);
    transition: all .7s cubic-bezier(.76, 0, .24, 1);
    z-index: -1;
}

.accountSection-navigation-item-active a:after,
.accountSection-navigation-item:hover a:after {
    border-color: #82ae46;
    background: #82ae46;
}

.accountSection-navigation-item {
    cursor: pointer;
    position: relative;
    display: block;
    border-top: 1px solid #c4c4c4;
}

.member-cache {
    margin-bottom: 0px;
    /* font-size: 15px; */
    font-weight: 600;
    /* line-height: 21px; */
    color: rgb(255, 255, 255);
    padding: 2px 10px;
    border: 1px solid rgb(255, 255, 255);
    border-radius: 300px;
    background-color: #82ae46;
    display: inline-flex;
    -webkit-box-align: center;
    align-items: center;
    box-sizing: border-box;
    width: fit-content;
}
</style>