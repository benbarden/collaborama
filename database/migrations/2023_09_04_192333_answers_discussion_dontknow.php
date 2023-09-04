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
        Schema::table('collab_answers', function (Blueprint $table) {
            $table->tinyInteger('needs_discussion')->default(0);
            $table->tinyInteger('dont_know')->default(0);
            $table->string('answer')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('collab_answers', function (Blueprint $table) {
            $table->dropColumn('needs_discussion');
            $table->dropColumn('dont_know');
            //$table->string('answer')->change();
        });
    }
};
