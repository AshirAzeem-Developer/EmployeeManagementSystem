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
        Schema::table('users', function (Blueprint $table) {
            // Naye columns add karein
            $table->string('role')->default('employee')->after('password'); // Role column
            $table->foreignId('shift_id')->nullable()->constrained('shifts')->after('department_id'); // Shift_id (FK)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Rollback ke liye columns drop karein
            $table->dropForeign(['shift_id']);
            $table->dropColumn(['shift_id', 'role']);
        });
    }
};
