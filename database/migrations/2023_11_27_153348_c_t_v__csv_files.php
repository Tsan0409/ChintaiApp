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
        Schema::create('ctv_csvfiles', function (Blueprint $table) {
            $table->id();
            $table->string('file_name', 100);
            $table->foreignId('prefecture_id')->constrained();
            $table->foreignId('city_id')->constrained();
            $table->integer('K');
            $table->integer('LDK');
            $table->integer('R');
            $table->integer('SDK');
            $table->integer('SK');
            $table->integer('SLDK');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ctv_csvfiles');
    }
};
