<script setup>
import { router } from '@inertiajs/vue3'
import CartContent from '@/DaisyComponents/Front/CartContent.vue'
import FrontLayout from '@/Layouts/FrontLayout.vue';

defineOptions({
    layout: FrontLayout,
})

const goCheckout = () => router.visit(route('checkout.index'))
const goShop = () => router.visit(route('home')) // 你自己換成 products.index / shop.index
</script>

<template>
  <div class="max-w-[var(--max-w-layout-wide)] mx-auto px-4 py-6 mt-[68px] md:mt-[108px]">
    <h1 class="text-2xl font-semibold text-[#67645e] mb-4">購物車</h1>

    <div class="">
      <CartContent
        @checkout="goCheckout"
        @continue="goShop"
        layoutMode="page"
      />
    </div>
  </div>
</template>
