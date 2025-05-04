<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->string('product_name');
            $table->integer('quantity');
            $table->string('sugar_level');
            $table->string('ice_level');
            $table->decimal('price', 10, 2);
            $table->enum('juice_type', ['fresh', 'mix', 'berry'])->default('fresh');
            $table->foreignId('fruit_id')->constrained('fruits');
            $table->foreignId('second_fruit_id')->nullable()->constrained('fruits');
            $table->boolean('stock_reduced')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('order_items');
    }
};
