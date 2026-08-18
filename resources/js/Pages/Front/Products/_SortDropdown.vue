<script setup>
import { ref, onMounted, onUnmounted } from 'vue';

const props = defineProps({
    options: Array,
    modelValue: Object,
})

const emit = defineEmits(['select'])

const isOpen = ref(false)
const rootRef = ref(null)

const toggle = () => {
    isOpen.value = !isOpen.value
}

const select = (opt) => {
    emit('select', opt)
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
            class="inline-flex items-center gap-2 px-4 py-2 border rounded-[4px] text-sm font-medium transition-colors"
            :class="isOpen ? 'border-primary text-primary' : 'border-base-300 text-heading hover:border-primary hover:text-primary'">
            {{ modelValue?.label }}
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                stroke="currentColor" class="w-3.5 h-3.5 transition-transform" :class="isOpen ? 'rotate-180' : ''">
                <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
            </svg>
        </button>

        <Transition enter-active-class="transition duration-150 ease-out" enter-from-class="opacity-0 -translate-y-1"
            leave-active-class="transition duration-100 ease-in" leave-to-class="opacity-0">
            <div v-if="isOpen"
                class="absolute right-0 top-full mt-2 w-44 bg-base-100 border border-base-300 rounded-2xl shadow-lg py-2 z-20">
                <ul class="flex flex-col">
                    <li v-for="(opt, idx) in options" :key="idx">
                        <button type="button" @click="select(opt)"
                            class="w-full flex items-center gap-2 px-4 py-2 text-sm transition-colors"
                            :class="opt.label == modelValue?.label ? 'text-primary font-medium' : 'text-base-content hover:bg-primary/10 hover:text-primary'">
                            {{ opt.label }}
                        </button>
                    </li>
                </ul>
            </div>
        </Transition>
    </div>
</template>
