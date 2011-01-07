<?php

//echo "<pre align='left'>";
//var_dump($_COOKIE);
//echo "</pre>";

?>

<script type="text/javascript">

    /**
     * Concatenates the values of a variable into an easily readable string
     * by Matt Hackett [scriptnode.com]
     * @param {Object} x The variable to debug
     * @param {Number} max The maximum number of recursions allowed (keep low, around 5 for HTML elements to prevent errors) [default: 10]
     * @param {String} sep The separator to use between [default: a single space ' ']
     * @param {Number} l The current level deep (amount of recursion). Do not use this parameter: it's for the function's own use
     */
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


    function lookup_mkeys(mkeys) {

        

    }

     $(function() {




        $('#lookup_mkeys').bind('click', function() {


        $.jsonRPC.setup({
            endPoint: 'http://localhost/MediaTag/src/api/',
            namespace: ''
        });


        $.jsonRPC.request('lookup_mkeys', [["btih:c389547e7551e9785c4fa87935824a5403d178e8","filename:test.torrent"]], {
            success: function(result) {

                try {
                    meob = result['meob'];
                    
                    switch(meob['mclass'].toLowerCase()) {
                        case 'movie':
                            if (typeof meob['title'] != 'undefined') $('#movie_title').val(meob['title']);
                            if (typeof meob['year'] != 'undefined') $('#movie_year').val(meob['year']);
                            if (typeof meob['genres'] != 'undefined') $('#movie_genres').val(meob['genres'].join(';'));
                            if (typeof meob['actors'] != 'undefined') $('#movie_actors').val(meob['actors'].join(';'));
                            if (typeof meob['directors'] != 'undefined') $('#movie_directors').val(meob['directors'].join(';'));
                            if (typeof meob['producers'] != 'undefined') $('#movie_producers').val(meob['producers'].join(';'));
                            if (typeof meob['writers'] != 'undefined') $('#movie_writers').val(meob['writers'].join(';'));
                            break;
                        default:
                            alert("Error: Unknown media class");
                            break;
                    }
                } catch(e) {
                    alert("Javascript error: " + e);
                }
            },
            error: function(result) {
                alert("Error: " + result['result']);
            }
        });




        
/*
        $.jsonRPC.setup({
            endPoint: 'http://localhost/MediaTag/src/api/',
            namespace: 'datagraph'
        });
        $.jsonRPC.request('method.name', [1,2,3], '', 'http://localhost/MediaTag/src/api/');
            */
/*
                    $.jsonRPC.setup({
            endPoint: 'http://localhost/MediaTag/src/api/',//,
            namespace: 'asd'
        });
        
            $.jsonRPC.request('lookup_mkeys', ['btih:XYZ1', 'filename:inception (2010).torrent'], {
                success: function(result) {
                    // Do something with the result here
                    // It comes back as an RPC 2.0 compatible response object
                    alert(var_dump(result['result']));
                },
                error: function(result) {
                    // Result is an RPC 2.0 compatible response object
                  alert(var_dump(result));
                    //alert("Error");
                }
            });
*/
            /*$.getJSON('json_lookup_mkeys.php', function(data) {
            $('.result').html('<p>' + data.sup + '</p>'
            + '<p>' + data.baz[1] + '</p>');
            });*/

        });

     });
</script>

<div class="box">
    <h2>Lookup Media Keys</h2>
    <h3>Media Keys</h3>
    <textarea id="media_keys"></textarea>
    <div align="right" style="padding-top: 5px;"><input id="lookup_mkeys" type="button" value="Lookup" /></div>
</div>
<hr />
<div class="box">
    <h2>Content Sources</h2>
    <h3>Sources</h3>
    <textarea id="content_sources"></textarea>
    <div align="right" style="padding-top: 5px;"><input id="add_trailer_url" type="button" value="Add Trailer URL" /></div>
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
<pre align="left"><?php print_r($_COOKIE) ?></pre>