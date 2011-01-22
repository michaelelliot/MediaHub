<?php
/*
  Copyright (C) 2011 Network Digital

  Permission is hereby granted, free of charge, to any person obtaining a copy
  of this software and associated documentation files (the "Software"), to deal
  in the Software without restriction, including without limitation the rights
  to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
  copies of the Software, and to permit persons to whom the Software is
  furnished to do so, subject to the following conditions:

  The Software shall be used for Good, not Evil.

  THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
  IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
  FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
  AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
  LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
  OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
  SOFTWARE.
 */
session_start();
require_once("config.inc.php");
require_once("common.inc.php");
require_once('json-rpc/jsonRPCClient.php');
require_once("bencode/bencode.php");

# Sanity check
if (!$logged_in) throw(new Exception("Must be logged in to view this page"));
if (!isset($_REQUEST['uploader_0_tmpname'])) die("Invalid POST");

# Clear any previous field values
unset($_SESSION['fields']);

# Get torrent data from temp upload file
$torrent_data = file_get_contents(ini_get("upload_tmp_dir") . DIRECTORY_SEPARATOR . "plupload" . DIRECTORY_SEPARATOR . preg_replace("/[\\/]/", "", $_POST['uploader_0_tmpname']));

# Decode torrent data and determine info_hash
$bencode = new Bencode();
$result = $bencode::decode($torrent_data);
$largest_length = 0;
$filetype_count = array();

# Count file types and find largest file
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

# Try to detect mclass
if (@$_REQUEST['mclass'] == "detect") {
    switch($largest_filetype) {
        # Video
        case "avi":
        case "m4v":
        case "mp4":
        case "mkv":
        case "wmv":
            # Probably an episode
            if (preg_match("/(^[0-9]{1,3}[\.]  | [0-9]{1,2}x[0-9]{1,3} | [0-9]{3} | s[0-9]{1,2} ?e[0-9]{1,3} )/i", $largest_filename)) {
                $mclass = "episode";
            # Otherwise probably a movie
            } else {
                $mclass = "movie";
            }
            break;

        # Audio
        case "mp3":
        case "m4a":
        case "wav":
        case "flac":
        case "wma":
            # If only 1, probably a track
            if (isset($filetype_count[$largest_filetype])) {
                if ($filetype_count[$largest_filetype] == 1) {
                    $mclass = "track";
                # Otherwise probably an album
                } else {
                    $mclass = "album";
                }
            }
            break;

        # Probably a game
        case "iso":
        case "bin":
        case "img":
            $mclass = "game";
        # Probably an application
            break;

        case "exe":
            $mclass = "application";
            break;
    }
} else {
    $mclass = @$_REQUEST['mclass'] or $mclass = null;
}

$name = null;
$title = null;
$artist = null;
$year = null;
$bitrate = null;
$info_hash = sha1(Bencode::encode($result['info']));
$torrent_filename = $_POST['uploader_0_name'];

# Set name if present in torrent
if (isset($result['info']['name'])) {
    $name = $result['info']['name'];
    # Set year if detected
    if (preg_match("/(1[89][0-9]{2}|2[01][0-9]{2})/i", $name, $matches)) {
        $year = $matches[1];
    }
    # Set bitrate if detected
    if (preg_match("/(128|160|192|224|256|320)(kbps|kb|kbs|kbits)/i", $name, $matches)) {
        $bitrate = $matches[1];
    } else if (preg_match("/VBR/i", $name)) {
        $bitrate = "VBR";
    }
    # Detect if it's a movie
    if (!$bitrate && $mclass == "movie") {
        if (preg_match("/^([a-z0-9:' ]+)(\(|\[)/i", $name, $matches)) {
            $title = trim($matches[1]);
        }
    # Detect if it's an album
    } else if ($mclass == "album") {
        if (preg_match("/^([a-z0-9' ]+)( - |: )([a-z0-9' ]+)([^a-z0-9' ]|$)/i", $name, $matches)) {
            $artist = trim($matches[1]);
            $title = trim($matches[3]);
        }
    }
}

# Set main fields
$_SESSION['fields']['mclass'] = $mclass;
$_SESSION['fields']['name'] = $name;
$_SESSION['fields']['artist'] = $artist;
$_SESSION['fields']['title'] = $title;
$_SESSION['fields']['year'] = $year;

# Set content specific fields
$_SESSION['fields']['bitrate'] = $bitrate;
$_SESSION['fields']['torrent_info_hash'] = $info_hash;
$_SESSION['fields']['torrent_filename'] = $torrent_filename;

# Send data to MediaTag for lookup
$client  = new jsonRPCClient(MEDIATAG_JSON_RPC_URL);
#$client->debug = true;

# Prepare mkeys
$mkeys = array("btih:$info_hash");
if ($torrent_filename && strtolower(substr($torrent_filename, 0, strlen($info_hash))) != strtolower($info_hash)) {
    $mkeys[] = json_sanitize(sanitize_search_term("filename:$torrent_filename"));
}
# TODO: What is name? How is it different from title?
if ($name) {
    $mkeys[] = json_sanitize(sanitize_search_term("name:$name"));
}
if ($mclass == "movie" && $title && $year) {
    #$mkeys[] = json_sanitize(sanitize_search_term("movie:title($title) year($year)"));
    $mkeys[] = json_sanitize(sanitize_search_term("title:$title ($year)"));
}
if ($mclass == "album" && $artist && $title && $year) {
    $mkeys[] = json_sanitize(sanitize_search_term("title:$artist - $title ($year)"));
}

# Make the call
try {
    $result = $client->lookup_mkeys(
            array('mkeys' => $mkeys));
} catch(Exception $e) {
    die ("RPC Error: " . $e->getMessage());
}

if ($mclass == "movie" || $mclass == "album") {
    $_SESSION['fields']['sources'] = "feature: btih(" . $info_hash . ");\n";
    if ($mclass == "movie") $_SESSION['fields']['sources'] .= "trailer: url(http://www.youtube.com/watch?v=XXX) in-browser;";
}
$_SESSION['fields']['mkeys'] = join("\n", $mkeys);

# Handle result
if (isset($result)) {
    if ($result['result'] == "not found") {
        header("Location: index.php?section=tags&result=not_found");
    } else if ($result['result'] == "success") {
        $_SESSION['fields']['found_via'] = 'mkeys';
        $_SESSION['fields']['found_using'] = $result['mkey'];
        foreach($result['meob'] as $key => $val) {

            // TODO sessions can use arrays...
            if (is_array($_SESSION['fields'][$key])) {
                $_SESSION['fields'][$key] = join("\n", $val);
            } else {
                $_SESSION['fields'][$key] = $val;
            }
        }
        header("Location: index.php?section=tags");
        exit;
    } else {
        die("Unknown error");
    }
}
?>