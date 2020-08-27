var _ = require('lodash');

const val = [];

function multiplyBy10(val) {
    return val * 10;
}
_.map(['1', '2', '3', '4'], multiplyBy10);

console.log(val);
