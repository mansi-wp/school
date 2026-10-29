<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_batch', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('batch_id')->constrained('batches')->onDelete('restrict');
            $table->timestamp('assigned_at')->useCurrent();
            $table->timestamp('transferred_at')->nullable();
            $table->boolean('is_primary')->default(true);
            $table->enum('status', ['active', 'transferred', 'dropped'])->default('active');
            $table->timestamps();

            $table->index(['student_id', 'batch_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_batch');
    }
};
