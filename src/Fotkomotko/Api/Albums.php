<?php
namespace Fotkomotko\Api;
use Fotkomotko\JsonResponse;

/**
 * Albums API Class file
 * @link {{\Fotkomotko\Api}}
 */
class Albums extends \Fotkomotko\Api {

	/**
	 * Get Album
	 */
	public function getAlbum($id, $params = array()) {
		$params = $this->mergeParams($params);
		$this->beforeRequest();

		return new JsonResponse( $this->client->get('/albums/' . intval($id), $params) );
	}

	/**
	 * Get Albums
	 */
	public function getAlbums($params = array()) {
		$params = $this->mergeParams($params);
		$this->beforeRequest();

		return new JsonResponse( $this->client->get('/albums', $this->mergeParams($params)) );
	}

}