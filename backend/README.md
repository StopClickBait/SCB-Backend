Backend
===

- [composer](https://getcomposer.org/download/)

- [laravel](https://laravel.com/docs/5.4/installation)

# API Routes
The following routes have to do with retreiving articles. All are working as specified as of 6/25/17, tested with Postman.

| Verb | Route | Result |
| ------ | ------ | ------ |
|GET|`scb/api/articles`|Returns all of the existing articles.|
|GET|`scb/api/articles/{article}`|Returns the article for which `{article}` is the id.|
|GET|`scb/api/users/{user}/articles`|Returns the articles for the `{user}`, which is the id of the user.|
|POST|`scb/api/articles`|Creates a new article in the database and returns it. Specify `nameURI` and `userID` as parameters. Example: `http://scb/api/articles?nameURI=urihere&userID=5`|
|PUT/PATCH|`scb/api/articles/{article}`|Updates the specified article, where `{article}` is the id of the article. Specify `nameURI`, `userID`, or both as parameters. It will return the updated article.|
|DELETE|`scb/api/articles/{article}`|Soft deletes the article for which `{article}` is the id. This simply sets `isDeleted` to 1 in the database, which means it will not be returned in the list of all articles. It then returns the article.|
