<script setup>
import FrontLayout from '@/Layouts/FrontLayout.vue';
import HomeHero from './_HomeHero.vue';
import Feature from './_Feature.vue';
import Category from './_Category.vue';
import TopPicks from './_TopPicks.vue';
import PageHero from './_PageHero.vue';
import { computed, inject, reactive, ref } from 'vue';
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


const formatTwd = (value) => {
    if (value == null) return ''

    return `$ ${Number(value).toLocaleString('zh-TW')}`
}

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


const addToCartPayload = reactive({
    product_option_id: null,
    qty: 1,
})

const addToCart = async (option_id) => {
    console.log(option_id);
    addToCartPayload.product_option_id = option_id;
    console.log(addToCartPayload);

    const res = await axios.post(route('cart.store'), addToCartPayload);
    console.log(res.data);

    await openCart()
    await router.reload({
        only: ['cartItems'],  // 對應 share 的 key
        preserveScroll: true,
    })
}




</script>

<template>
    <PageHero />

    <section class="mt-4">
        <PillsSwiper class="md:hidden mt-4" :categories="props.categories_tab" :activeCategory="categoryName"
            @select-category="onSelectCategory" />

        <div class="md:flex md:mt-8 md:mb-8 lg:mt-12">
            <div class="hidden md:block md:basis-1/4 xl:basis-1/5">
                <aside class="mr-8 p-4 px-8 border border-[#f1f0ed] rounded-[12px] bg-[#fafafa]">
                    <div class="mb-4 relative before:content-[''] before:absolute before:inset-x-0 before:-bottom-2
                before:h-[1px] before:bg-[#67645e] before:block">
                        <h3 class="text-md md:text-lg lg:text-xl font-semibold text-[#67645e]">
                            產品分類
                        </h3>
                    </div>
                    <ul>
                        <li class="text-[#67645e] px-2 py-1 rounded-sm"
                            :class="{ 'bg-[#67645e] text-base-100': categoryName == 'ALL' }">
                            <a href="#" class="block w-full" :class="{ 'hover:text-zinc-400': categoryName !== 'ALL' }"
                                @click.prevent="onSelectCategory('ALL')">全部</a>
                        </li>
                        <li v-for="value in props.categories_tab" class="text-[#67645e] px-2 py-1 rounded-sm"
                            :class="{ 'bg-[#67645e] text-base-100': value.name == categoryName }">
                            <a href="#" class="block w-full "
                                :class="{ 'hover:text-zinc-400': value.name !== categoryName }" @click.prevent="
                                    onSelectCategory(value.name)">
                                {{ value.name }}
                            </a>
                        </li>
                    </ul>
                </aside>
            </div>

            <div class="md:basis-3/4 xl:basis-4/5 ">
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
                        <span class="text-green-600 text-lg md:text-xl xl:text-2xl">
                            {{ props.products?.length }}
                        </span>
                        件商品
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-2 md:gap-4 md:grid-cols-3 xl:gap-8 xl:grid-cols-4">
                    <div v-for="value in props.products"
                        class="relative border border-[#f0f0f0] rounded-[12px] overflow-hidden flex flex-col"
                        :key="value.id">
                        <div class="overflow-hidden">
                            <a href="">
                                <img :src="value.primary_image?.img_url || '/images/categories/ChatGPT Image 2025年11月29日 下午02_44_25.png'"
                                    alt=""
                                    class="transition-all duration-200 hover:scale-110 object-cover aspect-3/2 w-full h-full">
                            </a>
                        </div>
                        <div class="flex flex-col px-4 pb-4 gap-1 mt-4">
                            <div class="text-sm sm:text-base md:text-lg">
                                {{ value.name }}
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <div class="text-green-600 text-sm sm:text-base md:text-lg">
                                        {{ formatTwd(value.cheapest_price) }}
                                    </div>
                                    <div v-if="value.has_discount"
                                        class="line-through text-gray-400 text-xs sm:text-sm ">
                                        {{ formatTwd(value.cheapest_original_price) }}
                                    </div>
                                </div>
                                <div>
                                    <button @click="addToCart(value.cheapest_option_id)"
                                        class="btn btn-ghost btn-circle text-gray-500 hover:bg-green-600 hover:text-base-100">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="size-5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                                        </svg>
                                    </button>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- <button type="button" class="relative btn btn-ghost btn-circle" @click="isCartOpen = true">
            🛒
        </button> -->

        <!-- <CartDrawer :open="isCartOpen" @close="isCartOpen = false" @remove="handleRemove" /> -->
        <!-- <CartDrawer :open="isCartOpen" @close="isCartOpen = false" @remove="handleRemove"/> -->
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