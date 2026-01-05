<script setup>
import BackLayout from '@/Layouts/BackLayout.vue';
defineOptions({ layout: BackLayout })
import { ref, computed, watch, reactive, inject, provide } from 'vue';
import { router } from '@inertiajs/vue3';
import { useFloatPop } from '@/Composables/useFloatPop';
import axios from 'axios';
import Pagination from '@/DaisyComponents/Pagination.vue';
import EditDrawer from '@/DaisyComponents/EditDrawer.vue';
import HeadlessTab from '@/DaisyComponents/HeadlessTab.vue';
import BasicForm from '@/Pages/Back/Product/_BasicForm.vue';
import OptionsForm from '@/Pages/Back/Product/_OptionsForm.vue'
import ImagesForm from '@/Pages/Back/Product/_ImagesForm.vue'
import api from '@/Lib/apiFeedback';
import DOMPurify from 'dompurify';


const props = defineProps({
    products: Object,
    subcategories: Array,
    categories: Array,
    filters: Object
})
// console.log(props.subcategories);
console.log(props.categories);

console.log(props.filters);

const filterForm = reactive(
    {
        subcategory_id: props.filters.subcategory_id ?? '',
        name: props.filters.name ?? ''
    }
)

// const products = ref([...props.products.data]);
const products = computed(() => props.products.data);
const subSelects = computed(() => props.subcategories || [])

console.log(products.value);
console.log(props.products);
console.log(subSelects.value);


const columns = [
    { key: 'slug', label: 'Slug', width: 'w-[15%]' },
    { key: 'name', label: '產品名稱', width: 'w-[15%]' },
    { key: 'subcategory_name', label: '子類別', width: 'w-[15%]' },
    // { key: 'price', label: '價格', width: 'w-[10%]' },
    { key: 'description', label: '描述', width: 'w-[35%]' },
    { key: 'is_enabled', label: '啟用', width: 'w-[8%]' },
    { key: 'opt', label: '操作', width: 'w-auto' },
]

const {
    reference: descRef,
    floating: descFloating,
    currentContent: descContent,
    floatingStyles: descStyles,
    openHover: openDesc,
    closeHover: closeDesc
} = useFloatPop()

// Delete Confirmation Popover
const {
    reference: delRef,
    floating: delFloating,
    currentContent: delContent,
    floatingStyles: delStyles,
    openHover: openDel,
    closeHover: closeDel
} = useFloatPop({
    placement: 'bottom-start',
    offsetValue: 30,
    enableWidth: true,
    customWidth: 200
})

const pop = useFloatPop()
provide('descPop', pop)

const handlePageChange = (page) => {
    const cleanFilters = Object.fromEntries(Object.entries(filterForm).filter(([_, v]) => v !== '' && v !== null));
    router.get(route('back.products.index'), {
        page: page,
        ...cleanFilters
    }, {
        // preserveState: true,
        preserveScroll: true
    })
}


const addProduct = async () => {
    if (!validateProduct()) {
        return
    }

    const res = await api.post(route('back.products.store'), addForm.value);
    console.log(res.data);
    if (res.status === 201) {
        // products.value.push(res.data);
        const my_modal_1 = document.getElementById("my_modal_1");
        my_modal_1.close();
        await reloadPage()
    }
}

const reloadPage = () => {
    const cur = props.products.current_page
    const count = props.products.data.length
    const target = (count === 1 && cur > 1) ? cur - 1 : cur

    return router.visit(route('back.products.index', { page: target }), {
        replace: true,
        only: ['products'],
        preserveState: true,
        preserveScroll: true,
    })
}

// Edit Drawer
const ui = inject('backUI')
const showDrawer = ref(false)
const addForm = ref({})

// 開啟新增
const openAdd = () => {
    Object.keys(errors).forEach(key => delete errors[key])
    addForm.value = {
        slug: '',
        name: '',
        // price: '',
        description: '',
        is_enabled: true,
        subcategory_id: '',
        category_id: ''
    }
    console.log(addForm.value);
    const my_modal_1 = document.getElementById("my_modal_1");
    my_modal_1.showModal();
    // showDrawer.value = true
}

const loading = ref(false)

// 開啟編輯
const openEdit = async (id) => {
    loading.value = true
    editOpen.value = !editOpen.value;
    // ui.toggleSidebar();
    // ui.closeSidebar()
    ui.toggleDesktop()
    productDetails.value = null
    selectedTr.value = id;
    try {
        await getDetail(id)
    } catch (error) {
        console.error('載入失敗:', error)
        alert('載入失敗')
        loading.value = true
    } finally {
        loading.value = false
    }
}

// 刪除
const handleDelete = async () => {
    const res = await api.delete(route('back.products.destroy', delContent.value.id))
    // console.log(res);
    if (res.status === 204) {
        const idx = products.value.findIndex(e => e.id == delContent.value.id);
        if (idx !== -1) products.value.splice(idx, 1)
    }
    await reloadPage()
    closeDel()

}


const editOpen = ref(false)
watch(editOpen, (v) => {
    // if (!v) ui?.openSidebar?.()      // 編輯抽屜關閉 → 讓側欄回來
    if (!v) ui?.toggleDesktop?.()
    if (!v) selectedTr.value = null;
})

const productDetails = reactive({
    basic: {},
    images: [],
    options: [],
    subSelects: subSelects.value
})

const tabs = [
    { key: 'basic', label: '產品資料' },
    { key: 'options', label: '規格選項' },
    { key: 'images', label: '附圖管理' },
]

const getDetail = async (id) => {
    const res = await axios.get(route('back.product.details', id));
    console.log(res.data);
    productDetails.basic = res.data?.product || {};
    productDetails.images = res.data?.images || [];
    productDetails.options = res.data?.options || [];
}

const handleSaveBasic = async (formData) => {
    console.log(formData);
    formData.description = DOMPurify.sanitize(formData.description);
    const res = await api.put(route('back.products.update', formData.id), formData)
    console.log(res.data);
    if (res.status === 200) {
        const updated = products.value.find(e => e.id == formData.id);
        Object.assign(updated, {
            description: res.data.description,
            name: res.data.name,
            // price: res.data.price,
            subcategory_id: res.data.subcategory_id,
            subcategory_name: res.data.subcategory_name,
            slug: res.data.slug
        })
    }
}

const handleSaveOptions = async (newOptions) => {
    productDetails.options = newOptions;
}

const handleSaveImages = async (images) => {
    // 處理圖片儲存
    alert('圖片已儲存')
}

const deleteMessage = computed(() => {
    return `確定要刪除${delContent.value.name}嗎？`
})

const selectedTr = ref(null);

const changeStatus = async (row) => {
    // console.log(row.id);
    const id = row.id;
    const res = await api.patch(route('back.product.changeStatus', id));
    // console.log(res);
    if (res.status === 200) {
        row.is_enabled = res.data.is_enabled;
    }
}

const subcategoriesInCategory = ref([]);
const getSubcategories = async () => {
    const id = addForm.value.category_id;
    const res = await api.get(route('back.product.getSubcategories', id));
    console.log(res.data);
    subcategoriesInCategory.value = res.data;
}

const errors = reactive({})
const validateProduct = () => {
    Object.keys(errors).forEach(key => delete errors[key])

    if (!addForm.value.category_id) {
        errors.category_id = '請選擇類別'
    }
    if (!addForm.value.subcategory_id) {
        errors.subcategory_id = '請選擇子類別'
    }

    if (!addForm.value.name?.trim()) {
        errors.name = '請輸入產品名稱'
    }

    if (!addForm.value.slug?.trim()) {
        errors.slug = '請輸入 Slug'
    }
    // if (!addForm.value.price || addForm.value.price <= 0) {
    //     errors.price = '請輸入有效價格'
    // }
    return Object.keys(errors).length === 0
}

// 清除單一欄位錯誤
const clearError = (field) => {
    delete errors[field]
}
</script>

<template>
    <!-- <BackLayout> -->
    <div class="relative">
        <!-- 遮罩層 -->
        <div v-if="editOpen" class="absolute inset-0 z-10 overflow-hidden cursor-not-allowed" @click="() => { }">
        </div>
        <div>
            <p class="text-[#1E2328] text-lg font-semibold">
                產品
            </p>

            <div class="shadow bg-base-100 mt-6 px-6 py-5">
                <div class="my-4 flex gap-4 flex-wrap">
                    <label class="input input-sm w-full sm:w-60 md:w-68 lg:w-76">
                        <svg class="h-[1em] opacity-50" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <g stroke-linejoin="round" stroke-linecap="round" stroke-width="2.5" fill="none"
                                stroke="currentColor">
                                <circle cx="11" cy="11" r="8"></circle>
                                <path d="m21 21-4.3-4.3"></path>
                            </g>
                        </svg>
                        <input type="search" required placeholder="搜尋產品" v-model="filterForm.name"
                            @change="handlePageChange()" />
                    </label>

                    <select v-model="filterForm.subcategory_id" @change="handlePageChange()"
                        class="select select-sm w-full sm:w-40 md:w-48 lg:w-56">
                        <option value="" selected>選擇子類別</option>
                        <option v-for="sel in subSelects" :key="sel.id" :value="sel.id">
                            {{ sel.name }}
                        </option>
                    </select>
                </div>
                <div class="py-4 flex justify-start">
                    <button @click="openAdd" class="btn btn-sm">
                        新增產品
                    </button>
                </div>
                <div class="overflow-x-auto rounded-box border border-base-content/5 bg-base-100">
                    <table class="table w-full">
                        <colgroup>
                            <col v-for="c in columns" :key="c.key" :class="c.width" />
                        </colgroup>

                        <thead class="bg-[#fafbfc]">
                            <tr>
                                <th v-for="column in columns">
                                    {{ column.label }}
                                </th>
                            </tr>
                        </thead>

                        <tbody v-if="products.length">
                            <tr v-for="product in products" :key="product.id"
                                :class="{ 'bg-stone-100': selectedTr === product.id }">
                                <td v-for="col in columns" :key="col.key">
                                    <template v-if="col.key === 'is_enabled'">
                                        <span :class="product.is_enabled ? 'text-600' : 'text-gray-400'">
                                            <label class="toggle toggle-xs text-base-content"
                                                @click="changeStatus(product)">
                                                <input type="checkbox" disabled="true" class=""
                                                    :checked="product?.is_enabled == 0" />
                                                <svg aria-label="enabled" xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 24 24">
                                                    <g stroke-linejoin="round" stroke-linecap="round" stroke-width="4"
                                                        fill="none" stroke="currentColor">
                                                        <path d="M20 6 9 17l-5-5"></path>
                                                    </g>
                                                </svg>
                                                <svg aria-label="disabled" xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="4" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M18 6 6 18" />
                                                    <path d="m6 6 12 12" />
                                                </svg>
                                            </label>
                                        </span>
                                    </template>
                                    <template v-else-if="col.key === 'description'">
                                        <div class="line-clamp-1 hover:cursor-pointer"
                                            @click="openDesc($event, product)">
                                            <!-- {{ product.description }} -->
                                            <div v-html="product.description"></div>
                                        </div>
                                    </template>
                                    <template v-else-if="col.key === 'opt'">
                                        <div class="flex gap-2">
                                            <button class="btn btn-xs" @click="openEdit(product.id)">編輯</button>
                                            <button class="btn btn-xs text-red-600"
                                                @click="openDel($event, product)">刪除</button>
                                        </div>
                                    </template>
                                    <template v-else>
                                        {{ product[col.key] }}
                                    </template>
                                </td>
                            </tr>
                        </tbody>

                        <tbody v-else>
                            <tr>
                                <td :colspan="columns.length + 1" class="text-center text-sm text-base-content/60 py-8">
                                    沒有資料
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>


            <!-- floating pop 顯示區域 -->
            <Teleport to="body">
                <Transition enter-active-class="transition-opacity duration-200 ease-out" enter-from-class="opacity-0"
                    enter-to-class="opacity-100" leave-active-class="transition-opacity duration-150 ease-in"
                    leave-from-class="opacity-100" leave-to-class="opacity-0">
                    <div v-if="descContent?.description" ref="descFloating" :style="descStyles"
                        class="fixed z-50 max-h-64 p-2 rounded-xl bg-base-100 shadow-xl leading-relaxed break-words whitespace-pre-line overflow-auto">
                        <div class="max-w-100">
                            <div v-html="descContent.description"></div>
                            <!-- {{ descContent.description }} -->
                        </div>
                    </div>
                </Transition>

                <Transition enter-active-class="transition-opacity duration-200 ease-out" enter-from-class="opacity-0"
                    enter-to-class="opacity-100" leave-active-class="transition-opacity duration-150 ease-in"
                    leave-from-class="opacity-100" leave-to-class="opacity-0">
                    <div v-if="delContent" ref="delFloating" :style="delStyles"
                        class="fixed z-50 p-4 rounded-xl bg-base-100 shadow-xl">
                        <p class="text-sm text-base-content/70 mb-4">
                            {{ deleteMessage }}
                        </p>
                        <div class="flex gap-2 justify-end">
                            <button @click="closeDel" class="btn btn-sm">取消</button>
                            <button @click="handleDelete()" class="btn btn-sm btn-error text-base-200">確認刪除</button>
                        </div>
                    </div>
                </Transition>
            </Teleport>

            <div class="mt-4">
                <Pagination :pagination="props.products" @change="handlePageChange" />
            </div>
        </div>

        <EditDrawer v-model:editOpen="editOpen">
            <div v-if="loading" class="flex items-center justify-center h-full">
                <div class="text-center">
                    <span class="loading loading-spinner loading-lg"></span>
                    <p class="mt-4 text-sm text-gray-500">載入中...</p>
                </div>
            </div>
            <HeadlessTab v-else-if="productDetails" :data="productDetails" :tabs="tabs">
                <!-- 基本資料 Tab -->
                <!-- data為headless傳回來的值，再傳給basicForm -->
                <template #basic="{ data }">
                    <BasicForm :product="data.basic" :subSelects="data.subSelects" @save="handleSaveBasic" />
                </template>

                <!-- 選項管理 Tab -->
                <template #options="{ data }">
                    <OptionsForm :options="data.options" :productId="data.basic.id" @save="handleSaveOptions" />
                </template>

                <!-- 圖片管理 Tab -->
                <template #images="{ data }">
                    <ImagesForm :images="data.images" :productId="data.basic.id" @save="handleSaveImages" />
                </template>
            </HeadlessTab>
        </EditDrawer>

        <dialog id="my_modal_1" class="modal">
            <div class="modal-box max-h-150">
                <h3 class="text-lg font-bold mb-4">新增產品</h3>
                <div class="space-y-4">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">類別</span>
                        </label>
                        <select class="select select-bordered w-full" v-model="addForm.category_id"
                            :class="{ 'select-error': errors.category_id }"
                            @change="getSubcategories(); clearError('category_id')">
                            <option value="" selected disabled>選擇子類別</option>
                            <option v-for="category in props.categories" :key="category.id" :value="category.id">{{
                                category.name }}</option>
                        </select>
                        <label v-if="errors.category_id" class="label">
                            <span class="label-text-alt text-error text-sm">{{ errors.category_id }}</span>
                        </label>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">子類別</span>
                        </label>
                        <select class="select select-bordered w-full" :class="{ 'select-error': errors.subcategory_id }"
                            v-model="addForm.subcategory_id" @change="clearError('subcategory_id')">
                            <option value="" selected disabled>選擇子類別</option>
                            <option v-for="sub in subcategoriesInCategory" :key="sub.id" :value="sub.id">{{ sub.name }}
                            </option>
                        </select>

                        <label v-if="errors.subcategory_id" class="label">
                            <span class="label-text-alt text-error text-sm">{{ errors.subcategory_id }}</span>
                        </label>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">產品名稱</span>
                        </label>
                        <input v-model="addForm.name" class="input input-bordered w-full" @change="clearError('name')"
                            :class="{ 'input-error': errors.name }" />
                        <label v-if="errors.name" class="label">
                            <span class="label-text-alt text-error text-sm">{{ errors.name }}</span>
                        </label>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Slug</span>
                        </label>
                        <input v-model="addForm.slug" class="input input-bordered w-full" @change="clearError('slug')"
                            :class="{ 'input-error': errors.slug }" />
                        <label v-if="errors.slug" class="label">
                            <span class="label-text-alt text-error text-sm">{{ errors.slug }}</span>
                        </label>
                    </div>

                    <!-- <div>
                        <label class="label">
                            <span class="label-text">價格</span>
                        </label>
                        <input v-model="addForm.price" type="number" class="input input-bordered w-full"
                            @change="clearError('price')" :class="{ 'input-error': errors.price }" />
                        <label v-if="errors.price" class="label">
                            <span class="label-text-alt text-error text-sm">{{ errors.price }}</span>
                        </label>
                    </div> -->

                    <div>
                        <label class="label">
                            <span class="label-text">描述</span>
                        </label>
                        <textarea v-model="addForm.description" class="textarea textarea-bordered w-full"
                            rows="4"></textarea>
                    </div>

                    <div>
                        <label class="label cursor-pointer justify-start gap-2">
                            <input v-model="addForm.is_enabled" type="checkbox" class="checkbox" />
                            <span class="label-text">啟用</span>
                        </label>
                    </div>
                </div>
                <div class="modal-action">
                    <button @click="addProduct" class="btn btn-primary">
                        新增
                    </button>
                    <form method="dialog">
                        <button class="btn">關閉</button>
                    </form>
                </div>
            </div>
        </dialog>

    </div>
</template>

<style>
.text-chop {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
</style>