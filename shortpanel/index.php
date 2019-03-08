<?php
use Cartalyst\Sentinel\Native\Facades\Sentinel;
require("includes/shortpanel.php");
require_once("includes/authentication.php");

if ($user = Sentinel::check()) {
    checkLogout();
    $currentPage = isset($_GET["p"]) ? (int) $_GET["p"] : 1;
    $panel = new ShortPanel();
    $panel->get_links($currentPage);
} else {
    header("Location: login.php");
}

function checkLogout(){
    if (isset($_POST["method"]) && $_POST["method"] == "logout") {
        Sentinel::logout(null, true);
        header("Location: login.php");
    }
}

?>