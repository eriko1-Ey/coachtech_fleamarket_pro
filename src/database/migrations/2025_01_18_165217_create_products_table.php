<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // 出品者
            $table->string('name'); // 商品名
            $table->text('description'); // 商品説明
            $table->decimal('price', 10, 2); // 価格
            $table->boolean('is_sold')->default(false); // 売却フラグ
            $table->string('condition'); // 商品の状態
            $table->string('brand')->nullable(); // ブランド名
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
