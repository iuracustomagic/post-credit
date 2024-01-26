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
        Schema::create('additional_order_data', function (Blueprint $table) {
            $table->id();
            $table->integer('order_id');
            $table->integer('repeat_cause');
            $table->integer('passport')->nullable();
            $table->integer('employment')->nullable();
            $table->integer('files')->nullable();
            $table->integer('photo_with_passport')->nullable();
            $table->integer('registration_address')->nullable();
            $table->integer('income_amount')->nullable();
            $table->integer('additional_phone')->nullable();
            $table->integer('foreign_passport')->nullable();
            $table->integer('permanent_residency')->nullable();
            $table->integer('residence_permit')->nullable();
            $table->integer('temporary_registration')->nullable();
            $table->integer('patent')->nullable();
            $table->integer('patent_payment')->nullable();
            $table->integer('snils')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('additional_order_data');
    }
};
