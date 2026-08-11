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

- ✅ 決定資料異動/通知模式:`useForm` 給有驗證錯誤狀態需求的表單(Auth/Profile)、`apiFeedback.js`(axios 攔截器 + 自動 toast)給其他所有資料異動。裸 axios 無錯誤處理的寫法(`DaisyComponents/Front/CartContent.vue`、`Pages/Front/Products/Show.vue`)全部淘汰,改走 apiFeedback。實際套用會跟著各頁改版一起做,不是獨立一步
- ✅ 剩餘 Options API 檔案:盤點結果為 **0 個**,`Pages`/`DaisyComponents` 底下全部已是 `<script setup>`,此項目不需動作
- ⬜ 整併 `Components/`(Breeze)與 `DaisyComponents/` 為單一元件庫 —— 盤點發現 `Components/` 13 個檔案全部還在用,對應到 Auth + Profile + Dashboard 這條還沒 DaisyUI 化的線,不是死程式碼。這個整併會跟著「逐頁改版」該頁面時一起做(改到 Auth/Profile 時順便把 Breeze 元件換成 DaisyComponents),不是分開的獨立步驟
- ⬜ 套用新 token 逐頁改版前台(Layouts → Home → Products → Cart → Checkout → Account → Auth),每頁改版時一併套用上面兩項決定(元件庫、資料異動模式)
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
- **[決策]** Phase 2 資料異動模式定案:`useForm` 只給 Auth/Profile 這種需要驗證錯誤狀態的表單用,其餘資料異動(購物車等)統一走 `apiFeedback.js`(axios 攔截器 + 自動 toast),淘汰目前 `CartContent.vue`/`Products/Show.vue` 那種裸 axios 無錯誤處理的寫法。盤點也發現 Options API 已 0 殘留、`Components/`(Breeze)沒有死程式碼,是 Auth/Profile/Dashboard 這條還沒 DaisyUI 化的線,整併會跟著逐頁改版一起做,不獨立成一步。
- **[改動]** Phase 2 開工,Layouts 第一輪:`Nav.vue`/`Footer.vue` 容器寬度從 1440px(`max-w-layout-wide`)改成 Econis 實測的 1140px(`max-w-layout-normal`),Header 陰影換成 Econis 淡陰影值。
- **[決策]** 使用者反饋前兩輪(Layouts、Home 第一輪)的 token 對應/微調「一點意義也沒有」,要求直接照 Econis 實際畫面重建結構,不要只在既有排版上套規格數值。改用 BeautifulSoup 解析 Econis home-7 完整 DOM(15 個 `elementor-top-section`),取得真實區塊順序與內容:Hero slider → Hot categories(6 色塊)→ 分類 grid(6 張圖+名稱)→ 2 欄 promo banner → Top products 標題 → 商品 grid → 「Wake Up Early」三圖示 feature+banner → Best seller/Top Rated/On Sale 分頁商品 grid → Testimonials → 品牌 logo 條 → Newsletter banner → Footer。
- **[改動]** 依上述結構重建 Home 頁 Hot Categories 區塊:`_Category.vue` 從「不對稱拼貼圖+單一行銷文案」改成真正的分類方塊 grid,每格對應一個真實 `Category`、連到 `categories.products` 路由。順手在 `ProductController::index`(Home 用的方法)多查一次 `categories` 傳給前端(3 個已啟用分類:蔬菜/水果/果汁),因為原本 Home 頁完全沒有接分類資料。原本的行銷文案(FRESH-PICK 果蔬箱促銷)是真實內容不是佔位文字,移到新建的 `_Promo.vue` 保留下來,不是刪掉。
- 待辦(下一輪):Econis 首頁還有 Testimonials、品牌 logo 條、Newsletter banner、Best seller 分頁式商品 grid 這幾個區塊 fruit_shoop 完全沒有對應元件或資料,需要新建;Top products 目前是 carousel 呈現,Econis 是靜態 grid,要不要改成 grid 待確認。
- **[決策]** 使用者進一步反饋:不要在既有首頁上繼續改,要一個**跟 Econis 畫面、內容、圖片都一樣**的全新頁面,之後使用者自己再改。改用預覽路由 `/home-preview`(`front.home-preview`)、新頁面 `resources/js/Pages/Front/HomePreview/Index.vue`,不動 `/`(正式首頁)。
- **[決策]** 使用者反饋 `/home-preview` 「80% 接近但還是不太一樣」。改用 Read 工具直接打開下載下來的圖片檢查內容(先前只看檔名/CSS 數值,沒真的看過圖片長什麼樣),發現兩個系統性誤判:(1) Hero 底圖 `hero-bg.jpg` 其實是淺色插畫背景(日出造型+蔬果線稿),不是照片,原本套「深色漸層遮罩+白字」完全套錯方向,實際應該是淺底+深色文字+產品去背圖層(果汁瓶+果汁潑濺去背圖)組成;(2) 分類方塊圖片(`cat-*.jpg`)本身已經是「白/淺紫底+置中商品去背照」的成品圖,不是滿版照片,原本套「object-cover 滿版+深色漸層+白字」也套錯,實際應該是淺底卡片+置中圖+一般深色文字說明。這次改版之前已經用同一套「照片+深色漸層+白字」樣板套用在多個區塊,沒有先看過圖片內容驗證,這是這次抓到落差的根本原因。
- **[決策]** 使用者提供 Econis 首頁的實際截圖(Windows 端截圖,透過 WSL `/mnt/c/...` 路徑讀取)後,對照發現 Hero 跟 Hot categories 兩處結構判斷錯誤:Hero 不是左右兩欄,是置中疊放(Organic 徽章+分隔線+「Always be yourself」文字列 → Shop now 按鈕 → 產品去背構圖,由上而下置中),且大標語文案是「Always be yourself」不是「Wake Up Early...」(那句屬於後面的 feature 區塊沒錯,只是 Hero 本身文案抓錯);Hot categories 不是方形卡片 grid,是圓形色塊泡泡(圖片裁圓,名稱+「N products」在下方,單排 6 個)。已依截圖重建,promo banner 也拿掉不需要的深色遮罩,文字直接疊在圖片本身自帶的留白區域。
- **[決策]** 討論「不想跟 Econis 一模一樣但自己想不出設計」的問題,建議兩條路:(1)多找幾個同類型參考站混搭(已用 WebSearch 找到 Thrive Market/Misfits Market/無毒農 三個不同調性的參考,無毒農因為在地食安訴求跟 fruit_shoop 情境最接近);(2)保留 Econis 骨架,刻意改掉幾個識別度最高的招牌特徵。使用者選(1)。
- **[改動]** 使用者提供 3 支免費素材庫影片(採摘水果 4K 6s、柑橘沖水 1080p 10s、果籃莓果 1080p 13s),用 ffmpeg 各裁出精華片段、統一 1920x1080/30fps、去音軌,接成一支 18 秒迴圈,再壓成 1280 寬、faststart 的 web 用版本(`public/videos/hero-fruit.mp4`,3MB)+ poster 圖(`hero-fruit-poster.jpg`)。套進 `/home-preview` 的 Hero 區塊測試真實影片背景的效果,取代原本的 Econis 插畫底圖+去背產品構圖+捲動縮放效果那組(這組互動邏輯因此被移除,如果最後決定不用影片、要改回插畫版本,需要重寫)。:用 BeautifulSoup 逐區解析出真實文案(含 Econis demo 本身用的 placeholder 文字,如 testimonials 的醫學名詞假文、features 的拉丁文假文——這是參考站自己就用佔位文字,不是我方便省事)與圖片網址,下載 28 張圖存到 `public/images/econis-ref/`(hero 背景、6 個分類圖、2 張促銷 banner、7 張商品圖、2 張裝飾圖、3 張大頭貼、4 個品牌 logo、1 個小圖示)。頁面涵蓋 Econis 首頁全部 11 個內容區塊:Hero → Hot categories(6格)→ 促銷 banner(2欄)→ Top products(4格)→ 標語+3 icon features → Best seller/Top Rated/On Sale 分頁 grid(7 商品,分頁點擊還沒接篩選邏輯,純視覺)→ Testimonials(4人)→ 品牌 logo 條 → Newsletter banner。Header/Footer 沿用既有 `FrontLayout`。頁面內資料全部寫死在元件裡,不接資料庫,純視覺參考用。
- **[改動]** 自我審查發現並修復一個 Phase 1 造成的迴歸 bug:`tailwind.config.js` 遷移到 CSS-first `@theme` 時,`maxWidth` token 命名成 `--max-w-8xl`/`--max-w-9xl`,但 Tailwind v4 要產生 `max-w-*` utility 必須用完整的 `--max-width-*` 命名,不能縮寫成 `--max-w-*`。命名錯誤導致 3 個 Hero 元件(`Home/_HomeHero.vue`、`Products/_HomeHero.vue`、`Home/_PageHero.vue`)裡的 `max-w-8xl` class 完全失效。已修正命名(連帶 `--max-w-layout-wide`/`--max-w-layout-normal` 一併改成 `--max-width-*`),並把原本 12 個檔案裡「`max-w-[var(--max-w-layout-wide)]` 這種為了繞過命名錯誤而寫的冗長 arbitrary-value 寫法」簡化成乾淨的 `max-w-layout-wide` bare class;順手移除 `_HomeHero.vue` 裡 `max-w-8xl` 與 `max-w-layout-wide` 同時出現在同一個 div、互相打架的殘留寫法。另外新增 `--shadow-soft` token 取代 Nav.vue 裡寫死的陰影 rgba 值,避免 Phase 2 後續頁面重複複製貼上同一組陰影數字。

### 2026-08-10

- **[改動]** `Nav.vue` 補上 hero 穿透狀態的正確寫法:原本是「固定掛 `text-base-content` + 條件疊加 `text-base-100`」,在 hero 內兩個 class 會同時存在,改成 `isInHeroState ? 'text-base-100' : 'text-base-content'` 二選一,避免同時掛兩個顏色 class。
- **[改動]** `/home-preview` Hero 改用使用者提供的 3 支免費素材庫影片剪輯而成,取代原本的插畫版本(細節見上則紀錄的補充)。之後接上 `heroRef`(`FrontLayout` 既有的 hero 穿透機制,之前有幾個頁面的 `setHeroRef` 是註解掉的死程式碼,這次是真的接上並驗證),Hero 改成 `100vh`、Nav 在 hero 範圍內透明背景+白字,離開後自動變回白底樣式。
- **[決策]** Hot categories 區塊使用者確認拿掉(fruit_shoop 只有 3 個真實分類,套 Econis 6 顆泡泡的排版會太空)。原本嘗試直接換成既有的 `Home/_Feature.vue` 元件,但使用者要求「文字排版風格維持 Hot categories 原樣、只有圓圈內容換成 Feature 圖示」,所以最後是重新刻一版:保留 Hot categories 的標題(現在文案是「我們的承諾」)與卡片結構,圓圈換成 Feature 的 4 色 SVG 遮罩圖示。卡片結構後來又用 BeautifulSoup 挖出 Econis 實際的 `.item-title`(18px)/`.item-count`(12px,後改 14px)/`.product-cat-content`(卡片 10px 圓角、圖片用負 margin 疊在卡片上緣)真實 CSS 數值校正過,不是用猜的。
- **[決策]** Hero 標語/按鈕文案定案為中文:「嚴選新鮮蔬果，讓日常採買更簡單。」+「立即選購 →」,取代 Econis 原本的「Always be yourself」/「Shop now」。按鈕 hover 色從主色綠改成 accent 珊瑚橘(`hover:bg-accent`),這是 Phase 1 就定義但一直沒地方用到的 token,第一次實際套用。
- **[改動]** 使用者反饋中文字出現後「整個 low 掉」,查出根本原因:`--font-sans` 字體堆疊裡完全沒有指定中文字體,Poppins 不含中文字,一路 fallback 到系統預設字體(通常是 Windows 微軟正黑體),沒特別挑過所以不協調。解法:透過 bunny.net 額外載入 **Noto Sans TC**(思源黑體),插在 Poppins 後面、其他 fallback 之前(`app.blade.php` 字體連結、`app.css` 的 `--font-sans`)。這是全域字體設定,影響全站不只 `/home-preview`。
- **[決策]** 使用者確認不採用 `"IBM Plex Mono"` 當全站字體:等寬字體會犧牲內文可讀性、跟品牌溫暖調性衝突,且中文字仍會 fallback 導致英文等寬、中文正常寬度混排的問題。
- **[決策]** 標題字重系統定案:h1~h3 統一改 `font-medium`(500),不是全部同一個字重憑感覺選的——字級越小字重要越重,不然小字撐不住(這個原則供之後其他頁面套用參考,目前只套在 `/home-preview`)。Hero 那句主標語也從 `<p>` 改成語意正確的 `<h1>`(這頁原本沒有 h1)。
- **[改動]** 新增 `--color-heading: #3e4a5e` token(`app.css`),取代原本重複打的 `text-[#3e4a5e]`(10 處),之後要調這個顏色只要改一個地方。此顏色只套用在淺底標題,Hero(影片背景)、Newsletter(綠底,已刪除)這種深色/彩色底的標題維持白字,沒有跟著套用。
- **[改動]** 新建共用元件 `HomePreview/_CtaButton.vue`(膠囊圓角、主色綠、hover 變 accent 橘、`tracking-[0.06em]`、`font-medium`),取代重複複製貼上的按鈕 class 字串,Hero/Promo banner/Feature banner 共 4 處按鈕改用這個元件,支援 `:as="'span'"` 給包在 `<a>` 裡面不能嵌 `<button>` 的情境。
- **[決策]** 「我們的承諾」「精選商品」這兩個區塊標題確立一套樣式公式:上面一行小字英文標語(兩側配葉子裝飾圖、14px、`text-primary`)+ 下面中文主標(`text-heading`、500 字重)。使用者提醒不要每個區塊標題都套這個公式,只用在有特別安排的一兩處,避免變成制式模板感。
- **[改動]** Promo banner(蔬菜/果汁)換成使用者提供的真實去背/留白構圖照片(`public/images/promo/`),文案更正為「分類層級的促銷入口」(不是特定商品),整張卡片改成連到 `categories.products` 路由的可點擊連結,按鈕文字「選購去 →」。
- **[決策]** 使用者反饋 Promo banner 不應該是特定商品(如「冬季產地蔬果箱」這種限定品項),而是分類層級的促銷入口(蔬菜類/果汁類目前有什麼活動),照 Econis 原本模板的邏輯調整文案跟連結目標。
- **[改動]** 依使用者提供的參考截圖,把 Top products 卡片樣式從「圖片+名稱+價格」的簡化版,改成完整電商卡片:收藏愛心按鈕、Hot 標記、星等評分+評論數、分類標籤(大寫小字)、加入購物車圓形按鈕。`topProducts` 資料補上 `category`/`rating`/`reviews`/`hot` 欄位,商品名稱/圖片本身仍是 Econis 佔位資料,之後要換真實商品。
- **[改動]** 使用者要求「Top products 只留下,其他下面(Feature banner、Best seller 分頁、Testimonials、品牌 logo 條、Newsletter)都刪掉」,已刪除並清掉對應的未使用 script 資料(`features`/`bestSellers`/`tabs`/`currentTab`/`testimonials`/`brands`)與未使用的 `ref` import。目前 `/home-preview` 頁面到 Top products 結束。
- **[改動]** 依附圖新增 CTA 區塊(深色底,左側「GOOD TASTE & GREAT CRUNCHY」小標語 + 大標題「Wake Up Early, Eat Fresh & Healthy」+ 橘色圓形播放鍵 + 綠色「Shop now」按鈕),放在 Top products 之後,不含下方 4 個 feature icon(使用者明確表示只要上半部)。新建共用元件 `HomePreview/_CtaButton.vue` 統一按鈕樣式並套用到 Hero/Promo banner/Feature banner/CTA 共用。
- **[決策]** 嘗試在 CTA 上下加波浪 divider(SVG path,背景色代表其中一側、path fill 代表另一側,`my-[-1px]` 接合避免縫隙),過程中換過兩種波浪路徑(divider1 單一山丘、divider2 多層次弧線),也修過「波浪寬度貼滿整個螢幕、跟 1440px viewBox 比例不合導致形狀跑掉」的 bug(改成 `max-w-layout-wide mx-auto` 鎖寬)。最終使用者反饋「不對,改成最初的樣子」,**整個 CTA 波浪實驗回退**,CTA 區塊恢復成最初的深色實色背景版本,不含波浪。保留這個過程的紀錄,提醒之後若要再嘗試波浪造型,SVG 寬度務必跟 viewBox 原生比例對齊,不要貼滿整個 100vw。
- **[改動]** `_CtaButton.vue` 在特定深色/彩色背景上會跟背景融在一起(例如同為 `bg-primary` 時完全看不出按鈕形狀),目前做法是在使用端額外疊加 `!bg-[#fff] !text-primary` 這類 `!important` class 覆蓋,同時要記得補上 `hover:!bg-accent hover:!text-accent-content` 覆蓋 hover 狀態,不然 `!important` 的底色會連 hover 也一起蓋掉,踩過這個坑。「鮮榨果汁」promo banner 的按鈕目前是白底綠字、hover 橘底白字;「蔬菜嚴選」維持元件預設的綠底白字。

### 2026-08-11

- **[改動]** `_CtaButton.vue` 基礎 class 拿掉 `border-none`(原本是死 class,daisyUI `.btn` 預設 border-width 就是 0,不影響既有畫面),CTA 區按鈕改用 `!border !border-[#fff]` 疊加白色邊框。曾一度加上 `hover:!border-primary` 又依使用者要求改回去,維持 hover 只變 `!bg-primary`/`!text-primary-content`,border 固定白色。
- **[決策]** 「選購去」兩顆 promo banner 按鈕也補上 `!border !border-[#fff]`。用 Playwright 實際渲染+讀 computed style 排查後確認**不是 class 打架**:border 確實有套用(`border-width:1px`、`border-color:#fff`),「蔬菜嚴選」(綠底)邊框看得出來,「鮮榨果汁」是因為按鈕本身 `!bg-[#fff]` 白底配白框,顏色本身沒對比度才看起來像沒加。使用者決定維持現狀不處理對比度問題。
- **[決策]** 這兩顆按鈕一度改成 `as="button"`(拿掉 `as="span"`)想解決上述「看起來沒套上」的疑慮,後來確認問題跟 tag 種類無關(span/button computed style 一致),使用者要求改回 `as="span"`——因為外層本來就是 `<a>` 包整張卡片當連結,裡面不該再嵌 `<button>`(interactive content 巢狀,不合法 HTML),用 `<span>` 純視覺呈現才對。
- **[改動]** CTA 區改版:播放鍵(橘色圓形 play icon)整個移除,「立即選購」按鈕改放到原本播放鍵的位置(標題正下方),原本在右側獨立的按鈕拿掉,CTA 區變成單欄(左側文字+按鈕,右側完全露出背景圖)。
- **[改動]** CTA 區背景加上使用者提供的蔬果空拍照(`public/images/cta/veggie-flatlay.webp`,深色桌面棚拍)。查了 CTA 背景圖融合手法(mask-image 漸層淡出、深色/主色疊色、clip-path 有機造型、縮小當裝飾插圖四種方向),採用「mask-image 淡出 + 深色漸層疊色」:圖片佔右側 70% 寬,左緣用 `mask-image: linear-gradient(to right, transparent, black 35%)` 融入 `#0d0d0d` 底色,另疊一層 `bg-gradient-to-r from-[#0d0d0d] via-[#0d0d0d]/55 to-[#0d0d0d]/15` 壓住原圖過雜的顏色、確保文字對比度,圖片本身加 `saturate-[0.85] brightness-90` 微降飽和度/亮度。之後使用者要求加 `background-attachment: fixed` 做視差,因為這個屬性只對 CSS `background-image` 生效、對 `<img>` 標籤無效,改把原本的 `<img>` 換成帶 `bg-[url(...)] bg-cover bg-center bg-fixed` 的 `<div>` 才能套用(iOS Safari 不支援 `bg-fixed`,會 fallback 成 `scroll`,不影響版面只是沒有視差效果)。
- **[改動]** Hero 按鈕文案「立即選購 →」改成「探索更多 →」,因為使用者確認這顆按鈕實際行為是往下捲動頁面(不是導去購買流程),用「探索更多」比較符合實際行為,避免文不對題。
- **[改動]** CTA 區英文標語換成中文,並來回調整多輪:「Good taste & great crunchy」/「Wake Up Early, Eat Fresh & Healthy」→「嚴選好滋味」/「早起吃新鮮,健康每一天」→「從產地到餐桌」/「把新鮮,帶回家」(使用者嫌太短)→「嚴選新鮮,安心每一口」/「把健康好味道,帶進日常生活」(使用者仍嫌不夠好,方向是想強調**自然/有機**,還在挑選中,截至目前尚未定案)。同時把標題字重從 `font-bold`/`font-semibold` 降到 `font-medium`(500),使用者要求字重不超過 500。
- **[專案脈絡]** 使用者提到 `/home-preview` 這個頁面本質是「作品集」性質的參考頁(不是要接真實資料的正式頁面),內容大致告一段落。之後如果要正式套用到 `/`(正式首頁),需要重新對照 Phase 2 的既有 checklist 規劃時程,不是這次順便做。
- **[改動]** 兩顆「選購去」按鈕一度加上 `shadow-[...]` 讓白色 border 在淺色背景上也看得出輪廓(排查發現 border 疊在照片淺色區域時,白框跟背景同色,人眼分不出交界,hover 時也一樣,因為 hover 只改按鈕內部填色、不影響按鈕外側貼著的照片顏色)。後續使用者陸續要求拿掉、加回、只留「蔬菜嚴選」一顆、透明度從 0.35 調輕到 0.18,來回調整過程都記錄在 commit 前的對話,最終狀態:只有「蔬菜嚴選」那顆有淡陰影(`shadow-[0_2px_8px_rgba(0,0,0,0.18)]`),「鮮榨果汁」沒有。
- **[改動]** `Footer.vue` 容器寬度從 `max-w-layout-normal`(1140px)改成 `max-w-layout-wide`(1440px),對齊 `Nav.vue` 的寬度。這是共用元件,影響全站所有頁面,不只 `/home-preview`。
- **[改動]** 人氣商品從 4 個佔位商品擴充到 8 個(2 排 x 4 欄),因為 Econis 佔位素材只有 4 張商品圖,後 4 筆重複沿用同一批圖片配上不同的佔位名稱/價格(`Cold Pressed Green Juice`/`Daily Multivitamin Pack`/`Herbal Wellness Tea`/`Free-Range Turkey Breast`),之後接真實商品時要一併換掉,不要誤認成真實資料。
- **[改動]** 五個 `<section>` 都補上 `id`(`hero`/`features`/`promo`/`products`/`cta`),方便之後錨點導覽或除錯時定位。
- **[決策]** 使用者反饋各區塊間距「感覺不夠」,盤點發現問題是**不一致**而非全部太窄:多數區塊只單邊設 `pb`(靠下一個區塊完全沒有 `pt` 硬接),只有 CTA(深色底,不能借用鄰居顏色)兩邊都有 padding,導致中間幾段間距 64-80px、CTA 前後卻疊加到 256px,節奏忽緊忽鬆。查了業界作法後(見下方 WebSearch 來源),結論是每個 section 應該自己負責完整的 `py`(上下都設),不要靠單邊 `pb`/`pt` 接鄰居——這樣每個區塊獨立、可預測,以後調順序/插入新區塊不用重算鄰居數值,唯一要注意的是相鄰兩區的 `py` 會相加,數值要抓小一點補償。
- **[改動]** 依上述結論,`features`/`promo`/`products`/`cta` 四個區塊全部改成各自獨立的三段式響應 `pt`/`pb`(mobile / `md:` / `lg:`),取代原本部分區塊沒有 `pt` 或沒有 lg 斷點的舊寫法。過程中來回調了非常多輪具體數值(CTA 從 `py-20 md:py-32` 先降到 `md:py-28` 再補上 `lg:py-28`、mobile/md 分別降到 `py-12 md:py-20`;`features` 的 `pt` 單獨拉到 `pt-28` 又降回 `pt-24`,理由是「128px 感覺太高」;`products` 的 `pb` 為了 CTA 前的留白特別加大到 `pb-20`(lg),但沒有跟著加到 128px,因為疊加 CTA 自己的 `pt` 後會變 240px、太誇張;`promo`/`products` 中間兩段原本 `lg` 都是 16(64px)相加 128px,使用者覺得偏大,最後拿掉 `lg:` override 讓它們沿用 `md:` 的 14(56px),兩兩相加變 112px)。最終定案(mobile / md / lg,單位 px):
  | 區塊 | pt | pb |
  |---|---|---|
  | `#features` | 48/64/96 | 40/56/56(無 lg override,沿用 md)|
  | `#promo` | 40/56/56(無 lg override) | 40/56/56 |
  | `#products` | 40/56/56(無 lg override) | 48/56/80 |
  | `#cta` | 48/80/112 | 48/80/112 |
  - Hero 本身跟 Footer 的 `p-10` 刻意沒有跟進這套三段式(Hero 全螢幕影片背景、Footer 米色底,使用者認為顏色邊界本身已經有區隔,不需要額外留白撐開),只有中間四個同為白底的區塊需要靠 padding 做視覺區隔。
- **[改動]** 「人氣商品」`h2` 的 `mb-8 md:mb-16` 改成 `mb-8 md:mb-12`,跟標題下方的間距配合上面 products 區重新調整過的 padding 一起微調。

**WebSearch 參考來源**(2026-08-11,section spacing 業界作法):[Web Design Spacing and Sizing Best Practices](https://www.conceptfusion.co.uk/post/web-design-spacing-and-sizing-best-practices)、[Automatic CSS - Section Padding Classes](https://docs.automaticcss.com/spacing/section-padding-classes)、[FED Mentor - padding vs margin](https://fedmentor.dev/posts/padding-margin/)、[Elementor - Margin vs Padding](https://elementor.com/blog/margin-vs-padding/)

- **[決策]** 查了業界作法後(多個主流電商平台 Shopify/WooCommerce/Avada 都把「查看全部商品」做成精選商品區塊的內建功能),確認是常見慣例,在人氣商品 8 張卡片下方新增「查看全部商品 →」連結,連到 `products.index` 路由(對應 Nav 的「所有商品」)。樣式用外框膠囊按鈕(`btn btn-outline border-primary text-primary`,非實心),避免跟上面 CtaButton 的主色實心按鈕搶視覺重量。過程中踩到一個坑:一開始加了 `inline-block` 想讓 `<a>` 置中,結果蓋掉 daisyUI `.btn` 內建的 `inline-flex` + `items-center` `justify-center`,導致文字沒有垂直置中——`.btn` 本身已經處理好置中,不需要再疊加 display 相關 class。
- **[改動]** 把「查看全部商品」這顆外框按鈕的樣式,同步套到 Promo banner 兩顆「選購去」按鈕,統一視覺語言。「蔬菜嚴選」改成透明底+綠框綠字、hover 實心綠底白字(拿掉了原本疊加的陰影)。「鮮榨果汁」試過白字白框版本,使用者反饋「不搭」改回原本白底綠字白框、hover 綠底白字的版本。
- **[改動]** Hero「探索更多」、CTA「立即選購」、Promo banner 兩顆按鈕,hover 時的 border 統一補上 `hover:!border-primary`(原本 hover 只變 bg/text,border 停留在原色沒跟著變,現在 hover 時 border 會融進新背景色)。Hero 按鈕另外把預設狀態從白底綠字改成透明底白字+白框,呼應 CTA 按鈕在深色/影片底的樣式邏輯。
