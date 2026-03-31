<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('paint_materials', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('unit')->default('шт');
            $table->decimal('stock', 12, 2)->default(0);
            $table->decimal('price_per_unit', 12, 2);
            $table->foreignId('status_id')->constrained('statuses');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paint_materials');
    }
};
