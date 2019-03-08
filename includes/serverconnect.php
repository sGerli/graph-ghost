<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/secret.php");

$mysql = new mysqli(SERVERNAME, USERNAME,
            PASSWORD, DATABASE);

?>