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
        Schema::create('deportation_rules', function (Blueprint $table) {
            $table->id();
            $table->integer('min_production');
            $table->integer('max_production')->nullable();
            $table->decimal('adjustment', 5, 2);
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deportation_rules');
    }
};
