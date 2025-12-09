<script setup>
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
            <div>
                <select class="select select-lg w-full">
                    <option>顧客資料同會員資料</option>
                    <option>自行輸入</option>
                </select>
            </div>
            <div>
                <input type="text" placeholder="姓名" class="input input-lg w-full" />
            </div>
            <div>
                <input type="email" placeholder="Email" class="input input-lg w-full" />
            </div>
            <div class="grid gap-4" style="grid-auto-flow: column;">
                <div>
                    <select class="select select-lg">
                        <option disabled selected>Pick a color</option>
                        <option>Crimson</option>
                        <option>Amber</option>
                        <option>Velvet</option>
                    </select>
                </div>
                <div>
                    <select class="select select-lg">
                        <option disabled selected>Pick a color</option>
                        <option>Crimson</option>
                        <option>Amber</option>
                        <option>Velvet</option>
                    </select>
                </div>
            </div>
            <div>
                <input type="text" placeholder="地址" class="input input-lg w-full" />
            </div>

            <div>
                <input type="text" placeholder="手機" class="input input-lg w-full" />
            </div>

            <div>
                <textarea class="textarea w-full textarea-lg" placeholder="備註"></textarea>
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
</style>