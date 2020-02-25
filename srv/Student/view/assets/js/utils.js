/**
 * Author: LyonWong
 * Date: 2018-05-21
 */

function screenAdaptor(designWidth, fit) {
  function f() {
    var d = document;
    var b = d.body;
    var s = b.style;
    s.maxWidth = fit ? fit(window.screen) +'px' : designWidth + 'px';
    s.fontSize = getComputedStyle(b)['font-size'];
    d.documentElement.style.fontSize = (b.offsetWidth * 100 / designWidth) + 'px';
  }
  f();
  window.addEventListener('onorientationchange' in window ? 'orientationchange' : 'resize', f, false);
}


