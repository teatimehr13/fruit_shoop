<script setup>
import FrontLayout from '@/Layouts/FrontLayout.vue';
import HomeHero from './_HomeHero.vue';
import Feature from './_Feature.vue';
import Category from './_Category.vue';
import TopPicks from './_TopPicks.vue';
import PageHero from './_PageHero.vue';
import { computed } from 'vue';



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


</script>

<template>
    <PageHero />

    <section class="pt-24">
        <div class="grid grid-cols-2 gap-2">
            <div v-for="value in props.products" class="relative" key="product.id">
                <div class="overflow-hidden border border-[#f0f0f0] rounded-t-[12px]">
                    <a href="">
                        <img :src="value.primary_image?.img_url || 'https://picsum.photos/600/900?random=9'" alt=""
                            class="transition-all duration-200 hover:scale-110 object-cover aspect-3/2 ">
                    </a>
                </div>
                <div>
                    <p v-if="value.has_discount" class="line-through text-gray-400">
                        {{ formatTwd(value.cheapest_original_price) }}
                    </p>
                    <p class="text-green-600">
                        {{ formatTwd(value.cheapest_price) }}
                    </p>
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