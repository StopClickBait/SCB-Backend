'use strict';

const knex = require('knex')({
  client: 'pg',
  connection: {
    host     : process.env.DB_HOST,
    user     : process.env.DB_USER  ,
    password : process.env.DB_PASSWORD,
    database : process.env.DB_DATABASE,
    ssl: true
  },
});

module.exports = knex; 

//building the database
//articles
knex.schema.createTableIfNotExists('articles', function (table) {
    table.increments();
    table.string('articleID');
    table.string('name');
    table.string('userID');     // author
    table.int('userVotes');
})

//users
knex.schema.createTableIfNotExists('users', function (table) {
    table.increments();
    table.string('userID');
    // will return to this later for other items
})

//roles
knex.schema.createTableIfNotExists('roles', function (table) {
    table.increments();
    table.string('roleID');
    table.string('userID');
}

//tags/categories
knex.schema.createTableIfNotExists('tags', function (table) {
    table.increments();
    table.string('categoryName');
    table.string('articleID');
    // will come back to this later for other items
})
