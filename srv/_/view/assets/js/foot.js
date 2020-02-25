/**
 * Author: LyonWong
 * Date: 2018-09-03
 */
(function () {
  var mta = document.createElement("script");
  mta.src = "https://pingjs.qq.com/h5/stats.js?v2.0.4";
  mta.setAttribute("name", "MTAH5");
  mta.setAttribute("sid", "500486480");
  var s = document.getElementsByTagName("script")[0];
  s.parentNode.insertBefore(mta, s);
})();

(function() {
  var hm = document.createElement("script");
  hm.src = "https://hm.baidu.com/hm.js?0a0aac37343b546ea47c4b07f07a1426";
  var s = document.getElementsByTagName("script")[0];
  s.parentNode.insertBefore(hm, s);
})();

(function() {
  var match = document.referrer.match(/https?:\/\/([\w.]*\.)?(\w+\.\w+)\//)
  if (match) {
    var origin = `site-${match[2]}`
    if (match[1]) {
      origin += '-'+match[1].replace(/\.$/, '')
    }
    app.cookie.set('origin', origin)
  }
})();
