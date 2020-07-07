<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    // テーブルの定義
    protected $table = "logs";

    // 属性のデフォルト値
    protected $attributes = [
        "is_displayed" => 1,
        "is_deleted" => 0,
        "is_registered" => 0
    ];

    protected $fillable = [
        "reception_number",
        "reception_date",
        "event_name",
        "event_start",
        "family_name",
        "given_name",
        "family_name_sort",
        "given_name_sort",
        "phone_number",
        "email",
        "job",
        "gender",
        "birth_date",
        "age",
        "is_displayed",
        "is_deleted",
        "is_registered",
        "unique_user_id",
    ];
}
