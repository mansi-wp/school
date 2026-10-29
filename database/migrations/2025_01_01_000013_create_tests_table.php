<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tests', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->enum('type', ['Unit Test', 'Mid-Term', 'Final', 'Practice', 'Mock Exam']);
            $table->foreignId('course_id')->constrained('courses')->onDelete('restrict');
            $table->string('subject', 100);
            $table->date('test_date');
            $table->time('start_time');
            $table->unsignedSmallInteger('duration_minutes')->default(60);
            $table->decimal('total_marks', 6, 2);
            $table->decimal('passing_marks', 6, 2);
            $table->boolean('negative_marking')->default(false);
            $table->decimal('negative_mark_value', 4, 2)->default(0.00);
            $table->text('syllabus')->nullable();
            $table->string('question_paper_path', 255)->nullable();
            $table->enum('status', ['scheduled', 'in_progress', 'completed', 'results_published', 'cancelled'])->default('scheduled');
            $table->foreignId('created_by')->constrained('users')->onDelete('restrict');
            $table->timestamps();

            $table->index('course_id');
            $table->index('test_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tests');
    }
};
