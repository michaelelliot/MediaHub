<?php

# Load config
if (!file_exists("config.ini")) die("Please rename config.ini.rename to config.ini and configure it!");
$config = parse_ini_file("config.ini", true);

define('MEDIATAG_JSON_RPC_URL', $config['mediatag']['rpc_url']);
define('VERSION', $config['general']['version']);
define('CONSUMER_KEY', $config['oauth']['consumer_key']);
define('CONSUMER_SECRET', $config['oauth']['consumer_secret']);
define('OAUTH_CALLBACK_FILE', $config['oauth']['callback_file']);
define('OAUTH_CALLBACK_URL', (!empty($_SERVER['HTTPS'])) ? "https://" . $_SERVER['SERVER_NAME'] . substr($_SERVER['REQUEST_URI'], 0, strlen($_SERVER['REQUEST_URI']) - strpos(strrev($_SERVER['REQUEST_URI']), "/")) . OAUTH_CALLBACK_FILE : "http://" . $_SERVER['SERVER_NAME'] . substr($_SERVER['REQUEST_URI'], 0, strlen($_SERVER['REQUEST_URI']) - strpos(strrev($_SERVER['REQUEST_URI']), "/")) . OAUTH_CALLBACK_FILE);
define('GET_CREDENTIALS', false);
define('LIB_PATH', 'libs/');
set_include_path(get_include_path() . ';' . __DIR__ . '/' . LIB_PATH);
if (CONSUMER_KEY === '' || CONSUMER_SECRET === '') die('You need a consumer key and secret to use this example. Get one from <a href="https://twitter.com/apps">https://twitter.com/apps</a> and then configure config.php correctly.');
?>