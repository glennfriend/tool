"use strict";

var log = function( content )
{
    var type = Object.prototype.toString.call( content );
    var old = $("#info").html();

    if ( type === '[object object]' ) {
        content = getHtmlByObject( content );
    }
    else if ( type === '[object Array]' ) {
        content = getHtmlByObject( content );
    }
    else if ( type === '[object Undefined]' ) {
        content = '';
    }
    else {
    }

    if ( old !== '' ) {
        old += "<br />\n";
    }
    app.resultDisplay( old + content );
}

var getHtmlByObject = function( object )
{

    var html = prettyPrint( object , {
        maxArray: 20,   // Set max for array display (default: infinity)
        expanded: true, // Expanded view (boolean) (default: true),
        maxDepth: 5     // Max member depth (when displaying objects) (default: 3)
    });
    return $(html).html();

}