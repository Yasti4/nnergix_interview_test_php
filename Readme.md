#Nnergix Crawler

## Requirements

1. Git
2. Docker & Docker Compose

## Before start

```bash
cp .env.example .env
cp .env.example .env.local
```

Download lib .phpunit
```bash
bin/phpunit
```

## Getting started

All commands are in the **MakeFile** file

1) Run `` make docker-compose-build`` to create the containers

2) Run `` make docker-compose-up `` to start containers

3) Execute `` make php-bash`` to connect to the php container
	
	**In this container**, if it is the first time to execute the migrations
	
	`` bin/console doctrine:migrations:migrate``
4) Execute test `` make php-tests `` or if you are in the php container execute `` bin/phpunit``

5) If you want stop the containers use: 

	`` make docker-compose-down ``

## Commands Available

These commands are found in the *AML\UserInterface\CLI\Command* folder

**Run from the php container**

This command searches for all links in the '\<a\>' tags, processes the first result and if not indicated 
[LevelIterator] = 0, sends the other links, if any, to the queue system to be processed asynchronously

This command return  a 'Uuid' to be able to consult the links that have been processed asynchronously

*example:*

   *Reference: 291e6787-fce3-4dc3-8b09-52ae9b3da97e*
    
```
bin/console crawler:search {URL} [LevelIterator]
```
*Example*:
```
bin/console crawler:search https://www.nnergix.com 1
bin/console crawler:search https://www.nnergix.com
```

This command returns all the pages you have processed according to the uuid entered.

**IMPORTANT: The page may still be processing**

```
bin/console worker:async-process-page-query {Uuid}
```
*Example*:

```
bin/console worker:async-process-page-query ce5b13e7-35fa-4c4d-bea6-b9af77338a10
```

This command returns a list of when a website has changed.

*To consider:* It works with the 'last-modified' header, 
if the website does not have this header or has been modified it will appear in this list.
```
bin/console worker:domain-events-url-changed-query
```

Automatic process -> using supervisor

config file: *docker/local/nnergix-php/rootfs/supervisor/conf.d*

**Important:** Don't touch this files

**Used commands:**

```
bin/console worker:async-domain-events
bin/console worker:async-process-page

``` 
