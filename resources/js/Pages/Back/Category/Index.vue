<script setup>
import BackLayout from '@/Layouts/BackLayout.vue';
import axios from 'axios';
import { ref, reactive, onBeforeUnmount, nextTick, watch } from 'vue';
import { Popover, PopoverButton, PopoverPanel } from '@headlessui/vue'
import { useFloating, offset, flip, shift, autoUpdate } from '@floating-ui/vue'
const props = defineProps({
    categories: Object
});

console.log(props.categories);
const categories = ref([...props.categories]);

const addCategory = async () => {
    const res = await axios.post(route('back.categories.store'), {
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
    { label: '水果', items: [{ name: '蘋果', href: '#' }, { name: '香蕉', href: '#' }] },
    { label: '蔬菜', items: [{ name: '高麗菜', href: '#' }, { name: '節瓜', href: '#' }] },
    { label: '飲品', items: [{ name: '咖啡', href: '#' }] },
]

const items = [
    { id: 1, name: '蘋果' },
    { id: 2, name: '香蕉' },
    { id: 3, name: '高麗菜' },
]

const current = ref(null)
const mode = ref(null) // 'menu' | 'edit' | 'delete' | 'add'
const reference = ref(null)
const floating = ref(null)
const active = ref({ label: null })

const { floatingStyles, update } = useFloating(reference, floating, {
    placement: 'bottom-start',
    middleware: [offset(4), flip(), shift({ padding: 4 })]
})


const openPanel = (event, item, type) => {
    reference.value = event.currentTarget
    current.value = {
        ...item,
        type: type // 'category' | 'subcategory'
    }
    mode.value = 'menu'
    active.value.label = current.value.label;
    console.log(active.value);
    
    update()
}

const showEdit = () => {
    mode.value = 'edit'
    console.log(current.value);
}

const showDelete = () => {
    mode.value = 'delete'
}

const showAdd = () => {
    mode.value = 'add'
}

const handleDelete = () => {
    if (current.value.type === 'category') {
        // 刪除整個類別
        console.log('刪除類別', current.value.label)
        // menus.value = menus.value.filter(m => m.id !== current.value.id)
    } else {
        // 刪除子類別
        console.log('刪除子類別', current.value.name)
        // 找到父類別，刪除子項目
        // const parent = menus.value.find(m => m.items.some(i => i.id === current.value.id))
        // parent.items = parent.items.filter(i => i.id !== current.value.id)
    }
    close()
}

const handleUpdate = () => {
    // 更新邏輯
    console.log('更新', current.value)
    close()
}

const handleAdd = () => {
    // 新增邏輯
    console.log('新增', current.value)
    close()
}

const close = () => {
    current.value = null
    mode.value = null
    active.value.label = null;
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
        <div class="shadow bg-base-100 mt-6 px-6 py-5 ">
            <ul class="menu menu-horizontal bg-base-200 rounded-box w-full flex flex-col">
                <li v-for="menu in menus" :key="menu.id" class="">
                    <a @click="openPanel($event, menu, 'category')" class="text-lg font-semibold"
                        :class="{ 'menu-active': active.label == menu.label }">
                        {{ menu.label }}
                    </a>
                    <ul>
                        <li v-for="submenu in menu.items" :key="submenu.id">
                            <a @click="openPanel($event, submenu, 'subcategory')">
                                {{ submenu.name }}
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
                    <!-- 選單模式 -->
                    <div v-if="mode === 'menu'" class="min-w-[220px]">
                        <button v-if="current.type == 'category'"
                            class="block w-full text-left px-4 py-2 hover:bg-stone-900 hover:text-base-200 transition-colors"
                            @click="showAdd">
                            新增子類別
                        </button>
                        <button
                            class="block w-full text-left px-4 py-2 hover:bg-stone-900 hover:text-base-200 transition-colors"
                            @click="showEdit">
                            編輯
                        </button>
                        <button class="block w-full text-left px-4 py-2 hover:bg-error/10 text-error transition-colors"
                            @click="showDelete">
                            刪除
                        </button>
                    </div>

                    <!-- 新增模式 -->
                    <div v-else-if="mode === 'add'" class="w-80 p-4">
                        <h4 class="font-semibold mb-2">
                            新增「{{ current.label }}」的子類別
                        </h4>
                        <input class="input input-bordered input-sm w-full" />
                        <div class="mt-3 flex justify-end gap-2">
                            <button class="btn btn-ghost btn-sm" @click="close">取消</button>
                            <button class="btn btn-primary btn-sm" @click="handleAdd">新增</button>
                        </div>
                    </div>

                    <!-- 編輯模式 -->
                    <div v-else-if="mode === 'edit'" class="w-80 p-4">
                        <h4 class="font-semibold mb-2">
                            編輯{{ current.type === 'category' ? '類別' : '子類別' }}「{{ current.type === 'category' ?
                                current.label : current.name }}」
                        </h4>
                        <input class="input input-bordered input-sm w-full"
                            :value="current.type === 'category' ? current.label : current.name" />
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
                            <span v-if="current.type === 'category'" class="block mt-1 text-error">
                                ⚠️ 這將同時刪除所有子類別
                            </span>
                            此操作無法復原。
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

<style>
.menu {

    :where(li:not(.menu-title) > *:not(ul, details, .menu-title, .btn)),
    :where(li:not(.menu-title) > details > summary:not(.menu-title)) {
        display: block;
    }
}
</style>