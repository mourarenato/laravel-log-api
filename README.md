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

You can see an example of a valid log file with multiple JSON objects in the root of this project (log.txt)

Each JSON object in the log file must be in the following format:

``` 
{
   "request":{
      "method":"GET",
      "uri":"\/",
      "url":"http:\/\/yost.com",
      "size":174,
      "querystring":[
         
      ],
      "headers":{
         "accept":"*\/*",
         "host":"yost.com",
         "user-agent":"curl\/7.37.1"
      }
   },
   "upstream_uri":"\/",
   "response":{
      "status":500,
      "size":878,
      "headers":{
         "Content-Length":"197",
         "via":"gateway\/1.3.0",
         "Connection":"close",
         "access-control-allow-credentials":"true",
         "Content-Type":"application\/json",
         "server":"nginx",
         "access-control-allow-origin":"*"
      }
   },
   "authenticated_entity":{
      "consumer_id":{
         "uuid":"72b34d31-4c14-3bae-9cc6-516a0939c9d6"
      }
   },
   "route":{
      "created_at":1564823899,
      "hosts":"miller.com",
      "id":"0636a119-b7ee-3828-ae83-5f7ebbb99831",
      "methods":[
         "GET",
         "POST",
         "PUT",
         "DELETE",
         "PATCH",
         "OPTIONS",
         "HEAD"
      ],
      "paths":[
         "\/"
      ],
      "preserve_host":false,
      "protocols":[
         "http",
         "https"
      ],
      "regex_priority":0,
      "service":{
         "id":"c3e86413-648a-3552-90c3-b13491ee07d6"
      },
      "strip_path":true,
      "updated_at":1564823899
   },
   "service":{
      "connect_timeout":60000,
      "created_at":1563589483,
      "host":"ritchie.com",
      "id":"c3e86413-648a-3552-90c3-b13491ee07d6",
      "name":"ritchie",
      "path":"\/",
      "port":80,
      "protocol":"http",
      "read_timeout":60000,
      "retries":5,
      "updated_at":1563589483,
      "write_timeout":60000
   },
   "latencies":{
      "proxy":1836,
      "gateway":8,
      "request":1058
   },
   "client_ip":"75.241.168.121",
   "started_at":1566660387
}
```

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
