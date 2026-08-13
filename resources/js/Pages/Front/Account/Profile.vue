<script setup>
import AccountLayout from '@/Layouts/AccountLayout.vue'
defineOptions({ layout: AccountLayout })

import { computed, reactive, watch, ref } from 'vue'
import { Link, router, usePage } from '@inertiajs/vue3'
import twCityData from '@/data/tw-zipcode.json';
import axios from 'axios';
import api from '@/Lib/apiFeedback';
import PrimaryButton from '@/DaisyComponents/Front/PrimaryButton.vue';

const cityOptions = computed(() => Object.keys(twCityData));

const districtOptions = computed(() => {
    if (!form.city) return []
    return twCityData[form.city] || []
});

const user = computed(() => usePage().props.auth.user)
const form = reactive({
    name: user.value?.name ?? '',
    email: user.value?.email ?? '',
    phone: user.value?.phone ?? '',
    city: user.value?.city ?? '',
    district: user.value?.district ?? '',
    address_detail: user.value?.address_detail ?? '',
    zip_code: user.value?.zip_code ?? ''
})

const errors = reactive({});
const saving = ref(false)
const saved = ref(false)
const validateForm = () => {
    Object.keys(errors).forEach(key => delete errors[key])

    if (!form.city) {
        errors.city = '請選擇城市'
    }

    if (!form.district) {
        errors.district = '請選擇地區'
    }

    if (!form.address_detail?.trim()) {
        errors.address_detail = '請輸入地址'
    }

    if (!form.phone?.trim()) {
        errors.phone = '請輸入電話號碼'
    }

    if (!form.email?.trim()) {
        errors.email = '請輸入地址'
    }

    if (!form.name?.trim()) {
        errors.name = '請輸入姓名'
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
    name: ref(null),
    email: ref(null),
    city: ref(null),
    district: ref(null),
    address_detail: ref(null),
    phone: ref(null),
}

//map errors focus
const fieldOrder = [
    'name',
    'email',
    'city',
    'district',
    'address_detail',
    'phone',
]

const focusFirstError = () => {
    const firstKey = fieldOrder.find(key => errors[key])
    if (!firstKey) return
    const el = fieldRefs[firstKey]


    el.scrollIntoView({
        behavior: 'smooth',
        block: 'center',
    })

    if (typeof el.focus === 'function') {
        el.focus({ preventScroll: true })
    }
}

watch(
    () => form.city,
    (newCity, oldCity) => {
        if (newCity === oldCity) return
        form.district = ''
        form.zip_code = ''
    }
)

//根據地區隨即填寫zip_code到物件
watch(
    () => form.district,
    (newDistrict) => {
        console.log(newDistrict);
        form.zip_code = districtOptions.value?.[newDistrict] ?? '';
    }
)

const createdDate = computed(() => {
    const v = user.value?.created_at
    if (!v) return ''
    return String(v).slice(0, 10)
})

const save = async () => {
    const ok = await validateForm();
    if (!ok) return;

    saving.value = true
    saved.value = false
    // clearErrors()
    try {
        const { data } = await api.patch(route('account.profile.update'), form);
        Object.assign(form, {
            name: data.user.name ?? '',
            phone: data.user.phone ?? '',
            city: data.user.city ?? '',
            district: data.user.district ?? '',
            address_detail: data.user.address_detail ?? '',
            zip_code: data.user.zip_code ?? '',
        })

        saved.value = true
        setTimeout(() => (saved.value = false), 1500)
        sessionStorage.setItem('users_dirty', '1')
        router.reload({ only: ['auth'] })
    } catch (err) {
        if (err.response?.status === 422) {
            const serverErrors = err.response.data.errors || {}

            for (const [field, msgs] of Object.entries(serverErrors)) {
                errors[field] = Array.isArray(msgs) ? msgs[0] : String(msgs)
            }

            focusFirstError()
            return
        }
        console.error(err)

    } finally {
        saving.value = false
    }
}


</script>

<template>
    <div class="form-layout ">
        <div class="grid gap-4 px-2 md:px-0 pt-6">
            <div class="mb-2 md:hidden ">
                <Link :href="route('account.index')" class="inline-flex text-heading">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4 self-center mr-1">
                        <path fill-rule="evenodd"
                            d="M9.53 2.47a.75.75 0 0 1 0 1.06L4.81 8.25H15a6.75 6.75 0 0 1 0 13.5h-3a.75.75 0 0 1 0-1.5h3a5.25 5.25 0 1 0 0-10.5H4.81l4.72 4.72a.75.75 0 1 1-1.06 1.06l-6-6a.75.75 0 0 1 0-1.06l6-6a.75.75 0 0 1 1.06 0Z"
                            clip-rule="evenodd" />
                    </svg>
                    <span>
                        返回會員中心
                    </span>
                </Link>
            </div>
            <h1 class="text-2xl md:text-3xl font-medium text-heading">個人資訊</h1>
            <div class="border-t border-base-300"></div>
            <div class="pt-4 md:pt-6">
                <div class="w-full md:w-[50%]">
                    <div class="border border-base-300 rounded-2xl bg-base-100 p-6 grid gap-4">

                        <div>
                            <label for="profile-name" class="field-label">姓名</label>
                            <input id="profile-name" type="text" class="input input-primary input-lg w-full border-base-300 rounded-lg"
                                v-model="form.name" @change="clearError('name')" :class="{ 'input-error': errors.name }"
                                :ref="el => fieldRefs.name = el" />
                            <p v-if="errors.name" class="errors-hint">{{ errors.name }}</p>
                        </div>

                        <div>
                            <label for="profile-email" class="field-label">Email</label>
                            <input id="profile-email" type="email"
                                class="input input-primary input-lg w-full border-base-300 rounded-lg" v-model="form.email"
                                @change="clearError('email')" :class="{ 'input-error': errors.email }"
                                :ref="el => fieldRefs.email = el" />
                            <p v-if="errors.email" class="errors-hint">{{ errors.email }}</p>
                        </div>

                        <div class="grid gap-4 grid-flow-col">
                            <div>
                                <label for="profile-city" class="field-label">城市 / 縣</label>
                                <select id="profile-city" class="select select-primary select-lg w-full border-base-300 rounded-lg" v-model="form.city"
                                    @change="clearError('city')" :class="{ 'input-error': errors.city }"
                                    :ref="el => fieldRefs.city = el">
                                    <option disabled selected value="">城市 / 縣</option>
                                    <option v-for="city in cityOptions" :value="city" :key="city">{{ city }}</option>
                                </select>
                                <p v-if="errors.city" class="errors-hint">{{ errors.city }}</p>
                            </div>

                            <div>
                                <label for="profile-district" class="field-label">地區</label>
                                <select id="profile-district" class="select select-primary select-lg w-full border-base-300 rounded-lg" v-model="form.district"
                                    @change="clearError('district')" :class="{ 'input-error': errors.district }"
                                    :ref="el => fieldRefs.district = el">
                                    <option disabled selected value="">地區</option>
                                    <option v-for="(zip, district) in districtOptions" :value="district" :key="zip">{{ zip +
                                        ' ' +
                                        district }}</option>
                                </select>
                                <p v-if="errors.district" class="errors-hint">{{ errors.district }}</p>
                            </div>
                        </div>

                        <div>
                            <label for="profile-address" class="field-label">地址</label>
                            <input id="profile-address" type="text" class="input input-primary input-lg w-full border-base-300 rounded-lg"
                                v-model="form.address_detail" @change="clearError('address_detail')"
                                :class="{ 'input-error': errors.address_detail }"
                                :ref="el => fieldRefs.address_detail = el" />
                            <p v-if="errors.address_detail" class="errors-hint">{{ errors.address_detail }}</p>
                        </div>

                        <div>
                            <label for="profile-phone" class="field-label">電話號碼</label>
                            <input id="profile-phone" type="text" class="input input-primary input-lg w-full border-base-300 rounded-lg"
                                v-model="form.phone" @change="clearError('phone')" :class="{ 'input-error': errors.phone }"
                                :ref="el => fieldRefs.phone = el"
                                @input="form.phone = $event.target.value.replace(/[^0-9]/g, '')" />
                            <p v-if="errors.phone" class="errors-hint">{{ errors.phone }}</p>
                        </div>

                        <PrimaryButton :disabled="saving" @click="save">
                            {{ saving ? '儲存中...' : '儲存' }}
                        </PrimaryButton>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style>
.errors-hint {
    margin-left: .25rem;
    margin-top: .25rem;
    font-size: .75rem;
    color: oklch(71% .194 13.428);
}

.field-label {
    display: block;
    margin-bottom: .375rem;
    font-size: .875rem;
    font-weight: 500;
    color: var(--color-heading);
}
</style>