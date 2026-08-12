<script setup>
// 共用的「主要動作」實心按鈕:功能性頁面裡畫面唯一該做的事(加入購物車、前往結帳、查看購物車...)用這顆。
// 跟 CtaButton.vue 的差異：CtaButton 是行銷/CTA 用的膠囊造型，這顆是功能頁面用的 4px 小圓角、
// 每個畫面只該出現一顆（其餘動作用 outline 按鈕降低視覺噪音）。
defineProps({
    as: { type: String, default: 'button' },
    type: { type: String, default: 'button' },
    disabled: { type: Boolean, default: false },
})
</script>

<template>
    <component :is="as" :type="as === 'button' ? type : undefined" :disabled="disabled"
        class="btn w-full py-3 bg-primary border-primary text-primary-content hover:bg-primary/90 rounded-[4px] transition-colors disabled:opacity-60 disabled:cursor-not-allowed">
        <slot />
    </component>
</template>
