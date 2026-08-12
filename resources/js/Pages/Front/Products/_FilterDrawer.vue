<script setup>
import { watch } from 'vue';

const props = defineProps({
    open: Boolean,
    categories: Array,
    activeCategory: String,
})

const emit = defineEmits(['close', 'select-category'])

// 跟 Nav.vue 手機選單同一套鎖 scroll 的做法
watch(() => props.open, (val) => {
    document.body.style.overflow = val ? 'hidden' : ''
})

const select = (name) => {
    emit('select-category', name)
    emit('close')
}
</script>

<template>
    <!-- 跟 Nav.vue 的手機 offcanvas 同一套機制：header 有 transform，fixed 子孫定位會跟著跑掉，
         Teleport 到 body 底下才能正確蓋滿整個畫面 -->
    <Teleport to="body">
        <Transition enter-active-class="transition-opacity duration-300 ease-out" enter-from-class="opacity-0"
            leave-active-class="transition-opacity duration-200 ease-in" leave-to-class="opacity-0">
            <div v-if="open" class="fixed inset-0 z-40 bg-black/50 backdrop-blur-[2px]" @click="emit('close')"></div>
        </Transition>

        <Transition enter-active-class="transition-transform duration-300 ease-[cubic-bezier(.76,0,.24,1)]"
            enter-from-class="-translate-x-full"
            leave-active-class="transition-transform duration-250 ease-[cubic-bezier(.76,0,.24,1)]"
            leave-to-class="-translate-x-full">
            <div v-if="open" class="fixed inset-y-0 left-0 z-50 w-[80%] max-w-xs bg-base-100 shadow-2xl flex flex-col">
                <div class="flex items-center justify-between px-5 py-4 border-b border-base-300">
                    <h2 class="text-lg font-medium text-heading">篩選</h2>
                    <button type="button" class="btn btn-ghost btn-circle btn-sm text-heading" @click="emit('close')">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75"
                            stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="flex-1 overflow-y-auto p-3">
                    <p class="px-3 pt-2 pb-1 text-xs font-medium tracking-[0.08em] text-base-content/50">產品分類</p>
                    <ul class="flex flex-col gap-1">
                        <li>
                            <a href="#" class="block w-full px-3 py-2.5 rounded-xl transition-colors"
                                :class="activeCategory == 'ALL'
                                    ? 'bg-primary/10 text-primary font-medium'
                                    : 'text-base-content hover:bg-primary/10 hover:text-primary'"
                                @click.prevent="select('ALL')">
                                全部
                            </a>
                        </li>
                        <li v-for="value in categories" :key="value.id">
                            <a href="#" class="block w-full px-3 py-2.5 rounded-xl transition-colors"
                                :class="value.name == activeCategory
                                    ? 'bg-primary/10 text-primary font-medium'
                                    : 'text-base-content hover:bg-primary/10 hover:text-primary'"
                                @click.prevent="select(value.name)">
                                {{ value.name }}
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>
