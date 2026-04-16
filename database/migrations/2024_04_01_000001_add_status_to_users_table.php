<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToUsersTable extends Migration
{
    public function up()
    {
        // Missing ALGORITHM and LOCK — will trigger Prism's unsafe_alter_table rule
        Schema::table('users', function (Blueprint $table) {
            $table->string('status')->default('active');
            $table->index('status');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}
