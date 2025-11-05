<script setup>
import { computed } from 'vue'

const props = defineProps({
    pagination: {
        type: Object,
        required: true
    },
    maxVisible: {
        type: Number,
        default: 5
    }
})

console.log(props.pagination);
console.log(props.maxVisible);



const emit = defineEmits(['change'])

const visiblePages = computed(() => {
    const current = props.pagination.current_page
    const last = props.pagination.last_page
    const max = props.maxVisible  //最多顯示5頁

    let start = Math.max(1, current - Math.floor(max / 2)) //取最大值 12
    let end = Math.min(last, start + max - 1) //取最小值  15

    // cur = 14; last = 15; max = 5
    // cur - 5/2 = 12 ->start = 12
    // 15, 12+5-1 = 16(15) end 
    // 15 - 12 +1 < max => start 從12變11多一個
    if (end - start + 1 < max) { 
        start = Math.max(1, end - max + 1) 
    }

    const pages = []
    for (let i = start; i <= end; i++) {
        pages.push(i)  //11~15
    }

    return pages
})

const goToPage = (page) => {
    if (page < 1 || page > props.pagination.last_page) return
    if (page === props.pagination.current_page) return

    emit('change', page)
}

const isFirstPage = computed(() => props.pagination.current_page === 1)
const isLastPage = computed(() => props.pagination.current_page === props.pagination.last_page)


</script>

<template>
    <div class="flex items-center justify-between">
        <div class="text-sm text-base-content/70">
            顯示 {{ pagination.from }} 到 {{ pagination.to }} 筆，共 {{ pagination.total }} 筆
        </div>

        <!-- 分頁按鈕 -->
        <div class="join">
            <!-- 上一頁 -->
            <button class="join-item btn btn-sm" :disabled="isFirstPage" @click="goToPage(pagination.current_page - 1)">
                «
            </button>

            <!-- 第一頁（如果不在可見範圍） -->
            <template v-if="visiblePages[0] > 1">
                <button class="join-item btn btn-sm" @click="goToPage(1)">
                    1
                </button>
                <button class="join-item btn btn-sm btn-disabled" v-if="visiblePages[0] > 2">
                    ...
                </button>
            </template>

            <!-- 頁碼按鈕 -->
            <button v-for="page in visiblePages" :key="page" class="join-item btn btn-sm"
                :class="{ 'btn-active': page === pagination.current_page }" @click="goToPage(page)">
                {{ page }}
            </button>

            <!-- 最後一頁（如果不在可見範圍） -->
            <template v-if="visiblePages[visiblePages.length - 1] < pagination.last_page">
                <button class="join-item btn btn-sm btn-disabled"
                    v-if="visiblePages[visiblePages.length - 1] < pagination.last_page - 1">
                    ...
                </button>
                <button class="join-item btn btn-sm" @click="goToPage(pagination.last_page)">
                    {{ pagination.last_page }}
                </button>
            </template>

            <!-- 下一頁 -->
            <button class="join-item btn btn-sm" :disabled="isLastPage" @click="goToPage(pagination.current_page + 1)">
                »
            </button>
        </div>
    </div>
</template>