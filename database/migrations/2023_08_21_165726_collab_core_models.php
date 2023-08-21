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
        Schema::create('collab_topics', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('topic', 100);
            $table->string('share_code', 20)->unique();
            $table->string('access_type', 20);
            $table->string('status', 20);
            $table->timestamps();
        });

        Schema::create('collab_questions', function (Blueprint $table) {
            $table->id();
            $table->integer('topic_id');
            $table->string('question', 100);
            $table->tinyInteger('question_no');
            $table->tinyInteger('answer_type');
            $table->timestamps();
        });

        Schema::create('collab_answers', function (Blueprint $table) {
            $table->id();
            $table->integer('question_id');
            $table->string('answer');
            $table->integer('user_id')->nullable();
            $table->string('guest_name', 50)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collab_topics');
        Schema::dropIfExists('collab_questions');
        Schema::dropIfExists('collab_answers');
    }
};
