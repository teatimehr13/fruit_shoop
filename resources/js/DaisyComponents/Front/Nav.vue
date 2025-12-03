<script setup>
import { ref, onMounted, watch, onUnmounted, inject, computed, toRefs } from 'vue'
import { useHeroNavState } from '@/Composables/useHeroNavState'
import { useSharedCart } from '@/Composables/useSharedCart';
import { Link } from '@inertiajs/vue3';

const navLinks = ref([
    // { name: 'product.index', label: '首頁' },
    { name: 'products.index', label: '所有商品' },
    { name: 'products.index', label: '關於我們' },
])

const isOpen = ref(false)
const wrapperRef = ref(null)
const contentRef = ref(null)
const mobileNavRef = ref();
const showMenuHeader = ref(false);

const toggleMenu = () => {
    isOpen.value = !isOpen.value
    // console.log(isInHeroOverride.value);
    if (isOpen.value) {
        isInHeroOverride.value = false
        showMenuHeader.value = true;

    } else {
        isInHeroOverride.value = null
        showMenuHeader.value = false;
        customNav.value = false;
    }
}

watch(isOpen, (open) => {
    const wrapper = wrapperRef.value
    const content = contentRef.value
    const mobileNav = mobileNavRef.value
    if (!wrapper || !content || !mobileNav) return

    const headerHeight = mobileNav.scrollHeight
    const viewportHeight = window.innerHeight - headerHeight
    const contentHeight = content.scrollHeight

    // 至少佔滿螢幕
    const targetHeight = Math.max(viewportHeight, contentHeight)

    if (open) {
        wrapper.style.height = `${targetHeight}px`
        document.body.style.overflow = 'hidden'
    } else {
        wrapper.style.height = '0px'
        document.body.style.overflow = ''
    }

    // console.log(props.isScrollingDown);
    // console.log(isInHeroState.value);
})

const props = defineProps({
    isInHero: {
        type: Boolean,
        defalut: true
    },
    isScrollingDown: {
        type: Boolean,
    },
})

const { isInHero, isScrollingDown } = toRefs(props)
const { isInHeroState, isInHeroOverride } = useHeroNavState({ isInHero, isScrollingDown })

//cartdrawer 開關
const emit = defineEmits(['open-cart'])

//購物車資料
const { ItemsCount } = useSharedCart();

</script>

<template>
    <div>
        <header
            class="box-shadow fixed z-5 3xl:w-[calc(100%-80px)] lg:w-[calc(100%-64px)] w-[calc(100%-32px)] translate-y-0"
            :class="{
                'Header__hidden': props.isScrollingDown && !showMenuHeader,
                'Header__sticky ': !props.isScrollingDown && !isInHeroState || showMenuHeader,
                'header': isInHeroState && !showMenuHeader,
            }">
            <!-- desktop -->
            <nav
                class="px-4 w-full relative hidden md:flex items-center justify-between sticky py-4 px-8  rounded-t-[12px] transition-all duration-700 ease-[cubic-bezier(.76,0,.24,1)]">
                <ul class="flex flex-1">
                    <li v-for="nav in navLinks" class="mr-12">
                        <a :href="route(nav.name)" class="font-semibold text-[#67645e]"
                            :class="{ 'text-[#fff]': isInHeroState }">{{ nav.label }}</a>
                    </li>
                </ul>

                <a class="flex-1 w-full" :href="route('front.home.index')">
                    <img class="h-14 m-auto" src="/images/logo/c3837bce-a01c-45e8-aa45-5b820428fe29.png" alt="vege">
                </a>
                <ul class="flex flex-1 justify-end gap-4">
                    <li class="mr-12">
                        <!-- <a v-if="$page.props.auth.user" :href="route('logout')" method="post" as="button"
                            class="text-[#67645e] font-semibold" :class="{ 'text-[#fff]': isInHeroState }">
                            登出
                        </a> -->
                        <Link v-if="$page.props.auth.user" :href="route('logout')" method="post" as="button"
                            class="text-[#67645e] font-semibold cursor-pointer"
                            :class="{ 'text-[#fff]': isInHeroState }">
                        登出
                        </Link>
                        <a v-else :href="route('login')" class="text-[#67645e] font-semibold"
                            :class="{ 'text-[#fff]': isInHeroState }">
                            會員登入
                        </a>

                    </li>
                    <li class="mr-12">
                        <a href="#" @click.prevent="emit('open-cart')" class="text-[#67645e] font-semibold"
                            :class="{ 'text-[#fff]': isInHeroState }">
                            {{ ItemsCount ? `購物車(${ItemsCount})` : '購物車' }}
                        </a>
                    </li>
                </ul>
            </nav>

            <!-- mobile -->
            <nav ref="mobileNavRef"
                class="relative flex md:hidden w-full items-center justify-between sticky py-4 px-2 md:px-8 rounded-t-[12px] transition-all duration-300 ease-[cubic-bezier(.76,0,.24,1)]">
                <ul class="hidden md:flex md:flex-1">
                    <li v-for="nav in navLinks" class="mr-12">
                        <a :href="route(nav.name)" class="font-semibold text-[#67645e]">{{ nav.label }}</a>
                    </li>
                </ul>

                <div class="md:hidden flex flex-1 justify-start">
                    <button class="relative btn btn-ghost btn-circle text-[#67645e]" @click="toggleMenu">
                        <span class="absolute inset-0 flex items-center justify-center
                            duration-600 ease-out" :class="isOpen ? 'rotate-180 opacity-0' : 'rotate-0 opacity-100'">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75"
                                stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                            </svg>
                        </span>

                        <!-- X icon -->
                        <span class="absolute inset-0 flex items-center justify-center
                             duration-600 ease-out"
                            :class="isOpen ? 'rotate-0 opacity-100' : 'rotate-[-180deg] opacity-0'">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                            </svg>
                        </span>
                    </button>
                </div>

                <a class="flex-1 w-full" :href="route('front.home.index')">
                    <img class="h-10 m-auto" src="/images/logo/c3837bce-a01c-45e8-aa45-5b820428fe29.png" alt="vege">
                </a>
                <ul class="flex flex-1 justify-end gap-2">
                    <li class="">
                        <a href="" class="btn btn-ghost btn-circle text-[#67645e]">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75"
                                stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>
                        </a>
                    </li>
                    <li class="" >
                        <a href="#" class="btn btn-ghost btn-circle text-[#67645e]"  @click.prevent="emit('open-cart')">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75"
                                stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                            </svg>
                        </a>
                    </li>
                </ul>

                <!-- 手機選單外框 -->
                <div ref="wrapperRef" class="absolute left-0 right-0 z-50 overflow-hidden 
             transition-[height] duration-400 ease-[cubic-bezier(.76,0,.24,1)]" style="top: 71px; height: 0">
                    <div class="w-full h-full bg-white pb-8">
                        <div class="w-full h-full bg-[#f1f0ed] rounded-b-[12px] overflow-hidden relative flex flex-col">
                            <div ref="contentRef"
                                class="p-4 space-y-8 overflow-y-auto flex-1 content-center mt-[-72px]">
                                <!-- <a href="#" class="block text-lg font-semibold text-[#67645e] px-4 py-2 rounded-[8px] text-center">所有商品</a>
                                <a href="#" class="block text-lg font-semibold text-[#67645e] px-4 py-2 rounded-[8px] text-center">關於我們</a> -->
                                <a 
                                    class="block text-lg font-semibold text-[#67645e] px-4 py-2 rounded-[8px] text-center"
                                    :class="{ 'text-[#fff]': isInHeroState }" :href="route('front.home.index')">首頁</a>
                                <a v-for="nav in navLinks"
                                    class="block text-lg font-semibold text-[#67645e] px-4 py-2 rounded-[8px] text-center"
                                    :class="{ 'text-[#fff]': isInHeroState }" :href="route(nav.name)">{{ nav.label }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>
        </header>
    </div>
</template>


<style>
/* 往上滑動的時候 */
.Header__sticky {
    padding: 1.25rem 0 0;
    position: fixed;
    top: 0;
    color: #67645e;
    background: #fff;
    transition: all .5s cubic-bezier(.76, 0, .24, 1), color 0s;
}

.Header__sticky nav {
    background-color: #f1f0ed;
}

/* 往下滑的時，header加上，往上時則拿掉  */
.Header__hidden {
    transform: translateY(-150%);
    background: transparent;
    padding: 2.5vw 0 0;
    transition: all .5s cubic-bezier(.76, 0, .24, 1), color 0s;
    top: 0;
}

/* 滑動到最上面時  */
/* isInHeroState */
.header {
    background: transparent !important;
}

.header nav {
    transition: all .5s cubic-bezier(.76, 0, .24, 1) .3s, color .5s;

}

.header nav a,
.header nav button,
.header nav button span {
    color: #fff;
    transition: all .5s cubic-bezier(.76, 0, .24, 1) 0s, color 1s;
}
</style>
