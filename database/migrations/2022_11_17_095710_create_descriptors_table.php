<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDescriptorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('descriptors', function (Blueprint $table) {
            $table->id('descriptor_id');

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('user_id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->json('descriptions');
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
        Schema::dropIfExists('descriptors');
    }
}
