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
require_once("TwitterOAuth/TwitterOAuth.php");
require_once('sql_db/sql_db.inc.php');

# Handle messages
$messages = array();
if (@count($_SESSION['messages'])) {
    foreach($_SESSION['messages'] as $msg) {
        $messages[] = $msg;
    }
    unset($_SESSION['messages']);
}

$section = @trim(preg_replace("/[^a-z]/i", "", $_REQUEST['section'])) or $section = "home";

if (isset($_REQUEST['logout'])) {
    $logged_in = false;
    unset($_SESSION['twitter']);
}

if (isset($_REQUEST['login']) && !$logged_in) $messages[] = 'Error logging in. The Twitter account @<b>' . TwitterOAuth::getUsername() . '</b> does not have access.';

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <title>MediaHub Web Manager</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta http-equiv="Content-language" content="en" />
        <meta name="description" content="MediaHub Manager" />
        <!-- CSS -->
        <style type="text/css">@import url(css/styles.css);</style>
        <style type="text/css">@import url(modal-loading/styles.css);</style>
        <style type="text/css">@import url(css/ui-lightness/jquery-ui-1.8.7.custom.css);</style>
        <style type="text/css">@import url(plupload/css/plupload.queue.css);</style>
        <!-- JavaScript -->
        <script type="text/javascript" src="js/jquery-1.4.4.min.js"></script>
        <script type="text/javascript" src="js/jquery-ui-1.8.7.custom.min.js"></script>
        <script type="text/javascript" src="js/jquery.jsonrpc.js"></script>
        <script type="text/javascript" src="modal-loading/modal-loading.js"></script>
        <script type="text/javascript" src="plupload/js/jquery.ui.plupload.min.js"></script>
        <script type="text/javascript" src="plupload/js/plupload.full.min.js"></script>
        <script type="text/javascript" src="plupload/js/jquery.plupload.queue.min.js"></script>
        <script type="text/javascript" src="js/mediahub.js"></script>
        <script type="text/javascript">
        $(function() {
            $("#uploader").plupload({
                // General settings
                runtimes : 'html5,flash,browserplus,silverlight,gears,html4',
                url : 'upload.php',
                max_file_size : '1000mb',
                max_file_count: 1, // user can add no more then 20 files at a time
                chunk_size : '1mb',
                unique_names : true,
                multiple_queues : false,
                rename: true,
                sortable: true,
                filters : [{title : "Torrent files", extensions : "torrent"}],
                flash_swf_url : 'plupload/js/plupload.flash.swf',
                silverlight_xap_url : 'plupload/js/plupload.silverlight.xap',
                init: {
                    StateChanged: function(up) {
                        if (up.state == plupload.STOPPED) {
                         setTimeout("$('form').submit();", 1);
                        }
                    }
                },
                FilesAdded: function(up, files) {
                    var current_file_count = (up.files.length-files.length);
                    // If room for more then allow
                    if (current_file_count < up.settings.max_file_count) {
                           for(var i = up.settings.max_file_count - current_file_count; i < files.length; i++) {
                            up.removeFile(files[i]);
                        }
                        return;
                    }
                    // Limit reached, exclude all new files
                    for(var i = 0; i < files.length; i++) {
                        up.removeFile(files[i]);
                    }
                }
            });
            // Client side form validation
            $('form').submit(function(e) {
                var uploader = $('#uploader').plupload('getUploader');
                if (uploader.total.uploaded > 1) {
                    alert("Only 1 file is allowed.");
                    return;
                }
                // Validate number of uploaded files
                if (uploader.total.uploaded == 0) {
                    // Files in queue upload them first
                    if (uploader.files.length > 0) {
                        uploader.start();
                    } else {
                        alert('You must at least upload one file.');
                    }
                    e.preventDefault();
                }
            });

            $('a.twitter_btn').click(function() {
                $(this).css('background', 'url(<?php echo LIB_PATH ?>TwitterOAuth/twitter_btn.gif) no-repeat bottom left');
            });
        });
        </script>
        <style type="text/css">
        a.twitter_btn  {
            display: block;
            background: url(<?php echo LIB_PATH ?>TwitterOAuth/twitter_btn.gif) no-repeat top left;
            width: 151px;
            height: 25px;
        }
        </style>
    </head>
    <body>
        <center>
            <!-- TOP PANE -->
            <div class="top">
                <table width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                        <td><div style="line-height: 35px; font-size: 15pt; position: relative; padding-left: 15px;">MediaHub Web Manager</div></td>
                        <td align="center">
                            <div style="font-size: 9pt; padding-right: 15px;"><?php

                            if ($logged_in) echo "Logged in as <b>@" . TwitterOAuth::getUsername() . "</b>";

                            ?></div>
                        </td>
                        <td align="center" width="100">
                        <?php
                        if (!$logged_in) {
                            print '<a class="twitter_btn" href="redirect.php"></a>';
                        } else {
                            print '<a href="?logout">Log out</a>';
                        }
                        ?>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="body">
                <table cellpadding="0" cellspacing="0">
                    <tr>
                        <td valign="top" class="menu">
                            <ul>
                                <li><a href="?section=upload">Add Content</a><br /></li>
                                <li><a href="?section=directory">Content Directory</a><br /></li>
                            </ul>
                        </td>
                        <td valign="top" class="page">
                            <div class="page">
                                <ul class="messages"<?php if (!count($messages)) echo ' style="display: none;"'; ?>>
                                    <?php
                                        if (count($messages)) {
                                            foreach($messages as $m) {
                                                print "<li>$m</li>";
                                            }
                                        }
                                    ?>
                                </ul>
                                <?php
                                try {
                                    if ($section == "upload") {
                                       include("page_upload.php");
                                    } else if ($section == "tags") {
                                       include("page_tags.php");
                                    } else if ($section == "home") {
                                        include("page_home.php");
                                    } else if ($section == "directory") {
                                        echo "Feature not yet available.";
                                    } else {
                                        throw(new Exception('Unknown section'));
                                    }
                                } catch (Exception $e) {
                                    print $e->getMessage();
                                }
                                ?>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
                <div style="font-size: 7.5pt; padding-top: 15px;">MediaHub v<?php echo $config['general']['version'] ?></div>
        </center>
    </body>
</html>
