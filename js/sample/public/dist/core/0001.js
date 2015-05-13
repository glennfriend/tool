"use strict";

var _createClass = (function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; })();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var Person = (function () {
    function Person(firstName, lastName) {
        _classCallCheck(this, Person);

        this.firstName = firstName;
        this.lastName = lastName;
    }

    _createClass(Person, [{
        key: "name",
        get: function () {
            return this.firstName + " " + this.lastName;
        },
        set: function (name) {
            var names = name.split(" ");
            this.firstName = names[0];
            this.lastName = names[1];
        }
    }]);

    return Person;
})();

var boy = new Person("Bo-Yi", "Wu");
boy.name = "Boy Apple";
console.log(boy.name);