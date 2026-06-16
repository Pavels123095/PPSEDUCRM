<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('work_sessions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('teacher_id')->constrained('teachers')->cascadeOnDelete();
            $table->foreignUuid('schedule_slot_id')->nullable()->constrained('schedule_slots')->nullOnDelete();
            $table->string('activity_type');
            $table->decimal('hours', 5, 2);
            $table->date('session_date');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['teacher_id', 'session_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('work_sessions');
    }
};
