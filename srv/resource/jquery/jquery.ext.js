/**
 * Author: LyonWong
 * Date: 2018-03-10
 */
$.fn.press = function(fn, ms) {
  ms = ms || 1000;
  var $this = this;
  for(var i = 0;i<$this.length;i++){
    $this[i].addEventListener('touchstart', function(event) {
      ct = setTimeout(fn, ms);
    }, false);
    $this[i].addEventListener('touchend', function(event) {
      clearTimeout(ct);
    }, false);
  }
};
