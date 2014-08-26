<?php
namespace Fotkomotko;
use Fotkomotko\RestClient;

class Api {

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
	protected $params = array();

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

	/**
	 * Merge request params
	 * @param array $params
	 */
	protected function mergeParams( $params = array() )
	{
		$params = is_array($params) ? $params : array();
		return array_merge_recursive( $this->params, $params );
	}

	/**
	 * Before request
	 */
	protected function beforeRequest()
	{
		$this->params = array();
	}

	// -------------------------------------------------------------------------
	// FILTERS
	// -------------------------------------------------------------------------

	/**
	 * Filter response by year of date taken
	 * @param integer|array $years
	 * @return \Fotkomotko\Api
	 */
	public function years( $years )
	{
		$this->params['years'] = $years;
		return $this;
	}

	/**
	 * Filter response by album ids
	 * @param integer|array $albums
	 * @return \Fotkomotko\Api
	 */
	public function albums( $albums )
	{
		$this->params['albumId'] = $albums;
		return $this;
	}

	/**
	 * Filter response by continents
	 * @param integer|array $continent
	 * @return \Fotkomotko\Api
	 */
	public function continents( $continent )
	{
		$this->params['continent'] = $continent;
		return $this;
	}

	/**
	 * Filter response by visibility flag
	 * @param integer|array $visibility
	 * @return \Fotkomotko\Api
	 */
	public function visibility( $visibility )
	{
		$this->params['visibility'] = $visibility;
		return $this;
	}

	/**
	 * Filter response by tags
	 * @param string|array $years
	 * @return \Fotkomotko\Api
	 */
	public function tags( $tags )
	{
		$this->params['tags'] = $tags;
		return $this;
	}

}