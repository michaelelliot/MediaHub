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
    </form>
    <p>Drag and drop the torrent file of the content into the container above.</p>
</div>