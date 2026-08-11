<script setup>
// 圓圈內容用 fruit_shoop 既有的 Feature 色塊圖示(產地直送/季節蔬果精選/生產履歷/24小時線上下單)
const categoryFeatures = [
    { iconBg: 'bg-feature-pink', iconMask: 'shipped-icon', title: '產地直送', subtitle: '當日採收冷鏈保鮮' },
    { iconBg: 'bg-feature-tan', iconMask: 'vege-icon', title: '季節蔬果精選', subtitle: '跟著產季吃得剛剛好' },
    { iconBg: 'bg-feature-blue', iconMask: 'reward-icon', title: '生產履歷', subtitle: '吃的安心' },
    { iconBg: 'bg-feature-olive', iconMask: 'customer-icon', title: '24小時線上下單', subtitle: '新鮮不遲到' },
]
</script>

<template>
    <section id="features" class="px-4 pt-12 md:pt-16 lg:pt-24 pb-10 md:pb-14 max-w-layout-wide mx-auto text-center">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 md:gap-[30px] text-center">
            <div v-for="f in categoryFeatures" :key="f.title" class="group">
                <div class="w-26 h-26 mx-auto mb-3 ft-icon hover:bg-primary" :class="f.iconBg">
                    <div class="aspect-6/3" :class="f.iconMask"></div>
                </div>
                <h3 class="text-lg font-medium mb-2 text-heading">{{ f.title }}</h3>
                <span class="block text-sm text-[#818995]">{{ f.subtitle }}</span>
            </div>
        </div>
    </section>
</template>

<style>
.shipped-icon {
    background: var(--color-base-100);
    mask-image: url('/images/svg/shipped.svg');
    mask-size: contain;
    mask-repeat: no-repeat;
    mask-position: center;
    -webkit-mask-image: url('/images/svg/shipped.svg');
    -webkit-mask-size: contain;
    -webkit-mask-repeat: no-repeat;
    -webkit-mask-position: center;
}

.vege-icon {
    background: var(--color-base-100);
    mask-image: url('/images/svg/vegetables.svg');
    mask-size: contain;
    mask-repeat: no-repeat;
    mask-position: center;
    -webkit-mask-image: url('/images/svg/vegetables.svg');
    -webkit-mask-size: contain;
    -webkit-mask-repeat: no-repeat;
    -webkit-mask-position: center;
}

.reward-icon {
    background: var(--color-base-100);
    mask-image: url('/images/svg/reward.svg');
    mask-size: contain;
    mask-repeat: no-repeat;
    mask-position: center;
    -webkit-mask-image: url('/images/svg/reward.svg');
    -webkit-mask-size: contain;
    -webkit-mask-repeat: no-repeat;
    -webkit-mask-position: center;
}

.customer-icon {
    background: var(--color-base-100);
    mask-image: url('/images/svg/customer.svg');
    mask-size: contain;
    mask-repeat: no-repeat;
    mask-position: center;
    -webkit-mask-image: url('/images/svg/customer.svg');
    -webkit-mask-size: contain;
    -webkit-mask-repeat: no-repeat;
    -webkit-mask-position: center;
}

.ft-icon {
    border-radius: 50%;
    align-content: center;
    position: relative;
}

.ft-icon::before {
    content: '';
    position: absolute;
    inset: 0.5rem;
    border-radius: 50%;
    border: 1px solid rgba(255, 255, 255, .5);
}
</style>
