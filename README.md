# Bookshelf

This project is a Book and Author Archive.  
Desired Books and Authors can be stored, edited, deleted and exported.  
The resource lists can also be searched and sorted.  

## Table of Contents
- [Dependencies](#dependencies)  
- [How to Initialize](#how-to-initialize)  
- [Tests](#tests)  
- [Issues](#issues)  
- [Development Ideas](#development-ideas)  
- [Final Word](#final-word)  

## Dependencies
Backend foundation is based on  
>  1. [php (8.0)](https://www.php.net/releases/8.0/en.php)  
>  2. [laravel (8.54)](https://laravel.com/docs/8.x/releases) 

For testing, following package is used  
> 1. [phpunit/phpunit (9.33)](https://github.com/sebastianbergmann/phpunit)  
>  For Unit and Features Tests

For Frontend compilation
> 1. [laravel-mix/laravel-mix (6.0.6)](https://github.com/laravel-mix/laravel-mix)  
> 2. [tailwindcss (2.2.9)](https://tailwindcss.com/)  
> 3. [bootstrap (4.6.0)](https://getbootstrap.com/docs/4.6/getting-started/introduction/)  
> 4. [JQuery (3.6)](https://jquery.com/)  
> 5. [PostCSS (8.3.6)](https://github.com/postcss/postcss/releases)  

Complete list of dependencies are provided via `composer.json` and `package.json`

## How to Initialize
1. After pulling the project, edit your `.env` and `docker-compose.yml` to setup the database connection and environment variables.  

2. Start the docker containers.
> docker-compose up -d

3. Run the migrations and seed the tables
> php artisan migrate  
> php artisan db:seed  

If you encounter an error such as below, visit this: [Issue 1](#issue-1)  

> SQLSTATE[HY000] [2002] php_network_getaddresses: getaddrinfo failed  

## Tests  

Run the tests using
> php artisan test  

If you are using `sqlite` & `:memory:`, you might face a problem regarding migrations: [Issue 2](#issue-2) 

---
### Unit Tests  
Tests the integrity of basic functionality and properties of both `Book` and `Author` mdels. 

---
### Feature Tests  

Testing different routes and different functionality of `Controllers`.  
Includes tests for __Creating__, __Deleting__, __Updating__, __Getting__ and __Exporting__ a resource from and into the Database.  

---
### Browser Tests  

Currently covers one of the form submissions of the website.  

---
## Issues  

---
### Issue 1  

**CAUSE:**  This might happen due to the inconsistency of connections between different containers of the docker.  

**SOLUTION:** Change your `.env` before migration to the host specified in `docker-compose.yml`; for e.g. if the following is:  
>  # docker-compose.yml  
>  ...  
> services:  
>    ports:  
>      - 127.0.0.1:8000:8000  

Then, `.env` should be changed to  
>  # .env   
>  ...  
>  DB_HOST=127.0.0.1

Then the migration can be run safely. After seeding is finished, revert `DB_HOST` to the name of the docker container of the database.

---
### Issue 2  

**CAUSE:**  This is a follow up to the last issue.  

**SOLUTION:** Change your `DB_HOST` in `.env` to `sqlite` and run the migrations; revert back to the old value after the migrations are done.  

---
### Issue 3  
##### Laravel/Dusk  
Not much Browser Tests were to be done since laravel/dusk stopped working.  

**CAUSE:** A version mismatch with my linux's chrome driver. Tried other drivers as well but was unsuccessful. 

**SOLUTION:** UNRESOLVED 

---
### Issue 4  
##### HTML special chars  
Some of the text values stored for the resources in the DB, were stored in HTML special chars escaped format. For example, "O'Riley" was stored, retrieved, and shown as "O& #39;Riley"  

**CAUSE:**  UNKNOWN  

**SOLUTION:** UNRESOLVED  

---
## Development Ideas  
Many ideas to further develop the project in the future exist, such as:

- #### More Tests  
Especially, Browser Tests.  
- #### SPA (Single Page Application)  
This whole project can be turned into a SPA which will drastically increase the project's speed, smoothness and performance. 

- #### Authentication  
Login page and mandatory authentication can be implemented to ensure the safety of the archive.  
Currently, the project is aimed towards a controlled environment (e.g. on a local server) book archive rather than a website.  

- #### Enforce CSRF Token Verification for API  
- #### More Custom Made Requests and Validations  
For some routes, the application implements custom made requests; this can be applied to all the requests for all routes to check the incoming request paramteres and validate the datas in more places.
- #### More Middleware for Security  
Middlewares to restrict headers and enfore authentication (as mentioned) can also be a good addition.  
- #### Use SCSS Instead of TailWindCSS  
Since the project was aimed towards a fast GUI development and more focused on the Backend, TailwindCSS was implemented and this can be replaced by using SASS and custom classes.   

---
## Final Word  
The unresolved issues could be traced and resolved by spending more time on the debugging, unfortunately I didn't trace them myself, yet.  
I hope this project meets your criteria!  
Also, regardless of the answer to be positive or negative (about whether I've passed or not), I want to hear Your thoughts on my coding and I would love to get some feedback on how to I improve myself, so, please be kind and send some of Your wisdom towards me!  
Can't wait to hear from You.
