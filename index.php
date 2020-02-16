<?php 

require_once("vendor/autoload.php");
require_once("vendor/DB/Sql.php");

$app = new \Slim\Slim();

$app->config('debug', true);

$app->get('/', function() {
    
	$sql = new vendor\DB\Sql();

	$results = $sql->select("SELECT* FROM tb_users");

	echo json_encode($results);
});

$app->run();

 ?>