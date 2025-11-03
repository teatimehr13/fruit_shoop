<script setup>
import { ref, reactive, computed, watch } from 'vue'
import { useFloating, offset, flip, shift } from '@floating-ui/vue'
import BackLayout from '@/Layouts/BackLayout.vue';
import api from '@/Lib/apiFeedback';

const props = defineProps({
    categories: Array
})


const categories = ref([...props.categories])

// ===== UI 狀態 =====
const mode = ref(null) // 'menu' | 'form' | 'delete'
const formMode = ref(null) // 'add' | 'edit'
const itemType = ref(null) // 'category' | 'subcategory'

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
    const target = itemType.value === 'category' ? '類別' : '子類別'

    if (formMode.value === 'add' && itemType.value === 'subcategory') {
        const parent = categories.value.find(c => c.id === activeCategory.value)
        return `${action}${target}到「${parent?.name}」`
    }
    return `${action}${target}`
})

const deleteMessage = computed(() => {
    const target = itemType.value === 'category' ? '類別' : '子類別'
    return `確定要刪除「${current.value?.name}」嗎？`
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
const openMenu = (event, item, type) => {
    reference.value = event.currentTarget
    current.value = item
    itemType.value = type
    mode.value = 'menu'

    setActiveState(item, type)
    fillForm(item)
    update()
}

// 打開新增類別表單
const openAddCategory = (event) => {
    reference.value = event.currentTarget
    current.value = null
    itemType.value = 'category'
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
    itemType.value = 'subcategory'
    mode.value = 'form'

    resetForm()
    form.category_id = activeCategory.value
}

const showDelete = () => {
    mode.value = 'delete'
}

// ===== CRUD 操作 =====

const handleSubmit = async () => {
    if (!form.name.trim()) {
        alert('請輸入名稱')
        return
    }

    try {
        if (formMode.value === 'add') {
            await handleAdd()
        } else {
            await handleUpdate()
        }
    } catch (error) {
        console.error('操作失敗:', error)
        alert('操作失敗，請稍後再試')
    }
}

const handleAdd = async () => {
    if (itemType.value === 'category') {
        // console.log('新增類別:', form)
        const res = await api.post(route('back.categories.store'), form);
        // console.log(res);
        categories.value.push(res.data)
        console.log(categories.value);
    } else {
        console.log('新增子類別:', form)
        // API 呼叫
        const id = form.category_id;
        const res = await api.post(route('back.categories.subcategories.store', id), form);
        console.log(res);
        

        // 模擬新增
        const parent = categories.value.find(c => c.id === form.category_id)
        if (parent) {
            parent.subcategories = parent.subcategories || []
            parent.subcategories.push({
                id: res.data.id,
                name: form.name,
                category_id: form.category_id,
                sort_order: form.sort_order,
                is_enabled: form.is_enabled
            })
        }
    }
    close()
}

const handleUpdate = async () => {
    if (itemType.value === 'category') {
        console.log('更新類別:', form)

        const res = await api.put(route('back.categories.update', form.id), form);
        console.log(res);

        //更新
        const category = categories.value.find(c => c.id === form.id)
        if (category) {
            Object.assign(category, {
                name: form.name,
                sort_order: form.sort_order,
                is_enabled: form.is_enabled
            })
        }
    } else {
        console.log('更新子類別:', form)
        const res = await api.put(route('back.subcategories.update', form.id), form);
        console.log(res);


        // 更新
        const parent = categories.value.find(c => c.id === form.category_id)
        const subcategory = parent?.subcategories?.find(s => s.id === form.id)
        console.log(parent);
        console.log(subcategory);
        
        if (subcategory) {
            Object.assign(subcategory, {
                name: form.name,
                sort_order: form.sort_order,
                is_enabled: form.is_enabled
            })
        }
    }
    close()
}

const handleDelete = async () => {
    try {
        if (itemType.value === 'category') {
            console.log('刪除類別:', current.value.id)
            const id = current.value.id;
            console.log(id);

            // API 呼叫
            const res = await api.delete(route('back.categories.destroy', id));
            console.log(res);

            // 刪除
            categories.value = categories.value.filter(c => c.id !== id)
        } else {
            console.log('刪除子類別:', current.value.id)
            const id = current.value.id;
            const res = await api.delete(route('back.subcategories.destroy', id))

            // 模擬刪除
            const parent = categories.value.find(c => c.id === current.value.category_id)
            if (parent) {
                parent.subcategories = parent.subcategories.filter(s => s.id !== current.value.id)
            }
        }
        close()
    } catch (error) {
        console.error('刪除失敗:', error)
        // alert('刪除失敗，請稍後再試')
    }
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
</script>

<template>
    <BackLayout>
        <p class="text-[#1E2328] text-lg font-semibold">
            類別
        </p>

        <div class="shadow bg-base-100 mt-6 px-6 py-5">
            <div class="mb-4">
                <button class="btn btn-sm" @click="openAddCategory">
                    新增類別
                </button>
            </div>

            <ul class="menu rounded-box w-full">
                <li v-for="category in categories" :key="category.id">
                    <a @click="openMenu($event, category, 'category')" class="text-lg font-semibold"
                        :class="{ 'menu-active': activeCategory === category.id }">
                        {{ category.name }}
                    </a>
                    <ul v-if="category.subcategories?.length">
                        <li v-for="subcategory in category.subcategories" :key="subcategory.id">
                            <a @click="openMenu($event, subcategory, 'subcategory')"
                                :class="{ 'menu-active': activeSubCategory === subcategory.id }">
                                {{ subcategory.name }}
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>

        <!-- Popover -->
        <Teleport to="body">
            <Transition enter-active-class="transition-opacity duration-200 ease-out" enter-from-class="opacity-0"
                enter-to-class="opacity-100" leave-active-class="transition-opacity duration-150 ease-in"
                leave-from-class="opacity-100" leave-to-class="opacity-0">
                <div v-if="mode" ref="floating" :style="floatingStyles"
                    class="fixed rounded-xl border border-base-300 bg-base-100 shadow-xl z-50">
                    <!-- 操作選單 -->
                    <div v-if="mode === 'menu'" class="min-w-[140px] py-1">
                        <button class="block w-full text-left px-4 py-2 hover:bg-base-200 transition-colors"
                            @click="showEdit">
                            編輯
                        </button>
                        <button v-if="itemType === 'category'"
                            class="block w-full text-left px-4 py-2 hover:bg-base-200 transition-colors"
                            @click="showAddSubcategory">
                            新增子類別
                        </button>
                        <button class="block w-full text-left px-4 py-2 hover:bg-error/10 text-error transition-colors"
                            @click="showDelete">
                            刪除
                        </button>
                    </div>

                    <!-- 表單模式（新增/編輯） -->
                    <div v-else-if="mode === 'form'" class="w-80 p-4">
                        <h4 class="font-semibold mb-3">{{ formTitle }}</h4>

                        <div class="space-y-3">
                            <div>
                                <input v-model="form.name" class="input input-bordered input-sm w-full"
                                    placeholder="請輸入名稱" @keyup.enter="handleSubmit" autofocus />
                            </div>

                            <label class="label cursor-pointer justify-start gap-2">
                                <input v-model="form.is_enabled" type="checkbox" class="checkbox checkbox-sm" />
                                <span class="label-text">啟用</span>
                            </label>
                        </div>

                        <div class="mt-4 flex justify-end gap-2">
                            <button class="btn btn-ghost btn-sm" @click="close">
                                取消
                            </button>
                            <button class="btn btn-primary btn-sm" @click="handleSubmit" :disabled="!form.name.trim()">
                                {{ formMode === 'add' ? '新增' : '更新' }}
                            </button>
                        </div>
                    </div>

                    <!-- 刪除確認 -->
                    <div v-else-if="mode === 'delete'" class="w-80 p-4">
                        <h4 class="font-semibold mb-2">
                            {{ itemType === 'category' ? '刪除類別' : '刪除子類別' }}
                        </h4>
                        <p class="text-sm text-base-content/70 mb-4">
                            {{ deleteMessage }}
                            <span v-if="itemType === 'category'" class="block mt-2 text-error font-medium">
                                ⚠️ 該類別若尚存在子類別則無法刪除
                            </span>
                            <span class="block mt-2">此操作無法復原。</span>
                        </p>
                        <div class="flex justify-end gap-2">
                            <button class="btn btn-ghost btn-sm" @click="close">取消</button>
                            <button class="btn btn-error btn-sm text-base-200" @click="handleDelete">
                                確認刪除
                            </button>
                        </div>
                    </div>
                </div>
            </Transition>
        </Teleport>
    </BackLayout>
</template>

<style scoped>
/* .menu .active {
    @apply bg-base-200;
} */
</style>