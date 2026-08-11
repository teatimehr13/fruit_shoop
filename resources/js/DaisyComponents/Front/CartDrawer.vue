<template>
  <Teleport to="body">
    <!-- 背景遮罩 -->
    <Transition enter-active-class="transition-opacity duration-300 ease-out" enter-from-class="opacity-0"
      leave-active-class="transition-opacity duration-200 ease-in" leave-to-class="opacity-0">
      <div v-if="open" class="fixed inset-0 z-40 bg-black/50 backdrop-blur-[2px]" @click="emit('close')" />
    </Transition>

    <!-- 抽屜本體:從右邊滑入 -->
    <Transition enter-active-class="transition-transform duration-300 ease-[cubic-bezier(.76,0,.24,1)]"
      enter-from-class="translate-x-full" leave-active-class="transition-transform duration-250 ease-[cubic-bezier(.76,0,.24,1)]"
      leave-to-class="translate-x-full">
      <aside v-if="open" class="fixed inset-y-0 right-0 z-50 w-full md:max-w-md bg-base-200 shadow-2xl flex flex-col">
        <header class="flex items-center justify-between px-5 py-4 border-b border-base-300 bg-base-100">
          <h2 class="text-lg font-semibold text-heading">
            購物車
            <span v-if="itemCount" class="text-primary">({{ itemCount }})</span>
          </h2>
          <button type="button" class="btn btn-ghost btn-circle btn-sm text-heading hover:text-primary transition-colors" @click="emit('close')">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75"
              stroke="currentColor" class="w-5 h-5">
              <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
            </svg>
          </button>
        </header>

        <CartContent @checkout="goCheckout" @continue="emit('close')" />
      </aside>
    </Transition>
  </Teleport>
</template>

<script setup>
import { computed } from 'vue'
import { router } from '@inertiajs/vue3'
import { useSharedCart } from '@/Composables/useSharedCart'
import CartContent from '@/DaisyComponents/Front/CartContent.vue'

const props = defineProps({
  open: { type: Boolean, required: true },
})

const emit = defineEmits(['close'])

const { cartItems } = useSharedCart()
const itemCount = computed(() => cartItems.value?.items?.length ?? 0)

const goCheckout = () => {
  router.visit(route('checkout.index'))
}
</script>
