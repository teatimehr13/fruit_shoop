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
const reference = ref(null)
const floating = ref(null)

const { floatingStyles, update } = useFloating(reference, floating, {
    placement: 'bottom-start',
    middleware: [offset(4), flip(), shift({ padding: 4 })]
})

const openPanel = (event, submenu) => {
    reference.value = event.currentTarget
    current.value = submenu
    update() // 更新位置
}

const close = () => {
    current.value = null
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
        category

        <!-- <button @click="addCategory">
            test
        </button>

        <button @click="updCategory(47)">
            update
        </button>

          <button @click="delCategory(47)">
            del
        </button> -->

        <ul class="menu menu-horizontal bg-base-200 rounded-box">
            <li v-for="menu in menus" :key="menu.id">
                <a>{{ menu.label }}</a>
                <ul>
                    <li v-for="submenu in menu.items" :key="submenu.id">
                        <button class="block w-full text-left px-3 py-2 rounded hover:bg-base-200"
                            @click="openPanel($event, submenu)">
                            {{ submenu.name }}
                        </button>
                    </li>
                </ul>
            </li>
        </ul>

        <Teleport to="body">
            <Transition enter-active-class="transition-opacity duration-200 ease-out" enter-from-class="opacity-0"
                enter-to-class="opacity-100" leave-active-class="transition-opacity duration-150 ease-in"
                leave-from-class="opacity-100" leave-to-class="opacity-0">
                <div v-if="current" ref="floating" :style="floatingStyles"
                    class="fixed w-80 rounded-xl border border-base-300 bg-base-100 p-4 shadow-xl z-50">
                    <h4 class="font-semibold mb-2">編輯「{{ current.name }}」</h4>
                    <input class="input input-bordered input-sm w-full" :value="current.name" />
                    <div class="mt-3 flex justify-end gap-2">
                        <button class="btn btn-ghost btn-sm" @click="close">取消</button>
                        <button class="btn btn-primary btn-sm">更新</button>
                    </div>
                </div>
            </Transition>
        </Teleport>
    </BackLayout>

</template>

<style>
/* .menu li ul:before {
    position: absolute;
    inset-inline-start: calc(0.25rem * 0);
    top: calc(0.25rem * 3);
    bottom: calc(0.25rem * 3);
    background-color: var(--color-base-content);
    opacity: 0% !important;
    width: 0;
    content: "";
    margin-inline-start: calc(0.25rem * 2);
    padding-inline-start: calc(0.25rem * 2);
} */

/* .menu {
    :where(li ul) {
        position: relative;
        margin-inline-start: calc(0.25rem * 0);
        padding-inline-start: calc(0.25rem * 0);
        white-space: nowrap;
    }
} */

.menu {

    :where(li:not(.menu-title) > *:not(ul, details, .menu-title, .btn)),
    :where(li:not(.menu-title) > details > summary:not(.menu-title)) {
        display: block;
    }
}
</style>