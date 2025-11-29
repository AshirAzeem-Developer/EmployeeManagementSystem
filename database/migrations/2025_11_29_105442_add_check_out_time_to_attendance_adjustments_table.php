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
        Schema::table('tbl_attendance_adjustments', function (Blueprint $table) {
            $table->time('check_out_time')->nullable()->after('requested_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_attendance_adjustments', function (Blueprint $table) {
            $table->dropColumn('check_out_time');
        });
    }
};
