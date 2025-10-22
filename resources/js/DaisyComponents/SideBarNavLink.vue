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

const classes = computed(() =>
    props.active
        ? 'menu-active'
        : ''
);
</script>

<template>
    <Link :href="href">
    <li :class="classes" >
        <a>
            <slot />
        </a>
    </li>
    </Link>
</template>
