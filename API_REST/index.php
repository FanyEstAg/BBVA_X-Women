<?php
	require 'vendor/autoload.php';
    $bancosURL = 'https://basebbva-default-rtdb.firebaseio.com/bancos.json';
    $fintechURL = 'https://basebbva-default-rtdb.firebaseio.com/fintech.json';
    $contracargosURL = 'https://basebbva-default-rtdb.firebaseio.com/contracargos.json';

    $bancos=obtenerDatos($bancosURL);
    $bancos=obtenerDatos($fintechURL);

    function obtenerDatos($url){
        $ch =  curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);

        if( curl_errno($ch) ) {
            //echo 'Error: '.curl_errno($ch);
            $resp = array(
                'code'    => 999,
                'message' => 'Ha ocurrido un error inesperado, estamos revisando la falla.',
                'status'  => 'error',
            );
        } else {
            return json_decode($response,true);
        }
    }

    function iniciarSesion($id){
        global $bancos,$fintech;

        if ( array_key_exists($id, $bancos) ) {
            $resp = array(
                'code'    => 200,
                'message' => 'La entidad que busca que busca si existe',
                'status'  => 'success',
                'data' =>json_encode($bancos[$id], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
            );

        }elseif (array_key_exists($id, $fintech)) {
            $resp = array(
                'code'    => 200,
                'message' => 'La entidad que busca que busca si existe',
                'status'  => 'success',
                'data' =>json_encode($fintech[$id], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
            );
        }
        return $resp;
    }

    function registrarAfiliado($param1,$param2,$param3){
        $resp=array();
        $resp['code']=210;
        $resp['message']="Soy el parametro ".$param1." y vengo con los parametros 2: ".$param2." y 3:".$param3;

        return $resp;
    }

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

    //solicitud tipo GET de prueba con parametros
    $app->get("/sesion/{id}",function($request, $response, $args){
        $response->write(json_encode(iniciarSesion($args['id']),JSON_PRETTY_PRINT));
        return $response;
    });

    $app->post("/registroAfiliado",function($request, $response, $args){
        $reqPost = $request->getParsedBody();
        $user = $reqPost["user"];
        $pass = $reqPost["pass"];
        $extra = $reqPost["extra"];
        //regresa la cadena de respuesta en formato JSON
        $response->write( json_encode(registrarAfiliado($user, $pass, $extra),JSON_PRETTY_PRINT));
        return $response;
    });

	$app->run();
?>
?>