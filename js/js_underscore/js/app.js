"use strict";

var app = {};
app.jsEditor = {};

app.loadPackage = function( file )
{
    var areWeThereYet = function() {
        if ( total===1 ) {
            app.renderView();
        }
    };

    var iMilli = +new Date();
    var total = 0;
    var pathFile = "sample/" + file + ".js";

    $.get( pathFile + "?" + iMilli, function(resp) {
        total++;
        app.jsEditor.getSession().setValue( resp );
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
        content += '    <' + 'script>';
        content += '        "use strict"; ' + "\n";
        content +=          struct.js;
        content += '    </' + 'script>';
        content += '</body>';
        content += '</html>';
        return content;
    }

    var struct = {
        'js' : app.jsEditor.getSession().getValue(),
    };

    app.resultClear();
    $('#canvasView').contents().find("body").html( mergeCode(struct) );
};


app.resultClear = function()
{
    $("#info").text('');
};

app.resultDisplay = function( content )
{
    $("#info").html( content );
};


app.parseEvent = function( obj )
{
    var result = {
        'addText':    null,
        'removeText': null,
        'lastChar':   null
    };

    if ( typeof(obj)           !== "undefined" &&
         typeof(obj.data.text) !== "undefined" 
    )
    {
        if ( obj.data.action==='insertText' ) {
            result.addText = obj.data.text;
            var len = result.addText.length
            if ( len > 0 ) {
                result.lastChar = result.addText[len-1];
            }
        }
        else if ( obj.data.action==='removeText' ) {
            result.removeText = obj.data.text;
        }
    }

    return result;
}

/**
 *  初始化 editor
 */
app.init = function()
{
    //
    var jsMode = require("ace/mode/javascript").Mode;

    //
    app.jsEditor = ace.edit("showJs");
    app.jsEditor.setTheme("ace/theme/crimson_editor");
    app.jsEditor.setFontSize("16px");
    app.jsEditor.getSession().setMode(new jsMode());
    app.jsEditor.getSession().on('change', function(o) {
        var result = app.parseEvent( o );
        if ( result.lastChar === "\n" ) {
            app.renderView();
        }
    });

    app.loadPackage('default');

};

