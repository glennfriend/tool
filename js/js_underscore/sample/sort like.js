// ============================================================
//  sort like
//
//
// ============================================================

var arrayValues = [[5, 1, 7], [3, 2, 1]];
log(
    _.invoke(arrayValues, 'sort')
);

var func = function(num) {
    return Math.sin(num); 
}
var values = [4, 5, 6, 1, 2, 3];
log(
    _.sortBy(values, func)
);
