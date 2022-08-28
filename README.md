## About The App
This is a console app to practice Flashcards. Users can create their own flashcard and start practicing. It provides an interactive menu to create flashcards, view the list and practice them.


## Requirements
This project uses:
- PHP8.0+
- Mysql

The application has been set up with docker and you need to install:
- docker
- docker-compose

on your machine to run the project.

## How to Run
### Running Docker Containers
In order to run the application you have to build the docker images and run the containers. Before building the docker images, make sure that you have copied the environment file and updated the parameters.

```bash
cp .env.example .env
```

Then run docker using the bellow command:
```bash
docker compose up --build -d
```

> **Note:** You may have port(s) conflict on your machine while trying to run the containers. You can change the default ports from `.env` file and try again.

Once the containers are up and running you should be able to see the list of running container:

```bash
docker compose ps
```

and you see these information:

| Name | Command | State | Ports | 
| ---- | ------- | ----- | ----- |
| FlashCard-mysql | docker-entrypoint.sh | UP | 0.0.0.0:3308->3306/tcp,:::3308->3306/tcp, 33060/tcp |
| FlashCard-nginx | /docker-entrypoint.sh ngin ... | UP | 0.0.0.0:8001->443/tcp,:::8001->443/tcp, 0.0.0.0:8000->80/tcp,:::8000->80/tcp |
| FlashCard-php | docker-php-entrypoint php-fpm | UP | 9000/tcp |
| FlashCard-redis | docker-entrypoint.sh redis ... | UP | 0.0.0.0:6380->6379/tcp,:::6380->6379/tcp |


### Setup Laravel Configuration
You should run these commands sequencially:

```bash
docker exec -it FlashCard-php composer install
```
> Installing composer packages may take a while to be executed.

```bash
docker exec -it FlashCard-php php artisan key:generate
```
```bash
docker exec -it FlashCard-php php artisan migrate
```

And that's it. Let's enjoy :)

## Artisan Commands
Your commands must be executed in running containers. Therefore, you can execute artisan commands like this: 

```bash
docker exec -it FlashCard-php php artisan flashcard:interactive
``` 

## Running Tests
To run the tests run:
```bash
docker exec -it FlashCard-php php artisan test
```