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