<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSellerCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seller_categories', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('user_type')->nullable();
            $table->integer('category_id');
            $table->enum('noti_on', ['0', '1'])->default('1')->comment('off=0, seller=1');
            $table->enum('favorite', ['0', '1'])->default('0')->comment('no=0, yes=1');
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
        Schema::dropIfExists('seller_categories');
    }
}
