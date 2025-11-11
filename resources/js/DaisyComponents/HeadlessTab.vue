<template>
  <div class="w-full  px-2 py-16 sm:px-0">
    <TabGroup>
      <TabList class="flex space-x-1 rounded-xl bg-blue-900/20 p-1">
        <Tab v-for="tab in tabs" as="template" :key="tab.key" v-slot="{ selected }">
          <button :class="[
            'w-full rounded-lg py-2.5 text-sm font-medium leading-5',
            'ring-white/60 ring-offset-2 ring-offset-blue-400 focus:outline-none focus:ring-2',
            selected
              ? 'bg-white text-blue-700 shadow'
              : 'text-blue-100 hover:bg-white/[0.12] hover:text-white',
          ]">
            {{ tab.label }}
          </button>
        </Tab>
      </TabList>

      <TabPanels class="mt-2">
        <TabPanel v-for="tab in tabs" :key="tab.key" :class="[
          'rounded-xl bg-white p-3',
          'ring-white/60 ring-offset-2 ring-offset-blue-400 focus:outline-none focus:ring-2',
        ]">
          <slot :name="tab.key" :data="data"> 
            <!-- 預設內容（如果沒提供 slot） -->
            <p class="text-sm text-gray-500">沒有內容</p>
          </slot>
        </TabPanel>
      </TabPanels>
    </TabGroup>
  </div>
  <!-- <pre class="text-xs opacity-60">{{ props }}</pre> -->
</template>

<script setup>
import { ref, watch } from 'vue'
import { TabGroup, TabList, Tab, TabPanels, TabPanel } from '@headlessui/vue'

const categories = ref({
  Recent: [
    {
      id: 1,
      title: 'Does drinking coffee make you smarter?',
      date: '5h ago',
      commentCount: 5,
      shareCount: 2,
    },
    {
      id: 2,
      title: "So you've bought coffee... now what?",
      date: '2h ago',
      commentCount: 3,
      shareCount: 2,
    },
  ],
  Popular: [
    {
      id: 1,
      title: 'Is tech making coffee better or worse?',
      date: 'Jan 7',
      commentCount: 29,
      shareCount: 16,
    },
    {
      id: 2,
      title: 'The most innovative things happening in coffee',
      date: 'Mar 19',
      commentCount: 24,
      shareCount: 12,
    },
  ],
  Trending: [
    {
      id: 1,
      title: 'Ask Me Anything: 10 answers to your questions about coffee',
      date: '2d ago',
      commentCount: 9,
      shareCount: 5,
    },
    {
      id: 2,
      title: "The worst advice we've ever heard about coffee",
      date: '4d ago',
      commentCount: 1,
      shareCount: 2,
    },
  ],
})

const props = defineProps({
  data: Object,
  tabs: Array,
})

// console.log(props.data);

watch(
  () => props.data,
  (newVal) => {
    console.log('子元件收到資料：', newVal)
  },
  { deep: true, immediate: true }
)

</script>
