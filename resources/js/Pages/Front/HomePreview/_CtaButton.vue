<script setup>
// 共用的主要 CTA 按鈕樣式,跟 Hero 的「立即選購」統一。
// :as="'span'" 給那種按鈕本身包在 <a> 裡面、不能再嵌一個 <button> 的情境用。
defineProps({
    as: { type: String, default: 'button' },
})
</script>

<template>
    <component :is="as"
        class="btn bg-primary text-primary-content border-none rounded-full px-8 font-medium tracking-[0.06em] hover:bg-accent transition-colors">
        <slot />
    </component>
</template>
