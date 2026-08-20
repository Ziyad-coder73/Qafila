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
        Schema::create('notification_settings', function (Blueprint $table) {
            $table->id();
            $table->string('type')->unique();
            $table->boolean('is_enabled')->default(true);
            $table->boolean('channel_whatsapp')->default(true);
            $table->boolean('channel_sms')->default(false);
            $table->boolean('channel_email')->default(false);
            $table->string('subject')->nullable();
            $table->text('body');
            $table->string('renewal_payment_link')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_settings');
    }
};
