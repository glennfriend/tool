// ================================================================================
// 
// ================================================================================
class ArticleParent
{
    constructor()
    {
        this.resetAll()
    }

    resetAll()
    {
        this._id           = 0
        this._blog_id      = 0
        this._topic        = null
        this._content      = null
        this._createDate   = 0
        this._updateDate   = 0
        this._user_id      = 0
        this._num_comments = 0

        // disabled
        // status, privatePassword
    }

    getTopic()
    {
        return this._topic
    }

    setTopic(topic)
    {
        this._topic = topic
    }

    getContent()
    {
        return this._content
    }

    setContent(content)
    {
        this._content = content
    }

}

class Article extends ArticleParent
{
    getShow()
    {
        return `topic is ${this._topic}`
    }
}

// ================================================================================
// 
// ================================================================================
{
    var articles = [];
    {
        let article = new Article()
        article.setTopic('hello 1');
        article.setContent('content 1');
        articles.push(article)
    }
    {
        var article = new Article()
        article.setTopic('hello 2');
        article.setContent('content 2');
        articles.push(article)
    }
}

for( let i in articles ) {
    var article = articles[i]
    console.log(article);
    console.log(article.getShow());
}

console.log('---- problem ----');
console.log('成員未私有化');
console.log('無法將 article.js 移出使用 import 方法');
