<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="ui.css"> </head>

<body>
    <?php
        require '../serverconnect.php';
        function addVideoToShorthand($short, $link){
            $videoId = parseVideoIdFromLink($link);
            $data = parseJSON($videoId);
            $scrapeData = scrapeVideo($data);
            insertToDB($scrapeData, $short, $link);
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
            $arr = array(
                "title" => $data["items"][0]["snippet"]["title"],
                "image" => $data["items"][0]["snippet"]["thumbnails"]["maxres"]["url"],
                "description" => $data["items"][0]["snippet"]["description"],
            );
            return $arr;
        }

        function insertToDB($arr, $short, $link){
            global $mysql;
            $title = $mysql->escape_string($arr["title"]);
            $image = $mysql->escape_string($arr["image"]);
            $description = $mysql->escape_string($arr["description"]);
            $short = $mysql->escape_string($short);
            $link = $mysql->escape_string($link);
            $query = "INSERT INTO youtube 
                VALUES (
                    '$title',
                    '$image',
                    '$description',
                    '$short',
                    '$link'
                )";
            $mysql->query($query);
        }

        if (isset($_POST["link"])){
            $link = $_POST["link"];
            $short = $_POST["short"];
            addVideoToShorthand($short, $link);
            unset($_POST["link"]);
            unset($_POST["short"]);
        }

        $query = "SELECT link, short FROM youtube ORDER BY short";
        $result = $mysql->query($query);
    ?>
    <div class="container">
        <h1>Shortened YouTube Links</h1>
        <form method="post">
            <input type="text" name="link" placeholder="full link">
            <input type="text" name="short" placeholder="abbreviation">
            <input type="submit" value="submit"> </form>
        <script>
            function popup(link){
                prompt("Copy the selected link", "https://peteryang.io/yt/" + link)
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
    </div>
</body>

</html>
