<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddFieldPersonChats extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('person_chats', function (Blueprint $table) {
            $table->timestamp('lasted_at')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'))->after('is_helper');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('person_chats', function (Blueprint $table) {
            //
        });
    }
}
