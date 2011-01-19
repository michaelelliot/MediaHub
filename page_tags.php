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
$sources = @$_SESSION['sources'] or $sources = null;
$mkeys = @$_SESSION['mkeys'] or $mkeys = null;
$mclass = @$_SESSION['mclass'] or $mclass = "unknown";
$mtag = @$_SESSION['mtag'] or $mtag = null;
$name = @$_SESSION['name'] or $name = null;
$year = @$_SESSION['year'] or $year = null;
$artist = @$_SESSION['artist'] or $artist = null;
$title = @$_SESSION['title'] or $title = null;
// TODO?
$bitrate = @$_SESSION['bitrate'] or $bitrate = null;

if (@$_SESSION['foundvia'] == 'mkeys') {
    $messages[] = "Matched to a media object (mtag <b>$mtag</b>) using mkeys.";
    $messages[] = "Content class is <b>$mclass.</b>";
} else {
    $messages[] = "Unable to match with a media object.";
    if ($mclass != "unknown" && $mclass) $messages[] = "Content class is probably <b>$mclass.</b>";
}
?>
<script type="text/javascript">
    // Clear messages
    function clear_messages() {
        $('ul.messages li').each(function(i){
            $(this).remove();
        });
    }
    // Add message
    function add_message(m) {
       $("ul.messages").append('<li>' + m + '</li>');
    }
    // Clear fields
    function clear_fields() {
        //$('#sources').val ('');
        //$('#mkeys').val ('');

        // Clear movie fields
        $('#movie_title').val ('');
        $('#movie_year').val ('');
        $('#movie_plot').val ('');
        $('#movie_tagline').val ('');
        $('#movie_genres').val ('');
        $('#movie_actors').val ('');
        $('#movie_directors').val ('');
        $('#movie_writers').val ('');
        $('#movie_runtime').val ('');
        $('#movie_release_date').val ('');
        $('#movie_classification').val ('');
        $('#movie_imdb_tt').val ('');
        $('#movie_imdb_rating').val ('');
    }
    // Process incoming meob fields from a lookup
    function process_meob_fields(meob) {
        try {
            if (typeof meob['mtag'] != 'undefined') $('#mtag').val(meob['mtag']);
            if (typeof meob['mclass'] != 'undefined') $('#mclass').val(meob['mclass']);

            // Ensure not already in field
            if (typeof meob['mkeys'] != 'undefined') {
                $(meob['mkeys']).each(function(i, e) {
                    // Note: .apppend() was playing up
                    if ($('#mkeys').val().indexOf(e) == -1) $('#mkeys').val($('#mkeys').val() + e + "\n")
                });
            }

            if (typeof meob['source'] != 'undefined') {
                $(meob['source']).each(function(i, e) {
                    if ($('#source').val().indexOf(e) == -1) $('#source').val($('#source').val() + e + "\n")
                });
            }

            switch(meob['mclass'].toLowerCase()) {
                case 'movie':
                    if (typeof meob['title'] != 'undefined') $('#movie_title').val(meob['title']);
                    if (typeof meob['year'] != 'undefined') $('#movie_year').val(meob['year']);
                    if (typeof meob['plot'] != 'undefined') $('#movie_plot').val(meob['plot']);
                    if (typeof meob['tagline'] != 'undefined') $('#movie_tagline').val(meob['tagline']);
                    if (typeof meob['genres'] != 'undefined') $('#movie_genres').val(meob['genres'].join('; '));
                    if (typeof meob['actors'] != 'undefined') $('#movie_actors').val(meob['actors'].join('; '));
                    if (typeof meob['directors'] != 'undefined') $('#movie_directors').val(meob['directors'].join('; '));
                    if (typeof meob['writers'] != 'undefined') $('#movie_writers').val(meob['writers'].join('; '));
                    if (typeof meob['runtime'] != 'undefined') $('#movie_runtime').val(meob['runtime']);
                    if (typeof meob['release_date'] != 'undefined') $('#movie_release_date').val(meob['release_date']);
                    if (typeof meob['classification'] != 'undefined') $('#movie_classification').val(meob['classification']);
                    if (typeof meob['imdb_tt'] != 'undefined') {
                        $('#movie_imdb_tt').val(meob['imdb_tt']);
                        // Add imdb_tt to mkeys
                       if ($('#mkeys').val().indexOf('imdb:') == -1) $('#mkeys').val($('#mkeys').val() + 'imdb:' + meob['imdb_tt'] + "\n");
                    }
                    if (typeof meob['imdb_rating'] != 'undefined') $('#movie_imdb_rating').val(meob['imdb_rating']);
                    $('#content_fields_tabs').tabs().tabs('select', '#tabs-movie');
                    break;
/*
            switch(meob['mclass'].toLowerCase()) {
                case 'movie':
                    if (typeof meob['title'] != 'undefined') $('#album_title').val(meob['title']);
                    if (typeof meob['year'] != 'undefined') $('#album_year').val(meob['year']);
                    if (typeof meob['artist'] != 'undefined') $('#album_artist').val(meob['artist']);
                    if (typeof meob['total_tracks'] != 'undefined') $('#album_total_tracks').val(meob['album_total_tracks']);
                    if (typeof meob['total_duration'] != 'undefined') $('#album_total_duration').val(meob['album_total_duration']);
                    if (typeof meob['release_date'] != 'undefined') $('#album_release_date').val(meob['album_release_date']);
                    if (typeof meob['label'] != 'undefined') $('#album_label').val(meob['album_label']);

                    $('#content_fields_tabs').tabs().tabs('select', '#tabs-album');
                    break;
            }*/

                default:
                    alert("Error: Unknown media class");
                    break;
            }
        } catch(e) {
            alert("Javascript error: " + e);
        }
    }
    
    $('#save').click(function() {
        
       if ($('#mtag').val() == "") throw("Invalid mtag entered");
       if ($('#mclass').val() == "unknown") throw("Must select an mclass");

       // TODO: ...
        
    });
    // Validate that an mtag is valid
    // Returns true if valid
    // TODO: ...
    function validate_mtag(mtag) {
        if (!mtag) return false;
        return true;
    }
    // On document ready
    $(function() {
        <?php
        if (isset($_SESSION['mclass'])) echo "$('#mclass').val ('{$_SESSION['mclass']}');";
        if (isset($_SESSION['mtag'])) echo "$('#mtag').val ('{$_SESSION['mtag']}');";
        if (isset($_SESSION['sources'])) echo "$('#sources').val ('" . trim(json_sanitize(preg_replace("/[\r\n|\n|\r]/", "\\n", $_SESSION['sources']))) . "\\n');";
        if (isset($_SESSION['mkeys'])) echo "$('#mkeys').val ('" . trim(json_sanitize(preg_replace("/[\r\n|\n|\r]/", "\\n", $_SESSION['mkeys']))) . "\\n');";
        ?>
        switch($('#mclass').val()) {
            case 'movie':
                <?php
                if (isset($_SESSION['title'])) echo "$('#movie_title').val ('" . json_sanitize($_SESSION['title']) . "');";
                if (isset($_SESSION['year'])) echo "$('#movie_year').val ('" . json_sanitize($_SESSION['year']) . "');";
                if (isset($_SESSION['plot'])) echo "$('#movie_plot').val ('" . json_sanitize($_SESSION['plot']) . "');";
                if (isset($_SESSION['tagline'])) echo "$('#movie_tagline').val ('" . json_sanitize($_SESSION['tagline']) . "');";
                if (isset($_SESSION['genres'])) echo "$('#movie_genres').val ('" . join("; ", json_sanitize($_SESSION['genres'])) . "');";
                if (isset($_SESSION['actors'])) echo "$('#movie_actors').val ('" . join("; ", json_sanitize($_SESSION['actors'])) . "');";;
                if (isset($_SESSION['directors'])) echo "$('#movie_directors').val ('" . join("; ", json_sanitize($_SESSION['directors'])) . "');";
                if (isset($_SESSION['writers'])) echo "$('#movie_writers').val ('" . join("; ", json_sanitize($_SESSION['writers'])) . "');";
                if (isset($_SESSION['runtime'])) echo "$('#movie_runtime').val ('" . json_sanitize($_SESSION['runtime']) . "');";
                if (isset($_SESSION['release_date'])) echo "$('#movie_release_date').val ('" . json_sanitize($_SESSION['release_date']) . "');";
                if (isset($_SESSION['classification'])) echo "$('#movie_classification').val ('" . json_sanitize($_SESSION['classification']) . "');";
                if (isset($_SESSION['imdb_tt'])) echo "$('#movie_imdb_tt').val ('" . json_sanitize($_SESSION['imdb_tt']) . "');";
                if (isset($_SESSION['imdb_rating'])) echo "$('#movie_imdb_rating').val ('" . json_sanitize($_SESSION['imdb_rating']) . "');";
                ?>
                $('#content_fields_tabs').tabs().tabs('select', '#tabs-movie');
                break;

            case 'album':
                <?php
                /*
                    if ($title) echo "$('#album_title').val ('" . $title . "');";
                    if ($year) echo "$('#album_year').val ('" . $year . "');";
                    if ($artist) echo "$('#album_artist').val ('" . $artist . "');";
                 * */
                 ?>
                $('#content_fields_tabs').tabs().tabs('select', '#tabs-album');
                break;
            case 'track':
        }

        $('#lookup_mtag').bind('click', function() {

            if (!validate_mtag($('#mtag').val())) {
                alert("Invalid mtag entered.");
                return;
            }
            clear_fields();
            clear_messages();
            add_message('Looking up mtag ' + $('#mtag').val() + '...');
            showLightbox();
            $.jsonRPC.setup({
                endPoint: 'http://localhost/MediaTag/src/api/',
                namespace: ''
            });
            $.jsonRPC.request('lookup_mtag', [$('#mtag').val()], {
                success: function(result) {
                    if (result['result'].toLowerCase() == 'success') {
                        meob = result['meob'];
                        process_meob_fields(meob);
                        add_message('<span class="success">Successfully matched mtag with media object.</span>');
                    } else {
                        add_message('<span class="failure">Couldn\'t match mtag</span>');
                    }
                },
                error: function(result) {
                    //alert("RPC Error" + var_dump(result));
                    add_message('<span class="failure">RPC Error: ' + var_dump(result) + '</span>');
                },
                completed: function(result) {
                    hideLightbox();
                }
            });

        });

        $('#lookup_mkeys').bind('click', function() {
            
            mclass = $('#mclass').val();
            if (mclass != 'unknown') {
              params = [{'mclass' : mclass, 'mkeys' : $.trim($('#mkeys').val()).split('\n')}];

            } else {
               params = [{'mkeys' : $('#mkeys').val().split('\n') }];
            }
            //alert(print_r(params));
            clear_fields();
            clear_messages();
            add_message('Looking up mkeys...');
            showLightbox();
            $.jsonRPC.setup({
                endPoint: 'http://localhost/MediaTag/src/api/',
                namespace: ''
            });
            $.jsonRPC.request('lookup_mkeys', params, {
                success: function(result) {
                    if (result['result'].toLowerCase() == 'success') {
                        meob = result['meob'];
                        process_meob_fields(meob);
                        add_message('<span class="success">Successfully matched mkey</span> (' + result['mkey'] + ') <span class="success">with media object.</span>');
                    } else {
                        add_message('<span class="failure">Couldn\'t match mkeys</span>');
                    }
                    hideLightbox();
                },
                error: function(result) {
                    //alert("Error: " + result['result']);
                    add_message('<span class="failure">Error: ' + result['error'] + '</span>');
                },
                completed: function(result) {
                    hideLightbox();
                }
            });
        });
     });
</script>

<ul class="messages">
    <?php
        if (count($messages)) {
            foreach($messages as $m) {
                print "<li>$m</li>";
            }
        }
    ?>
</ul>

<div class="box">
    <h2>MediaTag Fields</h2>
    <table cellpadding="0" cellspacing="0">
        <tr>
            <td class="first">mclass:</td>
            <td>
                <select id="mclass" name="mclass" style="width: 100px;">
                    <option value="unknown">Unknown</option>
                    <option value="movie">Movie</option>
                    <option value="album">Album</option>
                    <option value="track">Track</option>
                    <option value="series">Series</option>
                    <option value="season">Season</option>
                    <option value="episode">Episode</option>
                </select>
            </td>
        </tr>
        <tr><td class="first">mtag:</td><td><input id="mtag" name="mtag" type="text" value="" /></td><td><input id="lookup_mtag" type="button" value="Lookup" /></td></tr>
    </table>

</div>
<div class="box">
    
    <div id="tabs">
        <ul>
            <li><a href="#tabs-top-1">Content Sources</a></li>
            <li><a href="#tabs-top-2">Media Keys</a></li>
        </ul>
        <div id="tabs-top-1">
            <textarea id="sources" class="nowrap"></textarea>
            <div align="right" style="padding-top: 5px;"><input id="add_trailer_url" type="button" value="Add Source" /></div>
        </div>
        <div id="tabs-top-2">
            <textarea id="mkeys" class="nowrap"></textarea>
            <div align="right" style="padding-top: 5px;"><!-- <input id="add_mkey" type="button" value="Add Key" />&nbsp; --><input id="lookup_mkeys" type="button" value="Lookup" /></div>
        </div>
    </div>
</div>
<hr />
<div class="box">
    <h2>Content Fields</h2>
    <div id="content_fields_tabs">
        <ul>
            <li><a href="#tabs-movie">Movie</a></li>
            <li><a href="#tabs-album">Album</a></li>
        </ul>
        <div id="tabs-movie">
            <table cellpadding="0" cellspacing="0">
                <tr><td class="first">Title:</td><td><input id="movie_title" name="movie_title" type="text" value="" /></td></tr>
                <tr><td class="first">Year:</td><td><input id="movie_year" name="movie_year" type="number" value="" /></td>
                <tr><td valign="top" class="first">Plot:</td><td><textarea id="movie_plot" name="movie_plot" type="text" value=""></textarea></td>
                <tr><td class="first">Tagline:</td><td><input id="movie_tagline" name="movie_tagline" type="text" value="" /></td></tr>
                <tr><td class="first">Genres:</td><td><input id="movie_genres" name="movie_genres" type="text" value="" /></td>
                <tr><td class="first">Actors:</td><td><input id="movie_actors" name="movie_actors" type="text" value="" /></td>
                <tr><td class="first">Directors:</td><td><input id="movie_directors" name="movie_directors" type="text" value="" /></td>
                <tr><td class="first">Writers:</td><td><input id="movie_writers" name="movie_writers" type="text" value="" /></td>
                <tr><td class="first">Runtime:</td><td><input id="movie_runtime" name="movie_runtime" type="number" value="" /></td>
                <tr><td class="first">Release Date:</td><td><input id="movie_release_date" name="movie_release_date" type="text" value="" /></td></tr>
                <tr><td class="first">Classification:</td><td><input id="movie_classification" name="movie_classification" type="text" value="" /></td></tr>
                <tr><td class="first">IMDB Number:</td><td><input id="movie_imdb_tt" name="movie_imdb_tt" type="text" value="" /></td></tr>
                <tr><td class="first">IMDB Rating:</td><td><input id="movie_imdb_rating" name="movie_imdb_rating" type="number" value="" /></td>
            </table>
            <div class="note">
                Note: Separate fields that have multiple values with a semi-colon (;).
            </div>
        </div>
        <div id="tabs-album">
            <table cellpadding="0" cellspacing="0" style="visibility: hidden;">
                <tr><td class="first">Artist:</td><td><input id="album_artist" name="album_artist" type="text" value="" /></td>
                <tr><td class="first">Title:</td><td><input id="album_title" name="album_title" type="text" value="" /></td></tr>
                <tr><td class="first">Year:</td><td><input id="album_year" name="album_year" type="number" value="" /></td>
                <tr><td class="first">Genres:</td><td><input id="album_genres" name="album_genres" type="text" value="" /></td>
                <tr><td class="first">Total Tracks:</td><td><input id="album_total_tracks" name="album_total_tracks" type="number" value="" /></td>
                <tr><td class="first">Total Duration:</td><td><input id="album_total_duration" name="album_total_duration" type="number" value="" /></td>
                <tr><td class="first">Label:</td><td><input id="album_label" name="album_label" type="text" value="" /></td>
                <tr><td class="first">Release Date:</td><td><input id="album_release_date" named="album_release_date" type="text" value="" /></td>
            </table>
            <div class="note">
                Note: Separate fields that have multiple values with a semi-colon (;).
            </div>
        </div>

        <div id="">
            <!-- TODO: -->
        </div>
    </div>
</div>
<script type="text/javascript">
    $('#content_fields_tabs').tabs();
    $('#tabs').tabs()
</script>
<hr />
<div class="box" style="text-align: right;">
    <input id="save" type="button" value="Save" />
</div>
<hr />
<pre align="left"><?php //print_r($_COOKIE) ?></pre>