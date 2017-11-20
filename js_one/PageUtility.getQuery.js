;"use strict";

(function(){

    /**
     * get parameter by name
     */
    var getParameterByName = function(name) {
        var url = window.location.href;
        if (! url) {
            return;
        }
        name = name.replace(/[\[\]]/g, "\\$&");
        var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
            results = regex.exec(url);
        if (! results) return null;
        if (! results[2]) return '';
        return decodeURIComponent(results[2].replace(/\+/g, " "));
    };




    // --------------------------------------------------------------------------------
    //  public
    // --------------------------------------------------------------------------------
    var _ = {};

    /**
     * 取得網址上的參數值
     */
    _.getQuery = function(key) {
        return this.getParameterByName(key);
    };

    return _;

})();