<script setup>
import { ref } from 'vue'

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
</script>

<template>
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-semibold">選項管理</h3>
            <button @click="addOption" class="btn btn-sm btn-primary">
                ➕ 新增選項
            </button>
        </div>

        <div v-for="(option, index) in localOptions" :key="option.id" class="border rounded-lg p-4">
            <div class="flex justify-between items-start mb-3">
                <span class="font-medium">選項 {{ index + 1 }}</span>
                <button @click="removeOption(index)" class="btn btn-xs btn-error">
                    刪除
                </button>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div class="col-span-2">
                    <label class="label">
                        <span class="label-text">選項名稱</span>
                    </label>
                    <input v-model="option.option_text" class="input input-bordered w-full input-sm" />
                </div>

                <div>
                    <label class="label">
                        <span class="label-text">原價</span>
                    </label>
                    <input v-model="option.original_price" type="number" class="input input-bordered w-full input-sm" />
                </div>

                <div>
                    <label class="label">
                        <span class="label-text">售價</span>
                    </label>
                    <input v-model="option.price" type="number" class="input input-bordered w-full input-sm" />
                </div>

                <div>
                    <label class="label">
                        <span class="label-text">庫存</span>
                    </label>
                    <input v-model="option.inventory" type="number" class="input input-bordered w-full input-sm" />
                </div>

                <div class="flex items-center">
                    <label class="label cursor-pointer gap-2">
                        <input v-model="option.is_enabled" type="checkbox" class="checkbox checkbox-sm" />
                        <span class="label-text">啟用</span>
                    </label>
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