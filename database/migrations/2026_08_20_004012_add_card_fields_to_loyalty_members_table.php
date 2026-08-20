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
        Schema::table('loyalty_members', function (Blueprint $table) {
            $table->foreignId('policy_id')->nullable()->after('id')->constrained()->nullOnDelete();
            $table->string('card_token', 40)->nullable()->unique()->after('membership_number');
            $table->foreignId('issued_by')->nullable()->after('expires_at')->constrained('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loyalty_members', function (Blueprint $table) {
            $table->dropConstrainedForeignId('policy_id');
            $table->dropConstrainedForeignId('issued_by');
            $table->dropColumn('card_token');
        });
    }
};
