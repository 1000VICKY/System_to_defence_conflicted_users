<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class UniqueUser extends Model
{


    // テーブル名の定義
    protected $table = "unique_users";

    // 属性のデフォルト値を指定
    protected $attributes = [
        "is_displayed" => 1,
        "is_deleted" => 0,
    ];

    protected $primaryKey = "id";
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
        "reception_number",
    ];


    // CSVファイルとのリレーション
    public function logs()
    {
        return $this->hasMany(Log::class, "unique_user_id", "id");
    }

    // 参加したイベント一覧
    public function attended_events()
    {
        return $this->hasMany(AttendedEvent::class, "unique_user_id", "id");
    }
}
