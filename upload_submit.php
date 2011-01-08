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
#setcookie('torrent.info_hash', $info_hash);
#setcookie('torrent.filename', $torrent_filename);

# Send data to MediaTag for lookup
$client  = new jsonRPCClient('http://localhost/MediaTag/src/api/');

try {
    $result = $client->lookup_mkeys(
            array('btih:' . $info_hash,
                  'filename:' . $torrent_filename));
} catch(Exception $e) {
    die ("Error: " . $e->getMessage());
}
//var_dump($result);
//exit;
if (isset($result)) {
    
    if ($result['result'] == "not found") {
        header("Location: page_tags.php?not_found");
    } else if ($result['result'] == "success") {

        // TODO: Clear all fields
        // setcookie('meob_*', '') etc.

        $meob = $result['meob'];
        setcookie('meob_mtag', $meob['mtag']);
        setcookie('meob_mclass', $meob['mclass']);
        setcookie('meob_title', $meob['title']);
        setcookie('meob_year', $meob['year']);
        setcookie('meob_summary', $meob['summary']);
        setcookie('meob_genres', join(";", $meob['genres']));
        setcookie('meob_actors', join(";", $meob['actors']));
        setcookie('meob_directors', join(";", $meob['directors']));
        setcookie('meob_producers', join(";", $meob['producers']));
        setcookie('meob_artist', $meob['artist']);
        setcookie('meob_number', intval($meob['number']));
        setcookie('meob_duration', intval($meob['duration']));
        setcookie('meob_production_code', $meob['production_code']);
        setcookie('meob_ext_imdb', $meob['ext_imdb_tt']);
        setcookie('meob_ext_imdb_rating', floatval($meob['ext_imdb_rating']));
        setcookie('meob_ext_rottentomatoes_rating', intval($meob['ext_rotten_tomatoes_rating']));

        setcookie('content_sources', "relation: feature; source: btih(" . $info_hash . ");\r\nrelation: trailer; source: url(http://www.youtube.com/watch?v=UM5yepZ21pI);\r\n");
        setcookie('mediakeys', "btih:" . $info_hash . "\r\nfilename:" . $torrent_filename . "\r\n");

        header("Location: index.php?section=tags");
        exit;
    } else {
        die("Unknown error");
    }
}


?>