<?php
	require_once(dirname(__DIR__) . '/Bootstrap/bootstrap.php');
	
	define('ROOT', dirname(__DIR__) . DIRECTORY_SEPARATOR);
	// set a constant that holds the project's "application" folder, like "/var/www/application".
	define('APP_PATH', ROOT . 'app' . DIRECTORY_SEPARATOR);
	//echo constant()
	$config = require_once(dirname(__DIR__).'/config/main.php');
	$app = new App($config);
	$app->run();

	
?>