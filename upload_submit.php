<?php
/*
 * uploader_0_tmpname e.g. p15l8898k11a20c2612sv1jri11431.jpg
 * uploader_0_name	e.g. tumblr_lcvaneL4DT1qf6ccbo1_500.jpg
 * uploader_0_status e.g. done
 * uploader_count e.g. 1
  */

require_once('includes/json-rpc/jsonRPCClient.php');
include("includes/bencode/bencode.php");

error_reporting(E_ALL);// ^ E_NOTICE);

# Get torrent data from temp upload file
$torrent_data = file_get_contents(ini_get("upload_tmp_dir") . DIRECTORY_SEPARATOR . "plupload" . DIRECTORY_SEPARATOR . preg_replace("/[\\/]/", "", $_POST['uploader_0_tmpname']));

# Decode torrent data and determine info_hash
# Save all relevant values
$bencode = new Bencode();
$result = $bencode::decode($torrent_data);
$info_hash = sha1(Bencode::encode($result['info']));
$torrent_filename = $_POST['uploader_0_name'];

# Save values as cookies
setcookie('torrent.info_hash', $info_hash);
setcookie('torrent.filename', $torrent_filename);

# Send data to MediaTag for lookup
$client  = new jsonRPCClient('http://localhost/MediaTag/src/api/');

try {
    $result = $client->lookup_mkeys(
            array('torrent.info_hash:' . $info_hash,
                  'torrent.filename:' . $torrent_filename));
} catch(Exception $e) {
    die ("Error: " . $e->getMessage());
}

if (isset($result)) {
    
    if ($result['result'] == "not found") {
        header("Location: page_tags.php?not_found");
    } else {
        setcookie('media.title', $result['title']);
        setcookie('media.year', $result['year']);
    }
}

header("Location: page_tags.php");
exit;
?>