"use strict";

var _createClass = (function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; })();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var Person = (function () {
    function Person(name) {
        _classCallCheck(this, Person);

        this.name = name;
    }

    _createClass(Person, [{
        key: "name",
        get: function () {
            return "my name is " + this._name;
        },
        set: function (name) {
            //[this.firstName, this.lastName] = name.split(" ")
            this._name = name;
        }
    }, {
        key: "luckNumber",
        value: function luckNumber() {
            var PI = 3.141593;
            return PI;
        }
    }]);

    return Person;
})();

var boy = new Person("kevin Wu");
boy.name = "Vivian Li";
console.log(boy.name);
console.log(boy);
console.log(boy.luckNumber());

//SyntaxError