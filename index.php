<?php 

require_once("vendor/autoload.php");
require_once("vendor/DB/Sql.php");
require_once("vendor/Page.php");
require_once("vendor/PageAdmin.php");

$app = new \Slim\Slim();

$app->config('debug', true);

$app->get('/', function() {
    
	// $sql = new vendor\DB\Sql();

	// $results = $sql->select("SELECT* FROM tb_users");

	// echo json_encode($results);

	$page = new Hcode\Page();

	$page->setTpl("index");
	
});


$app->get('/admin', function() {
    
	$page = new HcodeAdmin\PageAdmin();

	$page->setTpl("index");
	
});

$app->run();



 ?>