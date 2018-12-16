<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

class AddYearToGroup extends Migration
{
    public function up() {
        Schema::table('group', function($table) {
            $table->integer('year')
                ->unsigned()
                ->nullable();
        });
    }

    public function down() {
        Schema::table('group', function($table) {
            $table->dropColumn('year');
        });
    }
}
