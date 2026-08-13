# fruit_shoop 全端重構

## 目標與範圍

全端重構 fruit_shoop:清死碼、統一 design token、前台逐頁改版、後端 Service 層清理、後台換 Filament、ECPay 金流重做。範圍排除:後台(`Back/*`)頁面樣式在 Phase 4 被 Filament 取代前不整理;既有 Breeze 殘留頁面(`Profile/*`、`Dashboard.vue`、`AuthenticatedLayout.vue`)在 Phase 2 Auth 收尾前不動。

## 工作項目與狀態

- ✅ **Phase 0** — 關鍵 bug 修復 + 死碼清理
- ✅ **Phase 1** — Design Token 系統建立(`resources/css/app.css`),前台範圍(`Pages/Front`、`Pages/Auth`、`Layouts`、`DaisyComponents/Front`)全面套用
- 🔄 **Phase 2** — 前端架構統一 + 前台改版:Home、Products(含商品詳情頁)、Cart、Checkout、Account 已完成;**訂單詳情頁(`order.show`)尚未開始**;**Auth 尚未開始**;`Components/`(Breeze)與 `DaisyComponents/` 整併成單一元件庫也還沒做(規劃隨改版頁面順便處理,不是獨立步驟)
- ⬜ **Phase 3** — 後端 Service 層清理:整併 `CartService`/`Front/CartController` 重複邏輯、拆解 `CheckoutController::createOrderByCart`、補 FormRequest 驗證、補 return type hint、修 N+1
- ⬜ **Phase 4** — 導入 Laravel Filament 後台:建立 Product/Category/Order/About 等 Resource,確認新舊後台功能對齊後刪除 `routes/back.php`、`Back/*Controller`、`Pages/Back/*`、`LayoutBack.vue`
- ⬜ **Phase 5** — ECPay 金流重新設計:URL 改 env 設定、`TradeDesc`/`ItemName` 逐項化、signed route 取代 session/re-login workaround、修復被註解掉的 retry 端點擁有者驗證、清死碼

## 關鍵決策

- 前台改版參考 [Econis](https://econis.wpbingosite.com/home-7/)(生鮮電商 demo)。Primary 沿用品牌綠 `#82ae46`;Accent 珊瑚橘 `#fc5d3d` **只用於特價標籤/CTA 強調**,不當 primary 用。
- 保留 DaisyUI,不拆除改純 Tailwind——`btn`/`input`/`select` 等 class 用量太大,拆除成本遠高於用 theme 系統套 token。
- 資料異動模式:一律走 `apiFeedback.js`(axios 攔截器 + 自動 toast);只有 Auth/Profile 這種需要顯示欄位驗證錯誤狀態的表單才用 `useForm`。
- 按鈕圓角規則:功能性按鈕(篩選/排序/表單/加購)用 `rounded-[4px]`,行銷/CTA 按鈕(Hero/CTA/promo)用 `rounded-full`。兩者不用統一成一種,但要照這個系統性規則套,不能隨手挑數字。
- 按鈕實心/outline 規則:實心 = 一個畫面裡**唯一**的主要動作,outline = 次要/一般動作,一畫面盡量只留一顆實心主要按鈕。共用元件 `DaisyComponents/Front/PrimaryButton.vue`(功能性主要動作)對應 `CtaButton.vue`(行銷按鈕)。
- 標題字重:字級越小字重要越重,不然小字撐不住;h1~h3 統一 `font-medium`(500),新頁面沿用此上限。
- `--color-heading` token 只套用在淺底標題,深色/彩色底標題維持白字,不套用。
- `/products` 分類頁不放 banner(已查證 Baymard/NN Group 對「大 banner 把商品擠到第一屏下面」的負評研究,生鮮雜貨屬於 pure navigation 類型,不是 editorial-heavy 類型)。
- 相關商品查詢用「分類」層級,不用「子分類」層級——子分類底下常常只有一兩筆商品,範圍抓太窄區塊會直接空著。

## 已知陷阱

- 祖先元素只要有 `transform`(例如 Tailwind 的 `translate-y-0`),就會變成子孫 `position:fixed` 的新 containing block,子孫的 fixed 定位會相對這個祖先算、不是相對 viewport。所有 modal/offcanvas/選單類元件都要 `<Teleport to="body">`,之後遇到 fixed 定位跑掉先檢查這個。
- Flex 容器裡要讓子層 `overflow-y-auto` 真正可以捲動,子層必須同時給 `flex-1` **和** `min-h-0`(flex 預設 `min-height:auto` 會讓內容撐高,光給 `flex-1` 不夠)。
- Section 間距:每個 section 自己負責完整的 `py`(上下都設),不要靠單邊 `pb`/`pt` 接鄰居;相鄰兩區的 `py` 會相加,數值要抓小一點補償。
- `resources/js/Pages/Profile/Edit.vue` 與其 Partials、`AuthenticatedLayout.vue` 依賴的 `profile.edit`/`profile.destroy` 命名路由並未註冊(只有 `profile.update` 由 `Front/AccountProfileController` 提供),這組 Breeze 頁面已被 `Front/Account/Profile.vue` 取代但還沒刪除,會導致相關舊測試持續失敗。Phase 2 Auth 收尾時要一併刪除整組未使用的 Breeze `Profile/*`、`Dashboard.vue`、`AuthenticatedLayout.vue` 並清掉對應測試。
- Nav 購物車角標與抽屜「購物車 (N)」數字容易對不上,根因是 `total_qty`(數量加總)vs `items.length`(不重複品項數)是兩個不同的數字。兩邊已改共用同一個 `ItemsCount`,之後新增計數邏輯要延用這個,不要各自重新計算。
- `DaisyComponents/Front/OutlineButton.vue`(共用 outline 按鈕元件)的 `tag` 若不是 `'button'`(例如 `tag="a"` 或傳入 Inertia `Link`),不能把 `disabled` 直接綁上去——`<a>` 沒有真正的 `disabled` DOM 屬性,Vue 會字面寫成 `disabled="false"` 字串,DaisyUI 的 `.btn[disabled]` 規則只看屬性存不存在、不看值,會讓按鈕整個點不到。已修成 `:disabled="tag === 'button' ? disabled : undefined"`,以後改這個元件或抽類似的多標籤共用按鈕元件時要留意同樣的坑。

## 下一步

訂單詳情頁(`order.show`,`route('order.show', order_number)` 對應的頁面)的 layout 設計,尚未討論細節。Auth 頁面(登入/註冊/忘記密碼)改版仍在 Phase 2 待辦清單裡,順序排在這個之後。
