<script setup>
import FrontLayout from '@/Layouts/FrontLayout.vue';
import HomeHero from './_HomeHero.vue';
import Feature from './_Feature.vue';
import Category from './_Category.vue';
import TopPicks from './_TopPicks.vue';
import PageHero from './_PageHero.vue';
import { computed, inject, reactive, ref, watch } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import PillsSwiper from './_PillsSwiper.vue';
import axios from 'axios';
// import CartDrawer from '@/DaisyComponents/Front/CartDrawer.vue';

const handleRemove = (id) => {
    console.log('remove item id:', id)
}

defineOptions({
    layout: FrontLayout,
})

const props = defineProps({
    categories_tab: Object,
    products: Object,
    activeCategory: String
})


console.log(props.categories_tab);
console.log(props.products);
console.log(props.activeCategory);

const filterForm = reactive({})
const categoryName = props.activeCategory ? ref(props.activeCategory) : ref('ALL');

const sortData = () => {
    // console.log(category.name);
    console.log(categoryName.value);
    const cleanFilters = Object.fromEntries(Object.entries(filterForm).filter(([_, v]) => v !== '' && v !== null));

    const isDefaultSort = selectedSort.value.by === 'created_at' && selectedSort.value.dir === 'desc'

    if (!isDefaultSort) {
        cleanFilters.sort_by = selectedSort.value.by
        cleanFilters.sort_dir = selectedSort.value.dir
    }

    if (categoryName.value == 'ALL') {
        router.get(route('products.index'), {
            ...cleanFilters
        }, {
            preserveState: true,
            preserveScroll: true,
        })
    } else {
        router.get(route('categories.products', { category: categoryName.value }), cleanFilters, {
            preserveState: true,
            preserveScroll: true,
            // only: ['products'],
            // onStart: () => {
            //     isUpdating.value = true
            // },
            // onFinish: () => {
            //     setTimeout(() => {
            //         isUpdating.value = false
            //     }, 50)
            // },
        })
    }
}

const onSelectCategory = (name) => {
    categoryName.value = name
    sortData()
}

const sortOptions = [
    { label: '最新上市優先', by: 'created_at', dir: 'desc' },
    { label: '價格由高到低', by: 'price', dir: 'desc' },
    { label: '價格由低到高', by: 'price', dir: 'asc' },
]

const selectedSort = ref(sortOptions[0])

//cartdrawer 開關
// const isCartOpen = inject('isCartOpen');
const openCart = inject('openCart')

const submitToCart = async (optionId) => {
    const payload = {
        product_option_id: optionId,
        qty: 1
    }

    const res = await axios.post(route('cart.store'), payload)
    console.log(res.data)

    await openCart()
    await router.reload({
        only: ['cartItems'],
        preserveScroll: true,
    })
}

const quickAddToCart = async (product) => {
    const { product_options } = product

    if (product_options?.length > 1) {
        // 有多個選項，顯示選擇面板
        toggleSelectCard(product.id)
    } else {
        // 只有一個選項，直接加入
        const selectedOption = getSelectedOption(product)
        await submitToCart(selectedOption.id)
    }
}

// 確認加入（選項面板內的加入按鈕）
const confirmAddToCart = async (product) => {
    const selectedOption = getSelectedOption(product)
    await submitToCart(selectedOption.id)
}

const selectToCartId = ref(null);
const toggleSelectCard = (id) => {
    if (id) {
        selectToCartId.value = id
    } else {
        selectToCartId.value = null
    }
}

// {5:10, 8:20 ...}
const selectedOptions = ref({})

//初始化select 預設值為 cheapest option
watch(() => props.products, (newProducts) => {
    if (newProducts) {
        newProducts.forEach(product => {
            selectedOptions.value[product.id] = product.cheapest_option_id
        })
    }
}, { immediate: true })

//拿到選中的option
const getSelectedOption = (product) => {
    // console.log(selectedOptions.value[product.id]);
    const selectedOptionId = selectedOptions.value[product.id]
    return product.product_options?.find(opt => opt.id === selectedOptionId)
}

const handleOptionChange = (product) => {
    getSelectedOption(product);
}

const formatTwd = (price) => {
    return `$ ${price?.toLocaleString() || 0}`
}
</script>

<template>
    <PageHero />

    <section class="mt-4 max-w-[var(--max-w-layout-wide)] mx-auto px-4">
        <PillsSwiper class="md:hidden mt-4" :categories="props.categories_tab" :activeCategory="categoryName"
            @select-category="onSelectCategory" />

        <div class="md:flex md:mt-8 md:mb-8 lg:mt-12">
            <div class="hidden md:block md:basis-2/5 lg:basis-1/4">
                <aside class="mr-8 p-4 border border-[#f1f0ed] rounded-[12px] bg-[#fafafa]">
                    <div class="mb-4 relative before:content-[''] before:absolute before:inset-x-0 before:-bottom-2
                before:h-[1px] before:bg-[#67645e] before:block">
                        <h3 class="text-md md:text-lg lg:text-xl font-semibold text-[#67645e]">
                            產品分類
                        </h3>
                    </div>
                    <ul>
                        <li class="text-[#67645e] px-2 py-1 rounded-sm"
                            :class="{ 'bg-[#82ae46] text-base-100': categoryName == 'ALL' }">
                            <a href="#" class="block w-full" :class="{ 'hover:text-zinc-400': categoryName !== 'ALL' }"
                                @click.prevent="onSelectCategory('ALL')">全部</a>
                        </li>
                        <li v-for="value in props.categories_tab" class="text-[#67645e] px-2 py-1 rounded-sm"
                            :class="{ 'bg-[#82ae46] text-base-100': value.name == categoryName }">
                            <a href="#" class="block w-full "
                                :class="{ 'hover:text-zinc-400': value.name !== categoryName }" @click.prevent="
                                    onSelectCategory(value.name)">
                                {{ value.name }}
                            </a>
                        </li>
                    </ul>
                </aside>
            </div>

            <div class="md:basis-3/5 lg:basis-3/4">
                <div class="py-2 my-4 flex w-full items-center rounded-[12px]">
                    <div class="w-[50%] flex items-center ">
                        <label for="sort_product" class="align-middle whitespace-nowrap">排序:</label>
                        <select class="border-b-1 border-[#67645e] w-full md:w-50 lg:w-58 py-1" id="sort_product"
                            v-model="selectedSort" @change="sortData">
                            <option v-for="(opt, idx) in sortOptions" :key="idx" :value="opt">
                                {{ opt.label }}
                            </option>
                        </select>
                    </div>

                    <div class="w-[50%] text-end">
                        <span class="text-[#82ae46] text-lg md:text-xl xl:text-2xl">
                            {{ props.products?.length }}
                        </span>
                        件商品
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3 md:gap-5 xl:grid-cols-3 mb-4">
                    <div v-for="value in props.products"
                        class="relative border border-[#f0f0f0] rounded-[12px] overflow-hidden flex flex-col"
                        :key="value.id">
                        <div class="overflow-hidden">
                            <a :href="route('products.show', value.slug)">
                                <img :src="value.primary_image?.img_url || '/images/categories/ChatGPT Image 2025年11月29日 下午02_44_25.png'"
                                    alt="" loading="lazy" decoding="async"
                                    class="transition-all duration-200 hover:scale-110 object-cover aspect-3/2 w-full h-full">
                            </a>
                        </div>
                        <div class="flex flex-col px-4 pb-4 gap-1 mt-4">
                            <div class="text-sm sm:text-base md:text-lg">
                                {{ value.name }}
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <div class="text-[#82ae46] text-sm sm:text-base md:text-lg">
                                        {{ formatTwd(value.cheapest_price) }}
                                    </div>
                                    <div v-if="value.has_discount"
                                        class="line-through text-gray-400 text-xs sm:text-sm ">
                                        {{ formatTwd(value.cheapest_original_price) }}
                                    </div>
                                </div>
                                <div>
                                    <button @click="quickAddToCart(value)"
                                        class="btn btn-ghost btn-circle text-[#67645e] hover:bg-[#82ae46] hover:text-base-100">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="size-5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="fixed left-0 md:absolute bottom-0 h-[45%] md:h-[80%] w-full rounded-[12px] p-4 bg-neutral-100/95 transition-transform duration-500 z-10"
                            :class="selectToCartId == value.id ? 'translate-y-[0%]' : 'translate-y-[100%]'">

                            <div class="flex flex-col h-full gap-3">
                                <!-- Top block: take more space -->
                                <div class="flex-1">
                                    <div
                                        class="grid grid-cols-[minmax(auto,100px)_1fr_32px] md:grid-cols-[1fr_1fr_32px] items-start gap-4">
                                        <div class="w-full aspect-square overflow-hidden rounded bg-white">
                                            <img :src="value.primary_image?.img_url" alt=""
                                                class="w-full h-full object-cover" />
                                        </div>

                                        <div class="min-w-0">
                                            <div class="text-base font-medium leading-snug line-clamp-2 pt-2">
                                                {{ value.name }}
                                            </div>
                                        </div>

                                        <button type="button" class="w-8 h-8 options-close" @click="toggleSelectCard">
                                            <span></span>
                                        </button>
                                    </div>
                                </div>

                                <!-- Bottom block: smaller -->
                                <div class="flex flex-1 flex-col justify-between gap-4">
                                    <div>
                                        <label>選項</label>
                                        <select class="select-md border-b-1 border-[#67645e] px-0 py-1 w-full outline-none focus:border-gray-500
                                            appearance-none bg-select-arrow bg-no-repeat bg-right"
                                            v-model="selectedOptions[value.id]" @change="handleOptionChange(value)">
                                            <option v-for="(opt, idx) in value.product_options" :key="idx"
                                                :value="opt.id">
                                                {{ opt.option_text }}
                                            </option>
                                        </select>
                                    </div>

                                    <div class="">
                                        <button type="button" @click="confirmAddToCart(value)"
                                            class="btn btn-md md:btn-sm w-full border-[#82ae46] bg-transparent text-[#82ae46] rounded-[40px]
                                                    hover:bg-[#82ae46] hover:text-white hover:border-[#82ae46] transition-colors">
                                            加入購物車 - {{ formatTwd(getSelectedOption(value)?.price) }}
                                        </button>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>
    <!-- <section> -->
    <!-- <HomeHero /> -->
    <!-- </section> -->
    <!-- icon feature-->
    <!-- <Feature /> -->

    <!-- category gallery -->
    <!-- <section></section>
      -->
    <!-- <Category /> -->
    <!-- recommand -->
    <!-- <section></section> -->
    <!-- <TopPicks /> -->
</template>

<style>
.bg-select-arrow {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='%236B7280'%3E%3Cpath fill-rule='evenodd' d='M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z' clip-rule='evenodd' /%3E%3C/svg%3E");
    background-size: 1.25rem;
    /* 箭頭大小 */
    padding-right: 1.5rem;
    /* 留出空間給箭頭 */
}

select::-ms-expand {
    display: none;
}

.options-close {
    position: absolute;
    right: .5em;
    top: .5rem;
    cursor: pointer;
    border-radius: 50%;
    background: #82ae46;
    width: 1.25rem;
    height: 1.25rem;
    opacity: 1;
    transition: all .7s cubic-bezier(.76, 0, .24, 1);
    min-width: 1.25rem;
    margin-left: 1rem;
}

.options-close span {
    width: 55%;
    height: 2px;
    background-color: #fff;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    transition: all .7s cubic-bezier(.76, 0, .24, 1);
}
</style>