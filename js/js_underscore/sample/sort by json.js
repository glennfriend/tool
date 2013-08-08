// ============================================================
//  對多屬性的 json 做排序
//
//  @use sortBy
//
// ============================================================

var items = [
    {name:'vivian', age:18  },
    {name:'KEN',    age:30  },
    {name:'baby',   age:0   },
];

var sortFunc = function(item) {
    return item.name; 
    // 想依年齡 ASC  排序可以使用 item.age 
    // 想依年齡 DESC 排序可以使用 -item.age 
}

log(
    _.sortBy(items, sortFunc)
);

