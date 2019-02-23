<?php
use Cartalyst\Sentinel\Native\Facades\Sentinel;
require_once("includes/authentication.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/vendor/autoload.php");
checkCredentials();

if ($user = Sentinel::check()) {
    header("Location: .");
    exit;
} else {
    $loader = new Twig_Loader_Filesystem($_SERVER["DOCUMENT_ROOT"] . "/templates/shortpanel");
    $twig = new Twig_Environment($loader);
    echo $twig->render("/login.html");
}

function checkCredentials(){
    global $auth;
    if (isset($_POST["username"])){
        $credentials = [
            "login"    => $_POST["username"],
            "password" => $_POST["password"]
        ];
        if (Sentinel::authenticate($credentials) != false) {
            header("Location: .");
            exit;
        }
    }
}

?>