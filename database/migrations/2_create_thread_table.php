<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('threads', function (Blueprint $table) {
            $table->id();
            $table->string('thread_name')->nullable();
            $table->foreignId('userOne');
            $table->foreignId('userTwo');
            $table->string('img_src')->default('https://cryptologos.cc/logos/chatcoin-chat-logo.png?v=023');
            $table->foreign('userOne')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('userTwo')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('thread');
    }
};
