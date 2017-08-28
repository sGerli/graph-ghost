<?php
require("Database.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/vendor/autoload.php");
session_start();

class LinkShortener
{
    function __construct()
    {
        $this->db = new Database();
        $loader = new Twig_Loader_Filesystem($_SERVER["DOCUMENT_ROOT"] . "/templates");
        $this->twig = new Twig_Environment($loader);
    }

    /**
     * Displays all links from db as the main panel
     */
    public function get_links() {
        $links = $this->db->selectShortLinks();

        $data = [
            "links" => $links
        ];

        if (isset($_SESSION["flash"]) && $_SESSION["flash"]) {
            $data = array_merge(
                $data,
                ["flash" => $_SESSION["flash"]]
            );
        }

        echo $this->twig->render("shortpanel.html", $data);
    }

    /**
     * @param string $link full url from the http part
     * @param string $short just the term to come at the end after /
     */
    public function post_addLink(string $link, string $short) {
        $scrapeData = self::scrapeLink($link);
        $scrapeData = self::cleanScrapeData($scrapeData, $link);
        echo "scrape data";
        echo json_encode($scrapeData);
        if ($this->db->insertShortLink($scrapeData, $short, $link))
            $message = "Link added successfully";
        else
            $message = "An error occurred and the link was not added";

        self::redirect($message);
    }

    /**
     * @param string $short
     */
    public function post_delete(string $short) {
        if ($this->db->deleteShortLink($short))
            $message = "Link deleted successfully";
        else
            $message = "An error occurred and the link was not deleted";

        self::redirect($message);
    }

    /**
     * @param string $short
     */
    public function post_showEdit(string $short) {
        $edit = $this->db->selectShortLink($short);

        $edit = array_merge(
            $edit, // ["title", "image", "desc", "link"]
            ["short" => $short]
        );

        $data = ["edit" => $edit];

        echo $this->twig->render("shortpanel.html", $data);
    }

    /**
     * @param string $title
     * @param string $image
     * @param string $desc
     * @param string $oldShort -> used as key to look up in table
     * @param string $newShort
     * @param string $link
     */
    public function post_updateLink(string $title, string $image, string $desc,
                                    string $oldShort, string $newShort, string $link) {

        if ($this->db->updateShortLink($oldShort, $title, $image, $desc, $newShort, $link))
            $message = "Link updated successfully";
        else
            $message = "An error occurred and the link was not updated";

        self::redirect($message);
    }

    /**
     * START PRIVATE FUNCTIONS
     */

    /**
     * @param string $link
     * @return array containing og data
     */
    private static function scrapeLink(string $link){
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
     * NOTE[1] -> Will convert relative image links to absolute image links
     *
     * @param array $scrapeData
     * @param string $link
     * @return array [$title, $image, $description]
     */
    private static function cleanScrapeData(array $scrapeData, string $link){
        $newScrapeData = [
            "title" => $scrapeData["og:title"],
            "image" => $scrapeData["og:image"],
            "description" => $scrapeData["og:description"]
        ];

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
     * Redirects with a flash message
     * @param string $message
     */
    private static function redirect(string $message) {
        $_SESSION["flash"] = $message;
        header("Location: " . $_SERVER["REQUEST_URI"]);
    }
}