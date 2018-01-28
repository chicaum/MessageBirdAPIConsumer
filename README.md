# MessageBird API Consumer

Small project intended to consume MessageBird API

Since the project goal is to consume the MessageBird API and not create a new draft API, some points have been left open: route validation, container for the application, etc.

### Prerequisites

PHP 7.1 or higher
```
php -v
PHP 7.1.13 (cli) (built: Jan  5 2018 15:30:29) ( NTS )
```

Redis
```
redis-server --version
Redis server v=4.0.7 sha=00000000:0 malloc=libc bits=64 build=8f3d1effb8103d27
```

Composer
```
composer --version
Composer version 1.4.1 2017-03-10 09:29:45
```

## Installing

After cloning the project install the dependencies

```
composer install
```

## Set up environment variables

Copy the .env.dist file to a new file .env 
and fill the required info:
```
ENVIRONMENT=[TEST,LIVE]
MESSAGE_BIRD_APP_KEY_LIVE=add_your_key
MESSAGE_BIRD_APP_KEY_TEST=add_your_key
REDIS_SCHEME=add_redis_scheme e.g. "tcp"
REDIS_HOST=add_redis_host
REDIS_PORT=add_redis_port
REDIS_PASSWORD=add_redis_password
```

## Running the tests
```
vendor/bin/phpunit
PHPUnit 6.5.5 by Sebastian Bergmann and contributors.

.....                                                               5 / 5 (100%)

Time: 280 ms, Memory: 4.00MB

OK (5 tests, 9 assertions)
```

## Running locally
Inside root dir
```
php -S localhost:8000 -t public_html
```
## Making a request
Post a message
```
curl -X POST \
  http://localhost:8000 \
  -H 'Cache-Control: no-cache' \
  -H 'Content-Type: application/json' \
  -d '{
 "recipient":499374398172,
 "originator":"MessageBird",
 "message":"This is a test message"
}'
```

Post a Unicode message 
```
curl -X POST \
  http://localhost:8000 \
  -H 'Cache-Control: no-cache' \
  -H 'Content-Type: application/json' \
  -d '{
 "recipient":499374398172,
 "originator":"MessageBird",
 "message":"This is a test message with a smiling emoji 😀 "
}'
```

## Authors

***Ademir Silva** - *Initial work*


