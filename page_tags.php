<?php

//echo "<pre align='left'>";
//var_dump($_COOKIE);
//echo "</pre>";

$content_sources = @$_COOKIE['content_sources'] or $content_sources = null;
$mediakeys = @$_COOKIE['mediakeys'] or $mediakeys = null;



?>

<script type="text/javascript">

    // TODO: Put into a library
    function print_r(x, max, sep, l) {

	l = l || 0;
	max = max || 10;
	sep = sep || ' ';

	if (l > max) {
            return "[WARNING: Too much recursion]\n";
	}

	var
        i,
        r = '',
        t = typeof x,
        tab = '';

	if (x === null) {
            r += "(null)\n";
	} else if (t == 'object') {

            l++;

            for (i = 0; i < l; i++) {
                tab += sep;
            }

            if (x && x.length) {
                t = 'array';
            }

            r += '(' + t + ") :\n";

            for (i in x) {
                try {
                    r += tab + '[' + i + '] : ' + print_r(x[i], max, sep, (l + 1));
                } catch(e) {
                    return "[ERROR: " + e + "]\n";
                }
            }

	} else {

            if (t == 'string') {
                if (x == '') {
                    x = '(empty)';
                }
            }

            r += '(' + t + ') ' + x + "\n";

	}

	return r;

    };
    var_dump = print_r;


    // TODO: Put into a library
    jQuery.extend({
        random: function(X) {
            return Math.floor(X * (Math.random() % 1));
        },
        randomBetween: function(MinV, MaxV) {
            return MinV + jQuery.random(MaxV - MinV + 1);
        }
    });

    // Process incoming meob fields from a lookup
    function process_meob_fields(meob) {
        try {
            if (typeof meob['mtag'] != 'undefined') $('#mtag').val(meob['mtag']);
            if (typeof meob['mclass'] != 'undefined') $('#mclass').val(meob['mclass']);
            
            switch(meob['mclass'].toLowerCase()) {
                case 'movie':
                    if (typeof meob['title'] != 'undefined') $('#movie_title').val(meob['title']);
                    if (typeof meob['year'] != 'undefined') $('#movie_year').val(meob['year']);
                    if (typeof meob['summary'] != 'undefined') $('#movie_summary').val(meob['summary']);
                    if (typeof meob['genres'] != 'undefined') $('#movie_genres').val(meob['genres'].join(';'));
                    if (typeof meob['actors'] != 'undefined') $('#movie_actors').val(meob['actors'].join(';'));
                    if (typeof meob['directors'] != 'undefined') $('#movie_directors').val(meob['directors'].join(';'));
                    if (typeof meob['producers'] != 'undefined') $('#movie_producers').val(meob['producers'].join(';'));
                    if (typeof meob['writers'] != 'undefined') $('#movie_writers').val(meob['writers'].join(';'));
                    if (typeof meob['ext_imdb'] != 'undefined') {
                        if ($('#media_keys').val().indexOf('imdb:') == -1) $('#media_keys').append('imdb:' + meob['ext_imdb'] + "\n");
                    }
                    break;

                default:
                    alert("Error: Unknown media class");
                    break;
            }
        } catch(e) {
            alert("Javascript error: " + e);
        }
    }

  

    // On document ready
    $(function() {
        $('#lookup_mtag').bind('click', function() {
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
                    }
                },
                error: function(result) {
                    alert("Error: " + result['result']);
                },
                completed: function(result) {
                    hideLightbox();
                }
            });

        });

        $('#lookup_mkeys').bind('click', function() {
            showLightbox();
            $.jsonRPC.setup({
                endPoint: 'http://localhost/MediaTag/src/api/',
                namespace: ''
            });
            $.jsonRPC.request('lookup_mkeys', [["btih:c389547e7551e9785c4fa87935824a5403d178e8","filename:test.torrent"]], {
                success: function(result) {
                    if (result['result'].toLowerCase() == 'success') {
                        meob = result['meob'];
                        process_meob_fields(meob);
                    }
                    hideLightbox();
                },
                error: function(result) {
                    alert("Error: " + result['result']);
                },
                completed: function(result) {
                    hideLightbox();
                }
            });
        });
     });
</script>
<h1>Add Content</h1>
<div class="box">
    <h2>MediaTag Fields</h2>
    <table cellpadding="0" cellspacing="0">
        <tr><td class="first">mTag:</td><td><input id="mtag" name="mtag" type="text" value="" /></td><td><input id="lookup_mtag" type="button" value="Lookup" /></td></tr>
        <tr>
            <td class="first">mClass:</td>
            <td>
                <select id="mclass" name="mclass" style="width: 100px;">
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
    <h2>Content Sources / Media Keys</h2>
    <div id="tabs">
        <ul>
            <li><a href="#tabs-top-1">Content Sources</a></li>
            <li><a href="#tabs-top-2">Media Keys</a></li>
        </ul>
        <div id="tabs-top-1">
            <textarea id="content_sources"><?php echo $content_sources ?></textarea>
            <div align="right" style="padding-top: 5px;"><input id="add_trailer_url" type="button" value="Add Source" /></div>
        </div>
        <div id="tabs-top-2">
            <textarea id="media_keys"><?php echo $mediakeys ?></textarea>
            <div align="right" style="padding-top: 5px;"><input id="add_mkey" type="button" value="Add Key" />&nbsp;<input id="lookup_mkeys" type="button" value="Lookup" /></div>
        </div>
    </div>
</div>
<hr />
<div class="box">
    <h2>Content Fields</h2>
    <div id="content_fields_tabs">
        <ul>
            <li><a href="#tabs-1">Movie</a></li>
            <li><a href="#tabs-2">Series Season</a></li>
            <li><a href="#tabs-3">Series Episode</a></li>
            <li><a href="#tabs-3">Album</a></li>
            <li><a href="#tabs-3">Album Track</a></li>
        </ul>
        <div id="tabs-1">
            <table cellpadding="0" cellspacing="0">
                <tr><td class="first">Title:</td><td><input id="movie_title" name="movie_title" type="text" value="" /></td></tr>
                <tr><td class="first">Year:</td><td><input id="movie_year" name="movie_year" type="number" value="" /></td>
                <tr><td valign="top" class="first">Summary:</td><td><textarea id="movie_summary" name="movie_summary" type="text" value=""></textarea></td>
                <tr><td class="first">Genres:</td><td><input id="movie_genres" name="movie_genres" type="text" value="" /></td>
                <tr><td class="first">Actors:</td><td><input id="movie_actors" name="movie_actors" type="text" value="" /></td>
                <tr><td class="first">Directors:</td><td><input id="movie_directors" name="movie_directors" type="text" value="" /></td>
                <tr><td class="first">Producers:</td><td><input id="movie_producers" named="movie_producers" type="text" value="" /></td>
                <tr><td class="first">Writers:</td><td><input id="movie_writers" name="movie_writers" type="text" value="" /></td>
            </table>
            <div class="note">
                Note: Fields with multiple entries are separated by a semi-colon (;)
            </div>
        </div>
        <div id="tabs-2">
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