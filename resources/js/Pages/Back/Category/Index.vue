<script setup>
import BackLayout from '@/Layouts/BackLayout.vue';
import axios from 'axios';
import { ref, reactive } from 'vue';
const props = defineProps({
    categories: Object
});

console.log(props.categories);
const categories = ref([...props.categories]);

const addCategory = async () => {
    const res = await axios.post(route('back.categories.store'), {
        name: '數位', is_enabled: false
    });

    console.log(res.status);
    //重拿
    const r = await axios.get(route('back.categories.index.json'));
    categories.value = r.data;
}

const updCategory = async (id) => {
    const res = await axios.put(route('back.categories.update', id), {
        name: '數位555', is_enabled: true
    });

    console.log(res.status);
    const r = await axios.get(route('back.categories.index.json'));
    categories.value = r.data;

}

const delCategory = async (id) => {
    const res = await axios.delete(route('back.categories.destroy', id));
    console.log(res.status);
    const r = await axios.get(route('back.categories.index.json'));
    categories.value = r.data;

}



</script>

<template>
    <BackLayout>
        category

        <button @click="addCategory">
            test
        </button>

        <button @click="updCategory(47)">
            update
        </button>

          <button @click="delCategory(47)">
            del
        </button>
    </BackLayout>
</template>