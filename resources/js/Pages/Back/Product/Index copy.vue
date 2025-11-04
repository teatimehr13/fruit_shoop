<script setup>
import BackLayout from '@/Layouts/BackLayout.vue';
import axios from 'axios';
import { ref } from 'vue';

const props = defineProps({
    products: Object
})
// console.log(props.products);
const products = ref([...props.products.data]);
console.log(products.value);



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

</script>

<template>
    <BackLayout>
        product
        <!-- <button @click="addProduct">
            addProduct
        </button> -->
        <!-- <button @click="updProduct('16')">
            updProduct
        </button> -->
        <!-- <button @click="delProduct('17')">
            delProduct
        </button> -->

        <input id="file" type="file" multiple />

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
    </BackLayout>

</template>
