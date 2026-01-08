<script setup>
import { ref, watch } from 'vue'

//文字區
import { QuillEditor } from '@vueup/vue-quill'
import '@vueup/vue-quill/dist/vue-quill.snow.css';



const props = defineProps({
    product: Object,
    subSelects: Array,
})

const emit = defineEmits(['save'])

const form = ref({
    name: '',
    slug: '',
    subcategory_id: '',
    // price: '',
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
    <div class="bg-stone-100 my-4 py-2 px-6">
        <h3 class="text-lg font-semibold">基本資料</h3>
    </div>

    <div class="space-y-4 px-6 py-4">
        <div class="form-control">
            <label class="label">
                <span class="label-text">產品名稱</span>
            </label>
            <input v-model="form.name" class="input input-bordered w-full" />
        </div>

        <div class="form-control">
            <label class="label">
                <span class="label-text">Slug</span>
            </label>
            <input v-model="form.slug" class="input input-bordered w-full" />
        </div>

        <div class="form-control">
            <label class="label">
                <span class="label-text">子類別</span>
            </label>
            <select v-model="form.subcategory_id" class="select select-bordered w-full">
                <option v-for="sel of subSelects" :value="sel.id">{{ sel.name }}</option>
            </select>
            <!-- <select v-model="form.subcategory_id" class="input input-bordered w-full" /> -->
        </div>

        <!-- <div>
            <label class="label">
                <span class="label-text">價格</span>
            </label>
            <input v-model="form.price" type="number" class="input input-bordered w-full" />
        </div> -->

        <div>
            <label class="label">
                <span class="label-text">描述</span>
            </label>
            <QuillEditor v-model:content="form.description" content-type="html" theme="snow" toolbar="essential"
                style="min-height: 160px;" />
            <!-- <textarea v-model="form.description" class="textarea textarea-bordered w-full" rows="4"></textarea> -->
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