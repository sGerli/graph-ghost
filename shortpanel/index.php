<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="ui.css"> </head>

<body>
    <?php
    require '../serverconnect.php';
    
    function addVideoToShorthand($short, $link){
        scrapeYouTubeLink($link);
        // If link host contains YouTube, parse YouTube video
        if (strpos(parse_url($link, PHP_URL_HOST), 'youtube')){
            $scrapeData = scrapeYouTubeLink($link);
        }
        else{
            $scrapeData = scrapeOtherLink($link);
            $scrapeData = cleanScrapeData($scrapeData, $link);
        }
        insertToDB($scrapeData, $short, $link);
    }

    function scrapeYouTubeLink($link){
        $videoId = parseVideoIdFromLink($link);
        $data = parseJSON($videoId);
        $scrapeData = scrapeVideo($data);
        return $scrapeData;
    }
    
    function scrapeOtherLink($link){
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
    
    function cleanScrapeData($scrapeData, $link){
        $newScrapeData = array(
            "title" => $scrapeData["og:title"],
            "image" => $scrapeData["og:image"],
            "description" => $scrapeData["og:description"],
        );
        
        // og:image is often a relative link
        // If link does not start with http, it is relative
        if (substr($newScrapeData["image"], 0, 4) !== "http"){
            // Trim the trailing slash
            $link = rtrim($link, "/");
            // Append relative link to absolute link to get full dir
            $newScrapeData["image"] = $link . $newScrapeData["image"];
        }
        return $newScrapeData;
    }
    
    function parseVideoIdFromLink($link){
        $linkParsed = parse_url($link, PHP_URL_QUERY);
        $arr = explode("v=", $linkParsed);
        return $arr[1];
    }

    function parseJSON($videoId){
        global $YouTubeAPIKey;
        $json = file_get_contents("https://www.googleapis.com/youtube/v3/videos?part=snippet&id=$videoId&key=$YouTubeAPIKey");
        return json_decode($json, true);
    }

    function scrapeVideo($data){
        if (isset($data["items"][0]["snippet"]["thumbnails"]["maxres"])){
            $image = $data["items"][0]["snippet"]["thumbnails"]["maxres"]["url"];
        }
        else if (isset($data["items"][0]["snippet"]["thumbnails"]["standard"])){
            $image = $data["items"][0]["snippet"]["thumbnails"]["standard"]["url"];
        }
        else if (isset($data["items"][0]["snippet"]["thumbnails"]["high"])){
            $image = $data["items"][0]["snippet"]["thumbnails"]["high"]["url"];
        }
        else if (isset($data["items"][0]["snippet"]["thumbnails"]["medium"])){
            $image = $data["items"][0]["snippet"]["thumbnails"]["medium"]["url"];
        }
        else{
            $image = $data["items"][0]["snippet"]["thumbnails"]["default"]["url"];
        }
        $arr = array(
            "title" => $data["items"][0]["snippet"]["title"],
            "image" => $image,
            "description" => $data["items"][0]["snippet"]["description"],
        );
        return $arr;
    }

    function insertToDB($scrapeData, $short, $link){
        global $mysql;
        $title = $mysql->escape_string($scrapeData["title"]);
        $image = $mysql->escape_string($scrapeData["image"]);
        $description = $mysql->escape_string($scrapeData["description"]);
        $short = $mysql->escape_string($short);
        $link = $mysql->escape_string($link);
        $query = "INSERT INTO linkTable 
            VALUES (
                '$title',
                '$image',
                '$description',
                '$short',
                '$link'
            )";
        $mysql->query($query);
    }

    function deleteThis($delete){
        global $mysql;
        $mysql->escape_string($delete);
        $query = ("DELETE FROM linkTable where short='$delete'");
        $mysql->query($query);
    }

    if (isset($_POST["link"])){
        $link = $_POST["link"];
        $short = $_POST["short"];
        addVideoToShorthand($short, $link);
        unset($_POST["link"]);
        unset($_POST["short"]);
    }

    if (isset($_POST["delete"])){
        deleteThis($_POST["delete"]);
        unset($_POST["delete"]);
    }

    $query = "SELECT link, short FROM linkTable ORDER BY short";
    $result = $mysql->query($query);
    ?>
        <div class="container">
            <h1>Shortened YouTube Links</h1>
            <form method="post">
                <input type="text" name="link" placeholder="full link">
                <input class="right" type="text" name="short" placeholder="abbreviation">
                <input type="submit"> </form>
            <script>
                function popup(link) {
                    prompt("Copy the selected link", "https://<?php echo $_SERVER['HTTP_HOST'];?>/" + link)
                }
            </script>
            <div class="links">
                <?php
                while ($row = $result->fetch_assoc()){
                    echo "<div><a href='${row['link']}' target='_blank'>${row['link']}</a></div>";
                    $withQuotes = "'${row['short']}'";
                    echo "<div onclick=\"popup($withQuotes)\">${row['short']}</div>";
                }
            ?> </div>
            <form method="post">
                <input type="text" name="delete" placeholder="delete short" class="delete">
                <input type="submit">
            </form>
        </div>
</body>

</html>