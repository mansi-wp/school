<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('courses')->onDelete('restrict');
            $table->string('name', 120);
            $table->foreignId('teacher_id')->constrained('staff')->onDelete('restrict');
            $table->foreignId('co_teacher_id')->nullable()->constrained('staff')->onDelete('set null');
            $table->date('start_date');
            $table->date('end_date');
            $table->json('class_days');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('room_number', 50);
            $table->unsignedInteger('max_capacity')->default(40);
            $table->unsignedInteger('enrolled_count')->default(0);
            $table->enum('status', ['upcoming', 'ongoing', 'completed', 'cancelled'])->default('upcoming');
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['course_id', 'name']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('batches');
    }
};
