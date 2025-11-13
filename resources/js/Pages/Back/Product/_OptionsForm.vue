<script setup>
import api from '@/Lib/apiFeedback';
import { ref, inject, watch, nextTick, onBeforeUpdate, onMounted, computed } from 'vue'

const props = defineProps({
    options: Array,
    productId: Number
})

// console.log(props.productId);

const emit = defineEmits(['save'])

const localOptions = ref([...props.options || []]);
const originalOptions = ref(JSON.parse(JSON.stringify(props.options)) || []);
const deletedIds = ref([]);

onMounted(() => {
    localOptions.value = localOptions.value.map(opt => ({
        ...opt,
        key: crypto.randomUUID()
    }));
})

const addOption = () => {
    localOptions.value.push({
        id: '',
        option_text: '',
        original_price: '',
        price: '',
        inventory: 0,
        is_enabled: true,
        key: crypto.randomUUID(),
        product_id: props.productId
    })
}

const removeOption = (index) => {
    const option = localOptions.value[index];
    if (option.id) {
        const pos = deletedIds.value.indexOf(option.id);
        if (pos > -1) {
            // 取消刪除
            deletedIds.value = deletedIds.value.filter(id => id !== option.id);
        } else {
            // 標記刪除
            deletedIds.value.push(option.id);
        }
        return;
    } else {
        localOptions.value.splice(index, 1);
    }
}

const changeOptions = computed(() => {
    return localOptions.value
        .filter(opt => !deletedIds.value.includes(opt.id))
        .filter((opt, idx) => {
            if (!opt.id) return true
            const { key, ...optWithoutKey } = opt
            const original = originalOptions.value.find(o => o.id === opt.id)
            if (!original) return true
            return JSON.stringify(optWithoutKey) !== JSON.stringify(original);
        })
})

const handleSave = async () => {
    // console.log('changeOptions =>', changeOptions.value);
    // console.log('localOptions =>', localOptions.value);
    const res = await api.post(route('back.products.options.save', props.productId), {
        options: changeOptions.value,
        deleted_ids: deletedIds.value
    });

    if (res.status === 200 || res.status === 201) {
        deletedIds.value = [];
        console.log(res.data.options);
    }

    emit('save', res.data.options);
}

const inputRefs = ref([])
const editingTarget = ref(null);


// 檢查是否點擊在任何 input 之外
const handleClickOutside = (event) => {
    const clickedOutside = !inputRefs.value.some(el => el && el.contains(event.target))
    // console.log(event.target);
    // console.log(inputRefs.value);    
    if (clickedOutside) {
        editingTarget.value = null
        document.removeEventListener('click', handleClickOutside)
    }
}

const setEditing = async (index, field) => {
    editingTarget.value = `${index}-${field}`
    await nextTick()
    const targetInput = inputRefs.value.find(el => el && el.dataset.field === `${index}-${field}`)
    if (targetInput) {
        targetInput.focus()
    }
}

const isEditing = (index, field) => {
    const option = localOptions.value[index]
    if (!option.id) {
        return true
    }

    return editingTarget.value === `${index}-${field}`
}

watch(editingTarget, async (newVal) => {
    // 先移除舊的監聽器
    document.removeEventListener('click', handleClickOutside)

    if (newVal !== null) {
        await nextTick()
        setTimeout(() => {
            document.addEventListener('click', handleClickOutside)
        }, 0)
    }
})

watch(() => props.options, (newOptions) => {
    if (newOptions) {
        localOptions.value = newOptions.map(opt => ({
            ...opt,
            key: opt.id || crypto.randomUUID()
        }))
        originalOptions.value = JSON.parse(JSON.stringify(newOptions))
    }
}, { immediate: true, deep: true })
</script>

<template>
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-semibold">選項管理</h3>
            <button @click="addOption" class="btn btn-sm btn-primary">
                ➕ 新增選項
            </button>
        </div>
        <div v-for="(option, index) in localOptions" :key="option.key" class="p-4"
            :class="{ 'bg-red-100': deletedIds.includes(option.id) }">
            <div class="flex justify-between items-start mb-3">
                <span class="font-medium">選項 {{ index + 1 }}</span>
                <button @click="removeOption(index)" class="cursor-pointer" role="button">
                    <!-- {{ deletedIds.includes(option.id) ? '待刪除' : '刪除' }} -->
                    <svg v-if="deletedIds.includes(option.id)" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                        fill="red" class="size-6">
                        <path fill-rule="evenodd"
                            d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25Zm3 10.5a.75.75 0 0 0 0-1.5H9a.75.75 0 0 0 0 1.5h6Z"
                            clip-rule="evenodd" />
                    </svg>
                    <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15 12H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </button>
            </div>


            <div class="relative grid lg:grid-cols-2 gap-x-12 gap-y-3
                lg:after:content-[''] lg:after:absolute lg:after:inset-y-0
                lg:after:left-1/2 lg:after:-translate-x-1/2 lg:after:w-px lg:after:bg-base-300
                before:content-[''] before:absolute before:inset-x-0 before:-bottom-4
                before:h-px before:bg-base-300">

                <div class="grid lg:grid-cols-[1fr_3fr] gap-x-4">
                    <label class="label">
                        <span class="label-text">選項名稱</span>
                    </label>
                    <input v-show="isEditing(index, 'option_text')" :ref="el => { if (el) inputRefs.push(el) }"
                        :data-field="`${index}-option_text`" v-model="option.option_text"
                        class="input input-bordered w-full input-sm" />
                    <div v-show="!isEditing(index, 'option_text')" class="self-center cursor-pointer"
                        @click="setEditing(index, 'option_text')">
                        {{ option.option_text }}
                    </div>
                </div>

                <div class="grid lg:grid-cols-[1fr_3fr] gap-x-4">
                    <label class="label">
                        <span class="label-text">原價</span>
                    </label>
                    <input v-show="isEditing(index, 'original_price')" :ref="el => { if (el) inputRefs.push(el) }"
                        :data-field="`${index}-original_price`" v-model="option.original_price" type="number"
                        class="input input-bordered w-full input-sm" />
                    <div v-show="!isEditing(index, 'original_price')" class="self-center cursor-pointer"
                        @click="setEditing(index, 'original_price')">
                        {{ option.original_price }}
                    </div>
                </div>

                <div class="grid lg:grid-cols-[1fr_3fr] gap-x-4">
                    <label class="label">
                        <span class="label-text">售價</span>
                    </label>
                    <input v-show="isEditing(index, 'price')" :ref="el => { if (el) inputRefs.push(el) }"
                        :data-field="`${index}-price`" v-model="option.price" type="number"
                        class="input input-bordered w-full input-sm" />
                    <div v-show="!isEditing(index, 'price')" class="self-center cursor-pointer"
                        @click="setEditing(index, 'price')">
                        {{ option.price }}
                    </div>
                </div>

                <div class="grid lg:grid-cols-[1fr_3fr] gap-x-4">
                    <label class="label">
                        <span class="label-text">庫存</span>
                    </label>
                    <input v-show="isEditing(index, 'inventory')" :ref="el => { if (el) inputRefs.push(el) }"
                        :data-field="`${index}-inventory`" v-model="option.inventory" type="number"
                        class="input input-bordered w-full input-sm" />
                    <div v-show="!isEditing(index, 'inventory')" class="self-center cursor-pointer"
                        @click="setEditing(index, 'inventory')">
                        {{ option.inventory }}
                    </div>
                </div>

                <div class="grid">
                    <label class="label">
                        <input type="checkbox" checked="checked" class="checkbox checkbox-xs"
                            v-model="option.is_enabled" :true-value="1" :false-value="0" />
                        啟用
                    </label>
                </div>

            </div>
        </div>

        <div class="flex justify-end">
            <button @click="handleSave" class="btn btn-primary">
                儲存
            </button>
        </div>
    </div>
</template>