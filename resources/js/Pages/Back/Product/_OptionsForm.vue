<script setup>
import { ref, inject, watch, nextTick, onBeforeUpdate } from 'vue'
const props = defineProps({
    options: Array
})


const emit = defineEmits(['save'])

const localOptions = ref([...props.options || []])

const addOption = () => {
    localOptions.value.push({
        id: Date.now(),
        option_text: '',
        original_price: '',
        price: '',
        inventory: 0,
        is_enabled: true
    })
}

const removeOption = (index) => {
    localOptions.value.splice(index, 1)
}

const handleSave = () => {
    emit('save', localOptions.value)
}

const inputRefs = ref([])
const editingTarget = ref(null);


// 檢查是否點擊在任何 input 之外
const handleClickOutside = (event) => {
    const clickedOutside = !inputRefs.value.some(el => el && el.contains(event.target))

    if (clickedOutside) {
        editingTarget.value = null
        document.removeEventListener('click', handleClickOutside)
    }
}

const setEditing = async (index, field) => {
    editingTarget.value = `${index}-${field}`
    await nextTick()
    const targetInput = inputRefs.value.find(el => el && el.dataset.field === `${index}-${field}`)
    console.log(targetInput);

    if (targetInput) {
        targetInput.focus()
    }
}

const isEditing = (index, field) => {
    return editingTarget.value === `${index}-${field}`
}

watch(editingTarget, async (newVal) => {
    // 先移除舊的監聽器
    document.removeEventListener('click', handleClickOutside)

    if (newVal !== null) {
        await nextTick()
        setTimeout(() => {
            document.addEventListener('click', handleClickOutside)
        }, 0)
    }
})
</script>

<template>
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-semibold">選項管理</h3>
            <button @click="addOption" class="btn btn-sm btn-primary">
                ➕ 新增選項
            </button>
        </div>
        <div v-for="(option, index) in localOptions" :key="option.key" class="p-4">
            <div class="flex justify-between items-start mb-3">
                <span class="font-medium">選項 {{ index + 1 }}</span>
                <button @click="removeOption(index)" class="btn btn-xs btn-error">
                    刪除
                </button>
            </div>

            <div class="relative grid lg:grid-cols-2 gap-x-12 gap-y-3
        lg:after:content-[''] lg:after:absolute lg:after:inset-y-0
        lg:after:left-1/2 lg:after:-translate-x-1/2 lg:after:w-px lg:after:bg-base-300
        before:content-[''] before:absolute before:inset-x-0 before:-bottom-4
        before:h-px before:bg-base-300">

                <div class="grid lg:grid-cols-[1fr_3fr] gap-x-4">
                    <label class="label">
                        <span class="label-text">選項名稱</span>
                    </label>
                    <input v-show="isEditing(index, 'option_text')" :ref="el => { if (el) inputRefs.push(el) }"
                        :data-field="`${index}-option_text`" v-model="option.option_text"
                        class="input input-bordered w-full input-sm" />
                    <div v-show="!isEditing(index, 'option_text')" class="self-center cursor-pointer"
                        @click="setEditing(index, 'option_text')">
                        {{ option.option_text }}
                    </div>
                </div>

                <div class="grid lg:grid-cols-[1fr_3fr] gap-x-4">
                    <label class="label">
                        <span class="label-text">原價</span>
                    </label>
                    <input v-show="isEditing(index, 'original_price')" :ref="el => { if (el) inputRefs.push(el) }"
                        :data-field="`${index}-original_price`" v-model="option.original_price" type="number"
                        class="input input-bordered w-full input-sm" />
                    <div v-show="!isEditing(index, 'original_price')" class="self-center cursor-pointer"
                        @click="setEditing(index, 'original_price')">
                        {{ option.original_price }}
                    </div>
                </div>

                <div class="grid lg:grid-cols-[1fr_3fr] gap-x-4">
                    <label class="label">
                        <span class="label-text">售價</span>
                    </label>
                    <input v-show="isEditing(index, 'price')" :ref="el => { if (el) inputRefs.push(el) }"
                        :data-field="`${index}-price`" v-model="option.price" type="number"
                        class="input input-bordered w-full input-sm" />
                    <div v-show="!isEditing(index, 'price')" class="self-center cursor-pointer"
                        @click="setEditing(index, 'price')">
                        {{ option.price }}
                    </div>
                </div>

                <div class="grid lg:grid-cols-[1fr_3fr] gap-x-4">
                    <label class="label">
                        <span class="label-text">庫存</span>
                    </label>
                    <input v-show="isEditing(index, 'inventory')" :ref="el => { if (el) inputRefs.push(el) }"
                        :data-field="`${index}-inventory`" v-model="option.inventory" type="number"
                        class="input input-bordered w-full input-sm" />
                    <div v-show="!isEditing(index, 'inventory')" class="self-center cursor-pointer"
                        @click="setEditing(index, 'inventory')">
                        {{ option.inventory }}
                    </div>
                </div>

            </div>
        </div>

        <div class="flex justify-end">
            <button @click="handleSave" class="btn btn-primary">
                儲存選項
            </button>
        </div>
    </div>
</template>