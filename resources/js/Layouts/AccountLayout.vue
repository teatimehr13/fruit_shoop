<template>
    <FrontLayout>
        <section class="mt-[52px] md:mt-[88px] py-8 max-w-[var(--max-w-layout-wide)] mx-auto px-4">
            <div class="md:grid md:grid-cols-[25rem_1fr] items-start">
                <!-- desktop sidebar only -->
                <aside class="hidden md:block mr-8 p-4">
                    <ul class="accountSection-navigation">
                        <li class="accountSection-navigation-item"
                            :class="{ 'accountSection-navigation-item-active': is('account.index') }">
                            <Link :href="route('account.index')" class="block w-full">帳號總覽</Link>
                        </li>

                        <li class="border-t border-neutral accountSection-navigation-item"
                            :class="{ 'accountSection-navigation-item-active': is('account.profile') }">
                            <Link :href="route('account.profile')" class="block w-full">個人資訊</Link>
                        </li>

                        <li class="border-t border-b border-neutral accountSection-navigation-item"
                            :class="{ 'accountSection-navigation-item-active': is('account.orders') }">
                            <Link :href="route('account.orders')" class="block w-full">訂單</Link>
                        </li>
                    </ul>

                    <div>
                        <Link v-if="$page.props.auth.user" :href="route('logout')" method="post" as="button"
                            class="tracking-wide btn btn-sm mt-8 mb-2 w-20 py-3 border-primary text-primary hover:text-white rounded-[40px] hover:bg-primary transition-colors bg-white" :class="{ 'text-base-100': isInHeroState }">
                            登出
                        </Link>
                    </div>
                </aside>

                <!-- main always -->
                <main class="min-w-0">
                    <slot />
                </main>
            </div>
        </section>
    </FrontLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import FrontLayout from './FrontLayout.vue';



const is = (name) => route().current(name) // 需要 ziggy
</script>
<style>
.accountSection-navigation a {
    text-decoration: none;
    white-space: nowrap;
    padding: 1rem 1rem 1rem 0;
    width: 100%;
    display: block;
}

.accountSection-navigation a:after {
    content: "";
    position: absolute;
    border: 1px solid var(--color-neutral);
    border-radius: 50%;
    width: 1.25rem;
    height: 1.25rem;
    top: 50%;
    right: 2rem;
    transform: translateY(-50%);
    transition: all .7s cubic-bezier(.76, 0, .24, 1);
    z-index: -1;
}

.accountSection-navigation-item-active a:after,
.accountSection-navigation-item:hover a:after {
    border-color: var(--color-primary);
    background: var(--color-primary);
}

.accountSection-navigation-item {
    cursor: pointer;
    position: relative;
    display: block;
    border-top: 1px solid var(--color-neutral);
}

.member-cache {
    margin-bottom: 0px;
    /* font-size: 15px; */
    font-weight: 600;
    /* line-height: 21px; */
    color: rgb(255, 255, 255);
    padding: 2px 10px;
    border: 1px solid rgb(255, 255, 255);
    border-radius: 300px;
    background-color: var(--color-primary);
    display: inline-flex;
    -webkit-box-align: center;
    align-items: center;
    box-sizing: border-box;
    width: fit-content;
}
</style>