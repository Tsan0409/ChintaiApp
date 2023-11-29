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
        Schema::create('city_csv_files', function (Blueprint $table) {
            $table->id();
            $table->string('file_name', 100);
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
        Schema::dropIfExists('city_csv_files');
    }
};

