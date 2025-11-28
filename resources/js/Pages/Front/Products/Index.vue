<script setup>
import FrontLayout from '@/Layouts/FrontLayout.vue';
import HomeHero from './_HomeHero.vue';
import Feature from './_Feature.vue';
import Category from './_Category.vue';
import TopPicks from './_TopPicks.vue';
import PageHero from './_PageHero.vue';
import { computed, ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import PillsSwiper from './_PillsSwiper.vue';



defineOptions({
    layout: FrontLayout,
})

const props = defineProps({
    categories_tab: Object,
    products: Object
})


console.log(props.categories_tab);
console.log(props.products);

const formatTwd = (value) => {
    if (value == null) return ''

    return `$ ${Number(value).toLocaleString('zh-TW')}`
}

const activeCategory = ref('all')

const filteredProducts = computed(() => {
    if (activeCategory.value === 'all') return props.products

    return props.products.filter(p =>
        p.subcategory_handle === activeCategory.value ||
        p.category_handle === activeCategory.value
    )
})

const handleCategoryChange = (handle) => {
    activeCategory.value = handle
}
</script>

<template>
    <PageHero />

    <section class="mt-4">
        <PillsSwiper class="md:hidden mt-4" :categories="props.categories_tab" initial-active="all"
            @change="handleCategoryChange" />



        <div class="md:flex md:mt-8 lg:mt-12">
            <div class="hidden md:block md:basis-1/4 xl:basis-1/5">
                <aside class="mr-8 p-4 px-8 border border-[#f1f0ed] rounded-[12px] bg-[#f1f0ed]">
                    <div class="mb-4 relative before:content-[''] before:absolute before:inset-x-0 before:-bottom-2
                before:h-[1px] before:bg-[#67645e] before:block">
                        <h3 class="text-md md:text-lg lg:text-xl font-semibold text-[#67645e]">
                            產品分類
                        </h3>
                    </div>
                    <ul>
                        <li v-for="value in props.categories_tab" class="text-[#67645e] px-2 py-1 rounded-sm">
                            <Link :href="route('front.home.index')" class="block w-full hover:text-zinc-400">
                            {{ value.name }}
                            </Link>
                        </li>
                    </ul>
                </aside>
            </div>

            <div class="md:basis-3/4 xl:basis-4/5">
                <div class="py-2 my-4 flex w-full items-center rounded-[12px]">
                    <div class="w-[50%] flex items-center ">
                        <label for="sort_product" class="align-middle whitespace-nowrap">排序:</label>
                        <select class="border-b-1 border-[#67645e] w-full md:w-50 lg:w-58 py-1" id="sort_product">
                            <option disabled selected>最新上市</option>
                            <option>Inter</option>
                            <option>Poppins</option>
                            <option>Raleway</option>
                        </select>
                    </div>

                    <div class="w-[50%] text-end">
                        50件商品
                    </div>
                </div>
                <div
                    class="grid grid-cols-2 gap-2 md:gap-4 md:grid-cols-3 xl:gap-8 xl:grid-cols-4">
                    <div v-for="value in props.products"
                        class="relative border border-[#f0f0f0] rounded-[12px] overflow-hidden flex flex-col"
                        :key="value.id">
                        <div class="overflow-hidden">
                            <a href="">
                                <img :src="value.primary_image?.img_url || 'https://picsum.photos/600/900?random=9'"
                                    alt=""
                                    class="transition-all duration-200 hover:scale-110 object-cover aspect-3/2 w-full h-full">
                            </a>
                        </div>
                        <div class="text-center flex flex-col m-auto p-2 gap-1">
                            <div
                                class="font-semibold text-green-600 text-xs sm:text-sm md:text-base lg:text-lg items-center justify-center">
                                {{ formatTwd(value.cheapest_price) }}
                            </div>
                            <div v-if="value.has_discount"
                                class="line-through text-gray-400 text-xs sm:text-sm md:text-base lg:text-lg items-center justify-center">
                                {{ formatTwd(value.cheapest_original_price) }}
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