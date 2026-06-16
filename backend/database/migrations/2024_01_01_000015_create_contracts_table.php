<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('applicant_id')->constrained('applicants')->cascadeOnDelete();
            $table->string('number')->unique();
            $table->string('template')->nullable();
            $table->string('file_path')->nullable();
            $table->string('status')->default('draft')->index();
            $table->timestamp('signed_at')->nullable();
            $table->foreignId('signed_by_manager_id')->nullable()->constrained('managers')->nullOnDelete();
            $table->string('external_id')->nullable()->index();
            $table->string('sync_status')->default('pending');
            $table->timestamp('last_synced_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
