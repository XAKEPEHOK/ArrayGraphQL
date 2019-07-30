# Convert php-array to GraphQL fields query [![Build Status](https://travis-ci.com/XAKEPEHOK/array-graphql.svg?branch=master)](https://travis-ci.com/XAKEPEHOK/array-graphql)

This library can convert php arrays to GraphQL fields query. It can remove duplicate fields and can thrown exceptions
about incorrect array data 

## Installation
```
composer require xakepehok/array-graphql
```

## Usage
```php
<?php
$fields = [
    'id',
    'id',
    'registeredAt',
    'name' => [
        'firstName',
        'firstName',
        'middleName',
        'lastName',
    ],
    'history' => [
        'count',
        'count',
        'records' => [
            'id',
            'name' => [
                'firstName',
                'middleName',
                'lastName',
            ],
        ]
    ],
];

echo \XAKEPEHOK\ArrayGraphQL\ArrayGraphQL::convert($fields);
```
will print something like
```
{
    id,
    registeredAt,
    name {
        firstName,
        middleName,
        lastName
    },
    history {
        count,
        records {
            id,
            name {
                firstName,
                middleName,
                lastName
            }
        }
    }
}
```