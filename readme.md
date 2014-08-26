# Fotkomotko PHP SDK

Fotkomotko Client is a SDK library that allows to fetch data from Fotkomotko Services.

## Basic Usage

Use `new \Fotkomotko\Api($options)` to create and initialize an API client. 
Option **base_url**  is required. The minimal you'll need to have is:

```php
<?php
// require the Faker autoloader
require_once '/path/to/FotkomotkoClient/src/autoload.php';
// alternatively, use composer

$options = array(
	'base_url' => 'http://url-to-fotkomotko/api',
	'username' => 'your_username',
	'password' => 'your_password',
);
$api = new \Fotkomotko\Api($options);
```

With Composer:

Add the `"meriksk/fotkomotko-php-sdk": "@stable"` into the `require` section of your `composer.json`.
Run composer install. The example will look like

```php
if (($loader = require_once __DIR__ . '/vendor/autoload.php') == null)  {
  die('Vendor directory not found, Please run composer install.');
}

$options = array(
	'base_url' => 'http://url-to-fotkomotko/api',
	'username' => 'your_username',
	'password' => 'your_password',
);
$api = new \Fotkomotko\Api($options);
```

## Endpoints

### Albums

```php
// Get single album
$photo = $api->getAlbum(1);

	if( $response->success ) {
		echo '<p>Album: <strong>' . $response->data['title'] . '</strong></p>';
	} else {
		echo '<p>Error: <strong>' . $response->code . ': ' . $response->message . '</strong></p>';
	}

// Get list of albums
$params = array('tags' => 'europe');
$albums = $api
	->visibility( \Fotkomotko\Api::VISIBILITY_PUBLIC )
	->continents( \Fotkomotko\Api::EUROPE )
	->years(2014)
	->getAlbums($params);

	if( $response->success ) {
		foreach( $albums as $album { ... }
	} else {
		echo '<p>Error: <strong>' . $response->code . ': ' . $response->message . '</strong></p>';
	}
```

### Photos

```php

// Get single photo
$photo = $api->getPhoto(1);

	if( $response->success ) {
		echo '<p>Photo: <strong>' . $response->data['title'] . '</strong></p>';
	} else {
		echo '<p>Error: <strong>' . $response->code . ': ' . $response->message . '</strong></p>';
	}

// Get list of photos from a single album
$photos = $api->albums(1)->getAlbums($params);

	if( $response->success ) {
		foreach( $photos as $photo { ... }
	} else {
		echo '<p>Error: <strong>' . $response->code . ': ' . $response->message . '</strong></p>';
	}
```

### Collections

```php
// Get list of collection
$collections = $api->getCollections($params);

	if( $response->success ) {
		foreach( $collections as $collection { ... }
	} else {
		echo '<p>Error: <strong>' . $response->code . ': ' . $response->message . '</strong></p>';
	}


```
