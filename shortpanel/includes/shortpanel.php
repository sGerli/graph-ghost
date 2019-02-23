<?php
require($_SERVER["DOCUMENT_ROOT"] . "/Database.php");
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
            unset($_SESSION['flash']);
        }

        echo $this->twig->render("/shortpanel.html", $data);
    }
}