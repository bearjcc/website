<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up(): void
    {
        Schema::create('scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_id')->constrained()->onDelete('cascade');
            $table->string('player_hash');
            $table->integer('value');
            $table->json('metadata_json')->nullable();
            $table->timestamp('created_at');

            $table->index(['game_id', 'value']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scores');
    }
};
