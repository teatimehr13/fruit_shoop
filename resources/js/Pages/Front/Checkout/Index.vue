<script setup>
import { ref } from 'vue';
import DeliveryForm from './_DeliveryForm.vue';
import OrderSummary from './_OrderSummary.vue';
import PaymentInfo from './_PaymentInfo.vue';

</script>

<template>
    <div class="min-h-screen flex flex-col mx-auto">
        <div class="flex-1 grid md:grid-cols-[53fr_47fr] md:after:content-[''] md:after:absolute md:after:inset-y-0
                md:after:left-[53%] md:after:-translate-x-1/2 md:after:w-px md:after:bg-[#f5f5f5] relative">
            <div class="checkout-section checkout-left">
                <div class="content items-start gap-8 px-6 py-8 md:max-w-[580px] md:grid md:p-9">
                    <DeliveryForm />
                </div>
                
            </div>
            <div class="checkout-section checkout-right bg-[#f5f5f5] ">
                <div class="sticky top-0">
                    <div class="content px-6 py-8 md:max-w-[500px] md:grid md:p-9">
                        <OrderSummary />
                    </div>
                </div>
            </div>
        </div>
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