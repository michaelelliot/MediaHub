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
function curl_get_file_contents($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_ENCODING, 1);
    $contents = curl_exec($ch);
    curl_close($ch);
    if ($contents) return $contents;
    else return FALSE;
}
?>