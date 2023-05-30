<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

# Installation

Get started with Makefile:

1. Run `make fileMode`
2. Certify your `.env` is configured correctly
2. Run `make install`
3. Make sure all containers is up and working
4. Make sure you have the databases created in your mysql database (production and test)
4. Run `make keys`
5. Run `make migrate`

# Project information

This project is a Rest API for processing log files and generating reports. Here PHP with Laravel framework is used.

# Using the project

#### Create a user:

Endpoint(POST): http://10.10.0.22/api/signup

    {
        "email": "myemail@email.com",
        "password": "mypassword",
    }

#### After you must authenticate to get your bearer token:

Endpoint(POST): http://10.10.0.22/api/signin

    {
        "email": "myemail@email.com",
        "password": "mypassword",
    }

Example of response: "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6W3siaWQiOjEsImVtYWlsIjoibXllbWFpbEBlbWFpbC5jb20iLCJwYXNzd29yZCI6IjQ3MGUzNzVhMmMyZjIxZWMyYTVhZDhjNTRiMTliYjc4YzA5YWRmNWFlZGEwODc4ZWY5N2VjNWRjMDRjNWU5MDQifV0sImlhdCI6MTY0NTg0MzAwOSwiZXhwIjoxNjQ1OTI5NDA5fQ.cESM6lySYrtmMXXTvjXuNd1lrKfM0eqzXND6eHPfkJg"

#### Now you are logged and can access others endpoints:


- `To process log file`:

1. Run `php artisan queue:work"` in your php container
2. Place your file in the desired path
3. Send a request (POST) with your bearer token to the URL http://10.10.0.22/api/logs/process specifying the path

Example of request:
 
    {
	    "log_path": "public/files/logs.txt"
    }


- `To export the report of requests by consumer`:

1. Run `php artisan queue:work"` in your php container
2. Send a request (POST) with your bearer token to the URL http://10.10.0.22/api/logs/export/requestsByConsumer specifying the path

Example of request:

    {
	    "log_path": "public/files/logs.txt"
    }

Obs: The file is available in `public/files`


- `To export the report of requests by service`:

1. Run `php artisan queue:work"` in your php container
2. Send a request (POST) with your bearer token to the URL http://10.10.0.22/api/logs/export/requestsByService specifying the path

Example of request:

    {
	    "log_path": "public/files/logs.txt"
    }

Obs: The file is available in `public/files`


- `To export the report of average time by service`:

1. Run `php artisan queue:work"` in your php container
2. Send a request (POST) with your bearer token to the URL http://10.10.0.22/api/logs/export/averageTime specifying the path

Example of request:

    {
	    "log_path": "public/files/logs.txt"
    }

Obs: The file is available in `public/files`


## Running unit tests

- Run `make run-tests` to run all tests
- Run `make run-coverage` to run all tests and to generate coverage report
