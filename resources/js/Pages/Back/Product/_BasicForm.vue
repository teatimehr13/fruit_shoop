<script setup>
import { ref, watch } from 'vue'

const props = defineProps({
    product: Object
})

const emit = defineEmits(['save'])

const form = ref({
    name: '',
    slug: '',
    price: '',
    description: '',
    // is_enabled: true
})

watch(() => props.product, (val) => {
    if (val) {
        form.value = { ...val }
    }
}, { immediate: true })

const handleSave = () => {
    emit('save', form.value)
}
</script>

<template>
    <div class="space-y-4">
        <h3 class="text-lg font-semibold mb-4">基本資料</h3>
        
        <div>
            <label class="label">
                <span class="label-text">產品名稱</span>
            </label>
            <input v-model="form.name" class="input input-bordered w-full" />
        </div>

        <div>
            <label class="label">
                <span class="label-text">Slug</span>
            </label>
            <input v-model="form.slug" class="input input-bordered w-full" />
        </div>

        <div>
            <label class="label">
                <span class="label-text">價格</span>
            </label>
            <input v-model="form.price" type="number" class="input input-bordered w-full" />
        </div>

        <div>
            <label class="label">
                <span class="label-text">描述</span>
            </label>
            <textarea v-model="form.description" class="textarea textarea-bordered w-full" rows="4"></textarea>
        </div>

        <!-- <div>
            <label class="label cursor-pointer justify-start gap-2">
                <input v-model="form.is_enabled" type="checkbox" class="checkbox" />
                <span class="label-text">啟用</span>
            </label>
        </div> -->

        <div class="flex justify-end">
            <button @click="handleSave" class="btn btn-primary">
                儲存
            </button>
        </div>
    </div>
</template>