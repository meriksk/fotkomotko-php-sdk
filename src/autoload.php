<?php

/**
 * You only need this file if you are not using composer.
 * Why are you not using composer?
 * https://getcomposer.org/
 */

if( version_compare(PHP_VERSION, '5.3.0', '<') ) {
	throw new Exception('This library requires PHP version 5.4 or higher.');
}

/**
 * Simple autoloader that follow the PHP Standards Recommendation #0 (PSR-0)
 * @see https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md for more informations.
 *
 * Code inspired from the SplClassLoader RFC
 * @see https://wiki.php.net/rfc/splclassloader#example_implementation
 */
spl_autoload_register(function($className){
	$className = ltrim($className, '\\');
	$fileName  = __DIR__ . DIRECTORY_SEPARATOR	;
	$namespace = '';
	
	if( $lastNsPos = strripos($className, '\\') ) {
		$namespace = substr($className, 0, $lastNsPos);
		$className = substr($className, $lastNsPos + 1);
		$fileName .= str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
	}
	
	$fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';

	if( file_exists($fileName) ) {
		require $fileName;
		return true;
	}
	
	return false;
});
