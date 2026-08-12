<script setup>
import { ref, onMounted, onUnmounted } from 'vue';

const props = defineProps({
    categories: Array,
    activeCategory: String,
    totalCount: Number,
})

const emit = defineEmits(['select-category'])

const isOpen = ref(false)
const rootRef = ref(null)

const toggle = () => {
    isOpen.value = !isOpen.value
}

const select = (name) => {
    emit('select-category', name)
    isOpen.value = false
}

// 點面板以外的地方要自動關閉
const handleClickOutside = (e) => {
    if (isOpen.value && rootRef.value && !rootRef.value.contains(e.target)) {
        isOpen.value = false
    }
}

onMounted(() => document.addEventListener('click', handleClickOutside))
onUnmounted(() => document.removeEventListener('click', handleClickOutside))
</script>

<template>
    <div ref="rootRef" class="relative">
        <button type="button" @click="toggle"
            class="inline-flex items-center gap-2 px-4 py-2 border rounded-full text-sm font-medium transition-colors"
            :class="isOpen ? 'border-primary text-primary' : 'border-base-300 text-heading hover:border-primary hover:text-primary'">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75"
                stroke="currentColor" class="w-4 h-4">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M10.5 6h9.75M10.5 6a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0M3.75 6H7.5m9 12h3.75M16.5 18a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0m-9 0H12m-8.25-6H12m8.25 0h-3" />
            </svg>
            篩選
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                stroke="currentColor" class="w-3.5 h-3.5 transition-transform" :class="isOpen ? 'rotate-180' : ''">
                <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
            </svg>
        </button>

        <Transition enter-active-class="transition duration-150 ease-out" enter-from-class="opacity-0 -translate-y-1"
            leave-active-class="transition duration-100 ease-in" leave-to-class="opacity-0">
            <div v-if="isOpen"
                class="absolute left-0 top-full mt-2 w-56 bg-base-100 border border-base-300 rounded-2xl shadow-lg py-2 z-20">
                <p class="px-4 pt-1 pb-2 text-xs font-medium tracking-[0.08em] text-base-content/50">產品分類</p>
                <ul class="flex flex-col">
                    <li>
                        <button type="button" @click="select('ALL')"
                            class="w-full flex items-center justify-between gap-2 px-4 py-2 text-sm transition-colors"
                            :class="activeCategory == 'ALL' ? 'text-primary font-medium' : 'text-base-content hover:bg-primary/10 hover:text-primary'">
                            <span class="flex items-center gap-2">
                                <span class="w-3.5 h-3.5 rounded-full border flex-shrink-0"
                                    :class="activeCategory == 'ALL' ? 'border-primary bg-primary' : 'border-base-300'"></span>
                                全部
                            </span>
                            <span class="text-base-content/40">({{ totalCount }})</span>
                        </button>
                    </li>
                    <li v-for="value in categories" :key="value.id">
                        <button type="button" @click="select(value.name)"
                            class="w-full flex items-center justify-between gap-2 px-4 py-2 text-sm transition-colors"
                            :class="value.name == activeCategory ? 'text-primary font-medium' : 'text-base-content hover:bg-primary/10 hover:text-primary'">
                            <span class="flex items-center gap-2">
                                <span class="w-3.5 h-3.5 rounded-full border flex-shrink-0"
                                    :class="value.name == activeCategory ? 'border-primary bg-primary' : 'border-base-300'"></span>
                                {{ value.name }}
                            </span>
                            <span class="text-base-content/40">({{ value.products_count }})</span>
                        </button>
                    </li>
                </ul>
            </div>
        </Transition>
    </div>
</template>
