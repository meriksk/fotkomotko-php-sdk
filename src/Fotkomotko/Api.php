<?php
namespace Fotkomotko;

use Fotkomotko\RestClient;
use Fotkomotko\JsonResponse;

class Api 
{

	const AUTH_BASIC = 'basic';
	const AUTH_DIGEST = 'digest';

	// continents
	const AFRICA = 1;
	const ANTARTICA = 2;
	const ASIA = 3;
	const AUSTRALIA = 4;
	const EUROPE = 5;
	const NORTH_AMERICA = 6;
	const SOUTH_AMERICA = 7;

	// visibility
	const VISIBILITY_PUBLIC = 1;
	const VISIBILITY_PROTECTED = 2;
	const VISIBILITY_PRIVATE = 3;

	/**
	 * REST Client class
	 * @var \Fotkomotko\RestClient
	 */
	protected $client;

	/**
	 * Request params
	 * @var array
	 */
	protected $params = [];

	/**
	 * Class constructor
	 */
	public function __construct($options = []) {
		
		// init rest client
		$this->client = new RestClient($options);
		
	}

	/**
	 * Authorize request
	 */
	public function auth($username, $password, $type = self::AUTH_DIGEST) 
	{
		if (!empty($username) && !empty($type)) {
			$this->client->set_option('username', $username);
			$this->client->set_option('password', $password);
			$this->client->set_option('auth_type', $type);
		}
	}

	/**
	 * Set cache lifetime per one request
	 */
	public function cache($lifetime = 300) 
	{
		$this->client->setCacheLifetime($lifetime);
		return $this;
	}

	/**
	 * Merge request params
	 * @param array $params
	 */
	protected function mergeParams($params = [])
	{
		$params = is_array($params) ? $params : [];
		return array_merge_recursive($this->params, $params);
	}

	/**
	 * Before request
	 */
	protected function beforeRequest()
	{
		$this->params = [];
	}


	// -------------------------------------------------------------------------
	// FILTERS
	// -------------------------------------------------------------------------


	/**
	 * Filter response by year of date taken
	 * @param int|array $years
	 * @return \Fotkomotko\Api
	 */
	public function years($years)
	{
		$this->params['years'] = $years;
		return $this;
	}

	/**
	 * Filter response by album ids
	 * @param int|array $albums
	 * @return \Fotkomotko\Api
	 */
	public function albums($albums)
	{
		$this->params['albumId'] = $albums;
		return $this;
	}

	/**
	 * Filter response by continents
	 * @param int|array $continent
	 * @return \Fotkomotko\Api
	 */
	public function continents($continent)
	{
		$this->params['continent'] = $continent;
		return $this;
	}

	/**
	 * Filter response by visibility flag
	 * @param int|array $visibility
	 * @return \Fotkomotko\Api
	 */
	public function visibility($visibility)
	{
		$this->params['visibility'] = $visibility;
		return $this;
	}

	/**
	 * Filter response by tags
	 * @param string|array $years
	 * @return \Fotkomotko\Api
	 */
	public function tags($tags)
	{
		$this->params['tags'] = $tags;
		return $this;
	}

	
	// -------------------------------------------------------------------------
	// ALBUMS
	// -------------------------------------------------------------------------
	

	/**
	 * Get Album
	 */
	public function getAlbum($id, $params = []) 
	{
		$params = $this->mergeParams($params);
		$this->beforeRequest();
		
		if (is_numeric($id)) {
			$url = '/albums/' . intval($id);
		} elseif (is_string($id)) {
			$url = '/albums';
			$params['q'] = $id;
		}

		return new JsonResponse($this->client->get($url, $params));
	}

	/**
	 * Get albums list
	 */
	public function getAlbums($params = []) 
	{
		$params = $this->mergeParams($params);
		$this->beforeRequest();

		return new JsonResponse($this->client->get('/albums', $params));
	}
	

	// -------------------------------------------------------------------------
	// PHOTOS
	// -------------------------------------------------------------------------
	

	/**
	 * Get Photo
	 */
	public function getPhoto($id, $params = []) 
	{
		$params = $this->mergeParams($params);
		$this->beforeRequest();

		return new JsonResponse($this->client->get('/photos/' . intval($id), $params));
	}

	/**
	 * Get photos list
	 */
	public function getPhotos($params = []) 
	{
		$params = $this->mergeParams($params);
		$this->beforeRequest();

		return new JsonResponse($this->client->get('/photos', $this->mergeParams($params)));
	}
	

	// -------------------------------------------------------------------------
	// COLLECTIONS
	// -------------------------------------------------------------------------
	

	/**
	 * Get Photo
	 */
	public function getCollection($id, $params = []) 
	{
		$params = $this->mergeParams($params);
		$this->beforeRequest();

		return new JsonResponse($this->client->get('/collections/' . intval($id), $params));
	}

	/**
	 * Get albums list
	 */
	public function getCollections($params = []) 
	{
		$params = $this->mergeParams($params);
		$this->beforeRequest();

		return new JsonResponse($this->client->get('/collections', $this->mergeParams($params)));
	}

}