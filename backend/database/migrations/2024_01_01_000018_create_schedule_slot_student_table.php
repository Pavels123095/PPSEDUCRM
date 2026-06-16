<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('schedule_slot_student', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('schedule_slot_id')->constrained('schedule_slots')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['schedule_slot_id', 'student_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schedule_slot_student');
    }
};
