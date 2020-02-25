/**
 * Author: LyonWong
 * Date: 2018-03-27
 */
module.exports = function (designWidth, fit) {
  function f() {
    let d = document;
    let b = d.body;
    let s = b.style;
    s.maxWidth = fit ? fit(window.screen) +'px' : designWidth + 'px';
    console.log('screen-adapt', b.offsetWidth, designWidth)
    d.documentElement.style.fontSize = (b.offsetWidth * 100 / designWidth) + 'px';
  }
  f();
  window.addEventListener('onorientationchange' in window ? 'orientationchange' : 'resize', f, false);
}
