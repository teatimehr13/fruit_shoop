<script setup>

</script>
<template>
    <section class="px-4 rounded-[12px] pb-16 md:pb-20 lg:pb-24 max-w-[var(--max-w-layout-wide)] mx-auto">
        <div class="grid grid-cols-12 gap-8 md:min-h-[550px] md:max-h-[600px] rounded-[12px] overflow-hidden">
            <div class="col-span-12 md:col-span-6 min-h-[350px] md:h-full">
                <div class="grid grid-flow-col grid-rows-6 gap-4 h-full w-full">
                    <div class="row-span-4 relative col-span-3">
                       <img src="/images/categories/unnamed.jpg" alt=""
                            class="rounded-[12px] object-cover w-full h-full absolute inset-0">
                    </div>
                    
                    <div class="row-span-2 relative col-span-3">
                        <img src="/images/categories/thought-catalog-EMX1eJ1BcgU-unsplash.jpg" alt=""
                            class="rounded-[12px] object-cover w-full h-full absolute inset-0">
                    </div>

                    <div class="row-span-3 relative col-span-2">
                         <img src="/images/categories/Gemini_Generated_Image_1b8lf61b8lf61b8l.png" alt=""
                        class="rounded-[12px] object-cover w-full h-full absolute inset-0">
                        
                    </div>

                    <div class="row-span-3 relative col-span-2">
                        <img src="/images/categories/Gemini_Generated_Image_5wt40w5wt40w5wt4.png" alt=""
                            class="rounded-[12px] object-cover w-full h-full absolute inset-0">
                    </div>
                </div>
            </div>

            <div class="col-span-12 md:col-span-6 md:h-full p-4 md:p-10">
                <div class="flex flex-col justify-end lg:max-w-[30rem] 3xl:max-w-xl h-full">
                    <div>
                        <div class="md:pt-6 mb-6 md:mb-12">
                            <h2 class="text-2xl md:text-4xl font-semibold tracking-wide text-[#67645e]">
                                FRESH-PICK 果蔬箱
                            </h2>
                        </div>

                        <div>
                            <span class="tracking-wide text-sm md:text-lg text-[#67645e]">
                                當季蔬菜與日照剛好的水果，做成「整箱直送」或「現打冷壓果汁」。輕盈、不負擔，冰到冰箱，想喝或想料理隨手就有，無論一個人簡單開伙，或是家人一起分著吃都剛剛好。
                                <br>
                                <br>
                                P.S. 12/1 早上 9 點，期間限定的【晨光綠野冷壓汁】與【冬季產地蔬果箱】同步開賣，只開放指定配送區，售完就沒了。
                            </span>
                        </div>

                        <div>
                            <button class="buy_btn mt-6 md:mt-12">
                                BUY NOW
                            </button>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </section>
</template>


<style>
.buy_btn {
    border-radius: 40px;
    color: #82ae46;
    box-shadow: inset 0 0 0 1px #82ae46;
    padding: 6px 38px;
    font-weight: 600;
    /* margin-top: 48px; */
    transition: all .4s cubic-bezier(.76, 0, .24, 1), color 1s;
}

.buy_btn:hover {
    cursor: pointer;
    background-color: #82ae46;
    color: white;
}
</style>