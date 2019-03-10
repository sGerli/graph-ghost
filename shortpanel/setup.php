<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/secret.php");
use Cartalyst\Sentinel\Native\Facades\Sentinel;

$query = file_get_contents('./includes/mysql-tables.sql');
$mysql = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);

if ($mysql->connect_errno) {
    printf("Connect failed: %s\n", $mysql->connect_error);
    exit();
}

$mysql->begin_transaction(MYSQLI_TRANS_START_READ_ONLY);

if ($mysql->multi_query($query)) {
    echo "New record created successfully.<br />";
} else {
    echo "Error: " .  $mysql->error . "<br />";
}
$mysql->commit();
$mysql->close();
echo "MySQL Query done.<br />";

require_once("includes/authentication.php");
Sentinel::register(array(
    'login'    => ADMIN_USERNAME,
    'password' => ADMIN_PASSWORD,
));
echo "Added user.<br />";
echo "<h2>Please remove the setup.php file and proceed to login to the shortpanel.</h2>"
?>