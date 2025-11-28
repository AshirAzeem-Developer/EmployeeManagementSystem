<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_shifts', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // e.g., 'General Day Shift'
            $table->time('start_time'); // Shift shuru hone ka time
            $table->time('end_time');   // Shift khatam hone ka time
            $table->integer('grace_period_minutes')->default(15); // Check-in ke liye extra time
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_shifts');
    }
};
