<?php
// Connects to MySQL Database and creates $mysql object
require '../serverconnect.php';
// IF a link is created THEN
doLinkCreation();
// IF a link is deleted THEN
doLinkDeletion();
// IF a flash exists THEN
doFlashCreation();
// IF an edit is requested THEN
doEditBoxCreation();
// IF an edit is submitted THEN
doEditBoxSubmission();
?>

<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="ui.css">
    <script
  src="https://code.jquery.com/jquery-3.1.1.min.js"
  integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8="
  crossorigin="anonymous"></script>
    <script src="script.js"></script>
</head>

<body>
    <div class="container">
        <h1>Shortened Links</h1>
        <form method="post" id="createForm">
            <input type="text" name="link" placeholder="full link">
            <input class="right" type="text" name="short" placeholder="abbreviation">
            <input type="submit"> </form>
        <div class="links">
            <?php //printLinks(); ?>
        </div>
        <form method="post" id="deleteForm">
            <input type="hidden" name="delete" id="delete"> 
            <input type="submit"> </form>
        <form method="post" id="editForm" class="hidden">
            <input type="text" name="editRequest" id="editField"> </form>
    </div>
</body>

</html>

<?php
/**
    After a form submit for a new link
*/
function doLinkCreation(){
    if (isset($_POST["link"])){
        $link = $_POST["link"];
        $short = $_POST["short"];
        // Add data to database with scraped og - if failed, return false
        if (!addDataToShorthand($short, $link)){
            $_SESSION["flash"] = "Short already exists. Not replacing with new one.";
        }
        $_POST = array();
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

/**
    @param String $short
        The key used to grab full data from Database
        Ex. https://short.com/key

    @param String $link
        The full link
        Ex. https://somesite.com/foo.index

    @return boolean
        False on failed database entry
*/
function addDataToShorthand($short, $link){
    $scrapeData = scrapeLink($link);
    // This scraper returns og:tag instead of tag so we have to clean it
    $scrapeData = cleanScrapeData($scrapeData, $link);
    // insertToDB will fail if a short already exists
    if (!insertToDB($scrapeData, $short, $link)){
        return false;
    }
    return true;
}

/**
    @return array $rmetas
        An array containing the relevant og data
*/
function scrapeLink($link){
    libxml_use_internal_errors(true);
    $doc = new DomDocument();
    $doc->loadHTMLFile($link);
    $xpath = new DOMXPath($doc);
    $query = '//*/meta[starts-with(@property, \'og:\')]';
    $metas = $xpath->query($query);
    $rmetas = array();
    foreach ($metas as $meta) {
        $property = $meta->getAttribute('property');
        $content = $meta->getAttribute('content');
        $rmetas[$property] = $content;
    }
    return $rmetas;
}

/**
    @param array $scrapeData
        An array containing the relevant og data
*/
function cleanScrapeData($scrapeData, $link){
    $newScrapeData = array(
        "title" => $scrapeData["og:title"],
        "image" => $scrapeData["og:image"],
        "description" => $scrapeData["og:description"],
    );

    // og:image is often a relative link
    // If link does not start with http, it is relative
    if (substr($newScrapeData["image"], 0, 4) !== "http"){
        $link = rtrim($link, "/");
        // Append relative link to absolute link to get full url
        $newScrapeData["image"] = $link . $newScrapeData["image"];
    }
    return $newScrapeData;
}

/**
    @param array $scrapeData
        Contains key/data pairs for og:title, image, and description

    @return boolean
        False on failed database entry
*/
function insertToDB($scrapeData, $short, $link){
    global $mysql;
    $query = $mysql->prepare("SELECT short FROM linkTable WHERE short=?");
    $query->bind_param('s', $short);
    $query->execute();
    $query->bind_result($result);
    $query->fetch();
    $query->close();
    // If the short already exists in the database
    if ($result !== NULL){
        return false;
    }
    else{
        $title = $scrapeData["title"];
        $image = $scrapeData["image"];
        $description = $scrapeData["description"];
        $query = $mysql->prepare("INSERT INTO linkTable VALUES (?, ?, ?, ?, ?)");
        $query->bind_param('sssss', $title, $image, $description, $short, $link);
        $query->execute();
        $query->close();
        return true;
    }
}

function showEditBox(){
    global $mysql;
    $short = $_POST["editRequest"];
    $query = $mysql->prepare("SELECT * FROM linkTable WHERE short=?");
    $query->bind_param('s', $short);
    $query->execute();
    $query->bind_result($editTitle, $editImage, $editDescription, $editShort, $editLink);
    $query->fetch();
    $query->close();
    echo "
        <div class='edit-box' id='edit-container'>
            <div class='container'>
                <form method='post' id='newData'>
                    <h2>Title</h2>
                    <input type='text' name='newTitle' value='$editTitle'>
                    <h2>Image Link</h2>
                    <input type='text' name='newImage' value='$editImage'>
                    <h2>Description</h2>
                    <textarea rows='5' name='newDescription'>$editDescription</textarea>
                    <input type='hidden' name='oldShort' value='$short'>
                    <h2>Short</h2>
                    <input type='text' name='newShort' value='$editShort'>
                    <h2>Full Link</h2>
                    <input type='text' name='newLink' value='$editLink'>
                    <input type='submit' value='submit'> </form>
                <button onclick=\"deleteItem('$short')\" id='delete-button'>Delete</button>
                <button onclick='cancel()' id='cancel-button'>Cancel</button>
                <button onclick='submit()'>Submit</button>
            </div>
        </div>
    ";
}

/**
    @param String $delete
        This is the short to be used as a key to delete an entire entry from the database
*/
function deleteThis($delete){
    global $mysql;
    $query = $mysql->prepare("DELETE FROM linkTable WHERE short=?");
    $query->bind_param('s', $delete);
    $query->execute();
    $query->close();
}

/**
    @param String $oldShort
        Preserved old short to be used as key

    @param array $ogs
        Contains key/data pairs for og:title, image, and description
*/
function updateDBEntry($oldShort, $ogs, $short, $link){
    global $mysql;
    $title = $ogs["title"];
    $image = $ogs["image"];
    $description = $ogs["description"];
    $query = $mysql->prepare("UPDATE linkTable SET title=?, image=?, description=?, short=?, link=? WHERE short=?");
    $query->bind_param('ssssss', $title, $image, $description, $short, $link, $oldShort);
    $query->execute();
    $query->close();
}
?>