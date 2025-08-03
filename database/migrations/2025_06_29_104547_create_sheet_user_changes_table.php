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
        Schema::create('sheet_user_changes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sheet_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('field'); // production or consumption
            $table->decimal('old_value', 10, 2);
            $table->decimal('new_value', 10, 2);
            $table->foreignId('changed_by')->constrained('users')->onDelete('cascade'); // Who made the change
            $table->boolean('reverted')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sheet_user_changes');
    }
};
