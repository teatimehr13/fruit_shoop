<script setup>
import { inject } from 'vue';
import FrontLayout from '@/Layouts/FrontLayout.vue';
import CtaButton from './_CtaButton.vue';

defineOptions({
    layout: FrontLayout,
})

// 接上 FrontLayout 提供的 heroRef,讓 Nav 知道現在是不是還在 hero 區塊內
// (在 hero 內:透明背景+白字;離開 hero:現在的白底+深色字樣式)
const heroRef = inject('heroRef', null)
const setHeroRef = (el) => {
    if (heroRef) {
        heroRef.value = el
    }
}

// 這整頁是 Econis (https://econis.wpbingosite.com/home-7/) 的結構+文案+圖片複製版，
// 純粹當作改版參考用,之後會替換成 fruit_shoop 自己的內容,不接真實資料庫資料。

// Hot categories 保留原本的標題/文字排版風格,但圓圈內容改成 fruit_shoop 既有的
// Feature 色塊圖示(產地直送/季節蔬果精選/生產履歷/24小時線上下單),所以只有 4 個
const categoryFeatures = [
    { iconBg: 'bg-feature-pink', iconMask: 'shipped-icon', title: '產地直送', subtitle: '當日採收冷鏈保鮮' },
    { iconBg: 'bg-feature-tan', iconMask: 'vege-icon', title: '季節蔬果精選', subtitle: '跟著產季吃得剛剛好' },
    { iconBg: 'bg-feature-blue', iconMask: 'reward-icon', title: '生產履歷', subtitle: '吃的安心' },
    { iconBg: 'bg-feature-olive', iconMask: 'customer-icon', title: '24小時線上下單', subtitle: '新鮮不遲到' },
]

// 商品名稱/圖片本身還是 Econis 的佔位資料,之後要換成真實商品
const topProducts = [
    { name: 'Probiotic For Women', price: '$115.00', image: '/images/econis-ref/prod-probiotic.jpg', hot: false },
    { name: 'Organic Chicken Tenders', price: '$200.00', image: '/images/econis-ref/prod-chicken.jpg', hot: true },
    { name: 'Whole Leaf Aloe Juice', price: '$100.00', image: '/images/econis-ref/prod-aloe-juice.jpg', hot: false },
    { name: 'Antioxidant Skin Protection', price: '$200.00', image: '/images/econis-ref/prod-antioxidant.jpg', hot: false },
    { name: 'Cold Pressed Green Juice', price: '$95.00', image: '/images/econis-ref/prod-aloe-juice.jpg', hot: false },
    { name: 'Daily Multivitamin Pack', price: '$150.00', image: '/images/econis-ref/prod-probiotic.jpg', hot: false },
    { name: 'Herbal Wellness Tea', price: '$85.00', image: '/images/econis-ref/prod-antioxidant.jpg', hot: true },
    { name: 'Free-Range Turkey Breast', price: '$220.00', image: '/images/econis-ref/prod-chicken.jpg', hot: false },
]

</script>

<template>
    <!-- Hero:實拍影片背景版本(測試用) -->
    <section id="hero" :ref="setHeroRef" class="relative w-full aspect-[4/3] md:h-screen overflow-hidden ">
        <video autoplay muted loop playsinline poster="/videos/hero-fruit-poster.jpg"
            class="absolute inset-0 w-full h-full object-cover">
            <source src="/videos/hero-fruit.mp4" type="video/mp4">
        </video>
        <div class="absolute inset-0 bg-gradient-to-b from-black/10 via-black/20 to-black/40"></div>

        <div class="relative h-full max-w-layout-wide mx-auto px-4 pb-8 md:pb-16 flex flex-col items-center justify-end text-center">
            <h1 class="text-2xl md:text-3xl font-medium text-base-100 mb-6">嚴選新鮮蔬果，讓日常採買更簡單。</h1>

            <CtaButton>探索更多 →</CtaButton>
        </div>
    </section>

    <!-- Hot categories:圖示不用卡片外框,直接排成一排 -->
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

    <!-- Promo banner (2 col):不是特定商品,是分類層級的促銷入口(蔬菜類/果汁類目前有什麼活動) -->
    <section id="promo" class="px-4 py-10 md:py-14 max-w-layout-wide mx-auto">
        <div class="grid md:grid-cols-2 gap-[30px]">
            <a :href="route('categories.products', { category: '蔬菜' })"
                class="group relative rounded-[20px] overflow-hidden aspect-16/9 block">
                <img src="/images/promo/veggie-box.webp" alt="蔬菜嚴選"
                    class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                <div class="absolute inset-0 flex flex-col justify-center items-start px-8">
                    <h3 class="text-2xl md:text-3xl font-medium text-heading mb-2">蔬菜嚴選</h3>
                    <p class="text-base text-heading/70 mb-4">當季現採，每日到貨</p>
                    <CtaButton as="span" class="!border !border-[#fff] shadow-[0_2px_8px_rgba(0,0,0,0.18)]">選購去 →</CtaButton>
                </div>
            </a>

            <a :href="route('categories.products', { category: '果汁' })"
                class="group relative rounded-[20px] overflow-hidden aspect-16/9 block">
                <img src="/images/promo/cold-pressed-juice.webp" alt="鮮榨果汁"
                    class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                <div class="absolute inset-0 flex flex-col justify-center items-start px-8">
                    <h3 class="text-2xl md:text-3xl font-medium text-heading mb-2">鮮榨果汁</h3>
                    <p class="text-base text-heading/70 mb-4">冷壓工法，鎖住營養</p>
                    <CtaButton as="span" class="!bg-[#fff] !text-primary !border !border-[#fff] hover:!bg-accent hover:!text-accent-content">選購去 →</CtaButton>
                </div>
            </a>
        </div>
    </section>

    <!-- Top products:跟「我們的承諾」同一套標題樣式(英文小標+葉子裝飾 → 中文主標) -->
    <section id="products" class="px-4 pt-10 md:pt-14 pb-12 md:pb-14 lg:pb-20 max-w-layout-wide mx-auto text-center">
        <p class="flex items-center justify-center gap-2 text-sm text-primary my-3">
            <img src="/images/econis-ref/hero-leaf-1.png" alt="" class="w-4 h-4 object-contain">
            Top products
            <img src="/images/econis-ref/hero-leaf-2.png" alt="" class="w-4 h-4 object-contain">
        </p>
        <h2 class="text-2xl md:text-4xl font-medium tracking-wide text-heading mb-8 md:mb-12">
            人氣商品
        </h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-[30px] text-left">
            <div v-for="p in topProducts" :key="p.name"
                class="group relative bg-base-100 border border-base-300 rounded-[20px] p-4">
                <!-- Hot 標記 -->
                <span v-if="p.hot"
                    class="absolute top-4 left-4 z-10 bg-accent text-accent-content text-xs font-medium px-3 py-1 rounded-full">
                    Hot
                </span>
                <!-- 圖片 -->
                <div class="aspect-square flex items-center justify-center mb-4">
                    <img :src="p.image" :alt="p.name"
                        class="max-w-[70%] max-h-[70%] object-contain transition-transform duration-500 group-hover:scale-105">
                </div>

                <h3 class="text-base md:text-lg font-medium mb-1 text-heading">{{ p.name }}</h3>
                <p class="text-primary font-semibold">{{ p.price }}</p>

                <!-- 加入購物車 -->
                <button
                    class="absolute bottom-4 right-4 w-10 h-10 rounded-full bg-transparent flex items-center justify-center hover:bg-primary hover:text-primary-content transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007Z" />
                    </svg>
                </button>
            </div>
        </div>
    </section>

    <!-- CTA 區:深色底,左側文字+CTA按鈕。
         背景右側疊蔬果空拍照,邊緣用 mask 漸層融入深色底,再疊一層深色漸層壓住雜色、保留文字對比度 -->
    <section id="cta" class="relative w-full py-12 md:py-20 lg:py-28 overflow-hidden bg-[#0d0d0d]">
        <div class="absolute inset-y-0 right-0 w-full md:w-[70%] h-full bg-[url('/images/cta/veggie-flatlay.webp')] bg-cover bg-center bg-fixed saturate-[0.85] brightness-90 [mask-image:linear-gradient(to_right,transparent,black_35%)] [-webkit-mask-image:linear-gradient(to_right,transparent,black_35%)]">
        </div>
        <div class="absolute inset-0 bg-gradient-to-r from-[#0d0d0d] via-[#0d0d0d]/55 to-[#0d0d0d]/15"></div>

        <div class="relative max-w-layout-wide mx-auto px-4 flex flex-col md:flex-row md:items-center md:justify-between gap-10">
            <div class="max-w-xl">
                <p class="text-sm md:text-base font-medium tracking-[0.15em] text-base-100 uppercase mb-4">
                    嚴選新鮮，安心每一口
                </p>
                <h2 class="text-4xl md:text-6xl font-medium text-base-100 leading-[1.15] mb-8">
                    把健康好味道，<br>帶進日常生活
                </h2>
                <CtaButton
                    class="!bg-transparent !text-base-100 !border !border-[#fff] hover:!bg-primary hover:!text-primary-content">
                    立即選購
                </CtaButton>
            </div>
        </div>
    </section>
</template>

<style>
/* Hot categories 的 Feature 圖示,跟 Home/_Feature.vue 用同一套 SVG 遮罩 */
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
