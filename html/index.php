<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$basedir = str_replace(['/html'], [''], dirname(__FILE__));
foreach (glob("$basedir/lib/*.php") as $filename) {
    include $filename;
}
cache::loadConfig();

include "{$basedir}/templates/_head.php";

if (isset($_REQUEST['search'])) {
    include "{$basedir}/templates/search.php";
} else {
    include "{$basedir}/templates/home.php";
}

include "{$basedir}/templates/_foot.php";

file::setConfig();