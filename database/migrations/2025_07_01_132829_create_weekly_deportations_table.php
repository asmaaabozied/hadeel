<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
      Schema::create('weekly_deportations', function (Blueprint $table) {
        $table->id();
        $table->foreignId('sheet_id')->constrained()->cascadeOnDelete();
        $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        $table->decimal('production', 8, 2);
        $table->decimal('consumption', 8, 2);
        $table->decimal('deportation_note', 8, 2);
        $table->timestamps();
    });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weekly_deportations');
    }
};
