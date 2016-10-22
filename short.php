<?php
require 'serverconnect.php';

function parseVideoIdFromLink($link){
    $linkParsed = parse_url($link, PHP_URL_QUERY);
    $arr = explode("v=", $linkParsed);
    return $arr[1];
}

$short = $mysql->escape_string($_GET["val"]);

$query = "SELECT title, image, description, link FROM linkTable WHERE short='$short'";
$result = $mysql->query($query);
if (!$result->num_rows == 0){
    $row = $result->fetch_assoc();
    $title = htmlspecialchars($row['title'], ENT_QUOTES);
    $image = htmlspecialchars($row['image'], ENT_QUOTES);
    $description = htmlspecialchars($row['description'], ENT_QUOTES);
    $link = htmlspecialchars($row['link'], ENT_QUOTES);
    $isYouTube = false;
    if (strpos(parse_url($link, PHP_URL_HOST), 'youtube')){
        $videoId = parseVideoIdFromLink($link);
        $isYouTube = true;
    }
    echo "<!DOCTYPE html><html>";
    
    //Google analytics tracker
    include_once("common/analyticstracking.php");
    echo "<meta property='og:title' content='$title'>";
    echo "<meta property='og:url' content='https://${_SERVER['HTTP_HOST']}/$short'>";
    echo "<meta property='og:image' content='$image'>";
    echo "<meta property='og:description' content='$description'>";
    if ($isYouTube){
        echo "<meta property='og:video' content='https://youtube.com/v/$videoId&autoplay=1'";
    }
    echo "<script>location = '$link'</script>";
    echo "<noscript><a href='$link'>Click here to go to manually go to link</a>Please enable JavaScript to automatically proceed to link</noscript>";
    echo "</html>";
}
else{
    echo "<script>location='https://${_SERVER['HTTP_HOST']}/404.php'</script>";
}