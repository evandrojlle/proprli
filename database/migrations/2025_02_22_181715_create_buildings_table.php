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
        Schema::create('buildings', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200)->nullable(false);
            $table->string('address', 255)->nullable(false);
            $table->string('number', 25)->nullable(false);
            $table->string('neighborhood', 200)->nullable(false);
            $table->string('city', 200)->nullable(false);
            $table->string('state', 3)->nullable(false);
            $table->string('country', 100)->nullable(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buildings');
    }
};
