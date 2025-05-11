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
        Schema::create('measure_vlaues', function (Blueprint $table) {
            $table->id();
            // $table->string('name');
            $table->foreignId('measure_name_id')->constrained()->cascadeOnDelete();
            $table->float('measure');
            $table->foreignId('measure_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('measure_vlaues');
    }
};
