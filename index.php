<?php
// required files
//include 'vendor/autoload.php';
include 'src/autoload.php';

// Api Options
$options = array(
	'base_url' => 'http://localhost/fotkocms/api',
	'username' => '',
	'password' => '',
);

// --------------------------------------------------------
// --------------------------------------------------------

// API
$api = new \Fotkomotko\Api($options);