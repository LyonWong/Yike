/**
 * Author: LyonWong
 * Date: 2015-12-01
 */

Date.prototype.format = function (pattern) {
    var vals = {
        '%Y': this.getFullYear(),
        '%m': this.getMonth()+1,
        '%d': this.getDate(),
        '%H': this.getHours(),
        '%i': this.getMinutes(),
        '%s': this.getSeconds()
    };
    for (var i in vals) {
        if (vals[i]<10) {
            vals[i] = '0'+vals[i];
        }
        pattern=pattern.replace(i, vals[i]);
    }
    return pattern;
};

String.prototype.repeat = function(n) {
    var a = new Array(n+1);
    return a.join(this.valueOf());
};

var Cookie = {
    prefix: '_',
    read: function (key) {
        var c = (('; ' + document.cookie).split('; ' + key + '=')[1] || '') + ';';
        return decodeURIComponent(c.substring(0, c.indexOf(';')));
    },
    write: function (key, value, expire, scope) {
        var cookie = '';

        if (expire) {
            var dt = new Date();
            dt.setTime(dt.getTime() + expire);
            cookie += "; expires=" + dt.toGMTString();
        }
        if (typeof (scope) == 'object') {
            if (scope.domain) {
                cookie += "; domain=" + scope.domain;
            }
            if (scope.path) {
                cookie += "; path=" + scope.path;
            }
        }
        document.cookie = key + "=" + encodeURIComponent(value) + cookie;
    }
};
