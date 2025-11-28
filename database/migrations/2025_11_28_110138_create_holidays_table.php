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
        Schema::create('tbl_holidays', function (Blueprint $table) {
            $table->id();
            $table->date('date')->unique(); // Jis din chutti hogi
            $table->string('description'); // Chutti kis wajah se hai (e.g., 'Pakistan Day')
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_holidays');
    }
};
