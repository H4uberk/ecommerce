<?php 

session_start();
require_once("vendor/autoload.php");
require_once("vendor/DB/Sql.php");
require_once("vendor/Page.php");
require_once("vendor/PageAdmin.php");
require_once("vendor/Model/User.php");

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

	// User::verifyLogin();
    
	$page = new HcodeAdmin\PageAdmin();

	$page->setTpl("index");
	
});


$app->get('/admin/login', function() {
    
	$page = new HcodeAdmin\PageAdmin([
		"header"=>false,
		"footer"=>false
	]);

	$page->setTpl("login");
	
});

$app->post('/admin/login', function() {

	User::login($_POST["login"],$_POST["password"]);

	header("Location: /admin");
	exit;
});


$app->get('/admin/logout', function() {

	User::logout();

	header("Location: /admin/login");
	exit;
});

$app->run();


class User{

	private $values = [];
	
    public function __call($name, $args) {
		
		$method = substr($name,0,3);
        $fieldName = substr($name, 3, strlen($name));
		
		switch ($method){
			case "get":
				return $this->values[$fieldName];
			break;
			case "set":
				$this->value[$fieldName] = $args[0];
			break;
		}
	}
	
	public function setData($data = array()){
		foreach ($data as $key => $value) {
			$this->{"set".$key}($value);
		}
	}
	
	public function getValues(){
		return $this->values;
	}
	
	
	
	const SESSION = "User";
    public static function login($login, $password) {

        $sql = new vendor\DB\Sql();

        $results = $sql->select("SELECT * FROM tb_users WHERE deslogin = :LOGIN", array(
            ":LOGIN"=>$login
        ));

        if(count($results) === 0) {

            throw new \Exception("Usuario fail", 1);
            
        }

        $data = $results[0];

        if(password_verify($password, $data["despassword"]) === true) {

            $user = new User();

			$user->setData($data);

			$SESSION[User::SESSION] = $user->getValues();
			
			return $user;

        } else {

            throw new \Exception("Usuario fail", 1);
        }
	}
	
	public static function verifyLogin($inadmin = true){
		if(!isset($_SESSION[User::SESSION]) || !$_SESSION[User::SESSION] || !(int)$_SESSION[User::SESSION]["iduser"] > 0 || (bool)$_SESSION[User::SESSION]["inadmin"] !== $inadmin)
		{
			header("Location: /admin/login");
			exit;
		}
	}

	public static function logout(){
		$_SESSION[User::SESSION] =  NULL;
	}

}



 ?>