<?php
return [
    /*
    |--------------------------------------------------------------------------
    | China(Traditional) Language
    |--------------------------------------------------------------------------
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */
    'accepted' => ': 屬性必須被接受。',
    'accepted_if' => '當 :other 是 :value 時，必須接受 :attribute。',
    'active_url' => ':attribute 不是有效的 URL。',
    'after' => ':attribute 必須是 :date 之後的日期。',
    'after_or_equal' => ':attribute 必須是晚於或等於 :date 的日期。',
    'alpha' => ':attribute 只能包含字母。',
    'alpha_dash' => ':attribute 只能包含字母、數字、破折號和下劃線。',
    'alpha_num' => ':attribute 只能包含字母和數字。',
    'array' => ':attribute 必須是一個數組。',
    'before' => ':attribute 必須是 :date 之前的日期。',
    'before_or_equal' => ':attribute 必須是早於或等於 :date 的日期。',
    'between' => [
        'array' => ':attribute 必須介於 :min 和 :max 項之間。',
        'file' => ':attribute 必須介於 :min 和 :max 千字節之間。',
        'numeric' => ':attribute 必須介於 :min 和 :max 之間。',
        'string' => ':attribute 必須介於 :min 和 :max 字符之間。',
    ],
    'boolean' => ':attribute 字段必須為真或假。',
    'confirmed' => ':attribute 確認不匹配。',
    'current_password' => '密碼不正確。',
    'date' => ':attribute 不是有效日期。',
    'date_equals' => ':attribute 必須是等於 :date 的日期。',

    'date_format' => ':attribute 與格式 :format 不匹配。',
    'declined' => ': 屬性必須被拒絕。',
    'declined_if' => '當 :other 是 :value 時，必須拒絕 :attribute。',
    'different' => ':attribute 和 :other 必須不同。',
    'digits' => ': 屬性必須是 :digits 數字。',
    'digits_between' => ': 屬性必須介於 :min 和 :max 數字之間。',
    'dimensions' => ':attribute 具有無效的圖像尺寸。',
    'distinct' => ':attribute 字段具有重複值。',
    'email' => ':attribute 必須是有效的電子郵件地址。',
    'ends_with' => ':attribute 必須以下列之一結尾：:values。',
    'enum' => '選擇的 : 屬性無效。',
    'exists' => '選擇的 : 屬性無效。',
    'file' => ':attribute 必須是一個文件。',
    'filled' => ':attribute 字段必須有一個值。',
    'gt' => [
        'array' => ':attribute 必須有多個 :value 項。',
        'file' => ':attribute 必須大於 :value 千字節。',
        'numeric' => ':attribute 必須大於 :value。',
        'string' => ':attribute 必須大於 :value 字符。',
    ],
    'gte' => [
        'array' => ':attribute 必須有 :value 項或更多項。',
        'file' => ':attribute 必須大於或等於 :value 千字節。',
        'numeric' => ':attribute 必須大於或等於 :value。',
        'string' => ':attribute 必須大於或等於 :value 字符。',
    ],
    'image' => ':attribute 必須是圖像。',
    'in' => '選擇的 : 屬性無效。',
    'in_array' => ':other 中不存在 :attribute 字段。',
    'integer' => ': 屬性必須是整數。',
    'ip' => ':attribute 必須是有效的 IP 地址。',
    'ipv4' => ':attribute 必須是有效的 IPv4 地址。',
    'ipv6' => ':attribute 必須是有效的 IPv6 地址。',
    'json' => ':attribute 必須是有效的 JSON 字符串。',
    'lt' => [
        'array' => ':attribute 必須少於 :value 項。',
        'file' => ':attribute 必須小於 :value 千字節。',
        'numeric' => ':attribute 必須小於 :value。',
        'string' => ':attribute 必須小於 :value 字符。',
    ],
    'lte' => [
        'array' => ':attribute 不能有超過 :value 項。',
        'file' => ':attribute 必須小於或等於 :value 千字節。',
        'numeric' => ':attribute 必須小於或等於 :value。',
        'string' => ':attribute 必須小於或等於 :value 字符。',
    ],
    'mac_address' => ':attribute 必須是有效的 MAC 地址。',
    'max' => [
        'array' => ':attribute 不能超過 :max 項。',
        'file' => ':attribute 不能大於 :max 千字節。',
        'numeric' => ':attribute 不能大於 :max。',
        'string' => ':attribute 不能大於 :max 個字符。',
    ],
    'mimes' => ':attribute 必須是類型為: :values 的文件。',
    'mimetypes' => ':attribute 必須是類型為: :values 的文件。',
    'min' => [
        'array' => ':attribute 必須至少有 :min 項。',
        'file' => ':attribute 必須至少為 :min 千字節。',
        'numeric' => ':attribute 必須至少為 :min。',
        'string' => ':attribute 必須至少為 :min 個字符。',
    ],
    'multiple_of' => ':attribute 必須是 :value 的倍數。',
    'not_in' => '選擇的 : 屬性無效。',
    'not_regex' => ':attribute 格式無效。',
    'numeric' => ':attribute 必須是一個數字。',
    'password' => [
        'letters' => ':attribute 必須至少包含一個字母。',
        'mixed' => ':attribute 必須至少包含一個大寫字母和一個小寫字母。',
        'numbers' => 'he :attribute 必須至少包含一個數字。',
        'symbols' => ':attribute 必須包含至少一個符號。',
        'uncompromised' => '給定的 :attribute 出現在數據洩漏中。 請選擇不同的 :attribute。',
    ],
    'present' => ':attribute 字段必須存在。',
    'prohibited' => ':attribute 字段被禁止。',
    'prohibited_if' => '當 :other 為 :value 時，禁止使用 :attribute 字段。',
    'prohibited_unless' => ':attribute 字段是禁止的，除非 :other 在 :values 中。',
    'prohibits' => ':attribute 字段禁止 :other 出現。',
    'regex' => ':attribute 格式無效。',
    'required' => ':attribute 字段是必需的。',
    'required_array_keys' => ':attribute 字段必須包含以下條目：:values。',
    'required_if' => '當 :other 是 :value 時需要 :attribute 字段。',
    'required_unless' => ':attribute 字段是必需的，除非 :other 在 :values 中。',
    'required_with' => '存在 :values 時需要 :attribute 字段。',
    'required_with_all' => '存在 :values 時需要 :attribute 字段。',
    'required_without' => '當 :values 不存在時，需要 :attribute 字段。',
    'required_without_all' => '當不存在任何 :value 時，需要 :attribute 字段。',
    'same' => ':attribute 和 :other 必須匹配。',
    'size' => [
        'array' => ':attribute 必須包含 :size 項。',
        'file' => ':attribute 必須是 :size 千字節。',
        'numeric' => ':attribute 必須是 :size。',
        'string' => ':attribute 必須是 :size 字符。',
    ],
    'starts_with' => ':attribute 必須以下列之一開頭：:values。',
    'string' => ':attribute 必須是字符串。.',
    'timezone' => ':attribute 必須是有效的時區。',
    'unique' => ':attribute 已被佔用。',
    'uploaded' => ':attribute 上傳失敗。',
    'url' => ':attribute 必須是有效的 URL。',
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
        'attribute-name' => [
            'rule-name' => '自定義消息',
        ],
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
    'attributes' => [],
];
