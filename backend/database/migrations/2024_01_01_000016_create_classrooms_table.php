<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('classrooms', function (Blueprint $table) {
            $table->id();
            $table->string('number');
            $table->string('building')->nullable();
            $table->unsignedSmallInteger('capacity')->default(30);
            $table->json('equipment')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['building', 'number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('classrooms');
    }
};
