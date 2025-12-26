<script setup>
import { computed, inject, ref, watch } from 'vue'

const props = defineProps({
    editOpen: Boolean
})

const emit = defineEmits(['update:editOpen'])
</script>

<template>
    <div class="relative z-50">
        <!-- <div :class="[editOpen ? 'pr-[620px]' : 'pr-0', 'transition-[padding] duration-300']"></div> -->

        <!-- 右側 sheet：固定定位 + 平移 -->
        <aside
            class="fixed inset-y-0 right-0 w-full md:w-[660px] bg-base-100 shadow-xl transition-transform duration-300 overflow-x-auto"
            :class="editOpen ? 'translate-x-0' : 'translate-x-full'">
            <div class="">
                <div class="float-right p-4 pb-0">
                    <button class="btn btn-ghost btn-circle btn-sm" @click="emit('update:editOpen', !editOpen)">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <!-- 表單內容 -->
                <slot></slot>
            </div>
        </aside>
    </div>
</template>