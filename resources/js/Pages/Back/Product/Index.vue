<script setup>
import BackLayout from '@/Layouts/BackLayout.vue';
import axios from 'axios';
import { ref } from 'vue';

const props = defineProps({
    products: Object
})
console.log(props.products);
const products = ref([...props.products.data]);

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

</script>

<template>
    <BackLayout>
        product
        <button @click="addProduct">
            addProduct
        </button>
        <button @click="updProduct('16')">
            updProduct
        </button>
        <button @click="delProduct('17')">
            delProduct
        </button>

        <input id="file" type="file" />
    </BackLayout>

</template>
