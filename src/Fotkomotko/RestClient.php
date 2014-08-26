<?php
namespace Fotkomotko;

use \Exception;

/**
 * PHP REST Client
 * https://github.com/tcdent/php-restclient
 * (c) 2013 Travis Dent <tcdent@gmail.com>
 */

class RestClient 
{
	
	public $options;
	public $handle; // cURL resource handle.
	private $requestMethod;
	private $parameters;

	// Populated after execution:
	protected $response; // Response body.
	protected $headers; // Parsed reponse header object.
	protected $info; // Response info object.
	protected $error; // Response error string.

	// Cache 
	protected $cacheTmpLifetime = 0;
	private $cachePath;

	// Digest Auth
	private $digestAlgorithm = 'MD5';
	private $digestNonce = 1;
	private $digestRealm = 'FOTKOMOTKO';
	private $digestQop = 'auth';
	private $digestNonceCount = 1;
	private $digestClientNonce = 1;
	private $digestOpaque = 1;

	/**
	 * Class constructor
	 */
	public function __construct( $options = array() ){

		$default_options = array(
			'headers' => array(), 
			'parameters' => array(), 
			'curl_options' => array(), 
			'user_agent' => 'FotkomotkoClient', 
			'base_url' => NULL, 
			'format' => NULL, 
			'username' => NULL, 
			'password' => NULL,
			'auth_type' => NULL,
			'cache_enabled' => true,
			'cache_lifetime' => 600,
			'cache_path' => 'Cache',
		);
		
		$this->options = array_merge($default_options, $options);
		$this->checkOptions( $default_options );
	}
	
	/**
	 * Check options
	 */
	public function checkOptions($default_options) {
	
		//base url
		if( !empty($this->options['base_url']) ) {
			$this->options['base_url'] = rtrim($this->options['base_url'], '/');
		}

		// cache
		if( $this->options['cache_enabled'] === true ) {
			if( $this->options['cache_lifetime'] ) {
				$this->options['cache_lifetime'] = $default_options['cache_lifetime'];
			}
			
			$this->cachePath = dirname(__FILE__) . DIRECTORY_SEPARATOR . $this->options['cache_path'];
			if( !is_writable($this->cachePath) ) {
				throw new Exception(
					'Directory "'. $this->cachePath .'" (property: cache_path) is not writeable. Check permissions!'
				);
			}
		}

	}

	public function set_option($key, $value){
		$this->options[$key] = $value;
	}
	
	// Request methods:
	public function get( $url, $parameters=array(), $headers=array() ){
		return $this->execute($url, 'GET', $parameters, $headers);
	}
	
	public function post( $url, $parameters=array(), $headers=array() ){
		return $this->execute($url, 'POST', $parameters, $headers);
	}
	
	public function put( $url, $parameters=array(), $headers=array() ){
		return $this->execute($url, 'PUT', $parameters, $headers);
	}
	
	public function delete( $url, $parameters=array(), $headers=array() ){
		return $this->execute($url, 'DELETE', $parameters, $headers);
	}

	public function addHeader( $value ){
		if( !empty($value) ) {
			$this->options['headers'][] = $value;
		}
	}

	/**
	 * Execute request
	 */
	public function execute( $url, $method = 'GET', $parameters = array(), $headers = array() ){

		// parameters
		$parameters = array_merge( $this->options['parameters'], $parameters );

		// set params
		$client = clone $this;
		$client->url = $url;
		$client->requestMethod = strtoupper($method);
		$client->parameters = $parameters;
		
		// check request method
		if( $client->requestMethod !== 'GET' ) { $client->cacheTmpDisable(); }
		
		// get cache
		$response = $client->getCache();
		if( $response !== false ) {
			$this->afterExecute();
			return $response; 
		}

		// CURL
		$client->handle = curl_init();
		$curlopt = array(
			CURLOPT_HEADER => TRUE, 
			CURLOPT_RETURNTRANSFER => TRUE, 
			CURLOPT_USERAGENT => $client->options['user_agent']
		);


		// POST
		if( $client->requestMethod === 'POST' ){
			$curlopt[CURLOPT_POST] = TRUE;
			$curlopt[CURLOPT_POSTFIELDS] = $client->formatQuery($parameters);
		}
		// OTHERS
		elseif( $client->requestMethod !== 'GET' ){
			$curlopt[CURLOPT_CUSTOMREQUEST] = $client->requestMethod;
			$curlopt[CURLOPT_POSTFIELDS] = $client->formatQuery($parameters);
		}
		// GET
		elseif( count($parameters) ){
			$client->url .= strpos($client->url, '?')? '&' : '?';
			$client->url .= $client->formatQuery($parameters);
		}
		
		// set url
		$curlopt[CURLOPT_URL] = $client->options['base_url'] . $client->url;

		// AUTH
		if( !empty($client->options['username']) && !empty($client->options['password']) ) {
			if( $client->options['auth_type'] === \Fotkomotko\Api::AUTH_BASIC ) {
				$curlopt[CURLAUTH_BASIC] = CURLAUTH_BASIC;
				$curlopt[CURLOPT_USERPWD] = sprintf("%s:%s", $client->options['username'], $client->options['password']);
			} else {
				$curlopt[CURLOPT_HTTPAUTH] = CURLAUTH_DIGEST;
				$client->addHeader( $client->formatDigestHeader() );				
			}
		}

		// HEADERS
		if( !empty($client->options['headers']) || !empty($headers) ) {
			$curlopt[CURLOPT_HTTPHEADER] = array();
			$headers = array_merge($client->options['headers'], $headers);
			foreach($headers as $key => $value){
				$curlopt[CURLOPT_HTTPHEADER][] = $value;
			}
		}

		if($client->options['curl_options']){
			// array_merge would reset our numeric keys.
			foreach($client->options['curl_options'] as $key => $value){
				$curlopt[$key] = $value;
			}
		}

		curl_setopt_array($client->handle, $curlopt);
		$client->parseResponse( curl_exec($client->handle) );
		$client->info = (object) curl_getinfo($client->handle);
		$client->error = curl_error($client->handle);		
		curl_close($client->handle);

		// cache
		$client->saveCache();
		
		// after execute
		$this->afterExecute();
		
		// response
		return $client->response;
	}
	
	/**
	 * After execute
	 */
	private function afterExecute()
	{
		// reset tmp cache liftime
		$this->cacheTmpLifetime = 0;
	}
	
	/**
	 * Format query
	 */
	public function formatQuery( $parameters, $primary = '=', $secondary = '&' ) {
		$query = '';		
		foreach( $parameters as $key => $value ){
			
			if( is_array($value) ) {
				$pair = array(urlencode($key), '');
				$query .= implode($primary, $pair);
				$query .= implode(',', $value) . $secondary;
			} else {
				$pair = array(urlencode($key), urlencode($value));
				$query .= implode($primary, $pair) . $secondary;
			}
		}

		return rtrim($query, $secondary);
	}

	/**
	 * Parse response
	 */
	public function parseResponse( $response ) {

		$headers = array();
		$http_ver = strtok($response, "\n");
		
		while( $line = strtok("\n") ) {
			if(strlen(trim($line)) === 0) break;
			list($key, $value) = explode(':', $line, 2);

			$key = trim(strtolower(str_replace('-', '_', $key)));
			$value = trim($value);

			if( empty($headers[$key]) )
				$headers[$key] = $value;
			elseif( is_array($headers[$key]) )
				$headers[$key][] = $value;
			else
				$headers[$key] = array($headers[$key], $value);
		}
		
		$this->headers = (object) $headers;
		$this->response = strtok('');
	}

	/**
	 * Format DIGEST header
	 * @link http://www.sitepoint.com/understanding-http-digest-access-authentication
	 * @link http://en.wikipedia.org/wiki/Digest_access_authentication#Example_with_explanation
	 */
	private function formatDigestHeader() {

		$A1 = md5( $this->options['username'] . ':' . $this->digestRealm . ':' . $this->options['password'] );
		$A2 = md5( $this->requestMethod . ':' . $this->url );
		$response = md5( $A1.':'.$this->digestNonce.':'.$this->digestNonceCount.':'.$this->digestClientNonce.':'.$this->digestQop.':'.$A2 );

		return 'Authorization: Digest username="'. $this->options['username'] .'", realm="'. $this->digestRealm .'", nonce="'. $this->digestNonce .'", uri="'. $this->url .'", qop='. $this->digestQop .', nc='. $this->digestNonceCount .', cnonce="'. $this->digestClientNonce .'", response="'. $response .'", opaque="'. $this->digestOpaque .'"';
	}


	/**
	 * Set temporary cache lifetime (per one request) or disable it
	 */
	public function setCacheLifetime( $lifetime ) {
		$this->cacheTmpLifetime = ($lifetime===false) ? false : (int)$lifetime;
	}

	/**
	 * Returns data from cache
	 */
	private function getCache() {
		if( $this->cacheTmpLifetime!==false && $this->options['cache_enabled'] === true ) {
			
			$key = $this->cacheKey();
			$path = $this->cachePath($key);
			
			if( file_exists($path) ) {
				$lifetime = ($this->cacheTmpLifetime > 0) ? $this->cacheTmpLifetime : $this->options['cache_lifetime'];
				if( (time() - filemtime($path)) <= $lifetime) {
					return unserialize(file_get_contents($path));
				} else {
					$this->deleteCache($key);
				}
			}
		}

		return false;
	}

	/**
	 * Set cache
	 */
	private function saveCache() {
		if( $this->cacheTmpLifetime!==false && $this->options['cache_enabled'] === true ) {
			$path = $this->cachePath($this->cacheKey());
			return file_put_contents($path, serialize($this->response));
		} else {
			return true;
		}
	}

	/**
	 * Delete cache file
	 */
	private function deleteCache($key) {
		if( $this->cacheTmpLifetime!==false && $this->options['cache_enabled'] === true ) {
			$path = $this->cachePath($key);
			if( file_exists($path) ) {
				return unlink($path);
			} else {
				return false;
			}
		} else {
			return true;
		}
	}

	/**
	 * Format unique cache key
	 */
	private function cacheKey() {
		return 
			'fotkomotko_' . 
			md5(
				$this->requestMethod . ':' . 
				( !empty($this->options['base_url']) ? $this->options['base_url'] : '' ) . 
				$this->url . '-' . 
				serialize($this->parameters) . 
				(!empty($this->options['username']) ? '-' . $this->options['username'] : '')
			);
	}

	/**
	 * Get cache path
	 */
	private function cachePath($key) {
		return $this->options['cache_path'] . '/' . $key;
	}

}