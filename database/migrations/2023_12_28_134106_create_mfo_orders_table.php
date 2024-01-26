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
        Schema::create('mfo_orders', function (Blueprint $table) {
            $table->id();
            $table->integer('status');
            $table->integer('order_id');
            $table->float('rate');
            $table->integer('salesman_id');
            $table->integer('company_id');
            $table->integer('division_id');
            $table->integer('customer_id');
            $table->integer('sum_credit');
            $table->integer('transfer_sum');
            $table->string('find_mfo')->nullable();
            $table->integer('term_credit')->nullable();
            $table->text('items');

            $table->integer('month_pay')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mfo_orders');
    }
};
