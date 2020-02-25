/**
 * Author: LyonWong
 * Date: 2015-12-01
 */

var Plot = {
    timeline: function (format, interval) {
        var tp = -8 * 3600 * 1000;
        var ep = tp + 24 * 3600 * 1000;
        var line = [];
        var date = new Date(tp);
        while (tp < ep) {
            line.push(date.format(format));
            tp += interval * 1000;
            date.setTime(tp);
        }
        return line;
    },
    trends: function (category, trends) {
        var data = [];
        for (var i in category) {
            data.push(trends[category[i]] || null)
        }
        return data;
    },
    diff: function (data) {
        var prev = 0;
        var ret = {};
        $.each (data, function (i, v) {
            ret[i] = v - prev;
            prev = v;
        });
        return ret;
    }
};
