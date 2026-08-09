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
| `--radius-field` | `0.75rem`(12px) | 對應決策的「中等圓角」 |
| `--depth: 0` / `--noise: 0` | — | 關閉 DaisyUI 元件內建的立體光影效果,走乾淨風格(⚠️ 見下方更正:Econis 實際上不是完全無陰影) |
| `--font-sans` | Poppins | 取代 Figtree(`app.blade.php` 字體連結、DaisyUI 均已切換) |

**驗證**:`npm run build` 成功;抽查編譯後 CSS 確認 `bg-primary`/`text-base-content`/`border-neutral\/50`/`bg-feature-pink` 等 utility 皆正確產生對應 CSS 變數。`php artisan test` 此次因環境 MySQL 連線問題(`Access denied for user 'root'`)整批失敗,與本次純前端 CSS/Vue 改動無關,未進一步排查(本階段沒有動任何 PHP/DB 相關程式碼)。

## Phase 2 — 前端架構統一 + 前台頁面改版

- ⬜ 決定並統一單一資料異動/通知模式(`useForm` vs `apiFeedback.js` axios vs `router` 直呼)
- ⬜ 整併 `Components/`(Breeze)與 `DaisyComponents/` 為單一元件庫
- ⬜ 剩餘 Options API 檔案轉為 `<script setup>`
- ⬜ 套用新 token 逐頁改版前台(Layouts → Home → Products → Cart → Checkout → Account → Auth)
- 註:後台(`Back/*`)頁面故意跳過,將於 Phase 4 由 Filament 取代

**Econis 版面規格參考**(2026-08-09 額外拉 Elementor 產生的實際 CSS 檔案(`post-14148.css`、`elementor/frontend.min.css`)量出來的具體數值,不是憑肉眼猜的,供 Phase 2 動工時對照):

| 項目 | 數值 | 備註 |
|---|---|---|
| 容器最大寬度 | `1140px` | 剛好等於本專案既有的 `--max-w-layout-normal`(71.25rem),不用新增 token |
| Grid/區塊間距 | `30px` | 多個 grid 區塊一致用這個 gap |
| 卡片/圖片區塊圓角 | `20px` | 跟按鈕的 9999px 全圓角是分開的規格 |
| 大型膠囊/標籤圓角 | `50px` | 次常見,用在較大的膠囊型元素 |
| 圓形元素 | `50%` | icon/頭像類 |
| 卡片陰影 | `0 9px 15px 0 rgba(0,0,0,.05)`、`0 8px 21px 3px rgba(0,0,0,.05)` | **更正 Phase 1 決策紀錄裡「扁平無陰影」的說法**:Econis 其實有用陰影,只是很淡(5% 透明度的黑),不是完全沒有陰影。Phase 1 設的 `--depth: 0` 只影響 DaisyUI 元件自帶的立體光影,不影響 Phase 2 卡片可以自訂這種淡陰影 |
| 標題字級 | `82px`(hero 大標)/`50px`(區塊標題)/`34px`/`30px`(次標題) | |
| 內文字級 | `25px`/`22px`/`20px`/`18px`(卡片標題/內文)/`16px`/`14px`/`12px`(輔助文字) | |
| Section 水平內距 | `0 15px`(最常見)、`0 30px`、`0 7.5px` | |

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

依時間順序記錄每次的設計決策與功能改動,作為各 Phase checklist 之外的補充脈絡(checklist 記錄「做了什麼」,這裡記錄「為什麼這樣做、什麼時候定案」)。

### 2026-08-09

- **[決策]** 前台改版參考 theme 確定為 [Econis](https://econis.wpbingosite.com/home-7/)(生鮮水果電商 demo),不再新增其他候選。風格:有機生鮮、大量留白、淺色區塊底、扁平無陰影。
- **[決策]** Primary 色維持 `#82ae46`(現有品牌綠)不變;新增 Accent 色為珊瑚橘 `#fc5d3d`,僅用於特價標籤/CTA 強調。
- **[決策]** 字體統一改為 Poppins,取代 `tailwind.config.js` 目前的 Figtree;不採用參考站的無襯線/襯線對比做法。
- **[決策]** 按鈕圓角採中等圓角(約 12px),不採用參考站的全圓角膠囊型。
- **[決策]** 保留 DaisyUI,不拆除。理由:`btn`/`input`/`select` 等 class 已用在 63/81/39 處以上,拆除成本遠高於用 DaisyUI theme 系統套 token 的作法。
- **[改動]** Phase 0 完成並 commit `98f349b`:修復 `OrderItem` qty/quantity 欄位不一致 bug、刪除 7 個空殼 Front controller 與 6 個前端死檔案、清除大段註解死程式碼。詳見上方 Phase 0 區塊。
- **[決策]** Design token 語意色票命名與對照表定案,詳見上方 Phase 1 區塊的表格。灰階系列刻意合併(如 `f9f7f2`/`fafafa`/`f1f0ed` 三色合併成 `base-200`)以減少色彩雜訊,這是 token 化的核心目的之一。
- **[改動]** Phase 1 完成(尚未 commit):`app.css` 語法修正、建立完整 DaisyUI custom theme token、刪除 `tailwind.config.js` 改用 CSS-first 設定、字體換 Poppins、Front 範圍(32 檔)寫死 hex 全數換成 token class、順手修掉 `OrderInfo.vue` 誤用瀏覽器預設連結藍(`#0000ee`)與兩處死程式碼註解(`QuantityStepper`/`QuantityStepper_Product` 的殘留 comment)。詳見上方 Phase 1 區塊。
