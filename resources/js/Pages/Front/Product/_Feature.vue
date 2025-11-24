<template>
    <section class="py-14 my-8 rounded-[12px] border border-solid border-base-300">
        <div class="w-full">
            <div class="grid md:grid-cols-4">
                <div class="mx-auto mb-4">
                    <div class="m-auto w-26 h-26 bg-[#e4b2d6] ft-icon mb-2 hover:bg-[#82ae46]">
                        <div class="shipped-icon aspect-6/3"></div>
                    </div>
                    <div class="text-center">
                        <h3 class="text-base font-semibold mb-3">
                            產地直送
                        </h3>
                        <p class="text-sm font-medium text-stone-400">
                            當日採收冷鏈保鮮
                        </p>
                    </div>
                </div>
                <div class="mx-auto mb-4">
                    <div class="m-auto w-26 h-26 bg-[#dcc698] ft-icon mb-2 hover:bg-[#82ae46]">
                        <div class="vege-icon aspect-6/3 object-contain"></div>
                    </div>
                    <div class="text-center">
                        <h3 class="text-base font-semibold mb-3">
                            季節蔬果精選
                        </h3>
                        <p class="text-sm font-medium text-stone-400">
                            跟著產季吃得剛剛好
                        </p>
                    </div>

                </div>
                <div class="mx-auto mb-4">
                    <div class="m-auto w-26 h-26 bg-[#a2d1e1] ft-icon mb-2 hover:bg-[#82ae46]">
                        <div class="reward-icon aspect-6/3 object-contain"></div>
                    </div>
                    <div class="text-center">
                        <h3 class="text-base font-semibold mb-3">
                            生產履歷
                        </h3>
                        <p class="text-sm font-medium text-stone-400">
                            吃的安心
                        </p>
                    </div>

                </div>
                <div class="mx-auto mb-4">
                    <div class="m-auto w-26 h-26 bg-[#dcd691] ft-icon mb-2 hover:bg-[#82ae46]">
                        <div class="customer-icon aspect-6/3 object-contain"></div>
                    </div>
                    <div class="text-center">
                        <h3 class="text-base font-semibold mb-3">
                            24小時線上下單
                        </h3>
                        <p class="text-sm font-medium text-stone-400">
                            新鮮不遲到
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<style>
.shipped-icon {
    background: #fff;
    /* 用 SVG 當遮罩 */
    mask-image: url('/images/svg/shipped.svg');
    mask-size: contain;
    mask-repeat: no-repeat;
    mask-position: center;

    /* Safari 需要 */
    -webkit-mask-image: url('/images/svg/shipped.svg');
    -webkit-mask-size: contain;
    -webkit-mask-repeat: no-repeat;
    -webkit-mask-position: center;
}

.vege-icon {
    background: #fff;
    /* 用 SVG 當遮罩 */
    mask-image: url('/images/svg/vegetables.svg');
    mask-size: contain;
    mask-repeat: no-repeat;
    mask-position: center;

    /* Safari 需要 */
    -webkit-mask-image: url('/images/svg/vegetables.svg');
    -webkit-mask-size: contain;
    -webkit-mask-repeat: no-repeat;
    -webkit-mask-position: center;
}

.customer-icon {
    background: #fff;
    /* 用 SVG 當遮罩 */
    mask-image: url('/images/svg/customer.svg');
    mask-size: contain;
    mask-repeat: no-repeat;
    mask-position: center;

    /* Safari 需要 */
    -webkit-mask-image: url('/images/svg/customer.svg');
    -webkit-mask-size: contain;
    -webkit-mask-repeat: no-repeat;
    -webkit-mask-position: center;
}

.reward-icon {
    background: #fff;

    /* 用 SVG 當遮罩 */
    mask-image: url('/images/svg/reward.svg');
    mask-size: contain;
    mask-repeat: no-repeat;
    mask-position: center;

    /* Safari 需要 */
    -webkit-mask-image: url('/images/svg/reward.svg');
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