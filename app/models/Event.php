<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{

    // テーブル名の定義
    protected $table = "events";

    // イベントはevent_dateをプライマリキーとする
    // 同日同時間に開催はしない前提
    protected $fillable = [
        "event_name",
        "event_start",
    ];


    public function logs ()
    {
        return $this->hasMany(Log::class, "event_start", "event_start");
    }

    public function attended_events()
    {
        return $this->hasMany(AttendedEvent::class, "event_id", "id");
    }
}
