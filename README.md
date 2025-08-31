# FACEIT viewer via Faceit Data API

## Original documentations & links

[Faceit.com Data API](https://docs.faceit.com/docs/data-api/data)

## About the tech-stack

- Composer based Laravel 12.x project

Faceit.com Data API processor app, for retrieving player and other ingame statistics 
from faceit gameplays.
Faceit.com is an esport tournament gathering website, where player can 
participate form all around the world, to show up their skills, and to prove they are undefeatable.
The App contains many joinable clubs, and events where the more precised, higher talented.

This goal of this micro-project is to, just build-up quickly a fast,
tipicly REST-based app, to get gameplay statistics, and maybe other informations about the given player's performance,
to re-use it other nice charts and websites. (Maybe for one of mine) :)

### Set-UP

#### Requirements:
- php 8.3
- composer 2.x
- A database like (MySQL / PostgreSQL).

To just test it, in e.g.: debug/dev mode.
In the CLI run the following command:

```
 composer install
```

Then run:
```
php artisan serve
```

Viole! You are done :) 
To use the endpoints, you have to create the database and register your user first.
You also have to claim an API key - you can generate it on the website, according to the documentation - and
then, store this key inside .env as FACEIT_API_KEY={key}.

To create a user, you can use the following comand:

```
php artisan app:create-user --name='Some name' --email='test@example.com' --password='Plainpassword'
```

### Example endpoint:

Login first (the endpoint is secured insite the app):

**request method:** POST
**endpoint:** /api/login
**accepts:** 
```json
{
  "email": "test@example.com",
  "password": "Plainpassword"
}
```

You will recieve a bearer token what you have to throw-up in the next request to retrieve data.\
**Important:** Unless you did not register for an api key in faceit, you still won't be able to
query data from the API.

To get player stats:

**request method:** GET \
**endpoint:** localhost:8000/api/faceit/player/stats?nickname={faceit-nickname}

where **{faceit-nickname}** is your actual nickname, which is given in, and belongs to your faceit.com account.

### Docker containerization coming soon...