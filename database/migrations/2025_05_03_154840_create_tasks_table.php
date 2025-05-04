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
            $table->id();

            $table->string('activity_name');
            $table->foreignId('activity_id')
                ->constrained('activities');

            $table->foreignId('requester_id')
                ->constrained('users');
            $table->string('requester_name');

            $table->foreignId('responsible_id')
                ->constrained('users');
            $table->string('responsible_name');

            $table->text('description');

            $table->dateTime('due_date')->nullable(); // "prazo"

            $table->enum('priority', ['low', 'medium', 'high'])->default('medium');

            $table->boolean('is_completed')->default(false);

            $table->timestamps();
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
