/**
 * Author: LyonWong
 * Date: 2016-01-05
 */

var Global = {
    platKey: Cookie.read('platKey')
};

$(function(){
    $('.sub-more > a').click(function(){
        window.location.href = $(this)[0].href;
    });
});

var Loading = {
    open: function(el){
        $(el).each(function(){
            var style = "width:" +$(this).width()+"px;height:"+$(this).height()+'px';
            var loading = "<div class='loading loading-frame'>" +
                "<div class='loading-mask' style='"+style+"'><div class='loading-spin'><div><i class='fa fa-spinner fa-spin'></i></div></div>" +
                "</div>";
            $(this).prepend(loading);
        });
    },
    close: function(el){
        var elm = el ? el+'>.loading' : '.loading';
        $(elm).remove();
    }
};
