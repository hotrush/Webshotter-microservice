[![Build Status](https://img.shields.io/travis/hotrush/Webshotter-microservice/master.svg?style=flat-square)](https://travis-ci.org/hotrush/Webshotter)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)

# Webshotter microservice

Simpe RESTful microservice with Lumen and [hotrush/Webshotter](https://github.com/hotrush/Webshotter) package that creates pages screenshots and store it locally / s3 / rackspace (last one TBD)

## Installation

```
git clone git@github.com:hotrush/Webshotter-microservice.git
cd Webshotter-microservice
cp .env.example .env
composer install
```

**note** - .env file has all required config options, take a look at ACCESS_KEY that used for api authentication. Also here you can configure destination storage - local, s3 or rackspace 

## Usage

Ping-pong

```GET /api/ping```

Webshot creating
```
POST /api/webshot?key=YOUR_ACCESS_KEY
{
    "url": "https://github.com", // required
    "extension": "jpg", // optional, can be jpg, png or pdf, jpg default 
    "width": 1200, // optional, integer, default 1200
    "height": 800, // optional, integer, default 800
    "full_page": 0, // optional, 1 or 0
    "filename": "test", // optional, result file name without extension, alpha_dash
    "path": "2016/01/01" // optional, path to save result file
}
```
And this will return next:
```
{
    "path": "2016/01/01/test.jpg",
    "url": "http://YOUR_SITE/webshots/2016/01/01/test.jpg" // or for example for s3 https://s3-eu-west-1.amazonaws.com/your-bucket/2016/01/01/test.jpg
}
```

## Contributing

Feel free to post found bugs or make prs.

## Testing

```php ./vendor/bin/phpunit```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
