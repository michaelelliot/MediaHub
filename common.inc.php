<?php
require_once('includes/sql_db_ex3.inc.php');

ini_set("memory_limit", "30M");

function ensure_sql_connected()
{
    global $sql_db,$config;

    if ($sql_db) return;
    
    if ($config['general']['local']) {
    $db_settings = array(
        "server" => $config['local']['mysql_server'],
        "database" => $config['local']['mysql_database'],
        "username" => $config['local']['mysql_username'],
        "password" => $config['local']['mysql_password']);
    } else {
    $db_settings = array(
        "server" => $config['remote']['mysql_server'],
        "database" => $config['remote']['mysql_database'],
        "username" => $config['remote']['mysql_username'],
        "password" => $config['remote']['mysql_password']);
    }
    $db_settings['persistent'] = false;
    $db_settings['die_message'] = false;

    $sql_db = new DB($db_settings);
}



?>