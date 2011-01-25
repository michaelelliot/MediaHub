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
if (!$logged_in) throw(new Exception("Must be logged in to view this page"));
?>
<h1>Add Content</h1>
<div style="width: 500px; text-align: left;">
    <form  method="post" action="upload_submit.php">
        <div class="box">
            <table cellpadding="0" cellspacing="0">
                <tr>
                    <td class="first">Media Type:</td>
                    <td>
                        <select name="mclass" style="width: 100px;">
                            <option value="detect">Detect</option>
                            <option value="movie">Movie</option>
                            <option value="album">Album</option>
                            <option value="track">Track</option>
                            <option value="series">Series</option>
                            <option value="season">Season</option>
                            <option value="episode">Episode</option>
                        </select>
                    </td>
                </tr>
            </table>
        </div>
        <div class="box">
            <h3>File Upload:</h3>
            <div id="uploader">
                <p>You browser doesn't have Flash, Silverlight, Gears, BrowserPlus or HTML5 support.</p>
            </div>
        </div>
        <p>Drag and drop a torrent file into the container above.</p>
        <div class="box">
            <h3>Torrent URL:</h3>
            <div>
            <input type="text" id="torrent_url" name="torrent_url" /><br />
            <input type="submit" value="Fetch Torrent" style="float: right;" />
            </div>
        </div><p>Or you can use the URL of a torrent file.</p>
        
    </form>
    
</div>