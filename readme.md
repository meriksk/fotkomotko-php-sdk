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
$Api = new \Fotkomotko\Api($options);

// get data from single album
$album = $api->cache(600)->getAlbum(2);
```
## License

Faker is released under the MIT Licence. See the bundled LICENSE file for details.