<template>
    <div class="max-w-layout-wide mx-auto px-4 mt-[var(--spacing-header-space)]">
        <div class="pt-8 md:pt-10 pb-6 border-b border-base-300">
            <h1 class="text-2xl md:text-3xl font-medium text-heading">關於我們</h1>
            <Breadcrumb :items="breadcrumbItems" bare class="mt-2" />
        </div>
    </div>

    <section class="py-10 md:py-14 px-4 max-w-layout-wide mx-auto">
        <div class="flex flex-col md:flex-row gap-8 md:gap-12 items-start">
            <!-- 左側：圖片區域 -->
            <div class="w-full md:w-[45%]">
                <div class="aspect-[18/19] overflow-hidden">
                    <img :src="props.about.img_url" class="w-full h-full block">
                </div>
            </div>

            <!-- 右側：故事文字 -->
            <div class="w-full md:w-[55%] md:max-w-[560px]">
                <h2 class="text-2xl md:text-3xl font-medium text-heading mb-5">
                    {{ props.about.title }}
                </h2>

                <div class="about-content text-base text-base-content/80 leading-relaxed" v-html="props.about.content"></div>
            </div>
        </div>
    </section>
</template>

<script setup>
import { computed } from 'vue';
import FrontLayout from '@/Layouts/FrontLayout.vue';
import Breadcrumb from '@/DaisyComponents/Front/Breadcrumb.vue';

defineOptions({
    layout: FrontLayout
})

const props = defineProps({
    about: Object
})

const breadcrumbItems = computed(() => [
    { label: '首頁', href: route('front.home.index') },
    { label: '關於我們' },
])
</script>

<style scoped>
/* 後台富文本編輯器排版產生的內容：清掉空段落、統一段距 */
.about-content :deep(p),
.about-content :deep(.lead) {
    margin-bottom: 1.75rem;
}

.about-content :deep(p:last-child),
.about-content :deep(.lead:last-child) {
    margin-bottom: 0;
}

.about-content :deep(p:has(> br:only-child)) {
    display: none;
}

/* 對應後台編輯器的「Lead」「Small」文字大小工具 */
.about-content :deep(.lead) {
    font-size: 1.15em;
}

.about-content :deep(small) {
    font-size: 0.85em;
}
</style>