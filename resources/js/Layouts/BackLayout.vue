<script setup>
import { Link } from '@inertiajs/vue3';
import { provide, ref } from 'vue';
import Nav from '../DaisyComponents/Nav.vue';
import SideBar from '@/DaisyComponents/SideBar.vue';

//for 左欄位跨組件控制
const drawerSideOpen = ref(false); // 手機版：預設關閉 (false)
const isPinned = ref(true);        // 桌機版：預設開啟 (true)

const ui = {
    drawerSideOpen,
    openSidebar: () => (drawerSideOpen.value = true),
    closeSidebar: () => (drawerSideOpen.value = false),
    toggleSidebar: () => (drawerSideOpen.value = !drawerSideOpen.value),
    isPinned,
    // 手機版按鈕觸發
    toggleMobile: () => (drawerSideOpen.value = !drawerSideOpen.value),
    // 桌機版按鈕觸發
    toggleDesktop: () => (isPinned.value = !isPinned.value),
}
provide('backUI', ui)


</script>

<template>
    <Nav>
        <template #open_drawer>
            <!-- <button @click="ui.toggleDesktop" class="hidden lg:inline-flex btn btn-ghost">
                {{ isPinned ? '收起選單' : '展開選單' }}
            </button> -->
            <label for="my-drawer-3" aria-label="open sidebar" class="btn btn-square btn-ghost lg:hidden">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    class="inline-block h-6 w-6 stroke-current">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16">
                    </path>
                </svg>
            </label>
        </template>
    </Nav>

    <SideBar v-model="drawerSideOpen" :is-pinned="isPinned">
        <template #drawer_content>
            <slot />
        </template>
    </SideBar>

</template>