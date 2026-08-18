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
        Schema::create('stripe_settings', function (Blueprint $table) {
            $table->id();
            $table->string('stripe_key'); // publishable key (pk_...)
            $table->text('stripe_secret_encrypted'); // secret key, encrypted
            $table->text('webhook_secret_encrypted'); // webhook secret, encrypted
            $table->string('price_id')->nullable(); // Stripe Price ID
            $table->timestamp('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stripe_settings');
    }
};
