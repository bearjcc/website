<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up(): void
    {
        Schema::create('letter_walker_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('player_name')->nullable(); // For arcade-style submissions
            $table->integer('score');
            $table->integer('moves');
            $table->integer('words_found');
            $table->integer('puzzle_number');
            $table->date('date_played');
            $table->timestamps();

            $table->index(['score', 'date_played']);
            $table->index('date_played');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('letter_walker_scores');
    }
};
