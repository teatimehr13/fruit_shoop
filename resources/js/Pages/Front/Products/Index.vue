<script setup>
import FrontLayout from '@/Layouts/FrontLayout.vue';
import Feature from './_Feature.vue';
import TopPicks from './_TopPicks.vue';
import Breadcrumb from '@/DaisyComponents/Front/Breadcrumb.vue';
import { computed, reactive, ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import ProductCard from '@/DaisyComponents/Front/ProductCard.vue';
import FilterDropdown from './_FilterDropdown.vue';
import SortDropdown from './_SortDropdown.vue';
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

const onSelectSort = (opt) => {
    selectedSort.value = opt
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
    <div class="max-w-layout-wide mx-auto px-4 mt-[var(--spacing-header-space)]">
        <div class="pt-8 md:pt-10 pb-6 border-b border-base-300">
            <h1 class="text-2xl md:text-3xl font-medium text-heading">所有商品</h1>
            <Breadcrumb :items="breadcrumbItems" bare class="mt-2" />
        </div>
    </div>

    <section class="mt-6 md:mt-8 mb-12 max-w-layout-wide mx-auto px-4">
        <div class="flex items-center justify-between gap-3 flex-wrap mb-6">
            <FilterDropdown :categories="props.categories_tab" :active-category="categoryName"
                :total-count="props.totalProductsCount" @select-category="onSelectCategory" />

            <div class="flex items-center gap-3">
                <p class="text-sm text-base-content/60">
                    <span class="text-primary font-medium text-[20px]">{{ props.products?.length }}</span> 件商品
                </p>

                <SortDropdown :options="sortOptions" :model-value="selectedSort" @select="onSelectSort" />
            </div>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 md:gap-6">
            <ProductCard v-for="value in props.products" :key="value.id" :product="value" />
        </div>
    </section>
    <!-- icon feature-->
    <!-- <Feature /> -->

    <!-- recommand -->
    <!-- <section></section> -->
    <!-- <TopPicks /> -->
</template>