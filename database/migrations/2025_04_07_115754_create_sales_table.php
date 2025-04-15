<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('total_price', 15, 2);
            $table->decimal('amount_paid', 15, 2);
            $table->decimal('change', 15, 2);  
            $table->boolean('is_member')->default(false);
            $table->string('phone')->nullable();
            $table->string('customer_name')->nullable();
            $table->boolean('use_points')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
