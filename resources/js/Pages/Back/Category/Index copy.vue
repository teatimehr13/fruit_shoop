<script setup>
import BackLayout from '@/Layouts/BackLayout.vue';
import axios from 'axios';
import api from '@/Lib/apiFeedback';
import { ref, reactive, onBeforeUnmount, nextTick, watch } from 'vue';
import { Popover, PopoverButton, PopoverPanel } from '@headlessui/vue'
import { useFloating, offset, flip, shift, autoUpdate } from '@floating-ui/vue'
const props = defineProps({
    categories: Object
});

console.log(props.categories);
const categories = ref([...props.categories]);

console.log(categories.value);
const addCategory = async () => {
    const res = await api.post(route('back.categories.store'), {
        name: '數位', is_enabled: false
    });

    console.log(res.status);
    //重拿
    const r = await axios.get(route('back.categories.index.json'));
    categories.value = r.data;
}

const updCategory = async (id) => {
    const res = await axios.put(route('back.categories.update', id), {
        name: '數位555', is_enabled: true
    });

    console.log(res.status);
    const r = await axios.get(route('back.categories.index.json'));
    categories.value = r.data;

}

const delCategory = async (id) => {
    const res = await axios.delete(route('back.categories.destroy', id));
    console.log(res.status);
    const r = await axios.get(route('back.categories.index.json'));
    categories.value = r.data;
}

const menus = [
    { id: 1, label: '水果', items: [{ id: 11, name: '蘋果' }, { id: 12, name: '香蕉' }] },
    { id: 2, label: '蔬菜', items: [{ id: 21, name: '高麗菜' }, { id: 22, name: '節瓜' }] },
]

const current = ref(null)
const mode = ref(null) // 'menu' | 'edit' | 'delete' | 'add' | 'addCategories'
const type = ref(null)
const reference = ref(null)
const floating = ref(null)
const activeCategory = ref(null) // 改名：更明確
const activeSubCategory = ref(null) // 改名：更明確

const { floatingStyles, update } = useFloating(reference, floating, {
    placement: 'bottom-start',
    middleware: [offset(4), flip(), shift({ padding: 4 })]
})

function openForm(initial, type) {
    // console.log(initial.value);
    current.value = initial.value ?? null
    form.name = initial.value?.name ?? ''
    // form.sort_order = initial?.sort_order ?? 0
    form.is_enabled = initial.value?.is_enabled ?? true
    type == 'subcategory' ? form.category_id = initial?.value.category_id : null
    // console.log(form);

}

// 打開選單（編輯/刪除/新增子類別）
const openPanel = (event, item, type) => {
    reference.value = event.currentTarget
    current.value = {
        ...item,
        type: type
    }

    console.log(item);
    console.log(current.value);
    mode.value = 'menu'

    // 如果是類別，設置為 active
    if (type === 'category') {
        activeCategory.value = item.id
    } else {
        activeSubCategory.value = item.id
    }

    openForm(current, type)
    update()
}

// 打開新增類別面板
const openPanelAdd = (event) => {
    reference.value = event.currentTarget
    current.value = {
        type: 'category',
        name: '',
        is_enabled: 1
    }

    console.log(current.value);
    
    mode.value = 'addCategories'
    activeCategory.value = null // 新增時不 highlight 任何類別
    activeSubCategory.value = null
    openForm(current, type)
    update()
}

const showEdit = () => {
    mode.value = 'edit'
}

const showDelete = () => {
    mode.value = 'delete'
}

const showAdd = () => {
    mode.value = 'add' // 新增子類別
}

const handleDelete = () => {
    if (current.value.type === 'category') {
        console.log('刪除類別', current.value.label)
        // menus.value = menus.value.filter(m => m.id !== current.value.id)
    } else {
        console.log('刪除子類別', current.value.name)
        // const parent = menus.value.find(m => m.items.some(i => i.id === current.value.id))
        // if (parent) {
        //     parent.items = parent.items.filter(i => i.id !== current.value.id)
        // }
    }
    close()
}

const handleUpdate = () => {
    console.log('更新', current.value)
    // 根據 type 更新不同欄位
    // if (current.value.type === 'category') {
    //     const menu = menus.value.find(m => m.id === current.value.id)
    //     if (menu) menu.label = current.value.label
    // } else {
    //     const parent = menus.value.find(m => m.items.some(i => i.id === current.value.id))
    //     const item = parent?.items.find(i => i.id === current.value.id)
    //     if (item) item.name = current.value.name
    // }
    close()
}

const handleAdd = () => {
    console.log('新增子類別到', activeCategory.value)
    // const parent = menus.value.find(m => m.label === activeCategory.value)
    // if (parent) {
    //     parent.items.push({
    //         id: Date.now(),
    //         name: current.value.name
    //     })
    // }
    close()
}

const handleAddCategory = () => {
    console.log('新增類別', current.value.label)
    // menus.value.push({
    //     id: Date.now(),
    //     label: current.value.label,
    //     items: []
    // })
    close()
}

const close = () => {
    current.value = null
    mode.value = null
    activeCategory.value = null;
    activeSubCategory.value = null;
    // activeCategory 保持不變，讓用戶看到目前選中的類別
}

// 點擊外部關閉
const handleClickOutside = (event) => {
    if (floating.value && !floating.value.contains(event.target) &&
        !reference.value?.contains(event.target)) {
        close()
    }
}

watch(() => current.value, (val) => {
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

        <!-- <button @click="addCategory">
            test
        </button>

        <button @click="updCategory(47)">
            update
        </button>

          <button @click="delCategory(47)">
            del
        </button> -->
        <div class="shadow bg-base-100 mt-6 px-6 py-5">
            <div class="mb-4">
                <button class="btn btn-sm" @click="openPanelAdd">
                    新增類別
                </button>
            </div>

            <ul class="menu rounded-box w-full">
                <li v-for="category in categories" :key="category.id">
                    <a @click="openPanel($event, category, 'category')" class="text-lg font-semibold"
                        :class="{ 'menu-active': activeCategory === category.id }">
                        {{ category.name }}
                    </a>
                    <ul v-if="category.subcategories?.length">
                        <li v-for="subcatgory in category.subcategories" :key="subcatgory.id">
                            <a @click="openPanel($event, subcatgory, 'subcategory')"
                                :class="{ 'menu-active': activeSubCategory === subcatgory.id }">
                                {{ subcatgory.name }}
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>

        <Teleport to="body">
            <Transition enter-active-class="transition-opacity duration-200 ease-out" enter-from-class="opacity-0"
                enter-to-class="opacity-100" leave-active-class="transition-opacity duration-150 ease-in"
                leave-from-class="opacity-100" leave-to-class="opacity-0">
                <div v-if="current" ref="floating" :style="floatingStyles"
                    class="fixed rounded-xl border border-base-300 bg-base-100 shadow-xl z-50">
                    <!-- 選單模式：編輯/刪除/新增子類別 -->
                    <div v-if="mode === 'menu'" class="min-w-[140px] py-1">
                        <button class="block w-full text-left px-4 py-2 hover:bg-base-200 transition-colors"
                            @click="showEdit">
                            編輯
                        </button>
                        <button v-if="current.type === 'category'"
                            class="block w-full text-left px-4 py-2 hover:bg-base-200 transition-colors"
                            @click="showAdd">
                            新增子類別
                        </button>
                        <button class="block w-full text-left px-4 py-2 hover:bg-error/10 text-error transition-colors"
                            @click="showDelete">
                            刪除
                        </button>
                    </div>

                    <!-- 編輯模式 -->
                    <div v-else-if="mode === 'edit'" class="w-80 p-4">
                        <h4 class="font-semibold mb-3">
                            編輯{{ current.type === 'category' ? '類別' : '子類別' }}
                        </h4>
                        <input v-model="current[current.type === 'category' ? 'label' : 'name']"
                            class="input input-bordered input-sm w-full" placeholder="請輸入名稱"
                            @keyup.enter="handleUpdate" />
                        <div class="mt-3 flex justify-end gap-2">
                            <button class="btn btn-ghost btn-sm" @click="close">取消</button>
                            <button class="btn btn-primary btn-sm" @click="handleUpdate">更新</button>
                        </div>
                    </div>

                    <!-- 刪除確認模式 -->
                    <div v-else-if="mode === 'delete'" class="w-80 p-4">
                        <h4 class="font-semibold mb-2">
                            {{ current.type === 'category' ? '刪除類別' : '刪除子類別' }}
                        </h4>
                        <p class="text-sm text-base-content/70 mb-4">
                            確定要刪除「{{ current.type === 'category' ? current.label : current.name }}」嗎？
                            <span v-if="current.type === 'category'" class="block mt-2 text-error font-medium">
                                ⚠️ 這將同時刪除所有子類別
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

                    <!-- 新增子類別模式 -->
                    <div v-else-if="mode === 'add'" class="w-80 p-4">
                        <h4 class="font-semibold mb-3">
                            新增子類別到「{{ activeCategory }}」
                        </h4>
                        <input v-model="current.name" class="input input-bordered input-sm w-full"
                            placeholder="請輸入子類別名稱" @keyup.enter="handleAdd" />
                        <div class="mt-3 flex justify-end gap-2">
                            <button class="btn btn-ghost btn-sm" @click="close">取消</button>
                            <button class="btn btn-primary btn-sm" @click="handleAdd">新增</button>
                        </div>
                    </div>

                    <!-- 新增類別模式 -->
                    <div v-else-if="mode === 'addCategories'" class="w-80 p-4">
                        <h4 class="font-semibold mb-3">新增類別</h4>
                        <input v-model="form.name" class="input input-bordered input-sm w-full"
                            placeholder="請輸入類別名稱" @keyup.enter="handleAddCategory" />
                        <div class="mt-3 flex justify-end gap-2">
                            <button class="btn btn-ghost btn-sm" @click="close">取消</button>
                            <button class="btn btn-primary btn-sm" @click="handleAddCategory">新增</button>
                        </div>
                    </div>
                </div>
            </Transition>
        </Teleport>
    </BackLayout>

</template>

<style>
.menu {

    :where(li:not(.menu-title) > *:not(ul, details, .menu-title, .btn)),
    :where(li:not(.menu-title) > details > summary:not(.menu-title)) {
        display: block;
    }
}

/* .menu .active {
    @apply bg-base-200;
} */
</style>