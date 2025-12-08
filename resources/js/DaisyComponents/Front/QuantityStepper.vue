<template>
  <div class="inline-flex items-center">
    <div class="rounded-[12px]  px-4 py-2 inline-flex">
      <!-- 減少 -->
      <button type="button" class="btn btn-ghost btn-xs w-7 h-7 flex items-center justify-center 
             disabled:opacity-40 disabled:cursor-not-allowed hover:bg-[#82ae46] hover:text-white" @click="decrease">
        <span class="sr-only">Reduce quantity</span>
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor"
          className="size-6">
          <path strokeLinecap="round" strokeLinejoin="round" d="M5 12h14" />
        </svg>

      </button>

      <!-- 中間數字 -->
      <div class="mx-3 text-sm text-[#67645e] content-center">
        <span class="sr-only">Quantity</span>
        {{ modelValue }}
      </div>

      <!-- 增加 -->
      <button type="button" class="btn btn-ghost btn-xs w-7 h-7 flex items-center justify-center 
             disabled:opacity-40 disabled:cursor-not-allowed hover:bg-[#82ae46] hover:text-white" @click="increase" :disabled="modelValue >= max">
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
<!-- class="inline-flex items-center rounded-[12px] border border-[#b8b5ae] bg-[#f9f7f2] px-3 " -->