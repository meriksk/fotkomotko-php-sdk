<?php
namespace Fotkomotko\Api;
use Fotkomotko\JsonResponse;

/**
 * Photos API Class file
 * @link {{\Fotkomotko\Api}}
 */
class Photos extends \Fotkomotko\Api {
	
	/**
	 * Get Album
	 */
	public function getPhoto($id, $params = array()) {
		$params = $this->mergeParams($params);
		$this->beforeRequest();

		return new JsonResponse( $this->client->get('/photos/' . intval($id), $params) );
	}

	/**
	 * Get Albums
	 */
	public function getAlbums($params = array()) {
		$params = $this->mergeParams($params);
		$this->beforeRequest();

		return new JsonResponse( $this->client->get('/photos', $this->mergeParams($params)) );
	}

}