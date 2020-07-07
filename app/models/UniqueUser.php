<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class UniqueUser extends Model
{


    // テーブル名の定義
    protected $table = "unique_users";

    // プライマリキーの定義
    protected $primaryKey = "id";
    public $incrementing = true;

    // 属性のデフォルト値を指定
    protected $attributes = [
        "is_displayed" => 1,
        "is_deleted" => 0,
    ];

    // ホワイトリスト指定
    protected $fillable = [
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
    ];
}
