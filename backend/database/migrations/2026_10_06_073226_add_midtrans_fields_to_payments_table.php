<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->string('transaction_id')->nullable()->after('amount');
            $table->string('payment_status')->nullable()->after('transaction_id');
            $table->string('payment_type')->nullable()->after('payment_status');
            $table->string('snap_token')->nullable()->after('payment_type');
            $table->timestamp('paid_at')->nullable()->after('snap_token');
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn([
                'transaction_id',
                'payment_status',
                'payment_type',
                'snap_token',
                'paid_at',
            ]);
        });
    }
};