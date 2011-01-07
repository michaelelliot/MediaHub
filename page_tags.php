<?php

//echo "<pre align='left'>";
//var_dump($_COOKIE);
//echo "</pre>";

?>


<div class="box">
    <h2>Lookup Media Keys</h2>
    <h3>Media Keys</h3>
    <textarea style="width: 100%; height: 100px;"></textarea>
    <div align="right" style="padding-top: 5px;"><input type="button" value="Lookup" /></div>
</div>
<hr />
<div class="box">
    <h2>Content Sources</h2>
    <h3>Sources</h3>
    <textarea style="width: 100%; height: 100px;"></textarea>
    <div align="right" style="padding-top: 5px;"><input type="button" value="Add Trailer URL" /></div>
</div>
<hr />
<div class="box">
    <h2>Content Fields</h2>
    <div id="content_fields_tabs">
        <ul>
            <li><a href="#tabs-1">Movie</a></li>
            <li><a href="#tabs-2">Series</a></li>
        </ul>
        <div id="tabs-1">
            <table cellpadding="0" cellspacing="0">
                <tr><td class="first">Title:</td><td><input id="movie_title" name="movie_title" type="text" value="" /></td></tr>
                <tr><td class="first">Year:</td><td><input id="movie_year" name="movie_year" type="number" value="" /></td>
                <tr><td class="first">Genres:</td><td><input id="movie_genres" name="movie_genres" type="text" value="" /></td>
                <tr><td class="first">Actors:</td><td><input id="movie_actors" name="movie_actors" type="text" value="" /></td>
                <tr><td class="first">Directors:</td><td><input id="movie_directors" name="movie_directors" type="text" value="" /></td>
                <tr><td class="first">Producers:</td><td><input id="movie_producers" named="movie_producers" type="text" value="" /></td>
                <tr><td class="first">Writers:</td><td><input id="movie_writers" name="movie_writers" type="text" value="" /></td>
            </table>
        </div>
        <div id="tabs-2">
            <!-- TODO: -->
        </div>
    </div>
</div>
<script type="text/javascript">
    $( "#content_fields_tabs" ).tabs();
</script>
<div class="note">
    <center>Note: Fields with multiple values are separated with a semi-colon (;).</center>
</div>
<hr />
