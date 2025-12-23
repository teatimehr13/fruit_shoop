<script setup>
import { computed, ref, watch, onMounted } from 'vue';
import SideBarNavLink from './SideBarNavLink.vue';
const props = defineProps({
    modelValue: { type: Boolean, default: true },
    isPinned: { type: Boolean, default: true }
    
})
const emit = defineEmits(['update:modelValue'])

console.log(props.modelValue);

watch(() => props.modelValue, v => console.log('drawer open =', v))

</script>

<template>
    <div class="drawer transition-all duration-300" :class="{ 'lg:drawer-open': props.isPinned }">
        <input id="my-drawer-3" type="checkbox" class="drawer-toggle" :checked="props.modelValue"
            @change="emit('update:modelValue', $event.target.checked)" />
        <div class="drawer-content flex flex-col h-[calc(100vh-72px)] overflow-y-auto">
            <slot name="drawer_content" />
        </div>
        <div class="drawer-side h-full ">
            <label for="my-drawer-3" aria-label="close sidebar" class="drawer-overlay lg:hidden"></label>
            <ul class="menu w-80 p-4 
           min-h-[calc(100vh-72px)] h-full overflow-y-auto
           bg-white border-e border-gray-200
           dark:bg-neutral-800 dark:border-neutral-700">

                <SideBarNavLink :href="route('back.products.index')" :active="route().current('back.products.*')"
                    @navigate="emit('update:modelValue', false)">產品
                </SideBarNavLink>
                <SideBarNavLink :href="route('back.categories.index')"
                    :active="route().current('back.categories.index')" @navigate="emit('update:modelValue', false)">類別
                </SideBarNavLink>
                <SideBarNavLink :href="route('back.orders.index')" :active="route().current('back.orders.*')"
                    @navigate="emit('update:modelValue', false)">訂單
                </SideBarNavLink>
                <SideBarNavLink :href="route('back.about.index')" :active="route().current('back.about.*')"
                    @navigate="emit('update:modelValue', false)">關於我們
                </SideBarNavLink>
            </ul>
        </div>
    </div>
</template>


<style scoped>
.drawer-content {
    background: #fafbfc;
    /* background: #000; */
    padding: 24px;
}
</style>