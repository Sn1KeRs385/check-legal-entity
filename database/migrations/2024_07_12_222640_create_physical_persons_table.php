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
        Schema::create('physical_persons', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('inn');
            $table->string('first_name')
                ->comment('Имя');
            $table->string('second_name')
                ->comment('Фамилия');
            $table->string('last_name')
                ->nullable()
                ->default(null)
                ->comment('Отчество');
            $table->timestamps();

            $table->unique(['inn']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('physical_persons');
    }
};
