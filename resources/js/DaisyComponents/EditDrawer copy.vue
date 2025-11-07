<script setup>
import { ref, watch } from 'vue'

const props = defineProps({
    modelValue: Boolean,
    product: Object
})

const emit = defineEmits(['update:modelValue', 'save'])

const form = ref({
    slug: '',
    name: '',
    price: '',
    description: '',
    is_enabled: true
})

// 監聽 product 變化
watch(() => props.product, (newProduct) => {
    if (newProduct) {
        form.value = { ...newProduct }
    }
}, { immediate: true })

// 監聽 modelValue，控制 checkbox
const checkboxRef = ref(null)
watch(() => props.modelValue, (isOpen) => {
    if (checkboxRef.value) {
        checkboxRef.value.checked = isOpen
    }
})

const close = () => {
    emit('update:modelValue', false)
}

const handleSave = () => {
    emit('save', form.value)
}

// 監聽 checkbox 變化（用戶點擊遮罩關閉）
const handleCheckboxChange = (e) => {
    if (!e.target.checked) {
        emit('update:modelValue', false)
    }
}
</script>

<template>
    <div class="drawer drawer-end z-50">
        <input 
            id="my-drawer-edit" 
            ref="checkboxRef"
            type="checkbox" 
            class="drawer-toggle"
            @change="handleCheckboxChange"
        />
        
        <!-- 這個 div 不需要內容，因為觸發按鈕在外面 -->
        <div class="drawer-content"></div>
        
        <div class="drawer-side">
            <label for="my-drawer-edit" aria-label="close sidebar" class="drawer-overlay"></label>
            
            <div class="bg-base-100 min-h-full w-full max-w-md p-6">
                <!-- 標題 -->
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold">
                        {{ product?.id ? '編輯產品' : '新增產品' }}
                    </h2>
                    <label for="my-drawer-edit" class="btn btn-ghost btn-sm btn-circle">
                        ✕
                    </label>
                </div>

                <!-- 表單 -->
                <div class="space-y-4">
                    <!-- Slug -->
                    <div>
                        <label class="label">
                            <span class="label-text">Slug</span>
                        </label>
                        <input 
                            v-model="form.slug" 
                            type="text"
                            class="input input-bordered w-full"
                            placeholder="product-slug"
                        />
                    </div>

                    <!-- 產品名稱 -->
                    <div>
                        <label class="label">
                            <span class="label-text">產品名稱</span>
                            <span class="label-text-alt text-error">*</span>
                        </label>
                        <input 
                            v-model="form.name" 
                            type="text"
                            class="input input-bordered w-full"
                            placeholder="輸入產品名稱"
                            required
                        />
                    </div>

                    <!-- 價格 -->
                    <div>
                        <label class="label">
                            <span class="label-text">價格</span>
                            <span class="label-text-alt text-error">*</span>
                        </label>
                        <input 
                            v-model="form.price" 
                            type="number"
                            class="input input-bordered w-full"
                            placeholder="0"
                            min="0"
                            required
                        />
                    </div>

                    <!-- 描述 -->
                    <div>
                        <label class="label">
                            <span class="label-text">描述</span>
                        </label>
                        <textarea 
                            v-model="form.description" 
                            class="textarea textarea-bordered w-full"
                            rows="5"
                            placeholder="產品描述..."
                        ></textarea>
                    </div>

                    <!-- 狀態 -->
                    <div>
                        <label class="label cursor-pointer justify-start gap-3">
                            <input 
                                v-model="form.is_enabled" 
                                type="checkbox"
                                class="checkbox"
                            />
                            <span class="label-text">啟用</span>
                        </label>
                    </div>
                </div>

                <!-- 按鈕 -->
                <div class="mt-8 flex gap-2">
                    <label for="my-drawer-edit" class="btn flex-1">
                        取消
                    </label>
                    <button 
                        @click="handleSave" 
                        class="btn btn-primary flex-1"
                        :disabled="!form.name || !form.price"
                    >
                        儲存
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>