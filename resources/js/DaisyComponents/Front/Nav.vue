<script setup>
import { ref, onMounted, watch, onUnmounted, inject, computed } from 'vue'

//中間:首頁、特選商品、關於我們 
//最右邊:會員、購物車
const navLinks = ref([
    // { name: 'product.index', label: '首頁' },
    { name: 'product.index', label: '所有商品' },
    { name: 'product.index', label: '關於我們' },
])

const isOpen = ref(false)
const wrapperRef = ref(null)
const contentRef = ref(null)
const mobileNavRef = ref();

const toggleMenu = () => {
    isOpen.value = !isOpen.value
    isInHeroOverride.value === false ? isInHeroOverride.value = true : isInHeroOverride.value = false;
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
    } else {
        wrapper.style.height = '0px'
    }
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

console.log(props.isInHero);

const isInHeroOverride = ref(null)

const isInHeroState = computed({
    get() {
        return isInHeroOverride.value ?? props.isInHero
    },
    set(val) {
        isInHeroOverride.value = val
    },
})

const forceInHero = (val) => {
    isInHeroOverride.value = val
}

const resetInHero = () => {
    isInHeroOverride.value = null 
}

// Debug
// watch(() => props.isInHero, (val) => {
//     console.log('Nav isInHero changed:', val)
// })
// watch(() => props.isScrollingDown, (val) => {
//     console.log('Nav isScrollingDown changed:', val)
// })

</script>

<template>
    <div>
        <header class="box-shadow fixed z-5 3xl:w-[calc(100%-80px)] lg:w-[calc(100%-64px)] w-[calc(100%-32px)]">
            <!-- desktop -->
            <nav class="bg-[#f1f0ed] px-4 w-full relative hidden md:flex items-center justify-between sticky py-4 px-8  rounded-t-[12px] transition-all duration-700 ease-[cubic-bezier(.76,0,.24,1)]"
                :class="{
                    '-translate-y-full': props.isScrollingDown,
                    'bg-transparent': isInHeroState,
                }">
                <ul class="flex flex-1">
                    <li v-for="nav in navLinks" class="mr-12">
                        <a :href="route(nav.name)" class="font-semibold text-[#67645e]"
                            :class="{ 'text-[#fff]': isInHeroState }">{{ nav.label }}</a>
                    </li>
                </ul>

                <a class="flex-1 w-full" href="">
                    <img class="h-14 m-auto" src="/images/logo/c3837bce-a01c-45e8-aa45-5b820428fe29.png" alt="vege">
                </a>
                <ul class="flex flex-1 justify-end gap-4">
                    <li class="mr-12">
                        <a href="" class="text-[#67645e] font-semibold" :class="{ 'text-[#fff]': isInHeroState }">
                            會員登入
                        </a>
                    </li>
                    <li class="mr-12">
                        <a href="" class="text-[#67645e] font-semibold" :class="{ 'text-[#fff]': isInHeroState }">
                            購物車
                        </a>
                    </li>
                </ul>
            </nav>

            <!-- mobile -->
            <nav ref="mobileNavRef"
                class="relative flex md:hidden w-full items-center justify-between sticky bg-[#f1f0ed] py-4 px-2 md:px-8 rounded-t-[12px] transition-all duration-700 ease-[cubic-bezier(.76,0,.24,1)]"
                :class="{
                    '-translate-y-full': props.isScrollingDown,
                    'bg-transparent': isInHeroState,
                }">
                <ul class="hidden md:flex md:flex-1">
                    <li v-for="nav in navLinks" class="mr-12">
                        <a :href="route(nav.name)" class="font-semibold text-[#67645e]">{{ nav.label }}</a>
                    </li>
                </ul>

                <div class="md:hidden flex flex-1 justify-start">
                    <button class="relative btn btn-ghost btn-circle text-[#67645e]" @click="toggleMenu"
                        :class="{ 'text-[#fff]': isInHeroState }">
                        <span class="absolute inset-0 flex items-center justify-center
                            transition-all duration-600 ease-out"
                            :class="isOpen ? 'rotate-180 opacity-0' : 'rotate-0 opacity-100'">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75"
                                stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                            </svg>
                        </span>

                        <!-- X icon -->
                        <span class="absolute inset-0 flex items-center justify-center
                            transition-all duration-600 ease-out"
                            :class="isOpen ? 'rotate-0 opacity-100' : 'rotate-[-180deg] opacity-0'">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                            </svg>
                        </span>
                    </button>
                </div>

                <a class="flex-1 w-full" href="">
                    <img class="h-10 m-auto" src="/images/logo/c3837bce-a01c-45e8-aa45-5b820428fe29.png" alt="vege">
                </a>
                <ul class="flex flex-1 justify-end gap-2">
                    <li class="">
                        <a href="" class="btn btn-ghost btn-circle text-[#67645e]" :class="{ 'text-[#fff]': isInHeroState }">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75"
                                stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>
                        </a>
                    </li>
                    <li class="">
                        <a href="" class="btn btn-ghost btn-circle text-[#67645e]" :class="{ 'text-[#fff]': isInHeroState }">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75"
                                stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                            </svg>
                        </a>
                    </li>
                </ul>

                <!-- 手機選單外框 -->
                <div ref="wrapperRef" class="absolute left-0 right-0 z-50 bg-[#f1f0ed] overflow-hidden
                    transition-[height] duration-500 ease-[cubic-bezier(.76,0,.24,1)]" style="top: 56px; height: 0">
                    <div ref="contentRef" class="p-4 space-y-4">
                        <a href="#" class="block">分類一</a>
                        <a href="#" class="block">分類二</a>
                        <a href="#" class="block">分類三</a>
                    </div>
                </div>
            </nav>

        </header>
    </div>
</template>


<style></style>

<!-- bg-[#f1f0ed] -->
<!-- text-[#67645e] -->

<!-- bg-transfer -->
<!-- text-fff -->