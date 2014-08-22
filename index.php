<?php
// required files
//include 'vendor/autoload.php';
include 'src/autoload.php';

// Api Options
$options = array(
	'base_url' => 'http://localhost/fotkocms/api',
	'cache_path' => '/Users/merik/www/tmp',
	'cache_lifetime' => 300,
);

$api = new \Fotkomotko\Api($options);
$api->auth( 'merik', 'merik', \Fotkomotko\Api::AUTH_DIGEST );

// --------------------------------------------------------
// --------------------------------------------------------

// ALBUMS
// Get album data (5min cache)
$album = $api->cache(600)->getAlbum(2);

	if( $album->success ) {
		echo '<p>Album: ' . $album->data['title'] . '</p>';
	} else {
		echo '<p>Error:' . $album->code . ': ' . $album->message . '</p>';
	}

// PHOTOS
// Get phot data (cache disabled)
$photo = $api->cache(false)->getPhoto(695);

	if( $photo->success ) {
		echo '<p>Photo: ' . $photo->data['filenameOrig'] . '</p>';
	} else {
		echo '<p>Error:' . $photo->code . ': ' . $photo->message . '</p>';
	}