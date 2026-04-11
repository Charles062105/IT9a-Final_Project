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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('employee_id')->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('first_name');
            $table->string('last_name');
            $table->enum('gender', ['Male', 'Female']);
            $table->date('birth_date');
            $table->text('address');
            $table->string('phone');
            $table->string('emergency_contact');
            $table->string('emergency_phone');
            $table->enum('civil_status', ['Single', 'Married', 'Widowed', 'Divorced']);
            $table->string('department');
            $table->string('position');
            $table->date('hire_date');
            $table->decimal('salary', 10, 2);
            $table->enum('status', ['active', 'inactive']);
            $table->string('sss_number')->nullable();
            $table->string('philhealth_number')->nullable();
            $table->string('pagibig_number')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
