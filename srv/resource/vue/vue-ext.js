/** 
 * Author: LyonWong
 * Date: 2016-04-01
 */

Vue.directive('exec', function(){});
Vue.filter('round', function(number, precision){
    var scale = Math.pow(10, precision);
    return Math.round(number * scale ) / scale || null;
});
Vue.filter('suffix', function(input, suffix){
    return input ? input.toString() + suffix : null;
});
Vue.filter('divide', function(dividend, divisor){
    if (divisor) {
        return dividend / divisor;
    } else {
        return null;
    }
});
