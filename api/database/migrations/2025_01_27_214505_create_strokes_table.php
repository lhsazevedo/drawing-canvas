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
        Schema::create('strokes', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->foreignId('drawing_session_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->string('color');
            $table->tinyInteger('size');
            $table->integer('min_x');
            $table->integer('min_y');
            $table->integer('max_x');
            $table->integer('max_y');
            $table->jsonb('points');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('strokes');
    }
};
