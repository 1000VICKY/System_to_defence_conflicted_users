<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendedEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attended_events', function (Blueprint $table) {
            $table->id();
            // 必須カラムイベントID
            $table->bigInteger("event_id");
            // end_user_id
            $table->bigInteger("end_user_id");
            // event_formでの受付番号
            $table->string("reception_number", 64);
            // イベントへの受付日時
            $table->dateTime("reception_date");
            $table->timestamps();

            // 該当するイベントのユニークキー
            $table->unique("reception_number");
            // 複合ユニークキー
            $table->unique(["event_id", "end_user_id"], "event_id_end_user_id");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attended_events');
    }
}
