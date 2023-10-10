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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->integer('status');
            $table->float('rate');
            $table->integer('salesman_id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('surname');

            $table->date('birthday');
            $table->string('phone');
            $table->integer('term_credit');
            $table->integer('sum_credit');
            $table->integer('month_pay');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
