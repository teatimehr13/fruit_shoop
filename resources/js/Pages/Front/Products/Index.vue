<script setup>
import FrontLayout from '@/Layouts/FrontLayout.vue';
import HomeHero from './_HomeHero.vue';
import Feature from './_Feature.vue';
import Category from './_Category.vue';
import TopPicks from './_TopPicks.vue';
import PageHero from './_PageHero.vue';
import { computed, reactive, ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import PillsSwiper from './_PillsSwiper.vue';
import ProductCard from '@/DaisyComponents/Front/ProductCard.vue';
import Breadcrumb from '@/DaisyComponents/Front/Breadcrumb.vue';
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

const breadcrumbItems = computed(() => {
    const items = [
        { label: '首頁', href: route('front.home.index') },
        { label: '所有商品', href: route('products.index') },
    ]

    if (categoryName.value !== 'ALL') {
        items.push({ label: categoryName.value })
    }

    return items
})
</script>

<template>
    <Breadcrumb :items="breadcrumbItems" />
    <PageHero />

    <section class="mt-4 max-w-layout-wide mx-auto px-4">
        <PillsSwiper class="md:hidden mt-4" :categories="props.categories_tab" :activeCategory="categoryName"
            @select-category="onSelectCategory" />

        <div class="md:flex md:mt-8 md:mb-8 lg:mt-12">
            <div class="hidden md:block md:basis-2/5 lg:basis-1/4">
                <aside class="mr-8 p-5 border border-base-300 rounded-[20px] bg-base-100">
                    <h3 class="text-base lg:text-lg font-medium text-heading pb-3 mb-3 border-b border-base-300">
                        產品分類
                    </h3>
                    <ul class="flex flex-col gap-1">
                        <li>
                            <a href="#" class="block w-full px-3 py-2 rounded-xl transition-colors"
                                :class="categoryName == 'ALL'
                                    ? 'bg-primary/10 text-primary font-medium'
                                    : 'text-base-content hover:bg-primary/10 hover:text-primary'"
                                @click.prevent="onSelectCategory('ALL')">
                                全部
                            </a>
                        </li>
                        <li v-for="value in props.categories_tab" :key="value.id">
                            <a href="#" class="block w-full px-3 py-2 rounded-xl transition-colors"
                                :class="value.name == categoryName
                                    ? 'bg-primary/10 text-primary font-medium'
                                    : 'text-base-content hover:bg-primary/10 hover:text-primary'"
                                @click.prevent="onSelectCategory(value.name)">
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
                        <select class="border-b-1 border-base-content w-full md:w-50 lg:w-58 py-1" id="sort_product"
                            v-model="selectedSort" @change="sortData">
                            <option v-for="(opt, idx) in sortOptions" :key="idx" :value="opt">
                                {{ opt.label }}
                            </option>
                        </select>
                    </div>

                    <div class="w-[50%] text-end">
                        <span class="text-primary text-lg md:text-xl xl:text-2xl">
                            {{ props.products?.length }}
                        </span>
                        件商品
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3 md:gap-5 xl:grid-cols-3 mb-4">
                    <ProductCard v-for="value in props.products" :key="value.id" :product="value" />
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