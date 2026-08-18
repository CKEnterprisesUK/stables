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
        Schema::create('gift_sponsorships', function (Blueprint $table) {
            $table->id();
            $table->string('code', 32)->unique();
            $table->foreignId('horse_id')->constrained()->cascadeOnDelete();
            $table->string('purchaser_name');
            $table->string('purchaser_email');
            $table->string('recipient_name')->nullable();
            $table->string('recipient_message')->nullable();
            $table->integer('months'); // number of months purchased
            $table->integer('amount_paid'); // total amount in cents (one-time payment)
            $table->string('stripe_payment_intent_id')->nullable();
            $table->string('status')->default('purchased'); // purchased, redeemed, expired
            $table->foreignId('redeemed_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('sponsorship_id')->nullable()->constrained()->nullOnDelete();
            $table->dateTime('redeemed_at')->nullable();
            $table->dateTime('expires_at'); // when the gift code expires (e.g. 1 year from purchase)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gift_sponsorships');
    }
};
