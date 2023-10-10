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
        Schema::create('manager_bonus_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('manager_id')->nullable()->index()->constrained('users');
            $table->float('credit_1');
            $table->float('credit_2');
            $table->float('credit_3');
            $table->float('credit_4');
            $table->float('sms');
            $table->float('referral');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manager_bonus_settings');
    }
};
