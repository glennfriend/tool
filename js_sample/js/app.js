"use strict";

var app = {};
app.jsEditor   = {};
app.cssEditor  = {};
app.htmlEditor = {};

app.loadPackage = function( packageName )
{
    var areWeThereYet = function() {
        if ( total===3 ) {
            app.renderView();
        }
    };

    var iMilli = +new Date();
    var total = 0;
    var path = "sample/" + packageName + "/";

    $.get( path + "script.js?" + iMilli, function(resp) {
        total++;
        app.jsEditor.getSession().setValue( resp );
        areWeThereYet();
    }, "text");
    $.get( path + "style.css?" + iMilli, function(resp) {
        total++;
        app.cssEditor.getSession().setValue( resp );
        areWeThereYet();
    }, "text");
    $.get( path + "structure.html?" + iMilli, function(resp) {
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
        content += '    <style type="text/css">';
        content +=          struct.css
        content += '    </style>';
        content += '</head>';
        content += '<body>';
        content +=      struct.html;
        content += '    <script type="text/javascript" charset="utf-8">';
        content += '        "use strict";';
        content +=          struct.js;
        content += '    <' + '/script>';
        content += '</body>';
        content += '</html>';
        return content;
    }

    var struct = {
        'js'   : app.jsEditor.getSession().getValue(),
        'css'  : app.cssEditor.getSession().getValue(),
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
    app.jsEditor = ace.edit("showJs");
    app.jsEditor.setTheme("ace/theme/crimson_editor");
    app.jsEditor.getSession().setMode(new jsMode());
    app.jsEditor.getSession().on('change', function(o) {
        var result = app.parseEvent( o );
        if ( result.addCharCode === 10 || result.addCharCode === 13 ) {
            app.renderView();
        }
    });

    //
    app.htmlEditor = ace.edit("showHtml");
    app.htmlEditor.setTheme("ace/theme/crimson_editor");
    app.htmlEditor.getSession().setMode(new htmlMode());
    app.htmlEditor.getSession().on('change', function(o) {
        var result = app.parseEvent( o );
        if ( result.addCharCode === 10 || result.addCharCode === 13 ) {
            app.renderView();
        }
    });

    //
    app.cssEditor = ace.edit("showCss");
    app.cssEditor.setTheme("ace/theme/crimson_editor");
    app.cssEditor.getSession().setMode(new cssMode());
    app.cssEditor.getSession().on('change', function(o) {
        var result = app.parseEvent( o );
        if ( result.addCharCode === 10 || result.addCharCode === 13 ) {
            app.renderView();
        }
    });

    app.loadPackage('default');

    //
    // editor.getSession().setUseWrapMode(true);
    // editor.getSession().setUseSoftTabs(true);
    // editor.setPrintMarginColumn(false);
    // editor.setDisplayIndentGuides(false);

};

