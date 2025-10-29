<script setup>
import { Link } from '@inertiajs/vue3';

</script>

<template>

    <div class="navbar bg-base-100 border-b border-gray-200 lg:z-20 sticky top-0">
        <div class="navbar-start">
            <slot name="open_drawer"/>
            <a class="btn btn-ghost text-xl">後台LOGO</a>
        </div>
        <div class="navbar-center hidden lg:flex">
        </div>
        <div class="navbar-end flex-none">
            <div class="dropdown dropdown-end">
                <ul class="menu menu-horizontal px-1">
                    <li>
                        <details>
                            <summary>{{ $page.props.auth.user.name }}</summary>
                            <ul tabindex="-1"
                                class="menu menu-sm dropdown-content bg-base-100 rounded-box z-1 mt-3 w-30 p-2 shadow">
                                <li><a>去前台</a></li>
                                <li>
                                    <Link :href="route('logout')" method="post" as="button">
                                    登出
                                    </Link>
                                </li>
                            </ul>
                        </details>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>