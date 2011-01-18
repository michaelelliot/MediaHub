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

function clear_cookie_fields() {
    setcookie('meob_mtag', '');
    setcookie('meob_mclass', '');
    setcookie('meob_title', '');
    setcookie('meob_year', '');;
    setcookie('meob_genres', '');
    setcookie('meob_actors', '');
    setcookie('meob_directors', '');
    setcookie('meob_producers', '');
    setcookie('meob_artist', '');
    setcookie('meob_number', '');
    setcookie('meob_duration', '');
    setcookie('meob_production_code', '');
    setcookie('meob_ext_imdb', '');
    setcookie('meob_ext_imdb_rating', '');
    setcookie('meob_ext_rottentomatoes_rating', '');
    setcookie('content_sources', '');
    setcookie('mediakeys', '');

}
function json_sanitize($json) {
    return preg_replace("/('|\")/", "\\\\$1", $json);
}
?>