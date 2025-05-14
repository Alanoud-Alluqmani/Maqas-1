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
        Schema::create('stores', function (Blueprint $table) {
            $table->id();
            $table->string('name_ar')->default('');
            $table->string('name_en')->default('');
            $table->string('legal');
            $table->string('email')->default('');
            $table->string('phone')->default('');
            $table->boolean('is_active')->default(false);
            $table->foreignId('product_category_id')->constrained();
            $table->float('rating_avr')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stores');
    }
};
