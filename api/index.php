<?php

	header("Content-Type: application/json; charset=utf-8");

	require_once 'config/conexao.php';

	$metodo = $_SERVER['REQUEST_METHOD'];
	$uri = explode('/', trim($_SERVER['REQUEST_URI'], '/'));

	$apiIndex = array_search('api', $uri);
	$recurso = $uri[$apiIndex + 1] ?? null;
	$parametro = $uri[$apiIndex + 2] ?? null;

	if (!$recurso){
		http_response_code(400);
		echo json_encode([
			"erro" => "Recurso nao informado"
		]);
		exit;
	}

	switch($recurso){
		case 'pedido':
			require_once 'routes/pedido.php';
			handlePedido($metodo, $parametro, $pdo);
			break;

		case 'motorista':
			require_once 'routes/motorista.php';
			handleMotorista ($metodo, $parametro, $pdo);
			break;

		default:
			http_response_code(404);
			echo json_encode([
				"erro" => "Recurso nao encontrado"
			]);
	}

?>