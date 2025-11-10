<script setup>
import { ref } from 'vue'

const props = defineProps({
    images: Array
})

const emit = defineEmits(['save'])

const handleFileChange = (e) => {
    // 處理圖片上傳
}

const removeImage = (imageId) => {
    // 刪除圖片
}
</script>

<template>
    <div class="space-y-4">
        <h3 class="text-lg font-semibold">圖片管理</h3>

        <div>
            <input 
                type="file" 
                multiple 
                accept="image/*"
                class="file-input file-input-bordered w-full"
                @change="handleFileChange"
            />
        </div>

        <div class="grid grid-cols-3 gap-4">
            <div v-for="image in images" :key="image.id" class="relative">
                <img :src="image.url" class="w-full h-32 object-cover rounded" />
                <button 
                    @click="removeImage(image.id)"
                    class="absolute top-1 right-1 btn btn-xs btn-error btn-circle"
                >
                    ✕
                </button>
            </div>
        </div>
    </div>
</template>