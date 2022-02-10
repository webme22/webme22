<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateFamilyMediaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('familyMedia', function (Blueprint $table) {
           $table->renameColumn("name", "name_en");
           $table->renameColumn("description", "description_en");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('familyMedia', function (Blueprint $table) {
            $table->renameColumn("name_en", "name");
            $table->renameColumn("description_en", "description");
        });
    }
}
