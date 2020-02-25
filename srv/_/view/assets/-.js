var _mtac = {};
(function() {
    var mta = document.createElement("script");
    mta.src = "https://pingjs.qq.com/h5/stats.js?v2.0.4";
    mta.setAttribute("name", "MTAH5");
    mta.setAttribute("sid", "APP_ID");
    var s = document.getElementsByTagName("script")[0];
    s.parentNode.insertBefore(mta, s);
})();

(function(){
    if (!location.search) {
        var url = location.protocol + '//' + location.host + location.pathname + '?v=2' + location.hash;
        history.replaceState('index', 'yike', url);
    }
})();
