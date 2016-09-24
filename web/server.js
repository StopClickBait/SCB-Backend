'use strict';
require('dotenv').config();

const hapi = require('hapi');
const joi = require('joi');
const Promise = require('bluebird');
const path = require('path');
const Url = require('url');

const knex = require('./db.js');
const server = new hapi.Server({
  connections: {
    routes: {
      auth: 'session',
      // auth: false,
    },
  },
});

const connection = server.connection({ port: process.env.PORT });

function cleanUrl(url) {
  const urlObject = Url.parse(url, true); // the second true is to parse querystring
  try {
    delete urlObject.query.utm_content;
    delete urlObject.query.utm_medium;
    delete urlObject.query.utm_source;
    delete urlObject.query.utm_campaign;
    delete urlObject.hash;
    delete urlObject.search;
  } finally {
    return Url.format(urlObject);
  }
}

server.register([
  require('bell'),
  require('inert'),
  require('hapi-auth-cookie'),
  require('vision'),
], (err) => {

  server.views({
    engines: { html: require('handlebars') },
    relativeTo: __dirname,
    path: 'templates',
  });

  // // Set cookie definition
  // server.state('session', {
  //     ttl: 24 * 60 * 60 * 1000,     // One day
  //     isSecure: true,
  //     path: '/',
  //     encoding: 'base64json'
  // });

  server.auth.strategy('session', 'cookie', true, {
      password: process.env.SECRET_PHRASE,
      cookie: 'sid-sac',
      redirectTo: '/login',
      isSecure: false,
      ttl: 30 * 24 * 60 * 60 * 1000,     // 30 days
      appendNext: true,
  });
  
  // Declare an authentication strategy using the bell scheme
  // with the name of the provider, cookie encryption password,
  // and the OAuth client credentials.
  server.auth.strategy('google', 'bell', {
    provider: 'google',
    password: process.env.SECRET_PHRASE,
    clientId: process.env.GOOG_CLIENT_ID,
    clientSecret: process.env.GOOG_CLIENT_SECRET,
    isSecure: false,
    scope: ['profile'],
  });

  server.auth.strategy('facebook', 'bell', {
    provider: 'facebook',
    password: process.env.SECRET_PHRASE,
    clientId: process.env.FB_CLIENT_ID,
    clientSecret: process.env.FB_CLIENT_SECRET,
    isSecure: false,
    scope: [],
  });

  // Use the 'twitter' authentication strategy to protect the
  // endpoint handling the incoming authentication credentials.
  // This endpoints usually looks up the third party account in
  // the database and sets some application state (cookie) with
  // the local application account information.
  server.route({
      method: ['GET', 'POST'], // Must handle both GET and POST
      path: '/login',          // The callback endpoint registered with the provider
      handler: function (request, reply) {
        if (!request.auth.isAuthenticated) {
            return reply('Authentication failed due to: ' + request.auth.error.message);
        }

        // Perform any account lookup or registration, setup local session,
        // and redirect to the application. The third-party credentials are
        // stored in request.auth.credentials. Any query parameters from
        // the initial request are passed back via request.auth.credentials.query.
        request.cookieAuth.set({ id: request.auth.credentials.profile.id });
        return reply.redirect(request.auth.credentials.query.next);
        // return reply('You are now logged-in. Please close this tab and try to open the page you were trying to access again');
      },
      config: {
        auth: {
          mode: 'try',
          strategies: ['facebook'],
        },
        plugins: { 'hapi-auth-cookie': { redirectTo: false } },
      }
  });

  // Serve the frontend
  server.route({
    method: 'GET',
    path: '/public/{param*}',
    handler: {
      directory: {
        path: './public',
        redirectToSlash: true,
        index: false
      }
    },
    config: {
      auth: false,
    },
  });


  function getAnswers(targetUrl) {
    return knex('clickbait').where('url', targetUrl).first().then(clickbait => {
      if (!clickbait) {
        return null;
      }
      return Promise.props({
        clickbait,
        answers: knex('clickbaitAnswers')
          .where('clickbaitAnswers.clickbaitId', clickbait.id)
          .leftOuterJoin('clickbaitAnswerVotes', 'clickbaitAnswers.id', '=', 'clickbaitAnswerVotes.clickbaitAnswerId')
          .groupBy(['clickbaitAnswers.id', 'clickbaitAnswers.answer'])
          .orderBy('numVotes', 'desc')
          .select(['clickbaitAnswers.answer', 'clickbaitAnswers.id', knex.raw('COUNT("clickbaitAnswerVotes".*) as "numVotes"')]), 
      });
    });
  }

  function cleanUrlPre(request, reply) {
    if (request.query && request.query.url) {
      request.query.url = cleanUrl(request.query.url);
    }
    reply.continue();
  }

  // Get answers for a given URL
  server.route({
    method: 'GET',
    path: '/',
    config: {
      pre: [cleanUrlPre],
      validate: {
        query: {
          url: joi.string().required(),
        },
      },
    },
    handler: function (request, reply) {
      var targetUrl = request.query.url;

      getAnswers(targetUrl).then(fulfilledClickbait => {
        reply.view('index', { 
          decodedBaitUrl: decodeURIComponent(targetUrl), 
          baitUrl: encodeURIComponent(targetUrl), 
          clickbait: fulfilledClickbait 
        });
      }).catch(err => {
        console.error(err.stack);
        reply('Something went wrong.').code(500);
      });
    },
  });

  // Get answers for a given URL
  server.route({
    method: 'GET',
    path: '/api/answers',
    config: {
      pre: [cleanUrlPre],
      auth: false,
      validate: {
        query: {
          url: joi.string().required(),
        },
      },
    },
    handler: function (request, reply) {
      var targetUrl = request.query.url;

      getAnswers(targetUrl).then(fulfilledClickbait => {
        if (fulfilledClickbait) {
          reply(fulfilledClickbait);
        } else {
          reply('Clickbait not yet registered.').code(404);
        }
      }).catch(err => {
        console.error(err.stack);
        reply('Something went wrong.').code(500);
      });
    },
  });

  // Add a new answer
  server.route({
    method: 'POST',
    path: '/answers',
    config: {
      pre: [cleanUrlPre],
      validate: {
        query: {
          url: joi.string().required(),
        },
        payload: {
          answer: joi.string().required(),
        },
      },
    },
    handler: function (request, reply) {
      
      // Check to see if there's already a registered clickbait with this link.
      // If not, just create it
      knex('clickbait').where('url', request.query.url).then(clickbait => {
        if (!clickbait || clickbait.length === 0) {
          return knex('clickbait').insert({
            url: request.query.url,
          }).returning('*');
        }
        return clickbait;
      })
      
      .then(clickbaits => {
        var clickbait = clickbaits[0];
        return knex('clickbaitAnswers').insert({
          clickbaitId: clickbait.id,
          answer: request.payload.answer,
          userId: request.auth.credentials.id,
        }).then(() => {
          reply.redirect(`/?url=${encodeURIComponent(request.query.url)}`);
        });
      })
      
      .catch(err => {
        console.error(err.stack);
        reply('Something went wrong.').code(500);
      });
    },
  });

  // Upvote a given answers
  server.route({
    method: 'POST',
    path: '/answers/{answerId}/votes',
    config: {
      pre: [cleanUrlPre],
      validate: {
        query: {
          url: joi.string().required(),
        },
        params: {
          answerId: joi.number().integer().positive().required(),
        },
      },
    },
    handler: function (request, reply) {
      
      // Check to see if there's already a registered clickbait with this link.
      // If not, just create it
      knex('clickbaitAnswerVotes')
        .where('clickbaitAnswerVotes.clickbaitAnswerId', request.params.answerId)
        .where('userId', request.auth.credentials.id)
        .first().then(existingVote => {
          if (!existingVote) {
            return knex('clickbaitAnswerVotes').insert({
              userId: request.auth.credentials.id,
              clickbaitAnswerId: request.params.answerId,
            });
          }
          return null;
        })

        .then(() => {
          reply.redirect(`/?url=${encodeURIComponent(request.query.url)}`);
        })
        
        .catch(err => {
          console.error(err.stack);
          reply('Something went wrong.').code(500);
        });
    },
  });

  console.log(`Starting the server at PORT: ${process.env.PORT}`);
  server.start();
}); 