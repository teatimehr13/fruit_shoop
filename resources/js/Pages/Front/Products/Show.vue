<script setup>
import FrontLayout from '@/Layouts/FrontLayout.vue';
import { inject, ref } from 'vue'
import axios from 'axios';
defineOptions({
    layout: FrontLayout,
})

const props = defineProps({
    product: Object
})

console.log(props.product);



</script>

<template>
{{ product.name }}
</template>