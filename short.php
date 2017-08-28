<?php
require("Database.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/vendor/autoload.php");

/**
 * server block says
 *      rewrite ^(.*)$ /short.php?val=$1
 * on files that do not exist, thus pointing to this routing file
 * val is used as the main key for matching to full link and data in database
 */
$db = new Database();
$short = substr($_GET["val"], 1);
$data = $db->selectShortLink($short);
$db->updateClicks($short);

if ($data["link"]) {
    $title = htmlspecialchars($data["title"], ENT_QUOTES);
    $image = htmlspecialchars($data["image"], ENT_QUOTES);
    $description = htmlspecialchars($data["description"], ENT_QUOTES);
    $link = htmlspecialchars($data["link"], ENT_QUOTES);
}

$loader = new Twig_Loader_Filesystem($_SERVER["DOCUMENT_ROOT"] . "/templates");
$twig = new Twig_Environment($loader);

$data = [
    "link" => isset($link) ? $link : false,
    "title" => isset($title) ? $title : null,
    "url" => $_SERVER["HTTP_HOST"] . "/$short",
    "image" => isset($image) ? $image : null,
    "description" => isset($description) ? $description : null,
    "error" => "/404.php"
];

echo $twig->render("short.html", $data);