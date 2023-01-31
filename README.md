# technical-backend-test
Technical Backend Test for Smart Point

## Documentation

A simple api simulating a registry using CQRS implemented with Symfony 6

For json OpenApi doc access to http://api.techtest.local/api/doc.json

## Use Cases

#### Items
- [x] Get item  [GET] http://api.techtest.local/items/{value}
- [x] Add item  [POST] http://api.techtest.local/items with json body containing an object with a *value* property
- [x] Delete Item [DELETE] http://api.techtest.local/items/{value}
- [x] Compare Set [GET] http://api.techtest.local/items/compare?values= {comma separated list of values}
- [x] Invert Get item response [POST] http://api.techtest.local/items/invert/toggle


## Project Setup

You need docker and docker-compose to run the project
Add api.techtest.local pointing to your localhost in your hosts file
To set up execute:
    
    #> make up
    #> make api-vendors-install

| Action        	                   |     Command    |
|-----------------------------------|---------------	|
| Setup 	                           | `make up`   |
| Run Tests       	                 | `make api-tests` |
| Api vendors install       	 | `make api-vendors-install` |
| PHP Bash 	                        | `make api-bash`|
