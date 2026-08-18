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
        Schema::table('horses', function (Blueprint $table) {
            $table->date('date_of_birth')->nullable()->after('facts');
            $table->string('breed')->nullable()->after('date_of_birth');
            $table->string('colour')->nullable()->after('breed');
            $table->string('gender')->nullable()->after('colour');
            $table->decimal('height_hands', 4, 1)->nullable()->after('gender');
            $table->date('arrival_date')->nullable()->after('height_hands');
            $table->text('personality')->nullable()->after('arrival_date');
            $table->string('favourite_treats')->nullable()->after('personality');
            $table->text('backstory')->nullable()->after('favourite_treats');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('horses', function (Blueprint $table) {
            $table->dropColumn([
                'date_of_birth',
                'breed',
                'colour',
                'gender',
                'height_hands',
                'arrival_date',
                'personality',
                'favourite_treats',
                'backstory',
            ]);
        });
    }
};
