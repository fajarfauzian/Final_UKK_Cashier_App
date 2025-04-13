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
        Schema::create('sales_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->foreignId('product_id')
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->decimal('unit_price', 10, 2)->default(0)->check('unit_price >= 0'); 
            $table->integer('quantity')->default(1)->check('quantity > 0');
            $table->decimal('subtotal', 10, 2)->default(0)->check('subtotal >= 0');
            $table->timestamps();
            
            // Index untuk performa
            $table->index(['sale_id', 'product_id']);
        }); 
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_details');
    }
};
