<template>
  <div class="w-full px-2 sm:px-0">
    <TabGroup>
      <TabList class="tabs tabs-border">
        <Tab v-for="tab in tabs" as="template" :key="tab.key" v-slot="{ selected }">
          <button :class="[
            'tab',
            'text-sm',
            selected
              ? 'tab-active' : '',
          ]">
            {{ tab.label }}
          </button>
        </Tab>
      </TabList>

      <TabPanels class="mt-2">
        <TabPanel v-for="tab in tabs" :key="tab.key" :class="[
          'rounded-xl bg-white',
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

<style>
.tabs-border {
  & .tab {
    &:before {
      --tw-content: "";
      content: var(--tw-content);
      background-color: var(--tab-border-color);
      transition: background-color 0.2s ease;
      width: 70%;
      height: 3px;
      border-radius: var(--radius-field);
      bottom: 5px;
      left: 15%;
      position: absolute;
    }
  }
}

.list {
    .list-row{
      padding: .5rem 0;
    } 
}
</style>