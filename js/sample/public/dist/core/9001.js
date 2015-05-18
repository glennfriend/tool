// ================================================================================
//
// ================================================================================
'use strict';

var _createClass = (function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ('value' in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; })();

function _inherits(subClass, superClass) { if (typeof superClass !== 'function' && superClass !== null) { throw new TypeError('Super expression must either be null or a function, not ' + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) subClass.__proto__ = superClass; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError('Cannot call a class as a function'); } }

var ArticleParent = (function () {
    function ArticleParent() {
        _classCallCheck(this, ArticleParent);

        this.resetAll();
    }

    _createClass(ArticleParent, [{
        key: 'resetAll',
        value: function resetAll() {
            this._id = 0;
            this._blog_id = 0;
            this._topic = null;
            this._content = null;
            this._createDate = 0;
            this._updateDate = 0;
            this._user_id = 0;
            this._num_comments = 0

            // disabled
            // status, privatePassword
            ;
        }
    }, {
        key: 'getTopic',
        value: function getTopic() {
            return this._topic;
        }
    }, {
        key: 'setTopic',
        value: function setTopic(topic) {
            this._topic = topic;
        }
    }, {
        key: 'getContent',
        value: function getContent() {
            return this._content;
        }
    }, {
        key: 'setContent',
        value: function setContent(content) {
            this._content = content;
        }
    }]);

    return ArticleParent;
})();

var Article = (function (_ArticleParent) {
    function Article() {
        _classCallCheck(this, Article);

        if (_ArticleParent != null) {
            _ArticleParent.apply(this, arguments);
        }
    }

    _inherits(Article, _ArticleParent);

    _createClass(Article, [{
        key: 'getShow',
        value: function getShow() {
            return 'topic is ' + this._topic;
        }
    }]);

    return Article;
})(ArticleParent);

// ================================================================================
//
// ================================================================================
{
    var articles = [];
    {
        var _article = new Article();
        _article.setTopic('hello 1');
        _article.setContent('content 1');
        articles.push(_article);
    }
    {
        var article = new Article();
        article.setTopic('hello 2');
        article.setContent('content 2');
        articles.push(article);
    }
}

for (var i in articles) {
    var article = articles[i];
    console.log(article);
    console.log(article.getShow());
}

console.log('---- problem ----');
console.log('成員未私有化');
console.log('無法將 article.js 移出使用 import 方法');