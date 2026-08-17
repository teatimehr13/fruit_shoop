<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => ':attribute 必須為已同意。',
    'accepted_if' => '當 :other 為 :value 時，:attribute 必須為已同意。',
    'active_url' => ':attribute 不是一個有效的網址。',
    'after' => ':attribute 必須是 :date 之後的日期。',
    'after_or_equal' => ':attribute 必須是 :date 之後或同一天的日期。',
    'alpha' => ':attribute 只能包含字母。',
    'alpha_dash' => ':attribute 只能包含字母、數字、破折號與底線。',
    'alpha_num' => ':attribute 只能包含字母與數字。',
    'array' => ':attribute 必須是陣列。',
    'ascii' => ':attribute 只能包含單一位元組的英數字元與符號。',
    'before' => ':attribute 必須是 :date 之前的日期。',
    'before_or_equal' => ':attribute 必須是 :date 之前或同一天的日期。',
    'between' => [
        'array' => ':attribute 項目數量必須介於 :min 到 :max 之間。',
        'file' => ':attribute 檔案大小必須介於 :min 到 :max KB 之間。',
        'numeric' => ':attribute 必須介於 :min 到 :max 之間。',
        'string' => ':attribute 字數必須介於 :min 到 :max 之間。',
    ],
    'boolean' => ':attribute 必須是 true 或 false。',
    'can' => ':attribute 包含未授權的值。',
    'confirmed' => ':attribute 與確認欄位不一致。',
    'current_password' => '密碼不正確。',
    'date' => ':attribute 不是一個有效的日期。',
    'date_equals' => ':attribute 必須與 :date 是同一天。',
    'date_format' => ':attribute 格式必須符合 :format。',
    'decimal' => ':attribute 必須有 :decimal 位小數。',
    'declined' => ':attribute 必須為不同意。',
    'declined_if' => '當 :other 為 :value 時，:attribute 必須為不同意。',
    'different' => ':attribute 與 :other 必須不同。',
    'digits' => ':attribute 必須是 :digits 位數字。',
    'digits_between' => ':attribute 位數必須介於 :min 到 :max 之間。',
    'dimensions' => ':attribute 圖片尺寸不正確。',
    'distinct' => ':attribute 有重複的值。',
    'doesnt_end_with' => ':attribute 不能以下列任一項結尾：:values。',
    'doesnt_start_with' => ':attribute 不能以下列任一項開頭：:values。',
    'email' => ':attribute 必須是有效的 Email 格式。',
    'ends_with' => ':attribute 必須以下列任一項結尾：:values。',
    'enum' => '選擇的 :attribute 無效。',
    'exists' => '選擇的 :attribute 無效。',
    'extensions' => ':attribute 檔案的副檔名必須是下列其中一種：:values。',
    'file' => ':attribute 必須是檔案。',
    'filled' => ':attribute 不能是空的。',
    'gt' => [
        'array' => ':attribute 項目數量必須大於 :value。',
        'file' => ':attribute 檔案大小必須大於 :value KB。',
        'numeric' => ':attribute 必須大於 :value。',
        'string' => ':attribute 字數必須大於 :value。',
    ],
    'gte' => [
        'array' => ':attribute 項目數量必須大於或等於 :value。',
        'file' => ':attribute 檔案大小必須大於或等於 :value KB。',
        'numeric' => ':attribute 必須大於或等於 :value。',
        'string' => ':attribute 字數必須大於或等於 :value。',
    ],
    'hex_color' => ':attribute 必須是有效的十六進位色碼。',
    'image' => ':attribute 必須是圖片。',
    'in' => '選擇的 :attribute 無效。',
    'in_array' => ':attribute 必須存在於 :other 中。',
    'integer' => ':attribute 必須是整數。',
    'ip' => ':attribute 必須是有效的 IP 位址。',
    'ipv4' => ':attribute 必須是有效的 IPv4 位址。',
    'ipv6' => ':attribute 必須是有效的 IPv6 位址。',
    'json' => ':attribute 必須是有效的 JSON 字串。',
    'lowercase' => ':attribute 必須是小寫。',
    'lt' => [
        'array' => ':attribute 項目數量必須小於 :value。',
        'file' => ':attribute 檔案大小必須小於 :value KB。',
        'numeric' => ':attribute 必須小於 :value。',
        'string' => ':attribute 字數必須小於 :value。',
    ],
    'lte' => [
        'array' => ':attribute 項目數量不能超過 :value。',
        'file' => ':attribute 檔案大小必須小於或等於 :value KB。',
        'numeric' => ':attribute 必須小於或等於 :value。',
        'string' => ':attribute 字數必須小於或等於 :value。',
    ],
    'mac_address' => ':attribute 必須是有效的 MAC 位址。',
    'max' => [
        'array' => ':attribute 項目數量不能超過 :max。',
        'file' => ':attribute 檔案大小不能超過 :max KB。',
        'numeric' => ':attribute 不能大於 :max。',
        'string' => ':attribute 字數不能超過 :max。',
    ],
    'max_digits' => ':attribute 位數不能超過 :max。',
    'mimes' => ':attribute 檔案格式必須是：:values。',
    'mimetypes' => ':attribute 檔案格式必須是：:values。',
    'min' => [
        'array' => ':attribute 項目數量至少要有 :min 個。',
        'file' => ':attribute 檔案大小至少要有 :min KB。',
        'numeric' => ':attribute 不能小於 :min。',
        'string' => ':attribute 字數至少要有 :min 個字。',
    ],
    'min_digits' => ':attribute 位數至少要有 :min 位。',
    'missing' => ':attribute 必須是空的。',
    'missing_if' => '當 :other 為 :value 時，:attribute 必須是空的。',
    'missing_unless' => '除非 :other 為 :value，否則 :attribute 必須是空的。',
    'missing_with' => '當 :values 存在時，:attribute 必須是空的。',
    'missing_with_all' => '當 :values 都存在時，:attribute 必須是空的。',
    'multiple_of' => ':attribute 必須是 :value 的倍數。',
    'not_in' => '選擇的 :attribute 無效。',
    'not_regex' => ':attribute 格式不正確。',
    'numeric' => ':attribute 必須是數字。',
    'password' => [
        'letters' => ':attribute 必須包含至少一個字母。',
        'mixed' => ':attribute 必須包含至少一個大寫字母與一個小寫字母。',
        'numbers' => ':attribute 必須包含至少一個數字。',
        'symbols' => ':attribute 必須包含至少一個符號。',
        'uncompromised' => '這組 :attribute 已出現在資料外洩紀錄中，請換一組不同的 :attribute。',
    ],
    'present' => ':attribute 必須存在。',
    'present_if' => '當 :other 為 :value 時，:attribute 必須存在。',
    'present_unless' => '除非 :other 為 :value，否則 :attribute 必須存在。',
    'present_with' => '當 :values 存在時，:attribute 必須存在。',
    'present_with_all' => '當 :values 都存在時，:attribute 必須存在。',
    'prohibited' => ':attribute 為禁止使用的欄位。',
    'prohibited_if' => '當 :other 為 :value 時，:attribute 為禁止使用的欄位。',
    'prohibited_unless' => '除非 :other 在 :values 中，否則 :attribute 為禁止使用的欄位。',
    'prohibits' => ':attribute 與 :other 不能同時存在。',
    'regex' => ':attribute 格式不正確。',
    'required' => ':attribute 為必填欄位。',
    'required_array_keys' => ':attribute 必須包含以下項目：:values。',
    'required_if' => '當 :other 為 :value 時，:attribute 為必填欄位。',
    'required_if_accepted' => '當 :other 已同意時，:attribute 為必填欄位。',
    'required_unless' => '除非 :other 在 :values 中，否則 :attribute 為必填欄位。',
    'required_with' => '當 :values 存在時，:attribute 為必填欄位。',
    'required_with_all' => '當 :values 都存在時，:attribute 為必填欄位。',
    'required_without' => '當 :values 不存在時，:attribute 為必填欄位。',
    'required_without_all' => '當 :values 都不存在時，:attribute 為必填欄位。',
    'same' => ':attribute 與 :other 必須相同。',
    'size' => [
        'array' => ':attribute 項目數量必須是 :size 個。',
        'file' => ':attribute 檔案大小必須是 :size KB。',
        'numeric' => ':attribute 必須是 :size。',
        'string' => ':attribute 字數必須是 :size 個字。',
    ],
    'starts_with' => ':attribute 必須以下列任一項開頭：:values。',
    'string' => ':attribute 必須是字串。',
    'timezone' => ':attribute 必須是有效的時區。',
    'unique' => ':attribute 已經被使用過了。',
    'uploaded' => ':attribute 上傳失敗。',
    'uppercase' => ':attribute 必須是大寫。',
    'url' => ':attribute 必須是有效的網址。',
    'ulid' => ':attribute 必須是有效的 ULID。',
    'uuid' => ':attribute 必須是有效的 UUID。',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        //
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [
        'name' => '姓名',
        'email' => 'Email',
        'password' => '密碼',
        'password_confirmation' => '密碼確認',
        'login' => '帳號',
        'address' => '地址',
        'category_id' => '分類',
        'subcategory_id' => '子分類',
        'content' => '內容',
        'description' => '說明',
        'image' => '圖片',
        'is_enabled' => '啟用狀態',
        'note' => '備註',
        'options' => '選項',
        'order_status' => '訂單狀態',
        'payment_method' => '付款方式',
        'phone' => '電話',
        'recipient_name' => '收件人姓名',
        'recipient_phone' => '收件人電話',
        'shipping_address_detail' => '收件地址',
        'shipping_city' => '城市',
        'shipping_district' => '地區',
        'shipping_email' => 'Email',
        'shipping_name' => '收件人姓名',
        'shipping_phone' => '收件人電話',
        'shipping_zip_code' => '郵遞區號',
        'slug' => '網址代稱',
        'sort_order' => '排序',
        'title' => '標題',
    ],

];
