<?php
require("includes/shortsubmitter.php");

$submitter = new ShortSubmitter();

if (isset($_POST["link"])){
    $link = $_POST["link"];
    $short = $_POST["short"];
    $submitter->post_addLink($link, $short);
} else {
    header("Location: .");
    exit;
}
?>