;"use strict";

//
// page utility
//
window.PageUtility = window.PageUtility || (function(){

    // --------------------------------------------------------------------------------
    //  config
    // --------------------------------------------------------------------------------
    var config = {
        "debug_cookie_name": "is_debug"
    };

    // --------------------------------------------------------------------------------
    //  private
    // --------------------------------------------------------------------------------

    // Cookie library
    var JsLibraryCookie=function(){function extend(){var i=0;var a={};for(;i<arguments.length;i++){var b=arguments[i];for(var c in b){a[c]=b[c]}}return a}function init(q){function api(b,c,d){var f;if(typeof document==='undefined'){return}var g=function(){var a=location.host.split('.').reverse();return'.'+a[1]+'.'+a[0]};if(arguments.length>1){d=extend({path:'/',domain:g()},api.defaults,d);if(typeof d.expires==='number'){var h=new Date();h.setMilliseconds(h.getMilliseconds()+d.expires*864e+5);d.expires=h}d.expires=d.expires?d.expires.toUTCString():'';try{f=JSON.stringify(c);if(/^[\{\[]/.test(f)){c=f}}catch(e){}if(!q.write){c=encodeURIComponent(String(c)).replace(/%(23|24|26|2B|3A|3C|3E|3D|2F|3F|40|5B|5D|5E|60|7B|7D|7C)/g,decodeURIComponent)}else{c=q.write(c,b)}b=encodeURIComponent(String(b));b=b.replace(/%(23|24|26|2B|5E|60|7C)/g,decodeURIComponent);b=b.replace(/[\(\)]/g,escape);var j='';for(var k in d){if(!d[k]){continue}j+='; '+k;if(d[k]===true){continue}j+='='+d[k]}return(document.cookie=b+'='+c+j)}if(!b){f={}}var l=document.cookie?document.cookie.split('; '):[];var m=/(%[0-9A-Z]{2})+/g;var i=0;for(;i<l.length;i++){var n=l[i].split('=');var o=n.slice(1).join('=');if(!this.json&&o.charAt(0)==='"'){o=o.slice(1,-1)}try{var p=n[0].replace(m,decodeURIComponent);o=q.read?q.read(o,p):q(o,p)||o.replace(m,decodeURIComponent);if(this.json){try{o=JSON.parse(o)}catch(e){}}if(b===p){f=o;break}if(!b){f[p]=o}}catch(e){}}return f}api.set=api;api.get=function(a){return api.call(api,a)};api.getJSON=function(){return api.apply({json:true},[].slice.call(arguments))};api.defaults={};api.remove=function(a,b){api(a,'',extend(b,{expires:-1}))};api.withConverter=init;return api}return init(function(){})};
    var factoryApi=function(a){var b=false;if(typeof define==='function'&&define.amd){define(a);b=true}if(typeof exports==='object'){module.exports=a();b=true}if(!b){var c=a();c.noConflict=function(){return c}}return c};
    var Cookie = factoryApi(JsLibraryCookie);

    // --------------------------------------------------------------------------------
    //  public
    // --------------------------------------------------------------------------------
    var _ = {};

    /**
     * version
     */
    _.ver = "1";

    /**
     * to log
     * if cookie name "is_debug" exists
     */
    _.log = function(message) {
        if (! this.getCookie(config.debug_cookie_name)) {
            return;
        }
        console.log(message);
    };




    return _;

})();
//
// page utility
//