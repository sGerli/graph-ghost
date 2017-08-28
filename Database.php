<?php

require_once($_SERVER["DOCUMENT_ROOT"] . "/secret.php");
class Database
{
    public function __construct() {
        $mysql = new mysqli(SERVERNAME, USERNAME,
            PASSWORD, DATABASE);

        if ($mysql->connect_error || !$mysql->select_db(DATABASE)) {
            echo("Failed to connect to the database");
            $this->db = null;
        }
        else {
            $this->db = $mysql;
        }
    }

    /**
     * @param string $short
     * @return bool success
     */
    public function deleteShortLink(string $short) {
        $query = $this->db->prepare("DELETE FROM short_links WHERE short=?");
        $query->bind_param("s", $short);

        if (!$query || !$query->execute()) return false;

        $query->close();
        return true;
    }

    /**
     * @param array $scrape ["title", "image", "description"]
     * @param string $short
     * @param string $link
     * @return bool success
     */
    public function insertShortLink(array $scrape, string $short, string $link) {
        $query = $this->db->prepare("SELECT short FROM short_links WHERE short=?");
        $query->bind_param("s", $short);

        if (!$query || !$query->execute()) return false;

        $query->bind_result($result);
        $query->fetch();
        $query->close();

        if ($result !== null) return false;
        else {
            $title = $scrape["title"];
            $image = $scrape["image"];
            $description = $scrape["description"];
            $query = $this->db->prepare("INSERT INTO short_links VALUES (?, ?, ?, ?, ?, ?, ?)");
            $pid = null;
            $clicks = 0;
            $query->bind_param("isssssi", $pid, $title, $image, $description, $short, $link, $clicks);

            if (!$query || !$query->execute()) return false;

            $query->close();
            return true;
        }
    }

    /**
     * @param string $short abbreviation used in shortened url
     * @return array|null ["title", "image", "description", "link"]
     */
    public function selectShortLink(string $short) {
        $query = $this->db->prepare("SELECT title, image, description, link FROM short_links WHERE short=?");
        $query->bind_param("s", $short);

        if (!$query || !$query->execute()) return null;

        $query->bind_result($title, $image, $description, $link);
        $query->fetch();
        $query->close();

        return [
            "title" => $title,
            "image" => $image,
            "desc" =>$description,
            "link" => $link
        ];
    }

    /**
     * Used for /shortpanel
     * @return array|null [ link, short ]
     */
    public function selectShortLinks() {
        $data = [];
        $query = $this->db->prepare("SELECT title, image, description, short, link, clicks FROM short_links ORDER BY short");
        if (!$query || !$query->execute()) return null;
        $query->bind_result($title, $img, $desc, $short, $link, $clicks);
        while ($query->fetch()) {
            $data[] = [
                "title" => $title,
                "image" => $img,
                "desc" => $desc,
                "short" => $short,
                "link" => $link,
                "clicks" => $clicks
            ];
        }
        $query->close();

        return $data;
    }

    /**
     * When accessing the shortened link, the click counter will be incremented
     * @param string $short
     * @return bool success
     */
    public function updateClicks(string $short) {
        $query = $this->db->prepare("UPDATE short_links SET clicks=clicks + 1 WHERE short=?");

        if (!$query) return false;

        $query->bind_param("s", $short);

        if (!$query->execute()) return false;

        $query->close();

        return true;
    }

    /**
     * @param string $oldShort
     * @param string $title
     * @param string $image
     * @param string $desc
     * @param string $newShort
     * @param string $link
     * @return bool success
     */
    public function updateShortLink(string $oldShort, string $title, string $image, string $desc, string $newShort, string $link) {
        $query = $this->db->prepare("
        UPDATE short_links
        SET title=?, image=?, description=?, short=?, link=?
        WHERE short=?");

        if (!$query) return false;

        $query->bind_param("ssssss", $title, $image, $desc, $newShort, $link, $oldShort);

        if (!$query->execute()) return false;

        $query->close();

        return true;
    }
}