<?php

spl_autoload_register(function($className){
	
	$className = ltrim($className, '\\');
	$className = str_replace('\\', DIRECTORY_SEPARATOR, $className);
	//var_dump($className);

	require_once __DIR__ . DIRECTORY_SEPARATOR . $className . '.php';
});

class_alias('\Matthewshafer\Aerodynamic\Aerodynamic', 'Aerodynamic');
?>