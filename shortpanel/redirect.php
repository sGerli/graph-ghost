<?php
use Cartalyst\Sentinel\Native\Facades\Sentinel;
require("includes/shortsubmitter.php");
require_once("includes/authentication.php");

if ($user = Sentinel::check()) {
    $submitter = new ShortSubmitter();
    if (isset($_POST["link"])){
        $link = $_POST["link"];
        $short = $_POST["short"];
        $submitter->post_addLink($link, $short);
    } else {
        header("Location: .");
        exit;
    }
} else {
    header("Location: login.php");
}

?>