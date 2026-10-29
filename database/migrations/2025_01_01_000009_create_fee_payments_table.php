<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fee_payments', function (Blueprint $table) {
            $table->id();
            $table->string('receipt_number', 60)->unique();
            $table->foreignId('student_id')->constrained('students')->onDelete('restrict');
            $table->foreignId('fee_plan_id')->nullable()->constrained('fee_plans')->onDelete('set null');
            $table->decimal('amount_paid', 12, 2);
            $table->decimal('late_fee_charged', 12, 2)->default(0.00);
            $table->decimal('late_fee_waived', 12, 2)->default(0.00);
            $table->string('waiver_reason', 255)->nullable();
            $table->enum('payment_method', ['cash', 'upi', 'card', 'net_banking', 'cheque', 'dd']);
            $table->string('reference_number', 100)->nullable();
            $table->date('payment_date');
            $table->foreignId('collected_by')->constrained('users')->onDelete('restrict');
            $table->text('receipt_note')->nullable();
            $table->timestamps();

            $table->index('payment_date');
            $table->index('student_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fee_payments');
    }
};
