<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
 public function up(): void
{
    Schema::create('employee_details', function (Blueprint $table) {
        $table->id();
        $table->foreignId('employee_id')->unique()->constrained('employees')->onDelete('cascade');
        $table->string('employee_number');
        $table->date('date_of_joining');
        $table->timestamps();
    });
}
};
