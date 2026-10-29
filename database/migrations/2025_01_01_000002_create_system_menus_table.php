<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('system_menus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('system_menus')->onDelete('cascade');
            $table->string('name', 100);
            $table->string('icon', 60)->default('fas fa-circle');
            $table->string('route_name', 150)->nullable();
            $table->string('url', 255);
            $table->integer('sort_order')->default(0);
            $table->boolean('is_visible')->default(true);
            $table->string('permission_code', 125)->nullable();
            $table->foreignId('created_by')->nullable();
            $table->foreignId('updated_by')->nullable();
            $table->timestamps();

            $table->index('parent_id');
            $table->index('sort_order');
            $table->index('permission_code');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('system_menus');
    }
};
