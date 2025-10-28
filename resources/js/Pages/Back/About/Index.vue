<script setup>
import BackLayout from '@/Layouts/BackLayout.vue';
import axios from 'axios';

const props = defineProps({
    about: Object
})

console.log(props.about);
const test = true;
const editAbout = async () => {
    
    const fd = new FormData();
    const file = document.getElementById('about').files[0];
    console.log(file);
    // return
    fd.append('title', 'title2');
    fd.append('content', 'content2');
    // fd.append('rm_img', 0);
    if (file) {
        fd.append('image', file);
    } else {
        if(test){
            fd.append('rm_img', 0);
        }
    }

    const res = await axios.post(route('back.about.save'), fd, {
        headers: { Accept: 'application/json' },
        validateStatus: s => s < 500
    });

    console.log(res);

}


</script>

<template>
    <BackLayout>
        about
        <input type="file" id="about">
        <button @click="editAbout">
            about
        </button>
    </BackLayout>
</template>