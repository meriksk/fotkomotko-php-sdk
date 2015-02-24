<?php
// composer
require_once '../autoload.php';

// api
$api = new \Fotkomotko\Api (array(
	'base_url' => '',
	'username' => '',
	'password' => '',
));

// Get single album (find by Id)
$recentPhotos = $api->getPhotos(array(
	'limit' => 10,
	'desc' => true
));

print_r($recentPhotos);