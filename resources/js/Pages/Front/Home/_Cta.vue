<script setup>
import CtaButton from '@/DaisyComponents/Front/CtaButton.vue';
</script>

<template>
    <!-- 深色底,左側文字+CTA按鈕。
         背景右側疊蔬果空拍照,邊緣用 mask 漸層融入深色底,再疊一層深色漸層壓住雜色、保留文字對比度 -->
    <section class="relative w-full py-12 md:py-20 lg:py-28 overflow-hidden bg-[#0d0d0d]">
        <div class="absolute inset-y-0 right-0 w-full md:w-[70%] h-full bg-[url('/images/cta/veggie-flatlay.webp')] bg-cover bg-center bg-fixed saturate-[0.85] brightness-90 [mask-image:linear-gradient(to_right,transparent,black_35%)] [-webkit-mask-image:linear-gradient(to_right,transparent,black_35%)]">
        </div>
        <!-- mobile 跟桌機的暗化遮罩分開兩個 div,避免 bg-color 跟 bg-gradient 疊加互相污染(這兩個是不同 CSS 屬性,同一個 div 用 md: 切換會疊在一起,不會互相取代) -->
        <div class="absolute inset-0 bg-black/60 md:hidden"></div>
        <div class="absolute inset-0 hidden md:block bg-gradient-to-r from-[#0d0d0d] via-[#0d0d0d]/55 to-[#0d0d0d]/15">
        </div>

        <div class="relative max-w-layout-wide mx-auto px-4 flex flex-col md:flex-row md:items-center md:justify-between gap-10">
            <div class="max-w-xl text-center md:text-left">
                <p class="text-sm md:text-base font-medium tracking-[0.15em] text-base-100 uppercase mb-5 md:mb-4">
                    嚴選新鮮，安心每一口
                </p>
                <h2 class="text-3xl md:text-4xl lg:text-5xl font-medium text-base-100 leading-[1.45] md:leading-[1.3] tracking-[0.02em] [text-shadow:0_2px_12px_rgba(0,0,0,0.35)] mb-6 md:mb-8">
                    把健康好味道<br>帶進日常生活
                </h2>
                <CtaButton as="a" :href="route('products.index')"
                    class="!bg-transparent !text-base-100 !border !border-[#fff] hover:!bg-primary hover:!text-primary-content hover:!border-primary">
                    立即選購
                </CtaButton>
            </div>
        </div>
    </section>
</template>
