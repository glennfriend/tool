;"use strict";

(function(){

    /**
     * @see https://cythilya.github.io/2017/03/12/uuid/
     */
    var getStandardUuid = function() {
        var d = Date.now();
        if (typeof performance !== 'undefined' && typeof performance.now === 'function') {
            d += performance.now(); //use high-precision timer if available
        }
        return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
            var r = (d + Math.random() * 16) % 16 | 0;
            d = Math.floor(d / 16);
            return (c === 'x' ? r : (r & 0x3 | 0x8)).toString(16);
        });
    };

    /**
     * modify by getStandardUuid()
     */
    var getShortUuid = function() {
        var d = Date.now();
        if (typeof performance !== 'undefined' && typeof performance.now === 'function') {
            d += performance.now(); //use high-precision timer if available
        }
        return 'xxxxxxxx-kyxx'.replace(/[xy]/g, function (c) {
            var r = (d + Math.random() * 16) % 16 | 0;
            d = Math.floor(d / 16);
            return (c === 'x' ? r : (r & 0x3 | 0x8)).toString(16);
        });
    };




    // --------------------------------------------------------------------------------
    //  public
    // --------------------------------------------------------------------------------
    var _ = {};

    _.getUuid = function() {
        return this.getStandardUuid();
    };

    return _;

})();