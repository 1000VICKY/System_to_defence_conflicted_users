<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAllEndUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('all_end_users', function (Blueprint $table) {
            $table->string("uuid", 255);
            $table->string("hash", 255);
            $table->dateTime("reception_date", 64);
            $table->string("reception_number", 64);
            $table->string("event_name", 1024);
            $table->dateTime("event_start");
            $table->string("family_name", 255);
            $table->string("given_name", 255);
            $table->string("family_name_sort", 255);
            $table->string("given_name_sort", 255);
            $table->string("phone_number", 255);
            $table->string("email", 1024);
            $table->string("job", 255);
            $table->string("gender", 64);
            $table->date("birth_date")->nullable();
            // 年齢と生年月日は別カラムでもつ
            $table->integer("age");
            $table->tinyInteger("is_displayed")->default(1);
            $table->tinyInteger("is_deleted")->default(0);
            $table->tinyInteger("is_registered")->default(0);
            $table->timestamps();

            // ユニークキー
            $table->unique("uuid");
            $table->unique("hash");
            // $table->unique("phone_number");
            // $table->unique("email");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('end_users');
    }
}
