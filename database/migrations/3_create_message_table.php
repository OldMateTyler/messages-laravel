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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->dateTime('sent_at')->default(now());
            $table->dateTime('read_at')->nullable();
            $table->string('body');
            $table->foreignId('author');
            $table->foreignId('recipient');
            $table->foreignId('thread_id');
            $table->foreign('thread_id')->references('id')->on('threads')->onDeleteCascade();
            $table->foreign('author')->references('id')->on('users')->onDeleteCascade();
            $table->foreign('recipient')->references('id')->on('users')->onDeleteCascade();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('message');
    }
};
