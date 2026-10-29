<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150)->unique();
            $table->string('code', 50)->unique();
            $table->enum('category', ['Class 8-10', 'Class 11-12', 'JEE (Main+Adv)', 'NEET Medical', 'Foundation']);
            $table->unsignedTinyInteger('duration_months')->default(12);
            $table->decimal('total_fee', 12, 2)->default(0.00);
            $table->decimal('admission_fee', 12, 2)->default(0.00);
            $table->unsignedTinyInteger('installments_allowed')->default(3);
            $table->enum('status', ['active', 'archived'])->default('active');
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
