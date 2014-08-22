# Fotkomotko Client

Fotkomotko Client is a SDK library that allows to fetch data from Fotkomotko Services.

## Basic Usage

Use `Faker\Factory::create()` to create and initialize a faker generator, which can generate data by accessing properties named after the type of data you want.

```php
<?php
// require the Faker autoloader
require_once '/path/to/FotkomotkoClient/src/autoload.php';
// alternatively, use composer

// use the factory to create a Faker\Generator instance
$options = array(
	'base_url' => 'http://url-to-fotkomotko/api',
	'cache_path' => '/tmp',
	'cache_lifetime' => 300,	
);
$api = new \Fotkomotko\Api\Albums($options);
$api->auth( 'username', 'password', \Fotkomotko\Api::AUTH_DIGEST );

// --------------------------------------------------------
// --------------------------------------------------------

// ALBUMS
// Get list of albums (cache 5 minutes)

// passed params
$params = array();

// params as function
$response = $api
	->cache(300)
	->visibility( \Fotkomotko\Api::VISIBILITY_PUBLIC )
	->continents( \Fotkomotko\Api::EUROPE )
	->years(2014)
	->getAlbums($params);

	if( $response->success ) {
		echo '<p>Album: <strong>' . $response->data['title'] . '</strong></p>';
	} else {
		echo '<p>Error: <strong>' . $response->code . ': ' . $response->message . '</strong></p>';
	}
```
## License

Faker is released under the MIT Licence. See the bundled LICENSE file for details.