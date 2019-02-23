<?php
require("includes/shortpanel.php");

$panel = new ShortPanel();

// IF a link is created THEN
doLinkCreation();

// IF a link is deleted THEN
/*doLinkDeletion();
// IF a flash exists THEN
doFlashCreation();
// IF an edit is requested THEN
doEditBoxCreation();
// IF an edit is submitted THEN
doEditBoxSubmission();*/

$panel->get_links();

/**
    After a form submit for a new link
*/
function doLinkCreation(){
    global $panel;
    if (isset($_POST["link"])){
        $link = $_POST["link"];
        $short = $_POST["short"];
        $panel->post_addLink($link, $short);
        unset($_POST["link"]);
        unset($_POST["short"]);
    }
}
/**
    After a form submit to delete a preexisting link
*/
function doLinkDeletion(){
    if (isset($_POST["delete"])){
        deleteThis($_POST["delete"]);
        $_POST = array();
    }
}
/**
    Prints a flash message if one exists
*/
function doFlashCreation(){
    if (isset($_SESSION['flash'])){
        echo "<div id='flash'><h2>${_SESSION['flash']}</h2><h3>Click to dismiss</h3></div>";
        unset($_SESSION['flash']);
    }
}
/**
    If an edit is requested, the edit panel is displayed
*/
function doEditBoxCreation(){
    if (isset($_POST["editRequest"])){
        showEditBox();
        $_POST = array();
    }
}
/**
    If an edit is submitted, the database is updated
*/
function doEditBoxSubmission(){
    if (isset($_POST["newTitle"])){
        $ogs = array(
            "title" => $_POST["newTitle"],
            "image" => $_POST["newImage"],
            "description" => $_POST["newDescription"],
        );
        $oldShort = $_POST["oldShort"];
        $newShort = $_POST["newShort"];
        $newLink = $_POST["newLink"];
        updateDBEntry($oldShort, $ogs, $newShort, $newLink);
        $_POST = array();
    }
}

?>