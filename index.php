<?php
/* 
 * Entry point
 */

// Load config
if (file_exists("config.ini.rename")) die("Please rename config.ini.rename to config.ini and configure it!");
$config = parse_ini("config.ini");

$local = $config['general']['local'];



?>
