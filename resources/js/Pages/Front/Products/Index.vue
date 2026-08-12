<script setup>
import FrontLayout from '@/Layouts/FrontLayout.vue';
import HomeHero from './_HomeHero.vue';
import Feature from './_Feature.vue';
import Category from './_Category.vue';
import TopPicks from './_TopPicks.vue';
import PageHero from './_PageHero.vue';
import { computed, reactive, ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import ProductCard from '@/DaisyComponents/Front/ProductCard.vue';
import FilterDropdown from './_FilterDropdown.vue';
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
    activeCategory: String,
    totalProductsCount: Number,
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
    <PageHero :breadcrumb-items="breadcrumbItems" />

    <section class="mt-8 md:mt-12 mb-12 max-w-layout-wide mx-auto px-4">
        <div class="flex items-center justify-between gap-3 flex-wrap pb-4 mb-6 border-b border-base-300">
            <FilterDropdown :categories="props.categories_tab" :active-category="categoryName"
                :total-count="props.totalProductsCount" @select-category="onSelectCategory" />

            <p class="order-3 md:order-2 w-full md:w-auto text-center md:text-left text-sm text-base-content/60">
                <span class="text-primary font-medium">{{ props.products?.length }}</span> 件商品
            </p>

            <select class="border-b border-base-content py-1 text-sm outline-none" id="sort_product"
                v-model="selectedSort" @change="sortData">
                <option v-for="(opt, idx) in sortOptions" :key="idx" :value="opt">
                    {{ opt.label }}
                </option>
            </select>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 md:gap-6">
            <ProductCard v-for="value in props.products" :key="value.id" :product="value" />
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