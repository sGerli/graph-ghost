<?php
require '../../serverconnect.php';
global $mysql;

if (isset($_REQUEST["id"])){
    $id = $_REQUEST["id"];
    $query = $mysql->prepare("SELECT * FROM linkTable WHERE short=?");
    $query->bind_param('s', $id);
    $query->execute();
    $query->bind_result($title, $image, $description, $short, $link);
    $query->fetch();
    $query->close();
    $data = array(
        "title" => $title,
        "image" => $image,
        "description" => $description,
        "short" => $short,
        "link" => $link
    );
    $_REQUEST = array();
    echo json_encode($data);
}
else{
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
}

?>