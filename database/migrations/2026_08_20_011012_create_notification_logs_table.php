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
        Schema::create('notification_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('policy_id')->nullable()->constrained()->nullOnDelete();
            $table->string('customer_name');
            $table->string('contact');
            $table->string('policy_number')->nullable();
            $table->string('insurance_type')->nullable();
            $table->string('notification_type');
            $table->unsignedInteger('reminder_stage')->nullable();
            $table->string('channel');
            $table->string('status');
            $table->text('message');
            $table->foreignId('sent_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('sent_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_logs');
    }
};
