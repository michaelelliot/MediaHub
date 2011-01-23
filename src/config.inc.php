<?php
# Load config
if (!file_exists("config.ini")) die("Please rename config.ini.rename to config.ini and configure it!");
$config = parse_ini_file("config.ini", true);

# Set global constants
define('VERSION', $config['general']['version']);
define('LIB_PATH', $config['general']['lib_path']);
define('MEDIATAG_JSON_RPC_URL', $config['mediatag']['rpc_url']);
define('CONSUMER_KEY', $config['oauth']['consumer_key']);
define('CONSUMER_SECRET', $config['oauth']['consumer_secret']);
define('OAUTH_CALLBACK_FILE', $config['oauth']['callback_file']);
define('OAUTH_CALLBACK_URL', (!empty($_SERVER['HTTPS'])) ? "https://" . $_SERVER['SERVER_NAME'] . substr($_SERVER['REQUEST_URI'], 0, strlen($_SERVER['REQUEST_URI']) - strpos(strrev($_SERVER['REQUEST_URI']), "/")) . OAUTH_CALLBACK_FILE : "http://" . $_SERVER['SERVER_NAME'] . substr($_SERVER['REQUEST_URI'], 0, strlen($_SERVER['REQUEST_URI']) - strpos(strrev($_SERVER['REQUEST_URI']), "/")) . OAUTH_CALLBACK_FILE);
define('OAUTH_ADMIN_USERNAME', str_replace('@', '', $config['oauth']['admin_username']));
define('OAUTH_ANYONE_CAN_LOGIN', $config['oauth']['anyone_can_login']);
define('GET_CREDENTIALS', false);
define('DEBUG', false);

# Sanity check
if (empty($config['oauth']['consumer_key']) || empty($config['oauth']['consumer_secret'])) {
    die('You need a consumer key and secret. Get one from <a href="https://twitter.com/apps">https://twitter.com/apps</a> and then update config.ini with your key values.');
}

set_include_path(get_include_path() . PATH_SEPARATOR . dirname(__FILE__) . '/' . LIB_PATH);
$logged_in = @$_SESSION['twitter']['logged_in'];
?>