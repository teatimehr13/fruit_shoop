<template>
  <div class="flex items-stretch" role="group" aria-label="Quantity controls">

    <button class="flex-shrink-0 px-3 py-1 text-xl font-medium  rounded-l-md hover:bg-gray-100 transition duration-150 ease-in-out 
       focus:outline-none" type="button" @click="decrease" >
      −
    </button>

    <input readonly class="w-20 text-center text-lg font-semibold focus:border-[#82ae46] bg-white focus:outline-none" type="text" name="quantity" :value="modelValue" data-old-value="1">

    <button class="flex-shrink-0 px-3 py-1 text-xl font-medium  rounded-r-md 
      hover:bg-gray-100 transition duration-150 ease-in-out 
      focus:outline-none" type="button" @click="increase">
      +
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
  if (props.modelValue > props.min) {
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