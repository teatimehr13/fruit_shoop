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
</script>

<template>
    <PageHero />

    <section class="mt-4 max-w-layout-wide mx-auto px-4">
        <PillsSwiper class="md:hidden mt-4" :categories="props.categories_tab" :activeCategory="categoryName"
            @select-category="onSelectCategory" />

        <div class="md:flex md:mt-8 md:mb-8 lg:mt-12">
            <div class="hidden md:block md:basis-2/5 lg:basis-1/4">
                <aside class="mr-8 p-4 border border-base-200 rounded-[12px] bg-base-200">
                    <div class="mb-4 relative before:content-[''] before:absolute before:inset-x-0 before:-bottom-2
                before:h-[1px] before:bg-base-content before:block">
                        <h3 class="text-md md:text-lg lg:text-xl font-semibold text-base-content">
                            產品分類
                        </h3>
                    </div>
                    <ul>
                        <li class="text-base-content px-2 py-1 rounded-sm"
                            :class="{ 'bg-primary text-base-100': categoryName == 'ALL' }">
                            <a href="#" class="block w-full" :class="{ 'hover:text-base-content/60': categoryName !== 'ALL' }"
                                @click.prevent="onSelectCategory('ALL')">全部</a>
                        </li>
                        <li v-for="value in props.categories_tab" class="text-base-content px-2 py-1 rounded-sm"
                            :class="{ 'bg-primary text-base-100': value.name == categoryName }">
                            <a href="#" class="block w-full "
                                :class="{ 'hover:text-base-content/60': value.name !== categoryName }" @click.prevent="
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