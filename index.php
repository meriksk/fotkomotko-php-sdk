<?php
// required files
//include 'vendor/autoload.php';
include 'src/autoload.php';

// Api Options
$options = array(
	'base_url' => 'http://localhost/fotkocms/api',
	'cache_path' => '/Users/merik/www/tmp',
	'cache_enabled' => false,
	'cache_lifetime' => 300,
);

$api = new \Fotkomotko\Api\Albums($options);
$api->auth( 'merik', 'merik', \Fotkomotko\Api::AUTH_DIGEST );

// --------------------------------------------------------
// --------------------------------------------------------

// ALBUMS
// Get album data (5min cache)
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

// PHOTOS
// Get phot data (cache disabled)
$photo = $api->cache(false)->getPhoto(1);

	if( $photo->success ) {
		echo '<p>Photo: <strong>' . $photo->data['filenameOrig'] . '</strong></p>';
	} else {
		echo '<p>Error: <strong>' . $photo->code . ': ' . $photo->message . '</strong></p>';
	}