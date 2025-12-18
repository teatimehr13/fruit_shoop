<script setup>
import AccountLayout from '@/Layouts/AccountLayout.vue'
defineOptions({ layout: AccountLayout })

import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'

const user = computed(() => usePage().props.auth.user)
const createdDate = computed(() => {
  const v = user.value?.created_at
  if (!v) return ''
  return String(v).slice(0, 10) // "2025-12-16"
})
</script>


<template>

</template>
