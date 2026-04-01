<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();                                                              // Primary Key (auto increment integer)
            $table->string('title');                                                   // Task title
            $table->date('due_date');                                                  // Deadline
            $table->enum('priority', ['low', 'medium', 'high'])->default('low');      // Priority level
            $table->enum('status', ['pending', 'in_progress', 'done'])->default('pending'); // Task status
            $table->timestamps();                                                      // created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};