<script setup>
</script>
<template>
    <section class="px-4 pb-16 md:pb-20 max-w-layout-wide mx-auto">
        <div class="bg-base-200 rounded-[20px] p-8 md:p-16 flex flex-col items-start md:max-w-[40rem]">
            <h2 class="text-3xl md:text-5xl font-semibold tracking-wide text-base-content mb-6 md:mb-8">
                FRESH-PICK 果蔬箱
            </h2>

            <span class="tracking-wide text-base md:text-xl text-base-content">
                當季蔬菜與日照剛好的水果，做成「整箱直送」或「現打冷壓果汁」。輕盈、不負擔，冰到冰箱，想喝或想料理隨手就有，無論一個人簡單開伙，或是家人一起分著吃都剛剛好。
                <br>
                <br>
                P.S. 12/1 早上 9 點，期間限定的【晨光綠野冷壓汁】與【冬季產地蔬果箱】同步開賣，只開放指定配送區，售完就沒了。
            </span>

            <a :href="route('products.index')">
                <button class="buy_btn mt-6 md:mt-12 tracking-wider">
                    搶鮮去!
                </button>
            </a>
        </div>
    </section>
</template>

<style>
.buy_btn {
    border-radius: 40px;
    color: var(--color-primary);
    box-shadow: inset 0 0 0 1px var(--color-primary);
    padding: 6px 38px;
    font-weight: 600;
    transition: all .4s cubic-bezier(.76, 0, .24, 1), color 1s;
}

.buy_btn:hover {
    cursor: pointer;
    background-color: var(--color-primary);
    color: var(--color-primary-content);
}
</style>
