var items = [3,2,1,0,3,9,20];

log();
log('first -> ' + _.first(items) );

log();
log('last -> ' + _.last(items) );

log();
log('// delete all -> false, null, 0, "", undefined, NaN ' );
log('compact -> ' + _.compact(items) );

log();
log('uniq -> ' + _.uniq(items) );

