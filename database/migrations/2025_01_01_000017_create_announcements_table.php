<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->string('title', 150);
            $table->enum('audience', ['all', 'by_course', 'by_batch', 'fee_defaulters'])->default('all');
            $table->json('target_ids')->nullable();
            $table->json('channels');
            $table->text('message');
            $table->dateTime('scheduled_at')->nullable();
            $table->dateTime('sent_at')->nullable();
            $table->foreignId('sent_by')->constrained('users')->onDelete('restrict');
            $table->timestamp('created_at')->useCurrent();

            $table->index('sent_at', 'idx_announcements_sent');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};
