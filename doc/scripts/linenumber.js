/*global document */
(function() {
    var source = document.getElementsByClassName('prettyprint source linenums'),
        lineNumber = 0,
        lineId,
        lines,
        totalLines,
        anchorHash,
        checkAnchor = function() {
            if (source && source[0]) {
                anchorHash = document.location.hash.substring(1);
                lines = source[0].querySelectorAll('li');
                totalLines = lines.length;

                for (i = 0; i < totalLines; i++) {
                    lineNumber++;
                    lineId = 'line' + lineNumber;
                    lines[i].id = lineId;

                    if (lineId === anchorHash) {
                        lines[i].className += ' selected';
                        var theURL = new URL( document.location.href );
                        theURL.hash = lineId;
                        document.location.href = theURL;
                    }
                }

                if ( totalLines ) return true;
                else return false;
            }
        },

        checkCurrLine = setInterval(function(){
            var is_good = checkAnchor();
            if ( is_good ) {
                clearInterval(checkCurrLine);
                checkCurrLine = null;
            }
        }, 300);
})();
