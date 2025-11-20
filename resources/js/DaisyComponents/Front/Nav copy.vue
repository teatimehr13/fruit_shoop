<script setup>
import { ref } from 'vue';

//中間:首頁、特選商品、關於我們 
//最右邊:會員、購物車
const navLinks = ref([
    { name: 'product.index', label: '首頁' },
    { name: 'product.index', label: '所有商品' },
    { name: 'product.index', label: '關於我們' },


])


</script>

<template>
    <div class="drawer drawer-end">
        <input id="my-drawer-2" type="checkbox" class="drawer-toggle" />
        <div class="drawer-content flex flex-col">
            <!-- Navbar -->
            <div class="navbar bg-base-300 w-full">
                <div class="flex-none lg:hidden">
                    <label for="my-drawer-2" aria-label="open sidebar" class="btn btn-square btn-ghost">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            class="inline-block h-6 w-6 stroke-current">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </label>
                </div>
                <div class="mx-2 px-2 navbar-start whitespace-nowrap">Navbar Title</div>
                <div class="navbar-center justify-center">
                    <ul class="menu menu-horizontal custom-menu">
                        <li v-for="link in navLinks">
                            <a :href="route(link.name)">{{ link.label }}</a>
                        </li>
                    </ul>
                </div>
                <div class="navbar-end">
                    <button class="btn btn-ghost btn-circle">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </button>
                    <button class="btn btn-ghost btn-circle">
                        <div class="indicator">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            <span class="badge badge-xs badge-primary indicator-item"></span>
                        </div>
                    </button>
                </div>
            </div>
            <!-- Page content here -->
            <!-- Content -->
        </div>
        <div class="drawer-side">
            <label for="my-drawer-2" aria-label="close sidebar" class="drawer-overlay"></label>
            <ul class="menu bg-base-200 min-h-full w-80 p-4">
                <!-- Sidebar content here -->
                <li v-for="link in navLinks">
                    <a :href="route(link.name)">{{ link.label }}</a>
                </li>
            </ul>
        </div>
    </div>
</template>


<style>
.custom-menu {
    & :where(li:not(.menu-title, .disabled) > :not(ul, details, .menu-title):not(.menu-active, :active, .btn),
        li:not(.menu-title, .disabled) > details > summary:not(.menu-title):not(.menu-active, :active, .btn)) {
        position: relative;

        &::after {
            content: "";
            position: absolute;
            left: 50%;
            bottom: 3px;
            width: calc(100% - 16px);
            height: 2px;
            background-color: currentColor;
            transform-origin: center;
            transform: translateX(-50%) scaleX(0);
            opacity: 0;
            transition:
                transform 0.3s ease,
                opacity 0.3s ease;
        }
    }

    & :where(li:not(.menu-title, .disabled) > :not(ul, details, .menu-title):not(.menu-active, :active, .btn):hover,
        li:not(.menu-title, .disabled) > details > summary:not(.menu-title):not(.menu-active, :active, .btn):hover) {
        background-color: transparent !important;

        &::after {
            transform: translateX(-50%) scaleX(1);
            opacity: 1;
        }
    }
}
</style>