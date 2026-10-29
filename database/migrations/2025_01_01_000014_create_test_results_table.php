<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('test_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('test_id')->constrained('tests')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->decimal('marks_obtained', 6, 2);
            $table->boolean('is_absent')->default(false);
            $table->unsignedInteger('rank')->nullable();
            $table->decimal('percentile', 5, 2)->nullable();
            $table->string('remarks', 255)->nullable();
            $table->timestamps();

            $table->unique(['test_id', 'student_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('test_results');
    }
};
