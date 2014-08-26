<?php
// required files
//include 'vendor/autoload.php';
include 'src/autoload.php';

// Api Options
$options = array(
	'base_url' => 'http://localhost/fotkocms/api',
	'cache_enabled' => false,
	'cache_lifetime' => 300,
	'username' => '',
	'password' => '',
);

// --------------------------------------------------------
// --------------------------------------------------------

// ALBUMS
// Get album data (5min cache)
$api = new \Fotkomotko\Api\Albums($options);
$album = $api
	->cache(300)
	->visibility( \Fotkomotko\Api::VISIBILITY_PUBLIC )
	->continents( \Fotkomotko\Api::EUROPE )
	->getAlbum(2);

	if( $album->success ) {
		echo '<p>Album: <strong>' . $album->data['title'] . '</strong></p>';
	} else {
		echo '<p>Error: <strong>' . $album->code . ': ' . $album->message . '</strong></p>';
	}

// ALBUMS
$params = array('visibility' => \Fotkomotko\Api::VISIBILITY_PUBLIC );
$albums = $api
	->years( array(2004,2015) )
	->getAlbums($params);