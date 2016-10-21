<?php
require '../serverconnect.php';

$short = $_GET["val"];

$query = "SELECT title, image, description, link FROM youtube WHERE short='$short'";
$result = $mysql->query($query);
$row = $result->fetch_assoc();
$title = $row['title'];
$image = $row['image'];
$description = $row['description'];
$link = $row['link'];

echo "<!DOCTYPE html><html>";
include_once("../common/analyticstracking.php");
echo "<meta property='og:title' content='$title'>";
echo "<meta property='og:url' content='https://peteryang.io/yt/$short'>";
echo "<meta property='og:image' content='$image'>";
echo "<meta property='og:description' content='$description'>";
echo "<script>location = '$link'</script>";
echo "<noscript>$link</noscript>";
echo "</html>";