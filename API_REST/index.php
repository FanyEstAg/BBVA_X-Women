<?php
	require 'vendor/autoload.php';

    //objeto de SLim Framework
	$app = new Slim\App();

    //solicitud tipo GET de prueba
	$app->get('/',function ($request,$response,$args) {
    	$response->write("Hola mundo!");
    	return $response;
	});

    //solicitud tipo GET de prueba con parametros
	$app->get("/hola/{nombre}",function($request, $response, $args){
    	$response->write("Hola ". $args["nombre"].", soy la respuesta de una API REST en PHP");
    	return $response;
	});

	$app->run();
?>