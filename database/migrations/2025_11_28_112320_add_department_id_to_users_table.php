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
            // Foreign key 'department_id' ko add karein
            // Aur isay 'departments' table se link karein
            $table->foreignId('department_id')
                ->nullable() // Agar koi user abhi department assign na ho toh null allowed hai
                ->constrained('tbl_departments')
                ->after('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Foreign key ko drop karein
            $table->dropForeign(['department_id']);
            $table->dropColumn('department_id');
        });
    }
};
