<?php
require($_SERVER["DOCUMENT_ROOT"] . "/includes/database.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/vendor/autoload.php");
session_start();

class ShortPanel
{
    function __construct()
    {
        $this->db = new Database();
        $loader = new Twig_Loader_Filesystem($_SERVER["DOCUMENT_ROOT"] . "/templates/shortpanel");
        $this->twig = new Twig_Environment($loader);
    }

    /**
     * Displays all links from db as the main panel
     */
    public function get_links(int $currentPage) {
        $links = $this->db->selectShortLinks($currentPage - 1);
        $pageCount = $this->db->getPageCount();
        $url = 'https://' . $_SERVER['HTTP_HOST'] . parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

        $data = [
            "links" => $links,
            "pageCount" => $pageCount + 1,
            "currentPage" => $currentPage,
            "pagePath" => $url
        ];

        if (isset($_SESSION["flash"]) && $_SESSION["flash"]) {
            $data = array_merge(
                $data,
                ["flash" => $_SESSION["flash"]]
            );
            unset($_SESSION['flash']);
        }

        echo $this->twig->render("/shortpanel.html", $data);
    }
}