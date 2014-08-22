<?php
namespace Fotkomotko;
use \Exception;

class JsonResponse {

	private $response;
	private $data = array();
	private $code = 500;
	private $requestMethod = 'GET';
	private $endpoint;
	private $success = false;
	private $message = '';
	private $error = '';

	/**
	 * Class constructor
	 */
	public function __construct($response) {
		$tmp = json_decode($response, true);
		if( $tmp !== NULL ) {
			$this->code = $tmp['code'];
			$this->data = $tmp['data'];
			$this->requestMethod = $tmp['requestMethod'];
			$this->endpoint = $tmp['endpoint'];
			$this->success = $tmp['success'];
			$this->message = $tmp['message'];
			$this->error = isset($tmp['error']) ? $tmp['error'] : null;;
			$tmp = null;
		}
	}

	/**
	 * Returns a property value.
	 * Do not call this method. This is a PHP magic method that we override
	 * to allow using the following syntax to read a property or obtain event handlers:
	 * <pre>
	 * $value=$JsonResponse->propertyName;
	 * </pre>
	 * @param string $name the property name or event name
	 * @return mixed the property value
	 * @throws Exception if the property is not defined
	 * @see __set
	 */
	public function __get($name) {
		$getter = 'get'.ucfirst($name);
		if( method_exists($this,$getter) )
			return $this->{$getter}();
		else 
			throw new Exception('Property "'. get_class($this) .'.'. $name .'" is not defined.');
	}

	/**
	 * Get response data
	 */
	public function getData() {
		return (array)$this->data;
	}

	/**
	 * Get response data
	 */
	public function getCode() {
		return (int)$this->code;
	}

	/**
	 * Get request method
	 */
	public function getRequestMethod() {
		return $this->requestMethod;
	}

	/**
	 * Get endpoint name
	 */
	public function getEndpoint() {
		return $this->endpoint;
	}

	/**
	 * Get response message
	 */
	public function getMessage() {
		return $this->message;
	}

	/**
	 * Get response error message
	 */
	public function getError() {
		return $this->error;
	}

	/**
	 * Get response error messageGet response "success" flag
	 * Alis for {@link JsonResponse::getSuccess}
	 */
	public function getSuccess() {
		if( $this->success===true && ($this->getCode() === 200) )
			return true;
		else 
			return false;
	}
	

}