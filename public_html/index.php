<?php

ini_set('display_errors', 0);
error_reporting(E_ALL);

$here = __DIR__;
include "$here/../private/lib/Site.php";
$site = new Site();
$site->render();
