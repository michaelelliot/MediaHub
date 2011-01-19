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

    //MinV + (Math.floor((MaxV - MinV + 1) * (Math.random() % 1)))
    jQuery.extend({
        random: function(X) {
            return Math.floor(X * (Math.random() % 1));
        },
        randomBetween: function(MinV, MaxV) {
            return MinV + jQuery.random(MaxV - MinV + 1);
        }
    });