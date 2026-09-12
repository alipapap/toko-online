<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('order_details', 'price')) {
            Schema::table('order_details', function (Blueprint $table) {
                $table->decimal('price', 12, 2)->after('quantity');
            });
        }

        if (!Schema::hasColumn('order_details', 'subtotal')) {
            Schema::table('order_details', function (Blueprint $table) {
                $table->decimal('subtotal', 12, 2)->after('price');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('order_details', 'subtotal')) {
            Schema::table('order_details', function (Blueprint $table) {
                $table->dropColumn('subtotal');
            });
        }

        if (Schema::hasColumn('order_details', 'price')) {
            Schema::table('order_details', function (Blueprint $table) {
                $table->dropColumn('price');
            });
        }
    }
};