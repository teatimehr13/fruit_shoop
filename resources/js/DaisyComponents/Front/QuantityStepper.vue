<template>
  <div
    class="inline-flex items-center rounded-[12px] border border-[#b8b5ae] bg-[#f9f7f2] px-3 "
  >
    <!-- 減少 -->
    <button
      type="button"
      class="w-7 h-7 flex items-center justify-center rounded-full bg-[#85827c] text-white
             disabled:opacity-40 disabled:cursor-not-allowed"
      @click="decrease"
    >
      <span class="sr-only">Reduce quantity</span>
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" class="w-3 h-3">
        <path
          d="M16 10c0 .553-.048 1-.601 1H4.601C4.049 11 4 10.553 4 10c0-.553.049-1 .601-1H15.4c.552 0 .6.447.6 1z"
        />
      </svg>
    </button>

    <!-- 中間數字 -->
    <div class="mx-3 text-sm text-[#67645e]">
      <span class="sr-only">Quantity</span>
      {{ modelValue }}
    </div>

    <!-- 增加 -->
    <button
      type="button"
      class="w-7 h-7 flex items-center justify-center rounded-full bg-[#85827c] text-white
             disabled:opacity-40 disabled:cursor-not-allowed"
      @click="increase"
      :disabled="modelValue >= max"
    >
      <span class="sr-only">Increase quantity</span>
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" class="w-3 h-3">
        <path
          d="M16 10c0 .553-.048 1-.601 1H11v4.399c0 .552-.447.601-1 .601-.553 0-1-.049-1-.601V11H4.601C4.049 11 4 10.553 4 10c0-.553.049-1 .601-1H9V4.601C9 4.048 9.447 4 10 4c.553 0 1 .048 1 .601V9h4.399c.553 0 .601.447.601 1z"
        />
      </svg>
    </button>
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
