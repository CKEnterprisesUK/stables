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
        Schema::table('stable_brandings', function (Blueprint $table) {
            $table->text('sponsorship_info')->nullable()->after('logo_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stable_brandings', function (Blueprint $table) {
            $table->dropColumn('sponsorship_info');
        });
    }
};
