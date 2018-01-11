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

$page = 'home.php';

if (isset($_REQUEST['search'])) {
    $page = 'search.php';
}
if (isset($_REQUEST['add'])) {
    $page = 'add.php';
}


include "{$basedir}/templates/_head.php";
include "{$basedir}/templates/{$page}";
include "{$basedir}/templates/_foot.php";

file::setConfig();