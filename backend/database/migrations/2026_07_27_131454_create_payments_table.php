<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('payments', function (Blueprint $table) {
        $table->id();
        $table->foreignId('order_id')->unique()->constrained('orders')->onDelete('cascade');
        $table->string('method');
        $table->decimal('amount', 12, 2);
        $table->timestamps();
    });
}
};
