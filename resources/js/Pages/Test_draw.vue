<script>
</script>

<template>
    <!-- 桌機雙欄 -->
<div class="hidden lg:grid h-[calc(100svh-65px)] grid-cols-[320px_1fr]">
  <aside class="border-e overflow-y-auto">
    <!-- Tree -->
  </aside>
  <section class="relative overflow-y-auto">
    <!-- 表單 / 空狀態 -->
    <div class="sticky bottom-0 border-t bg-white p-3 flex justify-end gap-2">
      <button class="btn btn-ghost">Cancel</button>
      <button class="btn btn-primary">Save</button>
    </div>
  </section>
</div>

<!-- 手機：左樹用 drawer -->
<div class="lg:hidden drawer">
  <input id="cat-drawer" type="checkbox" class="drawer-toggle" />
  <div class="drawer-content p-4">
    <label for="cat-drawer" class="btn mb-4">選擇分類</label>
    <!-- 右面板內容 -->
  </div>
  <div class="drawer-side">
    <label for="cat-drawer" class="drawer-overlay"></label>
    <aside class="w-80 bg-white border-e h-full overflow-y-auto">
      <!-- Tree -->
    </aside>
  </div>
</div>

</template>