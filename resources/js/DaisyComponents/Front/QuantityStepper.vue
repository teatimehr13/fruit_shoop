<template>
  <div class="inline-flex items-center">
    <div class="rounded-full border border-base-300 bg-base-100 px-1 py-1 inline-flex items-center">
      <!-- 減少 -->
      <button type="button" class="btn btn-ghost btn-xs rounded-full w-7 h-7 flex items-center justify-center
             disabled:opacity-40 disabled:cursor-not-allowed hover:bg-primary hover:text-white"
        :disabled="modelValue <= min" @click="decrease">
        <span class="sr-only">Reduce quantity</span>
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
          stroke="currentColor" class="size-6">
          <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14" />
        </svg>

      </button>

      <!-- 中間數字 -->
      <div class="mx-3 text-sm text-base-content content-center">
        <span class="sr-only">Quantity</span>
        {{ modelValue }}
      </div>

      <!-- 增加 -->
      <button type="button" class="btn btn-ghost btn-xs rounded-full w-7 h-7 flex items-center justify-center
             disabled:opacity-40 disabled:cursor-not-allowed hover:bg-primary hover:text-white" @click="increase" :disabled="modelValue >= max">
        <span class="sr-only">Increase quantity</span>
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
          class="size-6">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
        </svg>

      </button>
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  modelValue: {
    type: Number,
    required: true,
  },
  min: {
    type: Number,
    default: 1,
  },
  max: {
    type: Number,
    default: 50,
  },
})

const emit = defineEmits(['update:modelValue'])

const decrease = () => {
  if (props.modelValue >= props.min) {
    emit('update:modelValue', props.modelValue - 1)
  }
}

const increase = () => {
  if (props.modelValue < props.max) {
    emit('update:modelValue', props.modelValue + 1)
  }
}
</script>