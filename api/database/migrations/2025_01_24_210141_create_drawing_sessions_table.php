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
        Schema::create('drawing_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('public_id')->unique();
            $table->foreignId('user_id')->constrained();
            $table->string('name');
            $table->string('description')->nullable();
            $table->boolean(('is_public'))->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drawing_sessions');
    }
};
