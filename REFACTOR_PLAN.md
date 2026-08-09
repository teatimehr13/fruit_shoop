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

## Phase 1 — Design Token 系統

- ⬜ 修 `app.css` 語法 bug(`--spacing-padding-content` 缺分號)
- ⬜ 建立完整 color token(延續品牌綠 `#82ae46`)、spacing、typography、radius、shadow token
- ⬜ 設定 DaisyUI theme 對齊 token,取代 ~150 處寫死 hex
- ⬜ 統一 `tailwind.config.js` 為 v4 風格

## Phase 2 — 前端架構統一 + 前台頁面改版

- ⬜ 決定並統一單一資料異動/通知模式(`useForm` vs `apiFeedback.js` axios vs `router` 直呼)
- ⬜ 整併 `Components/`(Breeze)與 `DaisyComponents/` 為單一元件庫
- ⬜ 剩餘 Options API 檔案轉為 `<script setup>`
- ⬜ 套用新 token 逐頁改版前台(Layouts → Home → Products → Cart → Checkout → Account → Auth)
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
