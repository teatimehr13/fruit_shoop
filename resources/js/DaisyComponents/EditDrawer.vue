<script setup>
import { computed, inject, ref, watch } from 'vue'

const props = defineProps({
    editOpen: Boolean
})

const emit = defineEmits(['update:editOpen'])
</script>

<template>
        <div class="relative z-9999">
            <div :class="[editOpen ? 'pr-[560px]' : 'pr-0', 'transition-[padding] duration-300']">
                <!-- 你的表格 -->
                     <!-- <button class="btn" @click="emit('update:editOpen', !editOpen), ui?.toggleSidebar && ui.toggleSidebar()">Open
                    drawer</button> -->
                    <!-- <slot></slot> -->
                     
            </div>
    
            <!-- 右側 sheet：固定定位 + 平移 -->
            <aside class="fixed inset-y-0 right-0 w-[560px] bg-base-100 shadow-xl transition-transform duration-300"
                :class="editOpen ? 'translate-x-0' : 'translate-x-full'">
                <div class="p-4">
                    <button class="btn btn-ghost" @click="emit('update:editOpen', !editOpen)">關閉</button>
                    <!-- 表單內容 -->
                     <slot></slot>
                </div>
            </aside>
        </div>
</template>