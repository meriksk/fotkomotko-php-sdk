<?php
namespace Fotkomotko;

use Fotkomotko\RestClient;
use Fotkomotko\JsonResponse;

class Api {

	const AUTH_BASIC = 'basic';
	const AUTH_DIGEST = 'digest';

	/**
	 * REST Client class
	 */
	private $client;

	/**
	 * Class constructor
	 */
	public function __construct( $options = array() ) {

		// init rest client
		$this->client = new RestClient($options);

	}

    /**
     * Authorize request
     */
    public function auth( $username, $password, $type = self::AUTH_DIGEST ) {
        if( !empty($username) && !empty($type) ) {
            $this->client->set_option('username', $username);
            $this->client->set_option('password', $password);
            $this->client->set_option('auth_type', $type);
        }
    }

    /**
     * Set cache lifetime per one request
     */
    public function cache( $lifetime = 300 ) {
    	$this->client->setCacheLifetime($lifetime);
    	return $this;
    }

    // ------------------------------------------------------------------------
    //
    // ALBUMS
    //
    // ------------------------------------------------------------------------

	/**
	 * Get Album
	 */
	public function getAlbum($id, $params = array()) {
		return new JsonResponse( $this->client->get('/albums/' . intval($id), $params) );
	}

	/**
	 * Get Albums
	 */
	public function getAlbums($params = array()) {
		return new JsonResponse( $this->client->get('/albums', $params) );
	}

    // ------------------------------------------------------------------------
    //
    // PHOTOS
    //
    // ------------------------------------------------------------------------

	/**
	 * Get Album
	 */
	public function getPhoto($id, $params = array()) {
		return new JsonResponse( $this->client->get('/photos/' . intval($id), $params) );
	}

	/**
	 * Get Albums
	 */
	public function getPhotos($params = array()) {
		return new JsonResponse( $this->client->get('/photos', $params) );
	}


}