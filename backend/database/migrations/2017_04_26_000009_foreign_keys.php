<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ForeignKeys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {


        Schema::table('articles', function (Blueprint $table) {
            $table->foreign('userID')
                ->references('id')
                ->on('users');
        });
        
        Schema::table('user_articles', function (Blueprint $table) {
            $table->foreign('articleID')
                ->references('id')
                ->on('articles');
            $table->foreign('userID')
                ->references('id')
                ->on('users');
        });

        Schema::table('role_perms', function (Blueprint $table) {
            $table->foreign('roleID')
                ->references('id')
                ->on('roles');
            $table->foreign('permID')
                ->references('id')
                ->on('perms');
        });

        Schema::table('tags', function (Blueprint $table) {
            $table->foreign('articleID')
                ->references('id')
                ->on('articles');
            $table->foreign('userID')
                ->references('id')
                ->on('users');
        });

        Schema::table('article_tags', function (Blueprint $table) {
            $table->foreign('tagID')
                ->references('id')
                ->on('tags');
            $table->foreign('articleID')
                ->references('id')
                ->on('articles');
            $table->foreign('userID')
                ->references('id')
                ->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {   
        Schema::table('articles', function (Blueprint $table) {
            $table->dropForeign(['userID']);
        });
        
        Schema::table('user_articles', function (Blueprint $table) {
            $table->dropForeign(['articleID']);
            $table->dropForeign(['userID']);
        });

        Schema::table('role_perms', function (Blueprint $table) {
            $table->dropForeign(['roleID']);
            $table->dropForeign(['permID']);
        });

        Schema::table('tags', function (Blueprint $table) {
            $table->dropForeign(['articleID']);
            $table->dropForeign(['userID']);
        });

        Schema::table('article_tags', function (Blueprint $table) {
            $table->dropForeign(['tagID']);
            $table->dropForeign(['articleID']);
            $table->dropForeign(['userID']);
        });
    }
}
