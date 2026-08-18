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
        Schema::table('stripe_settings', function (Blueprint $table) {
            $table->string('stripe_account_id')->nullable()->after('price_id');
            $table->string('stripe_connect_status')->default('not_connected')->after('stripe_account_id');
            // Make legacy key columns nullable for Connect flow
            $table->string('stripe_key')->nullable()->change();
            $table->text('stripe_secret_encrypted')->nullable()->change();
            $table->text('webhook_secret_encrypted')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stripe_settings', function (Blueprint $table) {
            $table->dropColumn(['stripe_account_id', 'stripe_connect_status']);
            $table->string('stripe_key')->nullable(false)->change();
            $table->text('stripe_secret_encrypted')->nullable(false)->change();
            $table->text('webhook_secret_encrypted')->nullable(false)->change();
        });
    }
};
