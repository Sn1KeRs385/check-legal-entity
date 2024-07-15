<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('organizations', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('physical_person_id');
            $table->string('type')
                ->comment('Тип связи с физ. лицом (ИП, руководитель, учредитель и тд)');
            $table->unsignedBigInteger('inn');
            $table->string('name');
            $table->timestamps();

            $table->foreign('physical_person_id')
                ->references('id')
                ->on('physical_persons');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organizations');
    }
};
