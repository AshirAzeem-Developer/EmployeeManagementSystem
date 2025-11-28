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
        Schema::create('tbl_profiles', function (Blueprint $table) {
            $table->id();
            // One-to-One relationship: user_id unique aur linked hoga
            $table->foreignId('user_id')->unique()->constrained()->onDelete('cascade');

            $table->string('phone_number')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->text('current_address')->nullable();
            $table->string('cnic')->unique()->nullable();
            $table->string('marital_status')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_profiles');
    }
};
