<?php
/* 
 * Entry point
 */
include("common.inc.php");

error_reporting(E_ALL ^ E_NOTICE);

// Load config
//if (file_exists("config.ini.rename")) die("Please rename config.ini.rename to config.ini and configure it!");
$config = parse_ini_file("config.ini", true);

ensure_sql_connected();





?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta http-equiv="Content-language" content="en" />
	<meta name="description" content="" />
        <!--<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.js"></script>
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.7/jquery-ui.js"></script>-->

        <!-- Load Queue widget CSS and jQuery -->
        <style type="text/css">@import url(css/plupload.queue.css);</style>
        <script type="text/javascript" src="http://www.google.com/jsapi"></script>
        <script type="text/javascript">
                //google.load("jquery", "1.3");
                google.load("jquery", "1.4.4");
                google.load("jqueryui", "1.8.7");
        </script>
        <!-- Thirdparty intialization scripts, needed for the Google Gears and BrowserPlus runtimes -->
        <script type="text/javascript" src="plupload/js/gears_init.js"></script>
        <script type="text/javascript" src="plupload/js/browserplus-min.js"></script>

        <!-- Load plupload and all it's runtimes and finally the jQuery queue widget -->
        <script type="text/javascript" src="plupload/js/plupload.min.js"></script>
        <!--<script type="text/javascript" src="plupload/js/plupload.gears.min.js"></script>
        <script type="text/javascript" src="plupload/js/plupload.silverlight.min.js"></script>
        <script type="text/javascript" src="plupload/js/plupload.flash.min.js"></script>
        <script type="text/javascript" src="plupload/js/plupload.browserplus.min.js"></script>
        <script type="text/javascript" src="plupload/js/plupload.html4.min.js"></script>
        <script type="text/javascript" src="plupload/js/plupload.html5.min.js"></script>-->
        <script type="text/javascript" src="plupload/js/jquery.ui.plupload.min.js"></script>

        <!-- Load plupload and all it's runtimes and finally the jQuery queue widget -->
        <script type="text/javascript" src="plupload/js/plupload.full.min.js"></script>
        <script type="text/javascript" src="plupload/js/jquery.plupload.queue.min.js"></script>
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
                        resize : {width : 320, height : 240, quality : 90},

                        // Rename files by clicking on their titles
                        rename: true,

                        // Sort files
                        sortable: true,

                        // Specify what files to browse for
                        filters : [
                                {title : "Image files", extensions : "jpg,jpeg,gif,png", max_file_count: 1}
                            
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
	<link type="text/css" rel="stylesheet" href="css/styles.css" />
        <title>MediaHub</title>
        <script type="text/javascript">

        $(document).ready(function() {
        });



        </script>
    </head>
    <body>
        <?php
        // put your code here
        ?>
        <center>
            <textarea id="log" style="width: 100%; height: 150px; font-size: 11px" spellcheck="false" wrap="off">
</textarea>

        <div style="width: 1000px; border: 1px solid gray;">

            <br /><br /><br /><br />
            <table cellpadding="0" cellspacing="0">
                <tr>
                    <td>Title: </td><td><input id="title" type="text" value="" /></td>
                </tr>
                <tr>
                    <td>Year: </td><td><input id="title" type="text" value="" /></td>
                <tr>
                    <td> : </td><td><input id="title" type="text" value="" /></td>
                </tr>
                
            </table>

                <form  method="post" action="dump.php">
                        <div id="uploader">
                                <p>You browser doesn't have Flash, Silverlight, Gears, BrowserPlus or HTML5 support.</p>
                        </div>
                    <input type="submit" value="Send">
                </form>



            <br /><br /><br /><br /><br />
        </div>
        </center>
        

        
    </body>
</html>
