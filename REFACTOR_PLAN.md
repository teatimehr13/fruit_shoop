# fruit_shoop 全端重構追蹤

本文件追蹤全端重構的階段進度。狀態:⬜ 未開始 / 🔄 進行中 / ✅ 完成

## Phase 0 — 關鍵修復 + 死碼清理 ✅ 已完成

- ✅ 修復 `OrderItem` qty/quantity 欄位不一致 bug(`CheckoutController` 建立時、`Front/Back OrderController` 讀取時)
- ✅ 建立 `REFACTOR_PLAN.md` 追蹤文件
- ✅ 刪除未使用的空殼 controller(`Front/CartItemController`、`OrderItemController`、`ProductImageController`、`ProductOptionController`、`SubcategoryController`、`CategoryController`、`ProfileController`)
- ✅ 刪除前端死檔案(`Auth/*copy.vue`、`Test_draw.vue`、未使用的 `welcome.blade.php`)
- ✅ 清除大段註解死程式碼(`CheckoutController`、`Ecpay/PaymentController`、`Back/ProductController`)
- ✅ 驗證:`php artisan route:list`(87 條路由正常)、`php artisan test`(修改前後皆為 15 failed / 10 passed,無新增失敗,失敗皆為既有的 `profile.edit`/`profile.update` 死路由問題)、`npm run build`(編譯成功)

**驗證時發現但未修復的既有問題**(記錄供之後階段處理):
- `resources/js/Pages/Profile/Edit.vue` 與其 Partials(`UpdateProfileInformationForm.vue`、`DeleteUserForm.vue`)、`AuthenticatedLayout.vue` 依賴的 `profile.edit`/`profile.destroy` 命名路由並未註冊(只有 `profile.update` 由 `Front/AccountProfileController` 提供),導致 Breeze 預設的個人資料頁與 5 個既有測試失效。這組 Breeze 頁面已被 `Front/Account/Profile.vue` 取代,建議在 Phase 2(前端架構統一)一併處理:刪除整組未使用的 Breeze `Profile/*`、`Dashboard.vue`、`AuthenticatedLayout.vue`,並清掉對應測試。

## Phase 1 — Design Token 系統 ✅ 已完成

- ✅ 修 `app.css` 語法 bug(`--spacing-padding-content` 缺分號)
- ✅ 建立完整 color token(延續品牌綠 `#82ae46`)、spacing、typography、radius、shadow token
- ✅ 設定 DaisyUI theme 對齊 token,取代 Front 範圍(Pages/Front、Pages/Auth、Layouts、DaisyComponents/Front,共 32 檔)全部 262 處寫死 hex 中屬於此範圍的部分
- ✅ 統一 `tailwind.config.js` 為 v4 風格(刪除該檔,content/@source、fontFamily/maxWidth/width、forms plugin 全部搬進 `app.css` CSS-first 設定)

**範圍說明**:`Back/*`(後台)與 `Welcome.vue`(未使用的 Breeze 首頁殘留)刻意排除,前者將於 Phase 4 由 Filament 取代,後者待後續確認路由用途再處理。

**Token 對照表**(`resources/css/app.css`):
| 語意角色 | 值 | 取代的舊寫死色 |
|---|---|---|
| `primary` | `#82ae46` | `#82ae46`、`#0000ee`(誤用瀏覽器預設連結藍,順手修正) |
| `accent` | `#fc5d3d` | (新增,尚未有頁面使用,留給 Phase 2 特價標籤/CTA) |
| `base-content` | `#67645e` | `#67645e`、`#333` |
| `base-100` | `#ffffff` | `#fff`/`#ffffff` |
| `base-200` | `#f1f0ed` | `#f1f0ed`、`#f9f7f2`、`#fafafa` |
| `base-300` | `#eeeeee` | `#EDEDED`、`#eeeeee`、`#f5f5f5`、`#f0f0f0`、`#e5e5e5` |
| `neutral` | `#c4c4c4` | `#c4c4c4`、`#d8d8d8`(`neutral/50` 取代 `#cccccc80`) |
| `feature-pink`/`feature-tan`/`feature-blue`/`feature-olive` | `#e4b2d6`/`#dcc698`/`#a2d1e1`/`#dcd691` | 同值,僅正式收編為 token(`_Feature.vue` 四個特色圖示裝飾色,非語意色) |
| `--radius-field` | `0.75rem`(12px) | 對應決策的「中等圓角」(2026-08-12 已修正,見決策紀錄) |
| `--depth: 0` / `--noise: 0` | — | 關閉 DaisyUI 元件內建的立體光影效果,走乾淨風格 |
| `--font-sans` | Poppins | 取代 Figtree(`app.blade.php` 字體連結、DaisyUI 均已切換) |

**驗證**:`npm run build` 成功;抽查編譯後 CSS 確認 `bg-primary`/`text-base-content`/`border-neutral\/50`/`bg-feature-pink` 等 utility 皆正確產生對應 CSS 變數。

## Phase 2 — 前端架構統一 + 前台頁面改版

- ✅ 決定資料異動/通知模式:`useForm` 給有驗證錯誤狀態需求的表單(Auth/Profile)、`apiFeedback.js`(axios 攔截器 + 自動 toast)給其他所有資料異動
- ✅ 剩餘 Options API 檔案:盤點結果為 **0 個**,不需動作
- ⬜ 整併 `Components/`(Breeze)與 `DaisyComponents/` 為單一元件庫 —— 會跟著「逐頁改版」該頁面時一起做,不是分開的獨立步驟
- 🔄 套用新 token 逐頁改版前台(Layouts → Home → Products → Cart → Checkout → Account → Auth)。**Home、Products(含商品詳情頁)、Cart、Checkout、Account 已完成**,詳見下方決策紀錄。Auth 尚未開始
- 註:後台(`Back/*`)頁面故意跳過,將於 Phase 4 由 Filament 取代

## Phase 3 — 後端 Service 層清理

- ⬜ 整併 `CartService` 與 `Front/CartController` 重複邏輯
- ⬜ 拆解 `CheckoutController::createOrderByCart` 到 Service
- ⬜ 補齊缺少的 FormRequest 驗證
- ⬜ 補齊 return type hint、修 N+1 風險

## Phase 4 — 導入 Laravel Filament 後台

- ⬜ 安裝 Filament,設定 panel provider 與管理員權限
- ⬜ 建立 Product(含 Options/Images)、Category/Subcategory、Order、About 的 Filament Resource
- ⬜ 比對新舊後台功能無遺漏後,刪除 `routes/back.php`、`Back/*Controller`、`Pages/Back/*`、`LayoutBack.vue`

## Phase 5 — ECPay 金流重新設計

- ⬜ stage/production URL 改用 env 設定
- ⬜ `TradeDesc`/`ItemName` 改為逐項化
- ⬜ 用 signed route 取代目前脆弱的 session/re-login workaround
- ⬜ 修復 retry 端點的擁有者驗證(目前被註解掉)
- ⬜ 清除 `PaymentController`/`EcpayPaymentService` 死程式碼
- ⬜ 於 ECPay stage 環境完整測試付款/返回/查詢流程

## 決策與變更紀錄

依時間順序記錄**有理由/有規則、以後要一致套用**的設計決策與功能改動——純外觀微調(顏色、圓角、間距這種一兩個 class 的調整)不記錄,只記需要被記住的「規則」跟「為什麼」,以及有查出根本原因的 bug 修復。(2026-08-13 起改用這個精簡標準,舊內容已依此標準整理過。)

### 2026-08-09 — 專案基礎決策

- 前台改版參考 theme 定案為 [Econis](https://econis.wpbingosite.com/home-7/)(生鮮水果電商 demo),有機生鮮風、大量留白、淺色區塊底。
- Primary 沿用品牌綠 `#82ae46`;新增 Accent 珊瑚橘 `#fc5d3d`,只用於特價標籤/CTA 強調,不是 primary。
- 字體統一改 Poppins,取代 Figtree;不採用襯線/無襯線對比做法。
- 保留 DaisyUI 不拆除:`btn`/`input`/`select` 等 class 已用在 63/81/39+ 處,拆除成本遠高於用 theme 系統套 token。
- Design token 命名與對照表定案(見上方 Phase 1 表格),相近灰階刻意合併以減少色彩雜訊。
- Phase 2 資料異動模式定案:`useForm` 只給 Auth/Profile 這種需要驗證錯誤狀態的表單用,其餘(購物車等)統一走 `apiFeedback.js`,淘汰裸 axios 無錯誤處理的寫法。
- 方法論教訓:不能只在既有排版上套 token 數值,要對照參考站實際 DOM/畫面重建,單純套規格數值「一點意義也沒有」。

### 2026-08-10 — 字體與元件基礎

- 中文字體破圖根因:`--font-sans` 沒指定中文字體,Poppins 不含中文字,一路 fallback 到系統預設(通常是微軟正黑體),沒特別挑過所以不協調。解法:額外載入 Noto Sans TC,插在 Poppins 之後。這是全域字體設定,影響全站。
- 標題字重原則:字級越小字重要越重,不然小字撐不住;h1~h3 統一 `font-medium`(500),之後新頁面沿用這個上限。
- 新增 `--color-heading` token 取代重複打的 `text-[#3e4a5e]`,只套用在淺底標題,深色/彩色底標題維持白字不套用。
- 確認不採用等寬字體(`IBM Plex Mono`)當全站字體:犧牲可讀性、跟品牌溫暖調性衝突,中文仍會 fallback 導致中英文寬度混排問題。

### 2026-08-11 — CTA/Nav/購物車重寫

- 技術教訓:祖先元素只要有 `transform`(例如 Tailwind 的 `translate-y-0`),就會變成子孫 `position:fixed` 的新 containing block,子孫的 fixed 定位會相對這個祖先算、不是相對 viewport。這是所有 modal/offcanvas/選單類元件都要 `<Teleport to="body">` 的原因,之後遇到 fixed 定位跑掉的情況要先檢查這個。
- Section 間距原則:每個 section 自己負責完整的 `py`(上下都設),不要靠單邊 `pb`/`pt` 接鄰居——這樣區塊獨立可預測,以後調順序/插入新區塊不用重算鄰居數值,唯一要注意相鄰兩區的 `py` 會相加,數值要抓小一點補償。
- CJK 排版原則:中文字彼此緊貼,需要比英文更寬鬆的字距/行距才不會看起來像瀏覽器預設硬擠。
- Cart drawer 設計查過業界慣例(右側滑出+line item 卡片+底部 sticky 摘要+明確 CTA),跟本站既有架構吻合,改動聚焦在出現方式的實作機制(拿掉 `pointer-events-auto/none` 手動技巧,改用 `<Transition>`)。
- 修掉一個真實 bug:`toggleMenu()` 關閉手機選單時執行 `customNav.value = false`,但 `customNav` 從未宣告過,每次關閉選單都會噴 JS 錯誤,是既有未被發現的 bug。
- `QuantityStepper.vue` 減少按鈕的 SVG 誤用 JSX 語法(`strokeWidth={1.5}`),在 Vue template 裡不是合法屬性,導致樣式沒套上;順手補上原本缺的 `:disabled="modelValue <= min"`。
- 摘要區塊 `fixed w-full bottom-0` 換成 `<Transition>` 後跑版,根因是原本的 `fixed` 定位其實**意外依賴**了 `<aside>` 恆定的 `translate-x-0` 造成的 transform containing block,`<Transition>` 進場完成後會拿掉這個 class、`fixed` 就跳出去對齊整個 viewport。改成放棄 `fixed`,單純讓摘要區塊當 flex-col 容器裡最後一塊、自然貼底。

### 2026-08-12 — Products 頁與商品卡大改版

- ProductCard 抽成共用元件(`DaisyComponents/Front/ProductCard.vue`),Home 與 Products 頁共用,避免兩邊卡片各自維護以後長歪;共用卡片補上折扣 UI(Products 頁原本就有、Home 沒有)。折扣邏輯統一改成看「目前選中規格」的 `original_price` vs `price` 現算,不是固定顯示最低價選項的折扣,切換規格時會正確更新。
- Banner 該不該存在:查了 Baymard Institute(分類頁用大 banner 把商品/篩選擠到第一屏下面是「最常見也最傷的錯誤」,手機版尤其嚴重)、Nielsen Norman Group(使用者 80% 瀏覽時間花在第一屏以上)的研究,加上「editorial-heavy(banner+品牌故事,如 Zara)vs pure navigation(直接列商品,適合快速購物的實用型零售商)」這個分類頁類型慣例,生鮮雜貨屬於後者,確認拿掉 `/products` 的 banner。
- 篩選 UI 選定「按鈕+絕對定位下拉面板」模式(不是側邊抽屜):面板掛在按鈕正下方、純文字+選中變色(不用真的 `<input type="radio">`,跟站內其他清單一致用純點擊處理)、點外面自動關閉、選完自動關閉。
- 相關商品查詢要用「分類」層級,不是「子分類」層級篩選——子分類底下常常只有一兩筆商品(例如「葉菜」子分類目前只有羽衣甘藍自己),範圍抓太窄區塊會直接空著。
- 商品詳情頁圖片區選定「主圖+圓點指示器+箭頭」模式(不用縮圖列),原因是簡潔、且站內商品圖片數量本來就不多(1-2 張居多),縮圖列功能性有限。
- 按鈕圓角規則:功能性按鈕(篩選/排序/表單/加購)用 `rounded-[4px]`,行銷/CTA 按鈕(Hero/CTA/promo)用 `rounded-full`,查證 Material Design 3、Shopify Polaris 等 design system 後確認「用不同圓角區分主要 CTA 跟功能性按鈕」是被認可的做法,兩者不用統一成一種,但要有系統性、不能隨手挑數字(修正了 `Products/_Category.vue`/`Products/_HomeHero.vue` 兩個對不上規則的 outlier,順便發現這兩個檔案其實是 `Products/Index.vue` 裡註解掉沒渲染的死元件,已刪除)。
- 按鈕實心/outline 規則:實心 = 一個畫面裡唯一的主要動作,outline = 次要/一般動作,一畫面盡量只留一顆實心主要按鈕(業界通用的按鈕階層準則)。依此規則把 `Checkout` 前往結帳、`Show.vue` 加入購物車也改成實心。新增共用元件 `DaisyComponents/Front/PrimaryButton.vue`(功能性主要動作按鈕),對應既有的 `CtaButton.vue`(行銷按鈕),兩者是同一套邏輯的兩個變體,以後新增主要動作按鈕直接用這個元件、不用複製 class 字串。
- Bug:Nav 購物車角標跟抽屜「購物車 (N)」數字對不上,根因是兩邊各自算了不同東西——`total_qty`(所有品項數量加總)vs `items.length`(不重複品項筆數)。改成兩邊共用同一個 `ItemsCount`。
- Bug:購物車抽屜商品一多,底部 footer 會被擠出畫面外,根因是 flex item 只給 `h-full` 沒給 `flex-1`(沒有明確的 flex-basis 可依附百分比高度),加上 flex 預設 `min-height:auto` 讓內部 `overflow-y-auto` 沒辦法真的觸發捲動。改成 `flex-1 min-h-0`。
- Bug:Footer 內容跟 Header 沒對齊,根因是 Footer 外層用 `p-10`(四邊 40px),其他區塊統一用 `max-w-layout-wide mx-auto px-4`(16px),兩者水平留白值不同。統一改用 `px-4`。
- `--radius-field` 的「中等圓角(12px)」決策已被上面的圓角規則取代,不再是有效規則。

**WebSearch 參考來源**:[Baymard - Ecommerce Category Pages](https://baymard.com/learn/ecommerce-category-page)、[Homepage & Category UX Best Practices – Baymard](https://baymard.com/blog/current-state-of-ecommerce-category-ux)、[Button hierarchy – SubUX](https://subux.pro/guides/article/button-hierarchy-primary-secondary-tertiary)、[Telerik Design System - Button](https://www.telerik.com/design-system/docs/components/button/)
