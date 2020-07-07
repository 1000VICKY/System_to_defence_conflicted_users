<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class AttendedEvent extends Model
{

    protected $table = "attended_events";


    protected $fillable = [
        "event_id",
        "unique_user_id",
        "reception_number",
    ];
}
