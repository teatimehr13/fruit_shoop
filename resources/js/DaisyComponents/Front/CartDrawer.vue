<template>
  <Teleport to="body">
    <div
      class="fixed inset-0 z-40 flex justify-end"
      :class="open ? 'pointer-events-auto' : 'pointer-events-none'"
    >
      <div
        class="absolute inset-0 bg-black/40 transition-opacity duration-100 ease-out"
        :class="open ? 'opacity-100' : 'opacity-0 delay-100'"
        @click="emit('close')"
      />

      <aside
        class="relative z-10 w-full md:max-w-xl h-full bg-[#fafafa] shadow-xl flex flex-col border-l border-[#f1f0ed] transition-transform duration-500 cubic-bezier"
        :class="open ? 'translate-x-0 delay-100' : 'translate-x-full'"
      >
        <header class="flex items-center justify-between px-4 py-3 border-b border-stone-300 bg-white">
          <h2 class="text-lg font-semibold text-[#67645e]">{{ hasItems ? '購物車' : '' }}</h2>
          <button
            type="button"
            class="btn btn-ghost btn-circle text-sm text-gray-500 hover:text-gray-800"
            @click="emit('close')"
          >
            ✕
          </button>
        </header>

        <CartContent
          @checkout="goCheckout"
          @continue="emit('close')"
        />
      </aside>
    </div>
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
const hasItems = computed(() => (cartItems.value?.items?.length ?? 0) > 0)

const goCheckout = () => {
  router.visit(route('checkout.index'))
}
</script>

<style scoped>
.cubic-bezier {
  transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
}
</style>
