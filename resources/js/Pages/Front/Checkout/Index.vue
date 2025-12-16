<script setup>
import { computed, reactive, ref } from 'vue';
import DeliveryForm from './_DeliveryForm.vue';
import OrderSummary from './_OrderSummary.vue';
import PaymentInfo from './_PaymentInfo.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import axios from 'axios';


const page = usePage()
const user = computed(() => page.props.auth?.user ?? null)
console.log(user.value);

const form = useForm({
    filling_mode: user.value ? 'member' : 'custom',
    shipping_city: user.value?.city ?? '',
    shipping_email: user.value?.email ?? '',
    shipping_district: user.value?.district ?? '',
    shipping_zip_code: user.value?.zip_code ?? '',
    shipping_address_detail: user.value?.address_detail ?? '',
    recipient_phone: user.value?.phone ?? '',
    recipient_name: user.value?.name ?? '',
    // payment_method: 'credit_card'
});

const memberData = {
    shipping_city: user.value?.city ?? '',
    shipping_district: user.value?.district ?? '',
    shipping_zip_code: user.value?.zip_code ?? '',
    shipping_address_detail: user.value?.address_detail ?? '',
    recipient_phone: user.value?.phone ?? '',
    recipient_name: user.value?.name ?? '',
    shipping_email: user.value?.email ?? '',
}

const deliveryRef = ref(null);
const submitCheckout = async () => {
    const ok = deliveryRef.value?.validateForm();
    if (!ok) return;
    console.log(form);

    // form.post(route('checkout.store'), {
    //     onSuccess: (res) => {
    //         console.log(res);

    //     },
    //     onError: (errors) => {

    //     }
    // })
    try {
        const resp = await axios.post(route('checkout.store'), form.data())
        window.location.href = resp.data.pay_url  
    } catch (e) {
        console.log(e)
    }
}
</script>

<template>
    <div class="min-h-screen flex flex-col mx-auto">
        <!-- <form @submit.prevent="submitCheckout"> -->
        <div class="flex-1 grid md:grid-cols-[53fr_47fr] md:after:content-[''] md:after:absolute md:after:inset-y-0
                md:after:left-[53%] md:after:-translate-x-1/2 md:after:w-px md:after:bg-[#f5f5f5] relative">
            <div class="checkout-section checkout-left">
                <div class="content items-start gap-8 px-6 py-8 md:max-w-[580px] md:grid md:p-9">
                    <DeliveryForm v-model="form" :memberData="memberData" ref="deliveryRef" />

                    <div class="mt-4 hidden md:block">
                        <button type="button" @click="submitCheckout"
                            class="btn btn-lg w-full py-3 border-[#82ae46] text-[#82ae46] hover:text-white rounded-[40px] hover:bg-[#82ae46] transition-colors bg-white">
                            前往結帳
                        </button>
                    </div>
                </div>
            </div>
            <div class="checkout-section checkout-right bg-[#f5f5f5] ">
                <div class="sticky top-0">
                    <div class="content px-6 py-8 md:max-w-[480px] md:grid md:p-9">
                        <OrderSummary />

                        <div class="block md:hidden mt-8">
                            <button type="submit"
                                class="btn btn-lg w-full py-3 border-[#82ae46] text-[#82ae46] hover:text-white rounded-[40px] hover:bg-[#82ae46] transition-colors bg-white">
                                前往結帳
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- </form> -->
    </div>
</template>

<style>
.checkout-section {
    /* height: 100%;
    width: 100%;
    padding: 36px; */
}

.checkout-left {
    display: flex;
    justify-content: flex-end;
    height: 100%;

}

.checkout-right {
    display: block;
}

.checkout-left .content {
    /* max-width: 580px; */
    /* display: grid; */
    grid-template-rows: auto 1fr;
    grid-template-columns: 1fr;
    height: 100%;
    width: 100%;
    /* padding: 36px; */
}

.checkout-right .content {
    width: 100%;
    /* padding: 36px; */
    /* max-width: 500px; */
    position: sticky;
    left: auto;
    right: auto;
    top: 0;
    bottom: 0;
}
</style>