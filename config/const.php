<?php
return [



    // CSVのヘッダーとDBテーブルのカラムをマッチさせる
    "csv_header" => [
        "reception_date" => "申込日",
        "reception_number" => "受付番号",
        "event_name" => "イベント名",
        "family_name" => "名前(姓)",
        "given_name" => "名前(名)",
        "family_name_sort" => "フリガナ(姓)",
        "given_name_sort" => "フリガナ(名)",
        "email" => "メールアドレス",
        "age" => "フォーム情報1",
        "gender" => "フォーム情報2",
        "phone_number" => "フォーム情報3",
        "job" => "フォーム情報5",
        "question" => "フォーム情報8",
        "event_start" => "懇親会オプションチケット",
    ],

    "participated_status" => [
        "is_participated" => 1,
        "not_participated" => 0,
    ],
    "display_status" => [
        "is_displayed" => 1,
        "not_displayed" => 0,
    ],
];
