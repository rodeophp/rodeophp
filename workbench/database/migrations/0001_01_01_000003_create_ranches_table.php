<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ranches', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('ranch_user', function (Blueprint $table) {
            $table->foreignId('ranch_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->primary(['ranch_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ranch_user');
        Schema::dropIfExists('ranches');
    }
};
