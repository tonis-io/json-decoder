[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/tonis-io/json-decoder/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/tonis-io/json-decoder/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/tonis-io/json-decoder/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/tonis-io/json-decoder/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/tonis-io/json-decoder/badges/build.png?b=master)](https://scrutinizer-ci.com/g/tonis-io/json-decoder/build-status/master)

# Tonis\JsonDecoder

Tonis\JsonDecoder is simple middleware that json decodes the request body.

Composer
--------

```
composer require tonis-io/json-decoder
```

Usage
-----

```php
$app->add(new \Tonis\JsonDecoder\JsonDecoder())
```
Make sure to add this at the beginning of your app before any routes can process the request.

Configuration
-------------

`Tonis\JsonDecoder\JsonDecoder` optionally takes an array of options.

  * content-types: An array of content types strings that will cause the request body to be json decoded. Default is "application/json".
  * separator: The separator string used for multiple header values. Defaults to a comma.