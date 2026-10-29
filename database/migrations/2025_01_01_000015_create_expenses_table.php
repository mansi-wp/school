<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->string('expense_number', 60)->unique();
            $table->enum('category', ['Salary', 'Printing & Stationery', 'Utilities', 'Rent & Maintenance', 'Hardware & Software', 'Marketing & Events', 'Refreshments', 'Misc']);
            $table->decimal('amount', 12, 2);
            $table->date('expense_date');
            $table->string('paid_to', 150);
            $table->enum('payment_method', ['cash', 'bank_transfer', 'upi', 'card', 'cheque']);
            $table->string('reference_number', 100)->nullable();
            $table->string('attachment_path', 255)->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('approved_by')->constrained('users')->onDelete('restrict');
            $table->timestamps();

            $table->index('category');
            $table->index('expense_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
