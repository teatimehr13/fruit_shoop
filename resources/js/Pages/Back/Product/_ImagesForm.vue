<script setup>
import { computed, reactive, ref, watch } from 'vue'
import { useFloating, offset, flip, shift } from '@floating-ui/vue'
import api from '@/Lib/apiFeedback';

const props = defineProps({
    images: Array
})

const emit = defineEmits(['save'])

// ===== UI 狀態 =====
const mode = ref(null) // 'menu' | 'form' | 'delete'
const formMode = ref(null) // 'add' | 'edit'

const current = ref(null) // 當前操作的項目
const activeCategory = ref(null)
const activeSubCategory = ref(null)

// ===== 表單資料 =====
const form = reactive({
    id: null,
    name: '',
    sort_order: 0,
    is_enabled: true,
    category_id: null,
})

// ===== Floating UI =====
const reference = ref(null)
const floating = ref(null)
const { floatingStyles, update } = useFloating(reference, floating, {
    placement: 'bottom-start',
    middleware: [offset(4), flip(), shift({ padding: 4 })]
})

// ===== 計算屬性 =====
const formTitle = computed(() => {
    const action = formMode.value === 'add' ? '新增' : '編輯'

    if (formMode.value === 'add') {
        const parent = categories.value.find(c => c.id === activeCategory.value)
        return `${action}到「${parent?.name}」`
    }
    return `${action}${target}`
})

const deleteMessage = computed(() => {
    return `確定要刪除「${current.value?.name}」嗎？`
})

const formTitles = computed(() => {
    const action = formMode.value === 'edit' ? '編輯附件' : '刪除附件';
    return action;
})

// 重置表單
const resetForm = () => {
    form.id = null
    form.name = ''
    form.sort_order = 0
    form.is_enabled = true
    form.category_id = null
}

// 填充表單（編輯用）
const fillForm = (item) => {
    form.id = item.id
    form.name = item.name
    form.sort_order = item.sort_order ?? 0
    form.is_enabled = item.is_enabled ?? true
    form.category_id = item.category_id ?? null
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
const openMenu = (event) => {
    reference.value = event.currentTarget
    // current.value = item
    mode.value = 'menu'

    // setActiveState(item, type)
    // fillForm(item)
    update()
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

const showEdit = () => {
    formMode.value = 'edit'
    mode.value = 'form'
}

const showAddSubcategory = () => {
    formMode.value = 'add'
    mode.value = 'form'

    resetForm()
    form.category_id = activeCategory.value
}

const showDelete = () => {
    formMode.value = 'delete'
    mode.value = 'delete'
}

const close = () => {
    current.value = null
    mode.value = null
    formMode.value = null
    activeCategory.value = null;
    activeSubCategory.value = null;
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


const handleFileChange = (e) => {
    // 處理圖片上傳
}

const removeImage = (imageId) => {
    // 刪除圖片
}

console.log(props.images);
const localImages = ref([...props.images]);
console.log(localImages.value);

</script>

<template>
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-semibold">圖片管理</h3>
            <button @click="addOption" class="btn btn-sm">
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
                <button class="btn btn-square" @click="openMenu($event)">
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
                <div v-else-if="mode === 'form'" class="w-80 p-4">
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
                            <input type="text" class="input" placeholder="Type here" />
                        </div>
                    </div>

                    <div class="mt-4 flex justify-end gap-2">
                        <button class="btn btn-primary btn-block" @click="handleSubmit" :disabled="!form.name.trim()">
                            更新
                        </button>
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
                            刪除這個附件嗎？
                        </span>
                        <span class="mt-2">此操作無法復原。</span>
                    </p>
                    <div class="mt-4 flex justify-end gap-2">
                        <button class="btn hover:bg-red-700 bg-red-500 btn-block text-base-200">
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