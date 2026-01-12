<script setup>
import BackLayout from '@/Layouts/BackLayout.vue';
import axios from 'axios';
import { reactive, ref } from 'vue';
import api from '@/Lib/apiFeedback';

import { QuillEditor } from '@vueup/vue-quill'
import '@vueup/vue-quill/dist/vue-quill.snow.css';


const props = defineProps({
    about: Object
})

console.log(props.about);
const about = ref(...props.about);
console.log(about.value);

const test = true;

const aboutForm = reactive({
    title: about.value.title,
    content: about.value.content,
    image: about.value.img_url
});

const fileInput = ref(null)
const previewUrl = ref(null)
const fileObj = ref(null) //上傳時放入formData
const rmImg = ref(0);




const revoke = () => {
    if (previewUrl.value) URL.revokeObjectURL(previewUrl.value)
    previewUrl.value = null
}

const onFileChange = (e) => {
    const file = e.target.files?.[0]
    revoke()
    if (file) {
        fileObj.value = file
        previewUrl.value = URL.createObjectURL(file)
        rmImg.value = 0;
    } else {
        fileObj.value = null
    }
}

const clearFile = () => {
    revoke()
    fileObj.value = null
    // 清 input 值，否則同一檔再選不會觸發 change
    if (fileInput.value) fileInput.value.value = '';
    if (!aboutForm.image) rmImg.value = 1;
}


const rmExistImg = () => {
    aboutForm.image = null;
    rmImg.value = 1;
    document.activeElement.blur()
}

const editAbout = async () => {
    const fd = new FormData();
    fd.append('title', aboutForm.title);
    fd.append('content', aboutForm.content);
    if (fileObj.value) fd.append('image', fileObj.value);
    fd.append('rm_img', rmImg.value);

    // for (const [key, val] of fd) {
    //     console.log(val);
    // }
    const res = await api.post(route('back.about.save'), fd, {
        meta: {
            successMessage: '儲存成功'
        }
    })
    console.log(res);
}
</script>

<template>
    <BackLayout>
        <p class="text-[#1E2328] text-lg font-semibold">
            關於我們
        </p>

        <div class="shadow bg-base-100 mt-6 px-6 py-5 h-auto">
            <div>
                <fieldset class="fieldset max-w-xl">
                    <legend class="fieldset-legend text-sm">標題</legend>
                    <input type="text" class="input w-full" placeholder="Type here" v-model="aboutForm.title" />
                </fieldset>

                <fieldset class="mt-4 max-w-xl">
                    <legend class="fieldset-legend text-sm">內文</legend>
                    <!-- <textarea class="textarea w-full" placeholder="Bio" v-model="aboutForm.content"></textarea> -->

                    <QuillEditor v-model:content="aboutForm.content" content-type="html" theme="snow" toolbar="essential"
                        style="min-height: 160px;" />
                </fieldset>

                <fieldset class="fieldset max-w-xl mt-4">
                    <legend class="fieldset-legend text-sm">圖片</legend>
                    <input v-show="!aboutForm?.image" type="file" class="file-input w-full" ref="fileInput"
                        id="fileInput" @change="onFileChange" />

                    <div class="flex">
                        <div class="flex-auto content-center">
                            <div v-if="previewUrl" class="mt-3 preview-content"
                                style="width: max-content; position: relative;">
                                <img :src="previewUrl" alt="預覽"
                                    class="w-48 aspect-auto object-cover rounded-xl shadow" />
                                <div class="overlay-content">
                                    <button class="btn btn-sm" @click="clearFile">移除</button>
                                </div>
                            </div>
                            <div v-else>
                                <img :src="aboutForm?.image" alt=""
                                    class="w-48 aspect-auto object-cover rounded-xl shadow">
                            </div>
                        </div>

                        <div class="dropdown dropdown-end" v-show="aboutForm?.image" v-if="!previewUrl">
                            <div tabindex="0" role="button" class="btn btn-sm py-5 px-2">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M6.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM12.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM18.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                                </svg>
                            </div>
                            <ul tabindex="-1"
                                class="dropdown-content menu bg-base-100 rounded-box z-1 w-52 p-2 shadow-sm">
                                <li><a @click.prevent="rmExistImg">移除現有圖片</a></li>
                                <li><label for="fileInput">更換圖片</label></li>
                            </ul>
                        </div>

                    </div>
                </fieldset>

                <div class="mt-4 max-w-xl">
                    <button @click="editAbout"
                        class="btn btn-neutral btn-block sm:w-full md:w-full sm:justify-center lg:w-auto">
                        儲存
                    </button>
                </div>
            </div>
        </div>
    </BackLayout>
</template>

<style>
.overlay-content {
    position: absolute;
    width: 100%;
    height: 100%;
    left: 0;
    top: 0;
    background: rgba(0, 0, 0, .2);
    opacity: 0;
    transition: ease-in .2s;
}

.preview-content:hover .overlay-content {
    opacity: 1;
}

.overlay-content button {
    position: absolute;
    left: 50%;
    top: 50%;
    transform: translate(-50%, -50%);
}
</style>