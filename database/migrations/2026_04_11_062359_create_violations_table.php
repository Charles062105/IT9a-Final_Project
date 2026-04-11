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
        Schema::create('violations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->string('type');
            $table->integer('offense_number');
            $table->text('description');
            $table->string('action_taken');
            $table->foreignId('issued_by')->constrained('users');
            $table->date('date_issued');
            $table->enum('status', ['Pending', 'Resolved'])->default('Pending');
            $table->decimal('penalty_amount', 8, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('violations');
    }
};
