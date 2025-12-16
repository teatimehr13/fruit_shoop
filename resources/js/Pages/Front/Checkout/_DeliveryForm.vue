<script setup>

import twCityData from '@/data/tw-zipcode.json';
import { computed, reactive, ref, watch } from 'vue';

const props = defineProps({
    modelValue: Object,
    memberData: Object
})

console.log(props.modelValue);
console.log(props.memberData);

const memberData = props.memberData;

const emit = defineEmits(['update:modelValue'])

const form = computed({
    get: () => props.modelValue,
    set: (val) => emit('update:modelValue', val),
})

// const form = props.modelValue;

const cityOptions = computed(() => Object.keys(twCityData));

const districtOptions = computed(() => {
    if (!form.value.shipping_city) return []
    return twCityData[form.value.shipping_city] || []
});

const fillFromMember = () => {
    form.value.shipping_city = memberData.shipping_city
    form.value.shipping_district = memberData.shipping_district
    form.value.shipping_zip_code = memberData.shipping_zip_code
    form.value.shipping_address_detail = memberData.shipping_address_detail
    form.value.recipient_phone = memberData.recipient_phone
    form.value.recipient_name = memberData.recipient_name
    form.value.shipping_email = memberData.shipping_email
    // console.log(form.value);
    // console.log(memberData);
}

const clearAddress = () => {
    form.value.shipping_city = ''
    form.value.shipping_district = ''
    form.value.shipping_zip_code = ''
    form.value.shipping_address_detail = ''
    form.value.recipient_phone = ''
    form.value.recipient_name = ''
    form.value.shipping_email = ''
}

const errors = reactive({})
watch(
    () => form.value.filling_mode,
    (mode) => {
        if (mode === 'member') {
            fillFromMember();
            Object.keys(errors).forEach(key => delete errors[key])
        } else if (mode === 'custom') {
            // clearAddress()
        }
    },
    { immediate: true }
)


watch(
    () => form.value.shipping_city,
    () => {
        if (form.value.filling_mode === 'custom') {
            form.value.shipping_district = ''
            form.value.shipping_zip_code = ''
        }
    }
)

//根據地區隨即填寫zip_code到物件
watch(
    () => form.value.shipping_district,
    (newDistrict) => {
        form.value.shipping_zip_code = districtOptions.value?.[newDistrict] ?? '';
    }
)

watch(
    () => ({
        shipping_city: form.value.shipping_city,
        shipping_district: form.value.shipping_district,
        // shipping_zip: form.value.shipping_zip_code,
        shipping_detail: form.value.shipping_address_detail,
        recipient_phone: form.value.recipient_phone,
        recipient_name: form.value.recipient_name,
        shipping_email: form.value.shipping_email,
    }),
    (now) => {
        if (form.value.filling_mode !== 'member') return
        const same = JSON.stringify(now) === JSON.stringify(memberData)

        if (!same) {
            form.value.filling_mode = 'custom'
        }
    }
)



const validateForm = () => {
    Object.keys(errors).forEach(key => delete errors[key])

    if (!form.value.shipping_city) {
        errors.shipping_city = '請選擇城市'
    }

    if (!form.value.shipping_district) {
        errors.shipping_district = '請選擇地區'
    }

    if (!form.value.shipping_address_detail?.trim()) {
        errors.shipping_address_detail = '請輸入地址'
    }

    if (!form.value.recipient_phone?.trim()) {
        errors.recipient_phone = '請輸入電話號碼'
    }

    if (!form.value.shipping_email?.trim()) {
        errors.shipping_email = '請輸入地址'
    }

    if (!form.value.recipient_name?.trim()) {
        errors.recipient_name = '請輸入姓名'
    }

    const ok = Object.keys(errors).length === 0

    if (!ok) {
        focusFirstError()
    }

    return ok
}

// 清除單一欄位錯誤
const clearError = (field) => {
    delete errors[field]
}

const fieldRefs = {
    recipient_name: ref(null),
    shipping_email: ref(null),
    shipping_city: ref(null),
    shipping_district: ref(null),
    shipping_address_detail: ref(null),
    recipient_phone: ref(null),
}

//map errors focus
const fieldOrder = [
    'recipient_name',
    'shipping_email',
    'shipping_city',
    'shipping_district',
    'shipping_address_detail',
    'recipient_phone',
]

const focusFirstError = () => {
    const firstKey = fieldOrder.find(key => errors[key])
    if (!firstKey) return
    const el = fieldRefs[firstKey]
    

    el.scrollIntoView({
        behavior: 'smooth',
        block: 'start',
    })

    if (typeof el.focus === 'function') {
        el.focus({ preventScroll: true })
    }
}

defineExpose({
    validateForm,
})

</script>

<template>
    <div class="form-layout">
        <div class="grid gap-4">
            <div class="m-auto">
                <div class="h-30 w-30 flex items-center justify-center">
                    <img src="/images/logo/c3837bce-a01c-45e8-aa45-5b820428fe29.png" alt="vege">
                </div>
            </div>
            <div>
                <h1 class="text-xl font-semibold text-[#67645e]">顧客資料</h1>
            </div>
            <div v-if="$page.props.auth.user">
                <select class="select select-lg w-full" v-model="form.filling_mode">
                    <option value="member">顧客資料同會員資料</option>
                    <option value="custom">自行輸入</option>
                </select>
            </div>
            <div>
                <input type="text" placeholder="姓名" class="input input-lg w-full" v-model="form.recipient_name"
                    @change="clearError('recipient_name')" :class="{ 'input-error': errors.recipient_name }"
                    :ref="el => fieldRefs.recipient_name = el" />
                <p v-if="errors.recipient_name" class="errors-hint">{{ errors.recipient_name }}</p>
            </div>
            <div>
                <input type="email" placeholder="Email" class="input input-lg w-full" v-model="form.shipping_email"
                    @change="clearError('shipping_email')" :class="{ 'input-error': errors.shipping_email }"
                    :ref="el => fieldRefs.shipping_email = el" />
                <p v-if="errors.shipping_email" class="errors-hint">{{ errors.shipping_email }}</p>
            </div>
            <div class="grid gap-4 grid-flow-col">
                <div>
                    <select class="select select-lg  w-full" v-model="form.shipping_city"
                        @change="clearError('shipping_city')" :class="{ 'input-error': errors.shipping_city }"
                        :ref="el => fieldRefs.shipping_city = el">
                        <option disabled selected value="">城市 / 縣</option>
                        <option v-for="city in cityOptions" :value="city" :key="city">{{ city }}</option>
                    </select>
                    <p v-if="errors.shipping_city" class="errors-hint">{{ errors.shipping_city }}</p>
                </div>
                <div>
                    <select class="select select-lg  w-full" v-model="form.shipping_district"
                        @change="clearError('shipping_district')" :class="{ 'input-error': errors.shipping_district }"
                        :ref="el => fieldRefs.shipping_district = el">
                        <option disabled selected value="">地區</option>
                        <option v-for="(zip, district) in districtOptions" :value="district" :key="zip">{{ zip + ' ' +
                            district }}</option>
                    </select>
                    <p v-if="errors.shipping_district" class="errors-hint">{{ errors.shipping_district }}</p>
                </div>
            </div>
            <div>
                <input type="text" placeholder="地址" class="input input-lg w-full" v-model="form.shipping_address_detail"
                    @change="clearError('shipping_address_detail')"
                    :class="{ 'input-error': errors.shipping_address_detail }"
                    :ref="el => fieldRefs.shipping_address_detail = el" />
                <p v-if="errors.shipping_address_detail" class="errors-hint">{{ errors.shipping_address_detail }}</p>
            </div>
            <div>
                <input type="text" placeholder="電話號碼" class="input input-lg w-full" v-model="form.recipient_phone"
                    @change="clearError('recipient_phone')"
                    @input="form.recipient_phone = $event.target.value.replace(/[^0-9]/g, '')"
                    :class="{ 'input-error': errors.recipient_phone }" :ref="el => fieldRefs.recipient_phone = el" />
                <p v-if="errors.recipient_phone" class="errors-hint">{{ errors.recipient_phone }}</p>
            </div>
            <div>
                <textarea class="textarea w-full textarea-lg" placeholder="備註" v-model="form.note"></textarea>
            </div>

        </div>
    </div>
</template>

<style>
.form-layout {
    display: grid;
    grid-template-columns: minmax(0, 1fr);
    row-gap: 14px;
}

.errors-hint {
    margin-left: .25rem;
    margin-top: .25rem;
    font-size: .75rem;
    color: oklch(71% .194 13.428);
}
</style>