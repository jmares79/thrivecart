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
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('code')->unique();
            $table->float('price');

            $table->timestamps();
        });

        Schema::create('order_product', function (Blueprint $table) {
            $table->id();

            $table->integer('product_id')->unsigned();
            $table->integer('order_id')->unsigned();
            $table->string('description')->nullable();
            $table->float('amount');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
        Schema::dropIfExists('order_product');
    }
};
