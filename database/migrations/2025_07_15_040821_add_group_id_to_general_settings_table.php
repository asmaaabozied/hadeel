<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::table('general_settings', function (Blueprint $table) {
        $table->unsignedBigInteger('group_id')->nullable()->after('id');
    });

    // Now add foreign key constraint in a separate schema call
    Schema::table('general_settings', function (Blueprint $table) {
        $table->foreign('group_id')->references('id')->on('groups')->onDelete('cascade');
    });
}



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('general_settings', function (Blueprint $table) {
            //
        });
    }
};
