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
        Schema::create('policies', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name');
            $table->date('birthday')->nullable();
            $table->string('contact_number');
            $table->foreignId('insurance_type_id')->constrained();
            $table->string('insurance_company');
            $table->string('policy_number')->unique();
            $table->date('date_of_issue');
            $table->date('policy_start_date');
            $table->date('policy_expiry_date');
            $table->decimal('premium', 10, 3);
            $table->decimal('commission', 10, 3)->nullable();
            $table->string('agent_name');
            $table->string('policy_document');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('policies');
    }
};
