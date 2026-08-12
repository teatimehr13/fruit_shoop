<script setup>
import { ref, computed, toRefs } from 'vue'
import { useHeroNavState } from '@/Composables/useHeroNavState'
import { useSharedCart } from '@/Composables/useSharedCart';

const navLinks = ref([
    // { name: 'product.index', label: '首頁' },
    { name: 'products.index', label: '所有商品' },
    { name: 'front.about.index', label: '關於我們' },
])

const isOpen = ref(false)

const toggleMenu = () => {
    isOpen.value = !isOpen.value
    document.body.style.overflow = isOpen.value ? 'hidden' : ''
}

// 手機選單連結清單,首頁 + navLinks 合併成一份,面板 template 只需要迴圈一次
const mobileNavLinks = computed(() => [
    { key: 'home', href: route('front.home.index'), label: '首頁' },
    ...navLinks.value.map(nav => ({ key: nav.name, href: route(nav.name), label: nav.label })),
])

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
const { isInHeroState } = useHeroNavState({ isInHero, isScrollingDown })

//cartdrawer 開關
const emit = defineEmits(['open-cart'])

//購物車資料
const { ItemsCount } = useSharedCart();

</script>

<template>
    <div>
        <header class="box-shadow fixed z-5 w-full translate-y-0 " :class="{
            'Header__hidden': props.isScrollingDown,
            'Header__sticky ': !props.isScrollingDown && !isInHeroState,
        }">
            <!-- desktop -->
            <nav
                class="px-4 mx-auto w-full relative hidden md:flex items-center justify-between sticky py-4 transition-all duration-700 ease-[cubic-bezier(.76,0,.24,1)] max-w-layout-wide">
                <ul class="flex flex-1">
                    <li v-for="nav in navLinks" class="mr-12">
                        <a :href="route(nav.name)" class="font-semibold hover:text-primary transition-colors"
                            :class="isInHeroState ? 'text-base-100' : 'text-heading'">{{ nav.label }}</a>
                    </li>
                </ul>

                <a class="flex-1 w-full" :href="route('front.home.index')">
                    <img class="h-14 m-auto" src="/images/logo/c3837bce-a01c-45e8-aa45-5b820428fe29.png" alt="vege">
                </a>
                <ul class="flex flex-1 justify-end gap-4">
                    <li class="mr-12">
                        <!-- <a v-if="$page.props.auth.user" :href="route('logout')" method="post" as="button"
                            class="text-heading font-semibold" :class="{ 'text-base-100': isInHeroState }">
                            登出
                        </a> -->
                        <!-- <Link v-if="$page.props.auth.user" :href="route('logout')" method="post" as="button"
                            class="text-heading font-semibold cursor-pointer"
                            :class="{ 'text-base-100': isInHeroState }">
                        登出
                        </Link> -->
                        <a v-if="$page.props.auth.user" :href="route('account.index')" method="post" as="button"
                            class="font-semibold cursor-pointer hover:text-primary transition-colors"
                            :class="isInHeroState ? 'text-base-100' : 'text-heading'">
                            會員中心
                        </a>
                        <a v-else :href="route('login')" class="font-semibold hover:text-primary transition-colors"
                            :class="isInHeroState ? 'text-base-100' : 'text-heading'">
                            登入會員
                        </a>

                    </li>
                    <li class="">
                        <a href="#" @click.prevent="emit('open-cart')" class="group font-semibold hover:text-primary transition-colors"
                            :class="isInHeroState ? 'text-base-100' : 'text-heading'">
                            購物車
                            <span v-if="ItemsCount" class="transition-colors"
                                :class="isInHeroState ? 'text-base-100 group-hover:text-primary' : 'text-primary'">
                                ({{ ItemsCount }})
                            </span>

                        </a>
                    </li>
                </ul>
            </nav>

            <!-- mobile -->
            <nav
                class="relative flex md:hidden w-full items-center justify-between sticky py-4 px-2 md:px-8 rounded-t-[12px] transition-all duration-300 ease-[cubic-bezier(.76,0,.24,1)]">
                <ul class="hidden md:flex md:flex-1">
                    <li v-for="nav in navLinks" class="mr-12">
                        <a :href="route(nav.name)" class="font-semibold text-heading">{{ nav.label }}</a>
                    </li>
                </ul>

                <div class="md:hidden flex flex-1 justify-start">
                    <button class="relative btn btn-ghost btn-circle hover:text-primary transition-colors"
                        :class="isInHeroState ? 'text-base-100' : 'text-heading'" @click="toggleMenu">
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
                        <a v-if="$page.props.auth.user" :href="route('account.index')"
                            class="btn btn-ghost btn-circle hover:text-primary transition-colors" :class="isInHeroState ? 'text-base-100' : 'text-heading'">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75"
                                stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>
                        </a>

                        <a v-else :href="route('login')" class="btn btn-ghost btn-circle hover:text-primary transition-colors"
                            :class="isInHeroState ? 'text-base-100' : 'text-heading'">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75"
                                stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>
                        </a>
                    </li>
                    <li class="relative">
                        <a href="#" class="btn btn-ghost btn-circle hover:text-primary transition-colors"
                            :class="isInHeroState ? 'text-base-100' : 'text-heading'" @click.prevent="emit('open-cart')">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75"
                                stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                            </svg>
                        </a>
                        <div v-if="ItemsCount > 0" class="absolute right-0 top-0 bg-primary rounded-full text-[10px] w-4 text-center">
                            <span class="text-white">
                                {{ ItemsCount }}
                            </span>
                        </div>
                    </li>
                </ul>

                <!-- 手機選單:offcanvas 抽屜,從漢堡圖示同一側(左邊)滑入,而不是原本從 header 下方往下展開的面板。
                     <Teleport to="body"> 是必要的:header 本身有 translate-y-0(transform),會讓子孫的
                     position:fixed 改成相對 header 定位而不是相對 viewport,傳送到 body 底下才能正確蓋滿整個畫面 -->
                <Teleport to="body">
                    <!-- 背景遮罩,點擊可關閉 -->
                    <Transition enter-active-class="transition-opacity duration-300 ease-out"
                        enter-from-class="opacity-0" leave-active-class="transition-opacity duration-200 ease-in"
                        leave-to-class="opacity-0">
                        <div v-if="isOpen" class="fixed inset-0 z-40 bg-black/50 backdrop-blur-[2px]"
                            @click="toggleMenu"></div>
                    </Transition>

                    <!-- 抽屜本體:從左邊滑入 -->
                    <Transition enter-active-class="transition-transform duration-300 ease-[cubic-bezier(.76,0,.24,1)]"
                        enter-from-class="-translate-x-full"
                        leave-active-class="transition-transform duration-250 ease-[cubic-bezier(.76,0,.24,1)]"
                        leave-to-class="-translate-x-full">
                        <div v-if="isOpen"
                            class="fixed inset-y-0 left-0 z-50 w-[80%] max-w-xs bg-base-100 shadow-2xl flex flex-col">
                            <div class="flex items-center justify-between px-5 py-4 border-b border-base-300">
                                <img class="h-8" src="/images/logo/c3837bce-a01c-45e8-aa45-5b820428fe29.png"
                                    alt="vege">
                                <button type="button" class="btn btn-ghost btn-circle btn-sm text-heading"
                                    @click="toggleMenu">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.75" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                            <TransitionGroup tag="nav" appear class="flex-1 overflow-y-auto p-3 space-y-1"
                                enter-active-class="transition-all duration-300 ease-out"
                                enter-from-class="opacity-0 -translate-x-3">
                                <a v-for="(link, idx) in mobileNavLinks" :key="link.key"
                                    class="mobile-nav-link flex items-center justify-between gap-2 px-4 py-3 rounded-xl text-base font-semibold text-heading hover:bg-primary/10 hover:text-primary active:scale-[0.98] transition-colors"
                                    :style="{ transitionDelay: `${idx * 0.05}s` }" :href="link.href">
                                    {{ link.label }}
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="2" stroke="currentColor" class="w-4 h-4 opacity-40">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                                    </svg>
                                </a>
                            </TransitionGroup>
                        </div>
                    </Transition>
                </Teleport>
            </nav>
        </header>
    </div>
</template>


<style>
/* 往上滑動的時候 */
.Header__sticky {
    /* padding: 1.25rem 0 0; */
    position: fixed;
    top: 0;
    color: var(--color-base-content);
    background: var(--color-base-100);
    transition: all .5s cubic-bezier(.76, 0, .24, 1), color 0s;
    box-shadow: var(--shadow-soft);
}

.Header__sticky nav {

    /* background-color: var(--color-base-200); */
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

/* 手機選單連結錯開進場動畫由 <TransitionGroup> + template 內聯 transitionDelay 處理,
   這裡只處理對動態敏感的使用者:關掉位移、只留淡入 */
@media (prefers-reduced-motion: reduce) {
    .mobile-nav-link {
        transition-property: opacity, background-color, color !important;
        transform: none !important;
    }
}

.header nav a,
.header nav button,
.header nav button span {
    color: var(--color-base-100);
    transition: all .5s cubic-bezier(.76, 0, .24, 1) 0s, color 1s;
}
</style>
