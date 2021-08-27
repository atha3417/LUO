<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('answers', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->foreignId('user_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('quiz_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('test_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('choice_id')->nullable();
            $table->longText('text_answer')->nullable();
            $table->boolean('is_correct')->nullable();
            $table->boolean('is_doubt')->default(false);
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
        Schema::dropIfExists('answers');
    }
}
