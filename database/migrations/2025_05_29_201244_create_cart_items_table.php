<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->integer('amount');
            $table->unsignedBigInteger('cart_id')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->timestamps();

             $table->foreign('cart_id')
                ->references('id')
                ->on('carts')
                ->onDelete('cascade');

             $table->foreign('product_id')
                ->references('id')
                ->on('products')
                ->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};
