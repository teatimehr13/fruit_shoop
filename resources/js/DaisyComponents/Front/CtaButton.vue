<script setup>
// 共用的主要 CTA 按鈕樣式,首頁 Hero/Promo/TopProducts/CTA 跟 /home-preview 共用。
// :as="'span'" 給那種按鈕本身包在 <a> 裡面、不能再嵌一個 <button> 的情境用。
defineProps({
    as: { type: String, default: 'button' },
})
</script>

<template>
    <component :is="as"
        class="btn bg-primary text-primary-content rounded-full px-8 font-medium tracking-[0.06em] hover:bg-accent transition-colors">
        <slot />
    </component>
</template>
