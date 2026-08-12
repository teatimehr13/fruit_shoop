<script setup>
defineProps({
    // [{ label, href? }] — 沒有 href 的視為目前所在頁面
    items: {
        type: Array,
        required: true,
    },
    // true：不帶外層底色/邊框/置頂間距，讓母層自己決定怎麼嵌（例如疊在 banner 標題下方）
    bare: {
        type: Boolean,
        default: false,
    },
})
</script>

<template>
    <nav aria-label="breadcrumb" :class="bare ? '' : 'mt-[var(--spacing-header-space)] bg-base-200/60 border-b border-base-300'">
        <div :class="bare ? '' : 'max-w-layout-wide mx-auto px-4 py-3'">
            <ol class="flex items-center flex-wrap gap-1.5 text-sm">
                <li v-for="(item, idx) in items" :key="idx" class="flex items-center gap-1.5">
                    <a v-if="item.href && idx !== items.length - 1" :href="item.href"
                        class="text-base-content/60 hover:text-primary transition-colors">
                        {{ item.label }}
                    </a>
                    <span v-else class="text-primary font-medium">{{ item.label }}</span>

                    <span v-if="idx !== items.length - 1" class="text-base-content/30">/</span>
                </li>
            </ol>
        </div>
    </nav>
</template>
