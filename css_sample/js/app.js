"use strict";

var app = {};
app.htmlEditor = {};

app.loadPackage = function( file )
{
    var areWeThereYet = function() {
        if ( total===1 ) {
            app.renderView();
        }
    };

    var iMilli = +new Date();
    var total = 0;
    var pathFile = "sample/" + file + ".html";

    $.get( pathFile + "?" + iMilli, function(resp) {
        total++;
        app.htmlEditor.getSession().setValue( resp );
        areWeThereYet();
    }, "text");

}


app.renderView = function()
{
    var mergeCode = function( struct ) {
        var content = '';
        content += '<!DOCTYPE html><head>';
        content += '    <meta http-equiv="Content-Language" content="zh-tw" />';
        content += '    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
        content += '</head>';
        content += '<body>';
        content +=      struct.html;
        content += '</body>';
        content += '</html>';
        return content;
    }

    var struct = {
        'html' : app.htmlEditor.getSession().getValue(),
    };

    $('#canvasView').contents().find("body").html( mergeCode(struct) );

};


app.parseEvent = function( obj )
{
    var result = {
        'addCharCode':    null,
        'removeCharCode': null,
    };

    if ( typeof(obj)           !== "undefined" &&
         typeof(obj.data.text) !== "undefined" 
    )
    {
        if ( obj.data.action==='insertText' ) {
            result.addCharCode = String.charCodeAt( obj.data.text );
        }
        else if ( obj.data.action==='removeText' ) {
            result.removeCharCode = String.charCodeAt( obj.data.text );
        }
    }
    
    // console.log(result);
    return result;
}

/**
 *  初始化 editor
 */
app.init = function()
{
    //
    var jsMode      = require("ace/mode/javascript").Mode;
    var cssMode     = require("ace/mode/css").Mode;
    var htmlMode    = require("ace/mode/html").Mode;

    //
    app.htmlEditor = ace.edit("showHtml");
    app.htmlEditor.setTheme("ace/theme/crimson_editor");
    //app.htmlEditor.setFontSize("14px");
    app.htmlEditor.getSession().setMode(new htmlMode());
    app.htmlEditor.getSession().on('change', function(o) {
        var result = app.parseEvent( o );
        if ( result.addCharCode === 10 || result.addCharCode === 13 ) {
            app.renderView();
        }
    });

    app.loadPackage('default');

};

