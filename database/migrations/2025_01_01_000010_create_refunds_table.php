<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('refunds', function (Blueprint $table) {
            $table->id();
            $table->string('refund_number', 60)->unique();
            $table->foreignId('student_id')->constrained('students')->onDelete('restrict');
            $table->decimal('total_fee_paid', 12, 2);
            $table->decimal('refund_amount', 12, 2);
            $table->enum('refund_method', ['original_method', 'cash', 'bank_transfer', 'cheque']);
            $table->enum('reason', ['batch_cancelled', 'student_withdrawal', 'duplicate_payment', 'medical_grounds', 'other']);
            $table->text('reason_notes')->nullable();
            $table->foreignId('approved_by')->constrained('users')->onDelete('restrict');
            $table->date('refund_date');
            $table->timestamps();

            $table->index('student_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('refunds');
    }
};
