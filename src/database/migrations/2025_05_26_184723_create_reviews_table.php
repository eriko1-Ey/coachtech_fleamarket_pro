<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chat_id')->constrained()->onDelete('cascade'); // 取引ごとに1つの評価
            $table->foreignId('reviewer_id')->constrained('users')->onDelete('cascade'); // 評価者
            $table->foreignId('reviewee_id')->constrained('users')->onDelete('cascade'); // 評価される人
            $table->unsignedTinyInteger('rating'); // 星（1〜5）
            $table->text('comment')->nullable();   // コメント
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
        Schema::dropIfExists('reviews');
    }
}
