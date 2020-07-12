<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class AttendedEvent extends Model
{

    protected $table = "attended_events";


    protected $fillable = [
        "event_id",
        "unique_user_id",
    ];

    public function users() {
        // return $this->belongsTo(UniqueUser::class, "ローカルキー", "外部キー");
        return $this->belongsTo(UniqueUser::class, "unique_user_id", "id");
    }

    public function events()
    {
        // return $this->belongsTo(Event::class, "ローカルキー", "外部キー");
        return $this->belongsTo(Event::class, "event_id", "id");
    }
}
