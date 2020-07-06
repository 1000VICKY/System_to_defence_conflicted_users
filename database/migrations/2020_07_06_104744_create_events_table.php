<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string("event_name", 2048);
            $table->dateTime("event_start");

            // イベントへの申し込み開始日時
            $table->dateTime("reception_start")->nullable();
            // イベントへの申込み終了日時
            $table->dateTime("reception_end")->nullable();
            $table->timestamps();

            // イベントの開始日時はユニークの前提
            $table->unique("event_start");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('events');
    }
}
