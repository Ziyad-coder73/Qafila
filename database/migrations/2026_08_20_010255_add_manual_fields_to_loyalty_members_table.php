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
            $table->string('id_number')->nullable()->after('phone');
            $table->string('insurance_company')->nullable()->after('id_number');
            $table->foreignId('insurance_type_id')->nullable()->after('insurance_company')->constrained()->nullOnDelete();
            $table->string('delivery_method')->nullable()->after('issued_by');
            $table->string('delivery_status')->default('pending')->after('delivery_method');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loyalty_members', function (Blueprint $table) {
            $table->dropConstrainedForeignId('insurance_type_id');
            $table->dropColumn(['id_number', 'insurance_company', 'delivery_method', 'delivery_status']);
        });
    }
};
