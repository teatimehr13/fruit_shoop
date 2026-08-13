<script setup>
// 共用的「次要動作」outline 按鈕：跟 PrimaryButton.vue 是同一套按鈕階層的另一半，
// 一畫面盡量只留一顆 PrimaryButton（唯一主要動作），其餘動作都走這顆降低視覺噪音。
// tag 用來指定實際渲染的元素/元件（'button'、'a'，或直接傳入 Inertia 的 Link 元件本身），
// 命名跟 PrimaryButton 的 `as` 不同是刻意的：Link 元件自己就有一個 `as` prop（決定它要渲染成什麼標籤，
// 例如 as="button"），如果這顆也叫 as 會互相搶命名，外面沒辦法把 as="button" 傳給 Link。
defineProps({
    tag: { type: [String, Object], default: 'button' },
    type: { type: String, default: 'button' },
    disabled: { type: Boolean, default: false },
    width: { type: String, default: 'w-full' },
    size: { type: String, default: 'text-sm' },
})
</script>

<template>
    <!-- disabled 只在 tag 是 button 時綁定：<a> 沒有真正的 disabled DOM 屬性，Vue 會把它字面寫成
    disabled="false" 這個字串，而 DaisyUI 的 .btn[disabled] 只看屬性存不存在、不看值，會讓連結整個點不到 -->
    <component :is="tag" :type="tag === 'button' ? type : undefined"
        :disabled="tag === 'button' ? disabled : undefined"
        class="tracking-wide btn btn-sm py-3 border-primary text-primary hover:text-white rounded-[4px] hover:bg-primary transition-colors bg-white disabled:opacity-60 disabled:cursor-not-allowed"
        :class="[width, size]">
        <slot />
    </component>
</template>
