// ============================================================
//  reduce
//
//      把列表中元素歸結為一個簡單的數值
//      Memo 是 reduce 函數的初始值
//      reduce的每一步都需要由 iterator 返回
//
//  @alias inject
//  @alias foldl
//
// ============================================================

var func = function( total, num ) {
    log('total ' + total + ' + ' + num);
    return total + num;
};

var sum = _.reduce([3, 7, 5], func, 0 );

log('----------');
log(sum);
