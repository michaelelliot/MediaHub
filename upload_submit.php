<?php
/*
 * uploader_0_tmpname e.g. p15l8898k11a20c2612sv1jri11431.jpg
 * uploader_0_name	e.g. tumblr_lcvaneL4DT1qf6ccbo1_500.jpg
 * uploader_0_status e.g. done
 * uploader_count e.g. 1
  */

require_once('includes/json-rpc/jsonRPCClient.php');
include("includes/bencode/bencode.php");
include("common.inc.php");

error_reporting(E_ALL);// ^ E_NOTICE);

if (!isset($_REQUEST['uploader_0_tmpname'])) die("Invalid POST");

# Get torrent data from temp upload file
$torrent_data = file_get_contents(ini_get("upload_tmp_dir") . DIRECTORY_SEPARATOR . "plupload" . DIRECTORY_SEPARATOR . preg_replace("/[\\/]/", "", $_POST['uploader_0_tmpname']));

# Decode torrent data and determine info_hash
# Save all relevant values
$bencode = new Bencode();
$result = $bencode::decode($torrent_data);
$largest_length = 0;
$filetype_count = array();
$mclass = null;

if (isset($result['info']['files'])) {
    foreach($result['info']['files'] as $file) {
        if (preg_match("/\.([a-z0-9]+)$/i", $file['path'][count($file['path']) - 1], $matches)) {
            if (!isset($filetype_count[strtolower($matches[1])])) $filetype_count[strtolower($matches[1])] = 1;
            else $filetype_count[strtolower($matches[1])] += 1;
        }
        if ($file['length'] > $largest_length) {
            $largest_filename = $file['path'][count($file['path']) - 1];
            $largest_length = $file['length'];
            if (preg_match("/\.([a-z0-9]+)$/i", $largest_filename, $matches)) {
                $largest_filetype = strtolower($matches[1]);
            }
        }
    }
}

if ($_REQUEST['mclass'] == "detect") {
    switch($largest_filetype) {
        // Video
        case "avi":
        case "m4v":
        case "mp4":
        case "mkv":
        case "wmv":

            // Probably an episode
            if (preg_match("/(^[0-9]{1,3}[\.]  | [0-9]{1,2}x[0-9]{1,3} | [0-9]{3} | s[0-9]{1,2} ?e[0-9]{1,3} )/i", $largest_filename)) {
                $mclass = "episode";
            // Otherwise probably a movie
            } else {
                $mclass = "movie";
            }
            break;

        // Audio
        case "mp3":
        case "m4a":
        case "wav":
        case "flac":
        case "wma":

            // If only 1, probably a track
            if (isset($filetype_count[$largest_filetype])) {
                if ($filetype_count[$largest_filetype] == 1) {
                    $mclass = "track";
                // Otherwise probably an album
                } else {
                    $mclass = "album";
                }
            }
            break;

        // Probably a game
        case "iso":
        case "bin":
        case "img":
            $mclass = "game";
        // Probably an application
            break;

        case "exe":
            $mclass = "application";
            break;
    }
    setcookie('mclass', $mclass);
} else {
    setcookie('mclass', $_REQUEST['mclass']);
}

$name = null;
$title = null;
$artist = null;
$year = null;
$bitrate = null;

// Set name if present in torrent
if (isset($result['info']['name'])) {
    $name = $result['info']['name'];
    // Set year if detected
    if (preg_match("/(1[89][0-9]{2}|2[01][0-9]{2})/i", $name, $matches)) {
        $year = $matches[1];
    }
    // Set bitrate if detected
    if (preg_match("/(128|160|192|224|256|320)(kbps|kb|kbs|kbits)/i", $name, $matches)) {
        $bitrate = $matches[1];
    } else if (preg_match("/VBR/i", $name)) {
        $bitrate = "VBR";
    }
    // Detect if it's a movie
    if (!$bitrate && $mclass == "movie") {
        if (preg_match("/^([a-z0-9:' ]+)(\(|\[)/i", $name, $matches)) {
            $title = trim($matches[1]);
        }
    // Detect if it's an album
    } else if ($mclass == "album") {
        if (preg_match("/^([a-z0-9' ]+)( - |: )([a-z0-9' ]+)([^a-z0-9' ]|$)/i", $name, $matches)) {
            $artist = trim($matches[1]);
            $title = trim($matches[3]);
        }
    }
}

setcookie('name', $name);
setcookie('artist', $artist);
setcookie('title', $title);
setcookie('year', $year);
setcookie('bitrate', $bitrate);


#print "<pre>";
#print_r($result['info']);
#print "</pre>";
#exit;

$info_hash = sha1(Bencode::encode($result['info']));
$torrent_filename = $_POST['uploader_0_name'];

# Save values as cookies
#setcookie('torrent.info_hash', $info_hash);
#setcookie('torrent.filename', $torrent_filename);

// Send data to MediaTag for lookup
$client  = new jsonRPCClient('http://localhost/MediaTag/src/api/');
$client->debug = true;

// Prepare mkeys
$mkeys = array("btih:$info_hash");
if ($torrent_filename && strtolower(substr($torrent_filename, 0, strlen($info_hash))) != strtolower($info_hash)) $mkeys[] = json_sanitize("filename:$torrent_filename");
if ($name) $mkeys[] = json_sanitize("name:$name");
if ($mclass == "movie" && $title && $year) $mkeys[] = json_sanitize("movie:title($title) year($year)");
if ($mclass == "album" && $artist && $title && $year) $mkeys[] = json_sanitize("album:artist($artist) title($title) year($year)");

try {
    $result = $client->lookup_mkeys(
            array('mkeys' => $mkeys));
} catch(Exception $e) {
    die ("RPC Error: " . $e->getMessage());
}
//var_dump($result);
//exit;

if (isset($result)) {
    
    if ($result['result'] == "not found") {
        //header("Location: page_tags.php?not_found");
    } else if ($result['result'] == "success") {

        // TODO: Clear all fields
        // setcookie('meob_*', '') etc.

        clear_cookie_fields();

        //$mediakeys = "btih:" . $info_hash . "\r\n";
        //if (strtolower(substr($torrent_filename, 0, strlen($info_hash))) != strtolower($info_hash)) $mediakeys .= "filename:" . $torrent_filename . "\r\n";
        //if ($name) $mediakeys .= "name:" .  $name . "\r\n";
        
        $mediakeys = join("\n", $mkeys);


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
        setcookie('mediakeys', $mediakeys);

        header("Location: index.php?section=tags");
        exit;
    } else {
        die("Unknown error");
    }
}


?>