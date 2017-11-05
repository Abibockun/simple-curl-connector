# Simple CURL PHP Connector

### A  little tool for making CURL requests

## Installation

Simply add a package:

```
composer require abibockun/simple-curl-connector
```

## Usage

Add Namespace:

```
use Abibockun\SimpleCurlConnector\SimpleCurlConnector;
```

Create new Instance and add Settings:
```
$curl = new SimpleCurlConnector();
$curl->setEndPointBaseUrl('YOUR TARGET URL BASE');
```

Optionally you have option to pass Extra headers, example:
```
$curl->setExtraHeaders([
    CURLOPT_HEADER => false,
    CURLOPT_HTTPHEADER => ["Accept: application/json"],
    CURLOPT_SSL_VERIFYPEER => false
]);
```

Make a call to any API or Just URL
```
$results = $curl->send('YOUR URI TO API');
```

Default Method is GET, you can use POST, PATCH, DELETE
List of parameters for "send" function:
```
$requestType = 'GET|POST|PATCH|DELETE', 
$data = [], array of data, supported only one-dimensional arrays
$dataJson = false, Flag in what format your data is.
$returnObject = true, By Default it returns Parsed JSON to an Object.
```
