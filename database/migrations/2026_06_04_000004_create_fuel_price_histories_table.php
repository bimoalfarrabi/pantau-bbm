<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('fuel_price_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('region_id')->constrained()->cascadeOnDelete();
            $table->foreignId('fuel_product_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('old_price')->nullable();
            $table->unsignedInteger('new_price')->nullable();
            $table->timestamp('changed_at');
            $table->timestamps();

            $table->index('changed_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fuel_price_histories');
    }
};
