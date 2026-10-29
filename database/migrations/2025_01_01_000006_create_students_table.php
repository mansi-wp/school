<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('roll_number', 50)->unique();
            $table->enum('gender', ['male', 'female', 'other']);
            $table->date('dob');
            $table->string('school_name', 150)->nullable();
            $table->string('current_class', 50);
            $table->string('parent_name', 150);
            $table->string('parent_phone', 32);
            $table->string('parent_email', 191)->nullable();
            $table->string('address_line1', 255);
            $table->string('address_line2', 255)->nullable();
            $table->string('city', 100);
            $table->string('state', 100);
            $table->string('zip', 20);
            $table->date('admission_date');
            $table->decimal('total_fee', 12, 2);
            $table->decimal('discount_amount', 12, 2)->default(0.00);
            $table->enum('discount_reason', ['None', 'Sibling', 'Merit', 'Staff Ward', 'Economic Need', 'Other'])->default('None');
            $table->decimal('final_fee', 12, 2);
            $table->decimal('paid_fee', 12, 2)->default(0.00);
            $table->decimal('outstanding_fee', 12, 2);
            $table->enum('payment_plan', ['full', 'installment_2', 'installment_3', 'installment_4', 'monthly'])->default('installment_3');
            $table->string('photo_path', 255)->nullable();
            $table->string('id_proof_path', 255)->nullable();
            $table->enum('status', ['active', 'inactive', 'graduated', 'suspended'])->default('active');
            $table->timestamps();
            $table->softDeletes();

            $table->index('roll_number');
            $table->index('parent_phone');
            $table->index('outstanding_fee');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
