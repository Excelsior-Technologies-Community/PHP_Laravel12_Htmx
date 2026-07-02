<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('category')->default('general');
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
            $table->boolean('completed')->default(false);
            $table->date('due_date')->nullable();
            $table->date('reminder_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index('category');
            $table->index('priority');
            $table->index('due_date');
            $table->index('completed');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};