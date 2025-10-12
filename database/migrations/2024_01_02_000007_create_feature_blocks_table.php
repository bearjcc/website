<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up(): void
    {
        Schema::create('feature_blocks', function (Blueprint $table) {
            $table->id();
            $table->string('kind');
            $table->unsignedBigInteger('ref_id');
            $table->integer('order')->default(0);
            $table->timestamps();

            $table->index(['kind', 'ref_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('feature_blocks');
    }
};
