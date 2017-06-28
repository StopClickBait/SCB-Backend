Backend
===

- [composer](https://getcomposer.org/download/)

- [laravel](https://laravel.com/docs/5.4/installation)

# API Routes
Note that scb is simply what I have for the local server in the hosts file. Substitute the IP address or whatever mapping you have for the local server during dev. By default, that IP address is `192.168.123.10`. 

The following routes retrieve articles:

| Verb | Route | Result |
| ------ | ------ | ------ |
|GET|`scb/api/articles`|Returns all of the existing articles.|
|GET|`scb/api/articles/{article}`|Returns the article for which `{article}` is the id.|
|GET|`scb/api/users/{user}/articles`|Returns the articles for the `{user}`, which is the id of the user.|
|POST|`scb/api/articles`|Creates a new article in the database and returns it. Specify `nameURI` and `userID` as parameters. Example: `http://scb/api/articles?nameURI=urihere&userID=5`|
|PUT/PATCH|`scb/api/articles/{article}`|Updates the specified article, where `{article}` is the id of the article. Specify `nameURI`, `userID`, or both as parameters. It will return the updated article.|
|DELETE|`scb/api/articles/{article}`|Soft deletes the article for which `{article}` is the id. This simply sets `isDeleted` to 1 in the database, which means it will not be returned in the list of all articles. It then returns the article.|

The following routes retrieve users:

| Verb | Route | Result |
| ------ | ------ | ------ |
|GET|`scb/api/users`|Returns all of the existing users.|
|GET|`scb/api/users/{user}`|Returns the user for which `{user}` is the id.|
|POST|`scb/api/users`|Creates a new user in the database and returns it. Specify `email`, `name`, and `password` as parameters.|
|PUT/PATCH|`scb/api/users/{user}`|Updates the specified user, where `{user}` is the id of the user. Specify `email`, `name`, `password` or all as parameters. It will return the updated user.|