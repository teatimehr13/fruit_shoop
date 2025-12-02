<template>
  <Teleport to="body">
    <div class="fixed inset-0 z-40 flex justify-end" :class="open ? 'pointer-events-auto' : 'pointer-events-none'">
      <div class="absolute inset-0 bg-black/40 transition-opacity duration-100 ease-out"
        :class="open ? 'opacity-100' : 'opacity-0 delay-100'" @click="emit('close')" />

      <aside
        class="relative z-10 w-full md:max-w-xl h-full bg-[#fafafa] shadow-xl flex flex-col border-l border-[#f1f0ed] transition-transform duration-500 cubic-bezier"
        :class="open ? 'translate-x-0 delay-100' : 'translate-x-full'">
        <header class="flex items-center justify-between px-4 py-3 border-b border-stone-300 bg-white">
          <h2 class="text-lg font-semibold text-[#67645e]">購物車</h2>
          <button type="button" class="btn btn-ghost btn-circle text-sm text-gray-500 hover:text-gray-800"
            @click="emit('close')">
            ✕
          </button>
        </header>

        <section class="flex-1 overflow-y-auto px-2 space-y-3 py-4 bg-[#fafafa]">
          <div v-for="item in cartItems.items" :key="item.id"
            class="flex gap-3 p-2 bg-white border border-[#f1f0ed] rounded-xl">
            <div class="w-20 h-20 flex-shrink-0 rounded bg-gray-100 overflow-hidden">
              <div v-if="item.img_url">
                <img :src="item.img_url" alt="">
              </div>
              <div v-else class="w-full h-full flex items-center justify-center text-xs text-gray-400">
                圖片
              </div>
            </div>

            <div class="flex-1 flex flex-col justify-between">
              <div>
                <div class="text-sm font-semibold text-[#67645e] line-clamp-2">
                  {{ item.product_name }}
                </div>
                <div class="text-sm font-medium text-[#67645e] line-clamp-2">
                  {{ item.option_text }}
                </div>
              </div>
              <div class="text-xs text-[#67645e] mt-1">
                {{ item.qty }} x NT${{ item.price < item.original_price ? item.price : item.original_price }} </div>
              </div>

              <QuantityStepper v-model="item.qty" :min="1" :max="50"
                @update:modelValue="val => handleQtyChange(item, val)" />


              <button type="button" class="btn btn-ghost btn-circle btn-xs self-start text-xs"
                @click="delCartItem(item.product_option_id)">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                  stroke="currentColor" class="size-6 text-[#67645e]">
                  <path stroke-linecap="round" stroke-linejoin="round"
                    d="M15 12H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>

              </button>
            </div>
        </section>

        <footer class="border-t border-stone-300 px-4 py-3 space-y-2 bg-white">
          <div class="flex items-center justify-between text-sm">
            <span class="text-gray-600">小計</span>
            <span class="font-semibold text-[#333]">NT$ {{ cartItems.subtotal }}</span>
          </div>
          <button type="button"
            class="btn btn-lg mt-4 mb-2 w-full py-3 bg-[#67645e] text-white rounded-[40px] hover:bg-[#5a5751] transition-colors">
            前往結帳
          </button>
        </footer>
      </aside>
    </div>
  </Teleport>
</template>

<script setup>
import { useSharedCart } from '@/Composables/useSharedCart';
import { usePage } from '@inertiajs/vue3';
import axios from 'axios';
import { computed, inject } from 'vue';
import QuantityStepper from '@/DaisyComponents/Front/QuantityStepper.vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({
  open: {
    type: Boolean,
    required: true,
  },
})

const emit = defineEmits(['close', 'remove'])

//cartdrawer 開關
// const open = inject('isCartOpen');
const { cartItems } = useSharedCart()
console.log(cartItems.value);


const handleQtyChange = async (item, newQty) => {
  console.log(item);
  const res = await axios.patch(route('cart.update'), {
    product_option_id: item.product_option_id,
    qty: newQty,
  })

  await router.reload({
    only: ['cartItems'], // 對應 share 的 key
    preserveScroll: true,
  })
}

const delCartItem = async (id) => {
  console.log(id);
  const res = await axios.delete(route('cart.destroy'), {
    data: {
      product_option_id: id,
    },
  })

  console.log(res);

  await router.reload({
    only: ['cartItems'],
    preserveScroll: true,
  })
}



</script>

<style scoped>
.cubic-bezier {
  transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
}
</style>