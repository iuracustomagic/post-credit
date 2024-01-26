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
        Schema::create('mfo_clients', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('surname');
            $table->date('birthday');
            $table->string('phone');
            $table->string('password_id');
            $table->string('password_code');
            $table->string('password_date');
            $table->string('password_by');
            $table->string('address_index');
            $table->string('address_region');
            $table->string('address_city');
            $table->string('address_street');
            $table->string('address_house');
            $table->string('address_block');
            $table->string('address_flat');
            $table->integer('residence');
            $table->integer('doc_set');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mfo_clients');
    }
};
