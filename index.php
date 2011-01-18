<?php
/*
  Copyright (C) 2011 thermal

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

include("common.inc.php");

$section = @trim(preg_replace("/[^a-z]/i", "", $_REQUEST['section']));
if (!$section) {
    $section = "home";
    clear_cookie_fields();
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta http-equiv="Content-language" content="en" />
	<meta name="description" content="" />

        <style type="text/css">@import url(css/styles.css);</style>




        

        <script type="text/javascript" src="js/jquery-1.4.4.min.js"></script>
        <script type="text/javascript" src="js/jquery-ui-1.8.7.custom.min.js"></script>

        <script type="text/javascript" src="js/jquery.jsonrpc.js"></script>
        <script type="text/javascript" src="modal-loading/modal-loading.js"></script>
        <style type="text/css">@import url(modal-loading/styles.css);</style>

        <style type="text/css">@import url(css/ui-lightness/jquery-ui-1.8.7.custom.css);</style>

        <!-- Load Queue widget CSS and jQuery -->
        <style type="text/css">@import url(plupload/css/plupload.queue.css);</style>
        <!--
        
        <script type="text/javascript" src="http://www.google.com/jsapi"></script>
        <script type="text/javascript">
                google.load("jquery", "1.4.4");
                google.load("jqueryui", "1.8.7");
        </script>
        -->

        <!-- Load plupload and all it's runtimes and finally the jQuery queue widget -->
        <script type="text/javascript" src="plupload/js/jquery.ui.plupload.min.js"></script>
        <script type="text/javascript" src="plupload/js/plupload.full.min.js"></script>
        <script type="text/javascript" src="plupload/js/jquery.plupload.queue.min.js"></script>
        <script type="text/javascript" src="js/mediahub.js"></script>
        <script type="text/javascript">

        // Convert divs to queue widgets when the DOM is ready
        $(function() {



            function log() {
		var str = "";

		plupload.each(arguments, function(arg) {
			var row = "";

			if (typeof(arg) != "string") {
				plupload.each(arg, function(value, key) {
					// Convert items in File objects to human readable form
					if (arg instanceof plupload.File) {
						// Convert status to human readable
						switch (value) {
							case plupload.QUEUED:
								value = 'QUEUED';
								break;

							case plupload.UPLOADING:
								value = 'UPLOADING';
								break;

							case plupload.FAILED:
								value = 'FAILED';
								break;

							case plupload.DONE:
								value = 'DONE';
								break;
						}
					}

					if (typeof(value) != "function") {
						row += (row ? ', ' : '') + key + '=' + value;
					}
				});

				str += row + " ";
			} else {
				str += arg + " ";
			}
		});

		$('#log').append(str + "\n");
	}

        
                $("#uploader").plupload({
                        // General settings
                        runtimes : 'html5,flash,browserplus,silverlight,gears,html4',
                        url : 'upload.php',
                        max_file_size : '1000mb',
                        max_file_count: 1, // user can add no more then 20 files at a time
                        chunk_size : '1mb',
                        unique_names : true,
                        multiple_queues : false,

                        // Resize images on clientside if we can
                        //resize : {width : 320, height : 240, quality : 90},

                        // Rename files by clicking on their titles
                        rename: true,

                        // Sort files
                        sortable: true,

                        // Specify what files to browse for
                        filters : [
                                {title : "Torrent files", extensions : "torrent"}
                            
                        ],

                        // Flash settings
                        flash_swf_url : 'plupload/js/plupload.flash.swf',

                        // Silverlight settings
                        silverlight_xap_url : 'plupload/js/plupload.silverlight.xap',

                      
                        // PreInit events, bound before any internal events
                        preinit : {
                            Init: function(up, info) {
                                log('[Init]', 'Info:', info, 'Features:', up.features);
                            },

                            UploadFile: function(up, file) {
                                log('[UploadFile]', file);
                                // You can override settings before the file is uploaded
                                // up.settings.url = 'upload.php?id=' + file.id;
                                // up.settings.multipart_params = {param1 : 'value1', param2 : 'value2'};
                            }
                        },

                    // Post init events, bound after the internal events
                    init : {
                            Refresh: function(up) {
                                    // Called when upload shim is moved
                                    log('[Refresh]');
                            },

                            StateChanged: function(up) {
                                    // Called when the state of the queue is changed
                                    log('[StateChanged]', up.state == plupload.STARTED ? "STARTED" : "STOPPED");
                                    if (up.state == plupload.STOPPED) {
                                     setTimeout("$('form').submit();", 1);
                                    }
                            },

                            QueueChanged: function(up) {
                                    // Called when the files in queue are changed by adding/removing files
                                    log('[QueueChanged]');
                            },

                            UploadProgress: function(up, file) {
                                    // Called while a file is being uploaded
                                    log('[UploadProgress]', 'File:', file, "Total:", up.total);
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
                            },

                            FilesRemoved: function(up, files) {
                                    // Called when files where removed from queue
                                    log('[FilesRemoved]');

                                    plupload.each(files, function(file) {
                                            log('  File:', file);
                                    });
                            },

                            FileUploaded: function(up, file, info) {
                                    // Called when a file has finished uploading
                                    log('[FileUploaded] File:', file, "Info:", info);

                                    //$('form').submit();
                            },

                            ChunkUploaded: function(up, file, info) {
                                    // Called when a file chunk has finished uploading
                                    log('[ChunkUploaded] File:', file, "Info:", info);
                            },

                            Error: function(up, args) {
                                    // Called when a error has occured
                                    log('[error] ', args);
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
                                        // When all files are uploaded submit form
                                        uploader.bind('UploadProgress', function() {
                                                if (uploader.total.uploaded == uploader.files.length)
                                                        $('form').submit();
                                        });

                                        uploader.start();
                                } else
                                        alert('You must at least upload one file.');

                                e.preventDefault();
                        }
                });
        });
        </script>
        <title>MediaHub Web Manager</title>
        <script type="text/javascript">
            $(function() {
            });
        </script>
    </head>
    <body>
        <center>
            <textarea id="log" style="display: none; width: 100%; height: 150px; font-size: 11px" spellcheck="false" wrap="off"></textarea>
            <div style="width: 1000px; border: 2px solid #f4ce87; border-bottom: 0; text-align: left; background-color: #ffe8a8">
                <div style="color: #7b6a5d; line-height: 50px; font-size: 15pt; position: relative; padding-left: 15px;">MediaHub Web Manager
                    <div style="float: right; font-size: 7.5pt; bottom: 0px; position: absolute; right: 0px; padding-right: 15px;">MediaHub <?php echo $config['general']['version'] ?></div>
                </div>
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
                                <?php
                                if ($section == "upload") {
                                   include("page_upload.php");
                                } else if ($section == "tags") {
                                   include("page_tags.php");
                                } else if ($section == "home") {
                                    include("page_home.php");
                                } else {
                                    echo "Unknown section!";
                                }
                                ?>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </center>
    </body>
</html>
