<?php

# Create database object
function sql_connect() {
    global $sql_db;
    if ($sql_db) return;
    $sql_db = new DB(array("credentials" => $config['mysql']));
}

function json_sanitize($json) {
    return preg_replace("/('|\")/", "\\\\$1", $json);
}
function sanitize_search_term($t) {
    return preg_replace("/[^a-z0-9'!@#\$%^&\*\(\)\[\]\{\}\-\+:,\. ]/i", '', $t);
}
function add_message($m) {
    if (@!$_SESSION['messages']) {
        $_SESSION['messages'] = array($m);
    } else {
        $_SESSION['messages'][] = $m;
    }
}
?>