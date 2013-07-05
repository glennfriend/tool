// ============================================================
//  filter
//
//  @opposite reject
//
// ============================================================

var func = function(num) 
{ 
    return num % 2 == 0; 
};

var evens = _.filter([1, 2, 3, 4, 5, 6], func );

log(evens);
