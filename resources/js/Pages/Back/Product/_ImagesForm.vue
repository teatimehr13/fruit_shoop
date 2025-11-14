<script setup>
import { computed, nextTick, reactive, ref, watch } from 'vue'
import { useFloating, offset, flip, shift, autoUpdate } from '@floating-ui/vue'
import api from '@/Lib/apiFeedback';
import { useNotify } from '@/Composables/useNotify';

const props = defineProps({
    images: Array,
    productId: Number
})

const localImages = ref([...props.images]);
const emit = defineEmits(['save'])

// ===== UI 狀態 =====
const mode = ref(null) // 'menu' | 'form' | 'delete'
const formMode = ref(null) // 'add' | 'edit'

const current = ref(null) // 當前操作的項目
const activeCategory = ref(null)
const activeSubCategory = ref(null)

const { toast } = useNotify()

// ===== 表單資料 =====
const form = reactive({
    id: null,
    product_id: null,
    image: '',
    sort_order: 0,
    is_primary: null,
    alt_text: ''
})

// ===== Floating UI =====
const reference = ref(null)
const floating = ref(null)
const { floatingStyles, update } = useFloating(reference, floating, {
    placement: 'bottom-start',
    middleware: [offset(4), flip(), shift({ padding: 4 })]
})

// ===== 計算屬性 =====
const formTitles = computed(() => {
    // const action = formMode.value === 'edit' ? '編輯附件' : '刪除附件';
    let action = '';
    switch (formMode.value) {
        case "edit":
            action = '編輯附件';
            break
        case "add":
            action = '新增附件';
            break
        case "delete":
            action = '刪除附件'
            break
    }
    return action;
})

// 重置表單
const resetForm = () => {
    form.id = null;
    form.alt_text = '';
    form.product_id = null;
    form.is_primary = null;
    // form.sort_order = 0
    // form.image = ''
}

// 填充表單（編輯用）
const fillForm = (item) => {
    form.id = item.id
    form.alt_text = item.alt_text
    form.product_id = item.product_id
    form.is_primary = item.is_primary ?? 0
    // form.sort_order = item.sort_order ?? 0
    // console.log(form);
}

// 設置 active 狀態
const setActiveState = (item, type) => {
    if (type === 'category') {
        activeCategory.value = item.id
        activeSubCategory.value = null
    } else {
        activeSubCategory.value = item.id
        activeCategory.value = null
    }
}


// 打開操作選單
const openMenu = (event, item) => {
    reference.value = event.currentTarget
    // current.value = item
    mode.value = 'menu'
    update()
    fillForm(item)
    // console.log(reference.value);
    // console.log(current.value);
}

const returnMenu = () => {
    mode.value = 'menu'
    update()
}

// 打開新增類別表單
const openAddCategory = (event) => {
    reference.value = event.currentTarget
    current.value = null
    formMode.value = 'add'
    mode.value = 'form'

    activeCategory.value = null
    activeSubCategory.value = null
    resetForm()
    update()
}

// ===== 選單操作 =====

const showEdit = async () => {
    formMode.value = 'edit'
    mode.value = ''
    await nextTick()  // 等待 DOM 清空
    mode.value = 'form'
    await nextTick()  // 等待新內容渲染

    requestAnimationFrame(() => {
        update()
    })
}

const showAdd = (event) => {
    reference.value = event.currentTarget
    formMode.value = 'add'
    mode.value = 'form'
    resetForm()
}

const showDelete = async () => {
    formMode.value = 'delete'
    mode.value = ''
    await nextTick()
    mode.value = 'delete'
    await nextTick()

    requestAnimationFrame(() => {
        update()
    })
}

const addImages = async (files) => {
    // const files = document.getElementById('file').files;
    const id = props.productId;
    const fd = new FormData();
    Array.from(files).forEach((file, i) => {
        fd.append(`productImages[${i}][product_id]`, id);
        fd.append(`productImages[${i}][alt_text]`, file.name);
        fd.append(`productImages[${i}][is_primary]`, '0');

        if (file) fd.append(`productImages[${i}][image]`, file);
    })

    // for (const [key, val] of fd) {
    //     console.log(key, 'value =>', val);
    // }
    // return

    const res = await api.post(route('back.product.images.store', id), fd);
    if (res.status === 201) {
        const newImages = res.data.data
        if (Array.isArray(newImages)) {
            localImages.value.push(...newImages)
        } else {
            localImages.value.push(newImages)
        }
        close()
    }
}

const close = () => {
    current.value = null
    mode.value = null
    formMode.value = null
}

const handleSubmit = async () => {
    const data = { alt_text: form.alt_text };
    const res = await api.patch(route('back.images.update', form.id), data)
    // console.log(res);
    const image = localImages.value.find(e => e.id == form.id);
    if (image) {
        Object.assign(image, {
            alt_text: res.data.alt_text
        })
    }
    close()
}


// ===== 點擊外部關閉 =====
const handleClickOutside = (event) => {
    if (floating.value && !floating.value.contains(event.target) &&
        !reference.value?.contains(event.target)) {
        close()
    }
}

watch(() => mode.value, (val) => {
    if (val) {
        document.addEventListener('mousedown', handleClickOutside)
    } else {
        document.removeEventListener('mousedown', handleClickOutside)
    }
})

const removeImage = async () => {
    const res = await api.delete(route('back.images.destroy', form.id));
    if (res.status === 204) {
        close()
        localImages.value = localImages.value.filter(e => e.id !== form.id);
    }
    console.log(res.status);
}


const fileInput = ref(null)
const previewUrl = ref(null)
const fileObj = ref(null) //上傳時放入formData
const rmImg = ref(0);

const revoke = () => {
    if (previewUrl.value) URL.revokeObjectURL(previewUrl.value)
    previewUrl.value = null
}

const onFileChange = async (e) => {
    const file = e.target.files
    // console.log(file);

    await addImages(file);

    // revoke()
    // if (file) {
    //     fileObj.value = file
    //     previewUrl.value = URL.createObjectURL(file)
    //     rmImg.value = 0;
    // } else {
    //     fileObj.value = null
    // }
}

// const clearFile = () => {
//     revoke()
//     fileObj.value = null
//     // 清 input 值，否則同一檔再選不會觸發 change
//     if (fileInput.value) fileInput.value.value = '';
//     if (!aboutForm.image) rmImg.value = 1;
// }

</script>

<template>
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-semibold">圖片管理</h3>
            <button @click="showAdd($event)" class="btn btn-sm">
                新增圖片
            </button>
        </div>
        <ul class="list bg-base-100 rounded-box shadow-md" v-for="img in localImages">
            <li class="list-row">
                <div><img class="size-20 rounded-box" :src="img.img_url" />
                </div>
                <div class="grid content-center">
                    <div>{{ img.alt_text }}</div>
                </div>
                <button class="btn btn-square" @click="openMenu($event, img)">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M6.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM12.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM18.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                    </svg>
                </button>
            </li>
        </ul>
    </div>

    <!-- Popover -->
    <Teleport to="body">
        <Transition enter-active-class="transition-opacity duration-200 ease-out" enter-from-class="opacity-0"
            enter-to-class="opacity-100" leave-active-class="transition-opacity duration-150 ease-in"
            leave-from-class="opacity-100" leave-to-class="opacity-0">
            <div v-if="mode" ref="floating" :style="floatingStyles"
                class="fixed rounded-xl border border-base-300 bg-base-100 shadow-xl z-9999">
                <!-- 操作選單 -->
                <div v-if="mode === 'menu'" class="min-w-[140px] py-1 ">
                    <button class="block w-full text-left px-4 py-2 hover:bg-base-200 transition-colors"
                        @click="showEdit">
                        編輯
                    </button>
                    <button class="block w-full text-left px-4 py-2 hover:bg-error/10 text-error transition-colors"
                        @click="showDelete">
                        刪除
                    </button>
                </div>

                <!-- 表單模式（新增/編輯） -->
                <div v-else-if="mode === 'form' && formMode === 'edit'" class="w-80 p-4">
                    <header class="flex w-full">
                        <h4 class="font-semibold order-2 flex-1 self-center text-center">{{ formTitles }}</h4>
                        <button class="btn btn-ghost btn-square btn-sm order-1 flex-0" @click="returnMenu">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                            </svg>
                        </button>
                        <button class="btn btn-ghost btn-square btn-sm order-3 flex-0" @click="close">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </header>
                    <div class="space-y-3">
                        <div class="mt-4 mb-1">
                            <legend class="fieldset-legend">檔案名稱</legend>
                            <input type="text" class="input" placeholder="Type here" v-model="form.alt_text" />
                        </div>
                    </div>

                    <div class="mt-4 flex justify-end gap-2">
                        <button class="btn btn-primary btn-block" @click="handleSubmit"
                            :disabled="!form.alt_text.trim()">
                            更新
                        </button>
                    </div>
                </div>

                <!-- 新增附件 -->
                <div v-else-if="mode === 'form' && formMode === 'add'" class="w-80 p-4">
                    <header class="flex w-full">
                        <h4 class="font-semibold order-2 flex-1 self-center text-center">{{ formTitles }}</h4>

                        <button class="btn btn-ghost btn-square btn-sm order-3 flex-0" @click="close">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </header>

                    <div class="space-y-3">
                        <div class="mt-4 mb-1">
                            <input multiple type="file" class="input file-input" @change="onFileChange" />
                        </div>
                    </div>
                </div>

                <!-- 刪除確認 -->
                <div v-else-if="mode === 'delete'" class="w-80 p-4">
                    <header class="flex w-full">
                        <h4 class="font-semibold order-2 flex-1 self-center text-center">{{ formTitles }}</h4>
                        <button class="btn btn-ghost btn-square btn-sm order-1 flex-0" @click="returnMenu">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                            </svg>
                        </button>
                        <button class="btn btn-ghost btn-square btn-sm order-3 flex-0" @click="close">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </header>

                    <p class="mt-4 mb-1">
                        <span class="font-semibold">
                            確定要刪除這個附件嗎？
                        </span>
                        <span class="mt-2">此操作無法復原。</span>
                    </p>
                    <div class="mt-4 flex justify-end gap-2">
                        <button class="btn hover:bg-red-700 bg-red-500 btn-block text-base-200" @click="removeImage">
                            刪除
                        </button>
                    </div>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>

<style>
.list> :not(:last-child).list-row::after,
.list> :not(:last-child) .list-row::after {
    border-color: #e5e7eb !important;
}
</style>