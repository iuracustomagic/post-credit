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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->integer('status');
            $table->string('name');
            $table->string('idn');
            $table->string('ogrn');
            $table->string('address');
            $table->string('checking_account');
            $table->string('correspond_account');
            $table->string('bank_name');
            $table->string('bik');
            $table->foreignId('leader_id')->nullable()->index()->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
