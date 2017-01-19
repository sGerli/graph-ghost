<?php
require '../../serverconnect.php';
global $mysql;

// Requesting a specific entry with a "short" tag
if (isset($_REQUEST["q"])){
    $id = $_REQUEST["q"];
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

// Grabbing all entries in the database
else{
    $query = $mysql->prepare("SELECT title, image, description, short, link, clicks FROM linkTable ORDER BY short");
    $query->execute();
    $query->bind_result($title, $image, $description, $short, $link, $clicks);
    $data = array();
    while ($row = $query->fetch()){
        $data[] = array(
            "title" => $title,
            "image" => $image,
            "description" => $description,
            "short" => $short,
            "link" => $link,
            "clicks" => $clicks
        );
    }
    echo json_encode($data);
}

?>