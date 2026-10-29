<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendance', function (Blueprint $table) {
            $table->id();
            $table->foreignId('batch_id')->constrained('batches')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->date('attendance_date');
            $table->enum('session', ['morning', 'evening'])->default('morning');
            $table->enum('status', ['present', 'absent', 'late', 'leave']);
            $table->unsignedSmallInteger('late_minutes')->default(0);
            $table->string('remarks', 255)->nullable();
            $table->foreignId('marked_by')->constrained('users')->onDelete('restrict');
            $table->boolean('parent_notified')->default(false);
            $table->timestamps();

            $table->unique(['batch_id', 'student_id', 'attendance_date', 'session'], 'unique_att_entry');
            $table->index(['batch_id', 'attendance_date']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendance');
    }
};
