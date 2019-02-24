<?php
use Cartalyst\Sentinel\Native\Facades\Sentinel;
require_once("includes/authentication.php");
Sentinel::register(array(
    'login'    => 'sgerli',
    'password' => 'test',
));
?>