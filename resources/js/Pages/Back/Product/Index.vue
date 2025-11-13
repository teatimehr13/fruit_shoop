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



const props = defineProps({
    products: Object,
    subcategories: Array,
    filters: Object
})
// console.log(props.subcategories);
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
    { key: 'price', label: '價格', width: 'w-[10%]' },
    { key: 'description', label: '描述', width: 'w-[35%]' },
    { key: 'is_enabled', label: '狀態', width: 'w-[5%]' },
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
    const file = document.getElementById('file').files[0];
    const fd = new FormData();
    fd.append('subcategory_id', 1);
    fd.append('slug', 'test-slug');
    fd.append('name', 'test name');
    fd.append('price', 1999);
    fd.append('description', 'desc');
    fd.append('is_enabled', '1');
    if (file) fd.append('image', file); // 這裡才是真正的檔案

    const res = await axios.post(route('back.products.store'), fd, {
        headers: { Accept: 'application/json' },
        validateStatus: s => s < 500
    });

    // if (res.status === 201) {

    // } else if (res.status === 422) {
    //     console.log(res.data.errors);
    // }

    console.log(res.data);

}

const updProduct = async (id) => {
    const file = document.getElementById('file').files[0];
    const fd = new FormData();
    fd.append('subcategory_id', 1);
    fd.append('slug', 'test-slug2');
    fd.append('name', 'test name2');
    fd.append('price', 19992);
    fd.append('description', 'desc2');
    fd.append('is_enabled', '1');
    fd.append('_method', 'PUT') //form表單不知援axios.put
    // fd.append('remove_image', '1'); //只刪圖不更新

    if (file) fd.append('image', file);

    // for (const [k, v] of fd.entries()) console.log(k, v)

    const res = await axios.post(route('back.products.update', id), fd, {
        headers: { Accept: 'application/json' },
        validateStatus: s => s < 500
    });

    console.log(res.data);
    // const r = await axios.get(route('back.products.index.json'));
    // categories.value = r.data;

}

const delProduct = async (id) => {
    const res = await axios.delete(route('back.products.destroy', id));
    console.log(res.status);

    // const r = await axios.get(route('back.products.index.json'));
    // categories.value = r.data;
}

const getOptions = async (id) => {
    const res = await axios.get(route('back.product.options.index', id));
    console.log(res.data);
}

const delOptions = async (optId) => {
    const res = await axios.delete(route('back.options.destroy', optId));
    console.log(res);
}

const addOptions = async (id) => {
    const fd = new FormData();
    fd.append('product_id', id);
    fd.append('option_text', 'option_text');
    fd.append('original_price', 2500);
    fd.append('price', 1999);
    fd.append('inventory', 10);
    fd.append('is_enabled', '1');

    const res = await axios.post(route('back.product.options.store', id), fd, {
        headers: { Accept: 'application/json' },
        validateStatus: s => s < 500
    });
    console.log(res.data);
}

const updOptions = async (optId) => {
    const fd = new FormData();
    fd.append('option_text', 'option_text');
    fd.append('original_price', 500);
    fd.append('price', 999);
    fd.append('inventory', 5);
    fd.append('is_enabled', '1');
    fd.append('_method', 'PUT')

    const res = await axios.post(route('back.options.update', optId), fd, {
        headers: { Accept: 'application/json' },
        validateStatus: s => s < 500
    });
    console.log(res.data);
}

const getImages = async (id) => {
    const res = await axios.get(route('back.product.images.index', id));
    console.log(res.data);
}

const addImages = async (id) => {
    const files = document.getElementById('file').files;
    const fd = new FormData();
    console.log(files);

    Array.from(files).forEach((file, i) => {
        fd.append(`productImages[${i}][product_id]`, id);
        fd.append(`productImages[${i}][alt_text]`, file.name);
        fd.append(`productImages[${i}][is_primary]`, '0');

        if (file) fd.append(`productImages[${i}][image]`, file);
    })

    for (const [key, val] of fd) {
        console.log(key, 'value =>', val);

    }

    const res = await axios.post(route('back.product.images.store', id), fd, {
        headers: { Accept: 'application/json' },
        validateStatus: s => s < 500
    });
    console.log(res.data);
}

const delImages = async () => {
    const id_s = [18, 19, 20];
    const res = await axios.post(route('back.product.images.destroymany'), { ids: id_s }, {
        headers: { Accept: 'application/json' },
        validateStatus: s => s < 500
    });

    console.log(res);
}

const updImageText = async (id) => {
    const data = { alt_text: 'hello00' };
    const res = await axios.patch(route('back.images.update', id), data, {
        headers: { Accept: 'application/json' },
        validateStatus: s => s < 500
    })
    console.log(res);
}

const setPrimary = async (id) => {
    const res = await axios.patch(route('back.product.images.primary', id));

    console.log(res);

}

// Edit Drawer
const ui = inject('backUI')
const showDrawer = ref(false)
const editingProduct = ref(null)

// 開啟新增
const openAdd = () => {
    editingProduct.value = {
        slug: '',
        name: '',
        price: '',
        description: '',
        is_enabled: true
    }
    showDrawer.value = true
}

const loading = ref(false)

// 開啟編輯
const openEdit = async (id) => {
    loading.value = true
    editOpen.value = !editOpen.value;
    ui.toggleSidebar();
    productDetails.value = null
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


// 儲存
const handleSave = async (formData) => {
    try {
        if (formData.id) {
            // 編輯
            await axios.put(route('back.products.update', formData.id), formData, {
                headers: { Accept: 'application/json' },
                validateStatus: s => s < 500
            })
        } else {
            // 新增
            await axios.post(route('back.products.store'), formData, {
                headers: { Accept: 'application/json' },
                validateStatus: s => s < 500
            })
        }

        // 重新載入
        router.reload({ only: ['products'] })
        showDrawer.value = false
    } catch (error) {
        console.error(error)
        alert('儲存失敗')
    }
}

// 刪除
const handleDelete = async () => {
    try {
        const cur = props.products.current_page
        const count = props.products.data.length
        const target = (count === 1 && cur > 1) ? cur - 1 : cur

        const res = await api.delete(route('back.products.destroy', delContent.value.id))
        console.log(res);

        if (res.status === 204) {
            const idx = products.value.findIndex(e => e.id == delContent.value.id);
            if (idx !== -1) products.value.splice(idx, 1)
        }

        await router.visit(route('back.products.index', { page: target }), {
            replace: true,
            only: ['products'],
            preserveState: true,
            preserveScroll: true,
        })

        closeDel()
    } catch (error) {
        console.error(error)
        alert('刪除失敗')
    }
}


const editOpen = ref(false)
watch(editOpen, (v) => {
    if (!v) ui?.openSidebar?.()      // 編輯抽屜關閉 → 讓側欄回來
})

const productDetails = reactive({
    basic: {},
    images: [],
    options: []
})

const tabs = [
    { key: 'basic', label: '產品資料' },
    { key: 'options', label: '規格選項' },
    { key: 'images', label: '附圖管理' },
]

const getDetail = async (id) => {
    const res = await axios.get(route('back.product.details', id));
    // console.log(res.data);
    productDetails.basic = res.data?.product || {};
    productDetails.images = res.data?.images || [];
    productDetails.options = res.data?.options || [];
}

const handleSaveBasic = async (formData) => {
    console.log(formData);
    const res = await api.put(route('back.products.update', formData.id), formData)
    console.log(res.data);
    if (res.status === 200) {
        const updated = products.value.find(e => e.id == formData.id);
        Object.assign(updated, {
            description: res.data.description,
            name: res.data.name,
            price: res.data.price,
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

</script>

<template>
    <!-- <BackLayout> -->
    <div class="flex">
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
                            <tr v-for="product in products" :key="product.id">
                                <td v-for="col in columns" :key="col.key">
                                    <template v-if="col.key === 'is_enabled'">
                                        <span :class="product.is_enabled ? 'text-600' : 'text-gray-400'">
                                            <!-- {{ product.is_enabled == 1 ? '啟用' : '未啟用' }} -->
                                            <label class="toggle toggle-xs text-base-content">
                                                <input type="checkbox" disabled="true"
                                                    :checked="product?.is_enabled == 1" />
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
                                            {{ product.description }}
                                        </div>
                                    </template>
                                    <template v-else-if="col.key === 'opt'">
                                        <div class="flex gap-2">
                                            <button class="btn btn-xs" @click="openEdit(product.id)">編輯</button>
                                            <button class="btn btn-xs text-red-600"
                                                @click="openDel($event, product)">刪除</button>
                                            <div class="tooltip" data-tip="附圖及選項">
                                                <button class="btn btn-xs text-blue-600 whitespace-nowrap"
                                                    @click="getDetail(product.id)">更多</button>
                                            </div>
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
                    <!-- <div v-if="currentContent?.description" ref="floating" :style="floatingStyles" class="fixed z-50 max-h-64 p-2 rounded-xl bg-base-100 shadow-xl
             leading-relaxed break-words whitespace-pre-line overflow-auto">
                        <div class="max-w-100">
                            {{ currentContent?.description }}
                        </div>
                    </div>

                    <div v-else-if="del">
                        <p class="text-sm text-base-content/70 mb-4">
                            {{ deleteMessage }}
                            <span class="block mt-2">此操作無法復原。</span>
                        </p>
                    </div> -->
                    <div v-if="descContent?.description" ref="descFloating" :style="descStyles"
                        class="fixed z-50 max-h-64 p-2 rounded-xl bg-base-100 shadow-xl leading-relaxed break-words whitespace-pre-line overflow-auto">
                        <div class="max-w-100">
                            {{ descContent.description }}
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
                    <BasicForm :product="data.basic" @save="handleSaveBasic" />
                </template>

                <!-- 選項管理 Tab -->
                <template #options="{ data }">
                    <OptionsForm :options="data.options" :productId="data.basic.id" @save="handleSaveOptions" />
                </template>

                <!-- 圖片管理 Tab -->
                <template #images="{ data }">
                    <ImagesForm :images="data.images" @save="handleSaveImages" />
                </template>
            </HeadlessTab>
        </EditDrawer>

    </div>

    <!-- <button @click="addProduct">
            addProduct
        </button> -->
    <!-- <button @click="updProduct('16')">
            updProduct
        </button> -->
    <!-- <button @click="delProduct('17')">
            delProduct
        </button> -->

    <!-- <input id="file" type="file" multiple /> -->

    <!-- <button @click="getOptions(21)">
            getOptions
        </button> -->

    <!-- <button @click="addOptions(21)">
            addOptions
        </button> -->

    <!-- <button @click="updOptions(25)">
            updOptions
        </button> -->
    <!-- <button @click="delOptions(25)">
            delOptions
        </button> -->

    <!-- <button @click="getImages(23)">
            getImages
        </button> -->
    <!-- 
        <button @click="addImages(23)">
            addImages
        </button>

        <button @click="delImages()">
            delImages
        </button>

        <button @click="updImageText(4)">
            updImageText
        </button>

        <button @click="setPrimary(24)">
            setPrimary
        </button> -->

    <!-- </BackLayout> -->

</template>

<style>
.text-chop {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
</style>