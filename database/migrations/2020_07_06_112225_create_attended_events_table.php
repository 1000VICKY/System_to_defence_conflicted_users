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
            $table->bigInteger("unique_user_id");
            // 最終的な参加or不参加データ(デフォルトは参加)
            $table->tinyInteger("is_participated")->default(1);
            $table->timestamps();

            // 複合ユニークキー
            $table->unique(["event_id", "unique_user_id"], "event_id_end_user_id");
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
