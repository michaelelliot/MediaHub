<?php
require_once('includes/sql_db/sql_db.inc.php');

global $config;

//ini_set("memory_limit", "30M");

error_reporting(E_ALL);// ^ E_NOTICE);

# Load config
if (!file_exists("config.ini")) die("Please rename config.ini.rename to config.ini and configure it!");
$config = parse_ini_file("config.ini", true);

# Database setup
$db_settings = array(
    "server" => $config['mysql']['server'],
    "database" => $config['mysql']['database'],
    "username" => $config['mysql']['username'],
    "password" => $config['mysql']['password']);

$db_settings['die_message'] = false;

$sql_db = new DB($db_settings);




?>