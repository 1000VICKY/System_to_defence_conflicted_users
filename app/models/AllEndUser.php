<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class AllEndUser extends Model
{
    // テーブルの定義
    protected $table = "all_end_users";

    // プライマリキーはUUIDで定義
    protected $primaryKey = "uuid";
    public $incrementing = false;
    protected $keyType = "string";



    // 属性のデフォルト値
    protected $attributes = [
        "is_displayed" => 1,
        "is_deleted" => 0,
        "is_registered" => 0
    ];

    protected $fillable = [
        "uuid",
        "reception_date",
        "reception_number",
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
        "hash",
    ];
}
