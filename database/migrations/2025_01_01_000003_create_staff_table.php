<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('staff', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('employee_code', 50)->unique();
            $table->string('designation', 100);
            $table->string('qualification', 150)->nullable();
            $table->json('subjects')->nullable();
            $table->date('joining_date');
            $table->decimal('salary', 12, 2)->default(0.00);
            $table->string('photo_path', 255)->nullable();
            $table->string('emergency_contact', 32)->nullable();
            $table->enum('status', ['active', 'suspended', 'resigned'])->default('active');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('staff');
    }
};
