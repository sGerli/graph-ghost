<?php
require '../../serverconnect.php';
global $mysql;
// Prints by shorts in alphabetical order
$query = $mysql->prepare("SELECT title, image, description, short, link FROM linkTable ORDER BY short");
$query->execute();
$query->bind_result($title, $image, $description, $short, $link);
$data = array();
while ($row = $query->fetch()){
    $data[] = array(
        "title" => $title,
        "image" => $image,
        "description" => $description,
        "short" => $short,
        "link" => $link
    );
}
echo json_encode($data);
?>