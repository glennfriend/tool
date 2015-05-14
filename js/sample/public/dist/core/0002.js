"use strict";

var _createClass = (function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; })();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var DefaultHelper = (function () {
    function DefaultHelper() {
        _classCallCheck(this, DefaultHelper);
    }

    _createClass(DefaultHelper, null, [{
        key: "getData",
        value: function getData() {
            return { x: 1 };
        }
    }]);

    return DefaultHelper;
})();

var data = DefaultHelper.getData();
console.log(data);