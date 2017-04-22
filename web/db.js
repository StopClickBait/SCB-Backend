//'use strict';

/*const knex = require('knex')({
  client: 'pg',
  connection: {
    host     : process.env.DB_HOST,
    user     : process.env.DB_USER  ,
    password : process.env.DB_PASSWORD,
    database : process.env.DB_DATABASE,
    ssl: true
  },
});

module.exports = knex; */

//building the database
//articles
if (!(Schema::hasTable('articles'))) {    
    Schema::create('articles', function($table) {
        $table->increments('id')->unsigned();
        $table->string('nameURI');
        $table->boolean('isDeleted');
        $table->foreign('userID')
            ->references('id')
            ->on('users');
    //table->integer->unsigned('tagID')
    //table->integer->unsigned('userVotes');
    })
}

//User Articles
if (!(Schema::hasTable('UserArticles'))) {
    Schema::create('UserArticles', function($table) {
        $table->increments('id')->unsigned();
        $table->foreign('articleID')
            ->references('id')
            ->on('articles');
        $table->foreign('userID')
            ->references('id')
            ->on('users');
    })
}

//users
if (!(Schema::hasTable('users'))) {}
    Schema::create('users', function($table) {
        $table->increments('id')->unsigned();
        $table->string('nameURI');
        $table->string('authType');   // not sure yet if this will relate to perms or what
        $table->dateTime('createdDate');
        $table->dateTime('modifiedDate');
        $table->boolean('isActive');
    })
}

//roles
if (!(Schema::hasTable('roles'))) {
    Schema::create('roles', function($table) {
        $table->increments('id')->unsigned();
        $table->string('nameURI');
        $table->boolean('isDefault');
        $table->dateTime('createdDate');
        $table->dateTime('modifiedDate');
    })
}

//Role Permissions (relates roles and their permissions)
if (!(Schema::hasTable('RolePerms'))) {
    Schema::create('RolePerms', function($table) {
        $table->increments('id')->unsigned();
        $table->foreign('roleID')
            ->references('id')
            ->on('roles');
        $table->foreign('permID')
            ->references('id')
            ->on('perms');
    })
}

//perms
if (!(Schema::hasTable('perms'))) {
    Schema::create('perms', function($table) {
        $table->increments('id')->unsigned();
        $table->string('nameURI');
        $table->dateTime('createdDate');
        $table->dateTime('modifiedDate');
    })
}

//tags/categories
if (!(Schema::hasTable('tags'))) {
    Schema::create('tags', function($table) {
        $table->increments('id')->unsigned();
        $table->string('categoryName');
        $table->foreign('articleID')
            ->references('id')
            ->on('articles');
        $table->foreign('userID')
            ->references('id')
            ->on('users');
    })
}

//Article Tags (relation of articles and tags)
if (!(Schema::hasTable('ArticleTags'))) {
    Schema::create('ArticleTags', function($table) {
        $table->increments('id')->unsigned();
        $table->foreign('tagID')
            ->references('id')
            ->on('tags');
        $table->foreign('articleID')
            ->references('id')
            ->on('articles');
        $table->foreign('userID')
            ->references('id')
            ->on('users');          
    })
}
