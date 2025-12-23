<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    href: {
        type: String,
        required: true,
    },
    active: {
        type: Boolean,
    },
});

const emit = defineEmits(['navigate'])

const onClick = (e) => {
    // 如果使用者是 Ctrl/Cmd 點開新分頁，就不要關 sidebar
    if (e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) return
    const isDesktop = window.matchMedia('(min-width: 1024px)').matches
    if (!isDesktop) emit('navigate')
    //   emit('navigate')
}

const classes = computed(() =>
    props.active
        ? 'menu-active'
        : ''
);
</script>

<template>
    <Link :href="href" @click="onClick">
        <li :class="classes">
            <a>
                <slot />
            </a>
        </li>
    </Link>
</template>
