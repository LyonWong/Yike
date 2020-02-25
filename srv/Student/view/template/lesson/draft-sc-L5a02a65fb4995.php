<!DOCTYPE html>
<html style="-webkit-text-size-adjust: 100%; line-height: 1.60">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0,viewport-fit=cover">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no">


    <script nonce="2126483999" type="text/javascript">
        window.logs = {
            pagetime: {}
        };
        window.logs.pagetime['html_begin'] = (+new Date());
    </script>

    <script nonce="2126483999" type="text/javascript">
        var biz = "MzUyMDAzNDYxNw=="||"";
        var sn = "" || ""|| "00d182fe554eb3e7392cd2c896519279";
        var mid = "100000063" || ""|| "100000063";
        var idx = "1" || "" || "1";
        window.__allowLoadResFromMp = true;

    </script>
    <script nonce="2126483999" type="text/javascript">
        var page_begintime=+new Date,is_rumor="",norumor="";
        1*is_rumor&&!(1*norumor)&&biz&&mid&&(document.referrer&&-1!=document.referrer.indexOf("mp.weixin.qq.com/mp/rumor")||(location.href="http://mp.weixin.qq.com/mp/rumor?action=info&__biz="+biz+"&mid="+mid+"&idx="+idx+"&sn="+sn+"#wechat_redirect")),
            document.domain="qq.com";
    </script>
    <script nonce="2126483999" type="text/javascript">
        var MutationObserver=window.WebKitMutationObserver||window.MutationObserver||window.MozMutationObserver,isDangerSrc=function(t){
            if(t){
                var e=t.match(/http(?:s)?:\/\/([^\/]+?)(\/|$)/);
                if(e&&!/qq\.com(\:8080)?$/.test(e[1])&&!/weishi\.com$/.test(e[1]))return!0;
            }
            return!1;
        },ishttp=0==location.href.indexOf("http://");
        -1==location.href.indexOf("safe=0")&&ishttp&&"function"==typeof MutationObserver&&"mp.weixin.qq.com"==location.host&&(window.__observer_data={
            count:0,
            exec_time:0,
            list:[]
        },window.__observer=new MutationObserver(function(t){
            window.__observer_data.count++;
            var e=new Date,r=[];
            t.forEach(function(t){
                for(var e=t.addedNodes,o=0;o<e.length;o++){
                    var n=e[o];
                    if("SCRIPT"===n.tagName){
                        var i=n.src;
                        isDangerSrc(i)&&(window.__observer_data.list.push(i),r.push(n)),!i&&window.__nonce_str&&n.getAttribute("nonce")!=window.__nonce_str&&(window.__observer_data.list.push("inlinescript_without_nonce"),
                            r.push(n));
                    }
                }
            });
            for(var o=0;o<r.length;o++){
                var n=r[o];
                n.parentNode&&n.parentNode.removeChild(n);
            }
            window.__observer_data.exec_time+=new Date-e;
        }),window.__observer.observe(document,{
            subtree:!0,
            childList:!0
        })),function(){
            if(-1==location.href.indexOf("safe=0")&&Math.random()<.01&&ishttp&&HTMLScriptElement.prototype.__lookupSetter__&&"undefined"!=typeof Object.defineProperty){
                window.__danger_src={
                    xmlhttprequest:[],
                    script_src:[],
                    script_setAttribute:[]
                };
                var t="$"+Math.random();
                HTMLScriptElement.prototype.__old_method_script_src=HTMLScriptElement.prototype.__lookupSetter__("src"),
                    HTMLScriptElement.prototype.__defineSetter__("src",function(t){
                        t&&isDangerSrc(t)&&window.__danger_src.script_src.push(t),this.__old_method_script_src(t);
                    });
                var e="element_setAttribute"+t;
                Object.defineProperty(Element.prototype,e,{
                    value:Element.prototype.setAttribute,
                    enumerable:!1
                }),Element.prototype.setAttribute=function(t,r){
                    "SCRIPT"==this.tagName&&"src"==t&&isDangerSrc(r)&&window.__danger_src.script_setAttribute.push(r),
                        this[e](t,r);
                };
            }
        }();
    </script>

    <link rel="dns-prefetch" href="//res.wx.qq.com">
    <link rel="dns-prefetch" href="//mmbiz.qpic.cn">
    <link rel="shortcut icon" type="image/x-icon" href="//res.wx.qq.com/mmbizwap/zh_CN/htmledition/images/icon/common/favicon22c41b.ico">
    <script nonce="2126483999" type="text/javascript">
        String.prototype.html = function(encode) {
            var replace =["&#39;", "'", "&quot;", '"', "&nbsp;", " ", "&gt;", ">", "&lt;", "<", "&amp;", "&", "&yen;", "¥"];
            if (encode) {
                replace.reverse();
            }
            for (var i=0,str=this;i< replace.length;i+= 2) {
                str=str.replace(new RegExp(replace[i],'g'),replace[i+1]);
            }
            return str;
        };

        window.isInWeixinApp = function() {
            return /MicroMessenger/.test(navigator.userAgent);
        };

        window.getQueryFromURL = function(url) {
            url = url || 'http://qq.com/s?a=b#rd';
            var tmp = url.split('?'),
                query = (tmp[1] || "").split('#')[0].split('&'),
                params = {};
            for (var i=0; i<query.length; i++) {
                var arg = query[i].split('=');
                params[arg[0]] = arg[1];
            }
            if (params['pass_ticket']) {
                params['pass_ticket'] = encodeURIComponent(params['pass_ticket'].html(false).html(false).replace(/\s/g,"+"));
            }
            return params;
        };

        (function() {
            var params = getQueryFromURL(location.href);
            window.uin = params['uin'] || "NTg0MjAzMjU=" || '';
            window.key = params['key'] || "fef0cb6a4f422abcc027b116fb72c6d5a132566a197e53ebc245f3bde681445ba7e79d8b678dba115c2e1fa2f0a7fd7b74d1ec02a090957200d20abbae1a1881405785afe720218dba370f2349d4c983" || '';
            window.wxtoken = params['wxtoken'] || '';
            window.pass_ticket = params['pass_ticket'] || '';
            window.appmsg_token = "933_K%2FYauuxjTrG70UuINtAaHWDrsI6LzsUlePJOa3lczR0WBJhFfl29DHiQgM8~";
        })();

        function wx_loaderror() {
            if (location.pathname === '/bizmall/reward') {
                new Image().src = '/mp/jsreport?key=96&content=reward_res_load_err&r=' + Math.random();
            }
        }

    </script>

    <title>如何打造价值百万的细分网站</title>

    <style>html{-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;line-height:1.6}body{-webkit-touch-callout:none;font-family:-apple-system-font,"Helvetica Neue","PingFang SC","Hiragino Sans GB","Microsoft YaHei",sans-serif;background-color:#f3f3f3;line-height:inherit}body.rich_media_empty_extra{background-color:#fff}body.rich_media_empty_extra .rich_media_area_primary:before{display:none}h1,h2,h3,h4,h5,h6{font-weight:400;font-size:16px}*{margin:0;padding:0}a{color:#607fa6;text-decoration:none}.rich_media_inner{font-size:16px;word-wrap:break-word;-webkit-hyphens:auto;-ms-hyphens:auto;hyphens:auto}.rich_media_area_primary{position:relative;padding:20px 15px 15px;background-color:#fff}.rich_media_area_primary:before{content:" ";position:absolute;left:0;top:0;width:100%;height:1px;border-top:1px solid #e5e5e5;-webkit-transform-origin:0 0;transform-origin:0 0;-webkit-transform:scaleY(0.5);transform:scaleY(0.5);top:auto;bottom:-2px}.rich_media_area_primary .original_img_wrp{display:inline-block;font-size:0}.rich_media_area_primary .original_img_wrp .tips_global{display:block;margin-top:.5em;font-size:14px;text-align:right;width:auto;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;word-wrap:normal}.rich_media_area_extra{padding:0 15px 0}.rich_media_title{margin-bottom:10px;line-height:1.4;font-weight:400;font-size:24px}.icon_original_tag_primary{display:inline-block;padding:1px .65em;margin-top:-0.2em;vertical-align:middle;line-height:1.4;font-size:12px;border-top-left-radius:.85em 50%;-moz-border-radius-topleft:.85em 50%;-webkit-border-top-left-radius:.85em 50%;border-top-right-radius:.85em 50%;-moz-border-radius-topright:.85em 50%;-webkit-border-top-right-radius:.85em 50%;border-bottom-left-radius:.85em 50%;-moz-border-radius-bottomleft:.85em 50%;-webkit-border-bottom-left-radius:.85em 50%;border-bottom-right-radius:.85em 50%;-moz-border-radius-bottomright:.85em 50%;-webkit-border-bottom-right-radius:.85em 50%;border:1px solid #9e9e9e;color:#8c8c8c}.icon_original_tag_primary.title_tag{background-color:#e94442;border-color:#d04b4e;color:#fff;margin-bottom:.5em;padding:2px .65em;border-top-left-radius:.95em 50%;-moz-border-radius-topleft:.95em 50%;-webkit-border-top-left-radius:.95em 50%;border-top-right-radius:.95em 50%;-moz-border-radius-topright:.95em 50%;-webkit-border-top-right-radius:.95em 50%;border-bottom-left-radius:.95em 50%;-moz-border-radius-bottomleft:.95em 50%;-webkit-border-bottom-left-radius:.95em 50%;border-bottom-right-radius:.95em 50%;-moz-border-radius-bottomright:.95em 50%;-webkit-border-bottom-right-radius:.95em 50%}.rich_media_meta_list{margin-bottom:18px;line-height:20px;font-size:0}.rich_media_meta_list em{font-style:normal}.rich_media_meta{display:inline-block;vertical-align:middle;margin-right:8px;margin-bottom:10px;font-size:16px}.meta_original_tag{display:inline-block;vertical-align:middle;padding:1px .5em;border:1px solid #9e9e9e;color:#8c8c8c;border-top-left-radius:20% 50%;-moz-border-radius-topleft:20% 50%;-webkit-border-top-left-radius:20% 50%;border-top-right-radius:20% 50%;-moz-border-radius-topright:20% 50%;-webkit-border-top-right-radius:20% 50%;border-bottom-left-radius:20% 50%;-moz-border-radius-bottomleft:20% 50%;-webkit-border-bottom-left-radius:20% 50%;border-bottom-right-radius:20% 50%;-moz-border-radius-bottomright:20% 50%;-webkit-border-bottom-right-radius:20% 50%;font-size:15px;line-height:1.1}.meta_enterprise_tag img{width:30px;height:30px!important;display:block;position:relative;margin-top:-3px;border:0}.rich_media_meta_text{color:#8c8c8c}span.rich_media_meta_nickname{display:none}.rich_media_thumb_wrp{margin-bottom:6px}.rich_media_thumb_wrp .original_img_wrp{display:block}.rich_media_thumb{display:block;width:100%}.rich_media_content{overflow:hidden;color:#3e3e3e}.rich_media_content *{max-width:100%!important;box-sizing:border-box!important;-webkit-box-sizing:border-box!important;word-wrap:break-word!important}.rich_media_content p{clear:both;min-height:1em}.rich_media_content em{font-style:italic}.rich_media_content fieldset{min-width:0}.rich_media_content .list-paddingleft-2{padding-left:30px}.rich_media_content blockquote{margin:0;padding-left:10px;border-left:3px solid #dbdbdb}img{height:auto!important}@media screen and (device-aspect-ratio:2/3),screen and (device-aspect-ratio:40/71){.meta_original_tag{padding-top:0}}@media(min-device-width:375px) and (max-device-width:667px) and (-webkit-min-device-pixel-ratio:2){.mm_appmsg .rich_media_inner,.mm_appmsg .rich_media_meta,.mm_appmsg .discuss_list,.mm_appmsg .rich_media_extra,.mm_appmsg .title_tips .tips{font-size:17px}.mm_appmsg .meta_original_tag{font-size:15px}}@media(min-device-width:414px) and (max-device-width:736px) and (-webkit-min-device-pixel-ratio:3){.mm_appmsg .rich_media_title{font-size:25px}}@media only screen and (device-width:375px) and (device-height:812px) and (-webkit-device-pixel-ratio:3) and (orientation:portrait){.rich_media_area_extra{padding-bottom:34px}}@media only screen and (device-width:375px) and (device-height:812px) and (-webkit-device-pixel-ratio:3) and (orientation:landscape){.rich_media_area_primary{padding:20px 59px 15px 59px}.rich_media_area_extra{padding:0 59px 21px 59px}}@media screen and (min-width:1024px){.rich_media{width:740px;margin-left:auto;margin-right:auto}.rich_media_inner{padding:20px}body{background-color:#fff}}@media screen and (min-width:1025px){body{font-family:"Helvetica Neue",Helvetica,"Hiragino Sans GB","Microsoft YaHei",Arial,sans-serif}.rich_media{position:relative}.rich_media_inner{background-color:#fff;padding-bottom:100px}}.radius_avatar{display:inline-block;background-color:#fff;padding:3px;border-radius:50%;-moz-border-radius:50%;-webkit-border-radius:50%;overflow:hidden;vertical-align:middle}.radius_avatar img{display:block;width:100%;height:100%;border-radius:50%;-moz-border-radius:50%;-webkit-border-radius:50%;background-color:#eee}.cell{padding:.8em 0;display:block;position:relative}.cell_hd,.cell_bd,.cell_ft{display:table-cell;vertical-align:middle;word-wrap:break-word;word-break:break-all;white-space:nowrap}.cell_primary{width:2000px;white-space:normal}.flex_cell{padding:10px 0;display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-box-align:center;-webkit-align-items:center;-ms-flex-align:center;align-items:center}.flex_cell_primary{width:100%;-webkit-box-flex:1;-webkit-flex:1;-ms-flex:1;box-flex:1;flex:1}.original_tool_area{display:block;padding:.75em 1em 0;-webkit-tap-highlight-color:rgba(0,0,0,0);color:#3e3e3e;border:1px solid #eaeaea;margin:20px 0}.original_tool_area .tips_global{position:relative;padding-bottom:.5em;font-size:15px}.original_tool_area .tips_global:after{content:" ";position:absolute;left:0;bottom:0;right:0;height:1px;border-bottom:1px solid #dbdbdb;-webkit-transform-origin:0 100%;transform-origin:0 100%;-webkit-transform:scaleY(0.5);transform:scaleY(0.5)}.original_tool_area .radius_avatar{width:27px;height:27px;padding:0;margin-right:.5em}.original_tool_area .radius_avatar img{height:100%!important}.original_tool_area .flex_cell_bd{width:auto;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;word-wrap:normal}.original_tool_area .flex_cell_ft{font-size:14px;color:#8c8c8c;padding-left:1em;white-space:nowrap}.original_tool_area .icon_access:after{content:" ";display:inline-block;height:8px;width:8px;border-width:1px 1px 0 0;border-color:#cbcad0;border-style:solid;transform:matrix(0.71,0.71,-0.71,0.71,0,0);-ms-transform:matrix(0.71,0.71,-0.71,0.71,0,0);-webkit-transform:matrix(0.71,0.71,-0.71,0.71,0,0);position:relative;top:-2px;top:-1px}.weui_loading{width:20px;height:20px;display:inline-block;vertical-align:middle;-webkit-animation:weuiLoading 1s steps(12,end) infinite;animation:weuiLoading 1s steps(12,end) infinite;background:transparent url(data:image/svg+xml;base64,PHN2ZyBjbGFzcz0iciIgd2lkdGg9JzEyMHB4JyBoZWlnaHQ9JzEyMHB4JyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAxMDAgMTAwIj4KICAgIDxyZWN0IHg9IjAiIHk9IjAiIHdpZHRoPSIxMDAiIGhlaWdodD0iMTAwIiBmaWxsPSJub25lIiBjbGFzcz0iYmsiPjwvcmVjdD4KICAgIDxyZWN0IHg9JzQ2LjUnIHk9JzQwJyB3aWR0aD0nNycgaGVpZ2h0PScyMCcgcng9JzUnIHJ5PSc1JyBmaWxsPScjRTlFOUU5JwogICAgICAgICAgdHJhbnNmb3JtPSdyb3RhdGUoMCA1MCA1MCkgdHJhbnNsYXRlKDAgLTMwKSc+CiAgICA8L3JlY3Q+CiAgICA8cmVjdCB4PSc0Ni41JyB5PSc0MCcgd2lkdGg9JzcnIGhlaWdodD0nMjAnIHJ4PSc1JyByeT0nNScgZmlsbD0nIzk4OTY5NycKICAgICAgICAgIHRyYW5zZm9ybT0ncm90YXRlKDMwIDUwIDUwKSB0cmFuc2xhdGUoMCAtMzApJz4KICAgICAgICAgICAgICAgICByZXBlYXRDb3VudD0naW5kZWZpbml0ZScvPgogICAgPC9yZWN0PgogICAgPHJlY3QgeD0nNDYuNScgeT0nNDAnIHdpZHRoPSc3JyBoZWlnaHQ9JzIwJyByeD0nNScgcnk9JzUnIGZpbGw9JyM5Qjk5OUEnCiAgICAgICAgICB0cmFuc2Zvcm09J3JvdGF0ZSg2MCA1MCA1MCkgdHJhbnNsYXRlKDAgLTMwKSc+CiAgICAgICAgICAgICAgICAgcmVwZWF0Q291bnQ9J2luZGVmaW5pdGUnLz4KICAgIDwvcmVjdD4KICAgIDxyZWN0IHg9JzQ2LjUnIHk9JzQwJyB3aWR0aD0nNycgaGVpZ2h0PScyMCcgcng9JzUnIHJ5PSc1JyBmaWxsPScjQTNBMUEyJwogICAgICAgICAgdHJhbnNmb3JtPSdyb3RhdGUoOTAgNTAgNTApIHRyYW5zbGF0ZSgwIC0zMCknPgogICAgPC9yZWN0PgogICAgPHJlY3QgeD0nNDYuNScgeT0nNDAnIHdpZHRoPSc3JyBoZWlnaHQ9JzIwJyByeD0nNScgcnk9JzUnIGZpbGw9JyNBQkE5QUEnCiAgICAgICAgICB0cmFuc2Zvcm09J3JvdGF0ZSgxMjAgNTAgNTApIHRyYW5zbGF0ZSgwIC0zMCknPgogICAgPC9yZWN0PgogICAgPHJlY3QgeD0nNDYuNScgeT0nNDAnIHdpZHRoPSc3JyBoZWlnaHQ9JzIwJyByeD0nNScgcnk9JzUnIGZpbGw9JyNCMkIyQjInCiAgICAgICAgICB0cmFuc2Zvcm09J3JvdGF0ZSgxNTAgNTAgNTApIHRyYW5zbGF0ZSgwIC0zMCknPgogICAgPC9yZWN0PgogICAgPHJlY3QgeD0nNDYuNScgeT0nNDAnIHdpZHRoPSc3JyBoZWlnaHQ9JzIwJyByeD0nNScgcnk9JzUnIGZpbGw9JyNCQUI4QjknCiAgICAgICAgICB0cmFuc2Zvcm09J3JvdGF0ZSgxODAgNTAgNTApIHRyYW5zbGF0ZSgwIC0zMCknPgogICAgPC9yZWN0PgogICAgPHJlY3QgeD0nNDYuNScgeT0nNDAnIHdpZHRoPSc3JyBoZWlnaHQ9JzIwJyByeD0nNScgcnk9JzUnIGZpbGw9JyNDMkMwQzEnCiAgICAgICAgICB0cmFuc2Zvcm09J3JvdGF0ZSgyMTAgNTAgNTApIHRyYW5zbGF0ZSgwIC0zMCknPgogICAgPC9yZWN0PgogICAgPHJlY3QgeD0nNDYuNScgeT0nNDAnIHdpZHRoPSc3JyBoZWlnaHQ9JzIwJyByeD0nNScgcnk9JzUnIGZpbGw9JyNDQkNCQ0InCiAgICAgICAgICB0cmFuc2Zvcm09J3JvdGF0ZSgyNDAgNTAgNTApIHRyYW5zbGF0ZSgwIC0zMCknPgogICAgPC9yZWN0PgogICAgPHJlY3QgeD0nNDYuNScgeT0nNDAnIHdpZHRoPSc3JyBoZWlnaHQ9JzIwJyByeD0nNScgcnk9JzUnIGZpbGw9JyNEMkQyRDInCiAgICAgICAgICB0cmFuc2Zvcm09J3JvdGF0ZSgyNzAgNTAgNTApIHRyYW5zbGF0ZSgwIC0zMCknPgogICAgPC9yZWN0PgogICAgPHJlY3QgeD0nNDYuNScgeT0nNDAnIHdpZHRoPSc3JyBoZWlnaHQ9JzIwJyByeD0nNScgcnk9JzUnIGZpbGw9JyNEQURBREEnCiAgICAgICAgICB0cmFuc2Zvcm09J3JvdGF0ZSgzMDAgNTAgNTApIHRyYW5zbGF0ZSgwIC0zMCknPgogICAgPC9yZWN0PgogICAgPHJlY3QgeD0nNDYuNScgeT0nNDAnIHdpZHRoPSc3JyBoZWlnaHQ9JzIwJyByeD0nNScgcnk9JzUnIGZpbGw9JyNFMkUyRTInCiAgICAgICAgICB0cmFuc2Zvcm09J3JvdGF0ZSgzMzAgNTAgNTApIHRyYW5zbGF0ZSgwIC0zMCknPgogICAgPC9yZWN0Pgo8L3N2Zz4=) no-repeat;-webkit-background-size:100%;background-size:100%}@-webkit-keyframes weuiLoading{0%{-webkit-transform:rotate3d(0,0,1,0deg)}100%{-webkit-transform:rotate3d(0,0,1,360deg)}}@keyframes weuiLoading{0%{-webkit-transform:rotate3d(0,0,1,0deg)}100%{-webkit-transform:rotate3d(0,0,1,360deg)}}.gif_img_wrp{display:inline-block;font-size:0;position:relative;font-weight:400;font-style:normal;text-indent:0;text-shadow:none 1px 1px rgba(0,0,0,0.5)}.gif_img_wrp img{vertical-align:top}.gif_img_tips{background:rgba(0,0,0,0.6)!important;filter:progid:DXImageTransform.Microsoft.gradient(GradientType=0,startColorstr='#99000000',endcolorstr = '#99000000');border-top-left-radius:1.2em 50%;-moz-border-radius-topleft:1.2em 50%;-webkit-border-top-left-radius:1.2em 50%;border-top-right-radius:1.2em 50%;-moz-border-radius-topright:1.2em 50%;-webkit-border-top-right-radius:1.2em 50%;border-bottom-left-radius:1.2em 50%;-moz-border-radius-bottomleft:1.2em 50%;-webkit-border-bottom-left-radius:1.2em 50%;border-bottom-right-radius:1.2em 50%;-moz-border-radius-bottomright:1.2em 50%;-webkit-border-bottom-right-radius:1.2em 50%;line-height:2.3;font-size:11px;color:#fff;text-align:center;position:absolute;bottom:10px;left:10px;min-width:65px}.gif_img_tips.loading{min-width:75px}.gif_img_tips i{vertical-align:middle;margin:-0.2em .73em 0 -2px}.gif_img_play_arrow{display:inline-block;width:0;height:0;border-width:8px;border-style:dashed;border-color:transparent;border-right-width:0;border-left-color:#fff;border-left-style:solid;border-width:5px 0 5px 8px}.gif_img_loading{width:14px;height:14px}i.gif_img_loading{margin-left:-4px}.gif_bg_tips_wrp{position:relative;height:0;line-height:0;margin:0;padding:0}.gif_bg_tips_wrp .gif_img_tips_group{position:absolute;top:0;left:0;z-index:9999}.gif_bg_tips_wrp .gif_img_tips_group .gif_img_tips{top:0;left:0;bottom:auto}.rich_media_global_msg{position:fixed;top:0;left:0;right:0;padding:1em 35px 1em 15px;z-index:2;background-color:#c6e0f8;color:#8c8c8c;font-size:13px}.rich_media_global_msg .icon_closed{position:absolute;right:15px;top:50%;margin-top:-5px;line-height:300px;overflow:hidden;-webkit-tap-highlight-color:rgba(0,0,0,0);background:transparent url(//res.wx.qq.com/mmbizwap/zh_CN/htmledition/images/icon/appmsg/icon_appmsg_msg_closed_sprite.2x2eb52b.png) no-repeat 0 0;width:11px;height:11px;vertical-align:middle;display:inline-block;-webkit-background-size:100% auto;background-size:100% auto}.rich_media_global_msg .icon_closed:active{background-position:0 -17px}.preview_appmsg .rich_media_title{margin-top:1.9em}@media screen and (min-width:1024px){.rich_media_global_msg{position:relative;margin:0 20px}.preview_appmsg .rich_media_title{margin-top:0}}.pages_reset{color:#3e3e3e;line-height:1.6;font-size:16px;font-weight:400;font-style:normal;text-indent:0;letter-spacing:normal;text-align:left;text-decoration:none;white-space:normal}.weapp_element,.weapp_display_element,.mp-miniprogram{display:block;margin:1em 0}.share_audio_context{margin:16px 0}.weapp_text_link{font-size:17px}.weapp_text_link:before{content:'';display:inline-block;line-height:1;background-size:12px 12px;background-repeat:no-repeat;background-image:url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABwAAAAcCAMAAABF0y+mAAAAb1BMVEUAAAB4it11h9x2h9x2h9x2htx8j+R8i+B1h9x2h9x3h92Snv91htt2h9x1h9x4h9x1h9x1h9x2idx1h9t2h9t1htt1h9x1h9x1htx2h9x1h912h9x4h913iN17juOOjuN1iNx2h9t4h958i+B1htvejBiPAAAAJHRSTlMALPLcxKcVEOXXUgXtspU498sx69DPu5+Yc2JeRDwbCYuIRiGBtoolAAAA3ElEQVQoz62S1xKDIBBFWYiFYImm2DWF///G7DJEROOb58U79zi4O8iOo8zuCRfV8EdFgbYE49qFQs8ksJInajOA1wWfYvLcGSueU/oUGBtPpti09uNS68KTMcrQ5jce4kmN/HKn9XVPAo702JEdx9hTUrWUqVrI3KwUmM1NhIWMKdwiGvpGMWZOAj1PZuzAxHwhVSplrajoseBnbyDHAwvrtvKKhdqTtFBkL8wO5ijcsS3G1JMNvQ5mdW7fc0x0+ZcnlJlZiflAomdEyFaM7qeK2JahEjy5ZyU7jC/q/Rz/DgqEuAAAAABJRU5ErkJggg==');vertical-align:middle;font-size:11px;color:#888;border-radius:10px;background-color:#f4f4f4;margin-right:6px;margin-top:-4px;background-position:center;height:20px;width:20px}.weui-mask{position:fixed;z-index:1000;top:0;right:0;left:0;bottom:0;background:rgba(0,0,0,0.6)}.weui-dialog{position:fixed;z-index:5000;width:80%;max-width:300px;top:50%;left:50%;-webkit-transform:translate(-50%,-50%);transform:translate(-50%,-50%);background-color:#fff;text-align:center;border-radius:3px;overflow:hidden}.weui-dialog__hd{padding:1.3em 1.6em .5em}.weui-dialog__title{font-weight:400;font-size:18px}.weui-dialog__bd{padding:0 1.6em .8em;min-height:40px;font-size:15px;line-height:1.3;word-wrap:break-word;word-break:break-all;color:#999}.weui-dialog__bd:first-child{padding:2.7em 20px 1.7em;color:#353535}.weui-dialog__ft{position:relative;line-height:48px;font-size:18px;display:-webkit-box;display:-webkit-flex;display:flex}.weui-dialog__ft:after{content:" ";position:absolute;left:0;top:0;right:0;height:1px;border-top:1px solid #d5d5d6;color:#d5d5d6;-webkit-transform-origin:0 0;transform-origin:0 0;-webkit-transform:scaleY(0.5);transform:scaleY(0.5)}.weui-dialog__btn{display:block;-webkit-box-flex:1;-webkit-flex:1;flex:1;color:#3cc51f;text-decoration:none;-webkit-tap-highlight-color:rgba(0,0,0,0);position:relative}.weui-dialog__btn:active{background-color:#eee}.weui-dialog__btn:after{content:" ";position:absolute;left:0;top:0;width:1px;bottom:0;border-left:1px solid #d5d5d6;color:#d5d5d6;-webkit-transform-origin:0 0;transform-origin:0 0;-webkit-transform:scaleX(0.5);transform:scaleX(0.5)}.weui-dialog__btn:first-child:after{display:none}.weui-dialog__btn_default{color:#353535}.weui-dialog__btn_primary{color:#0bb20c}</style>
    <style>
    </style>
    <!--[if lt IE 9]>
    <link onerror="wx_loaderror(this)" rel="stylesheet" type="text/css" href="//res.wx.qq.com/mmbizwap/zh_CN/htmledition/style/page/appmsg/page_mp_article_improve_pc2c9cd6.css">
    <![endif]-->

</head>
<body id="activity-detail" class="zh_CN mm_appmsg">

<script nonce="2126483999" type="text/javascript">
    var write_sceen_time = (+new Date());
</script>

<div id="js_article" class="rich_media preview_appmsg">

    <div id="js_top_ad_area" class="top_banner"></div>

    <div class="rich_media_inner">
        <div class="rich_media_global_msg">

        <a id="js_close_temp" href="<?=$this->url?>">如何打造价值百万的细分网站,点击前往课堂</a>
        </div>
        <div id="page-content" class="rich_media_area_primary">

            <div id="img-content">

                <h2 class="rich_media_title" id="activity-name">
                    如何打造价值百万的细分网站                                    </h2>
                <div id="meta_content" class="rich_media_meta_list">

                    <span class="rich_media_meta rich_media_meta_text rich_media_meta_nickname">易灵微课</span>

                        <span class="profile_arrow_wrp" id="js_profile_arrow_wrp">
                            <i class="profile_arrow arrow_out"></i>
                            <i class="profile_arrow arrow_in"></i>
                        </span>
                    </div>
                </div>







                <div class="rich_media_content " id="js_content">






                    <p style="text-align:left;">大家好，我是<span style="font-family: Arial;">jersy</span>，前网易<span style="font-family: Arial;">PM</span>，因为觉得单纯上班工作越来越无趣，躁动的心总想着搞点事情，便以小白的身份扎进了英文流量圈子，玩起了亚马逊<span style="font-family: Arial;">niche</span>站，这个对于普通人来说门槛最低的行业，曾<span style="font-family: Arial;">6</span>个月打造出价值百万的<span style="font-family: Arial;">niche</span>站。应亦仁的邀请，为大家介绍<span style="font-family: Arial;">niche</span>站相关的一些知识。</p><p style="text-align:left;">&nbsp;</p><p style="margin-top: 27px;margin-bottom: 8px;text-align: left;"><span style="font-size: 27px;">何</span><span style="font-size: 27px;font-family: SimSun;">为</span><span style="font-size: 27px;font-family: Arial;">niche</span><span style="font-size: 27px;">？</span></p><p style="text-align:left;">&nbsp;</p><p style="text-align:left;"><span style="font-family: Arial;">niche</span>意为细分市场，因为小或者细分，竞争弱，通过关键词找到用户需求（如经典的<span style="font-family: Arial;">Instgram </span>图片下载需求），你的解决方案可以是内容<span style="font-family: Arial;">/</span>工具<span style="font-family: Arial;">/</span>实体产品，通过导流<span style="font-family: Arial;">/</span>增值服务<span style="font-family: Arial;">/</span>电商实现盈利。</p><p style="text-align:left;">&nbsp;</p><p style="text-align:left;">今天这里我要说的是针对普通人来说门槛最低的一种选择，亚马逊<span style="font-family: Arial;">niche</span>站，顾名思义，即瞄准<span style="font-family: Arial;">”best+product”</span>这类有着挑选<span style="font-family: Arial;">&amp;</span>测评需求关键词，撰写测评文章，（用户每通过你的链接达成的交易你可以得到一定百分比的佣金）以搜索引擎作为主要流量来源，最后通过亚马逊赚取佣金的站点。</p><p style="text-align:left;">&nbsp;</p><p style="text-align:left;">不要小瞧了这种站点，一个月流量<span style="font-family: Arial;">3w</span>的<span style="font-family: Arial;">review</span>站点，一个月利润可以达到<span style="font-family: Arial;">5k-8k</span>刀，如果卖掉，价值是其月收入的<span style="font-family: Arial;">30</span>倍左右，可以达到<span style="font-family: Arial;">15w-24w</span>刀。</p><p style="text-align:left;">&nbsp;</p><p style="text-align:left;">接下来的内容围绕<span style="font-family: Arial;">Niche</span>站建设的几个重点，即：</p><p style="text-align:left;">&nbsp;</p><p style="text-align:left;">「先确定测评的是洗衣机还是笔记本<span style="font-family: Arial;">=</span>挑选<span style="font-family: Arial;">niche</span>」</p><p style="text-align:left;">「怎么写出大量且高转化的文章<span style="font-family: Arial;">=</span>产出内容」</p><p style="text-align:left;">「如何获取流量<span style="font-family: Arial;">=SEO</span>」</p><p style="margin-bottom:16px;text-align:left;"><br  /><br  /></p><p style="margin-top: 27px;margin-bottom: 8px;text-align: left;"><span style="font-size: 27px;">挑</span><span style="font-size: 27px;font-family: SimSun;">选</span><span style="font-size: 27px;font-family: Arial;">niche</span></p><p style="text-align:left;">&nbsp;</p><p style="text-align:left;">其实<span style="font-family: Arial;">niche</span>站这玩意在国外极为流行，所以玩家众多，量大客单价高的坑位早就被很多人占了，竞争异常激烈，一个新玩家去这里就是送死，所以挑选合适的弱竞争的<span style="font-family: Arial;">niche</span>就十分重要了（且看<span style="font-family: Arial;">10beasts</span>的<span style="font-family: Arial;">niche</span>策略也是选了诸如<span style="font-family: Arial;"> 3D PEN</span>这种极为冷门的和<span style="font-family: Arial;">Wireles router</span>这种竞争极大的，希望能借弱竞争的文章起来后带起来那些竞争大的）</p><p style="text-align:left;">&nbsp;</p><p style="text-align:left;">这里有个好方法，针对<span style="font-family: Arial;">review</span>站找合适的<span style="font-family: Arial;">niche(review</span>站即亚马逊<span style="font-family: Arial;">niche</span>站，因为<span style="font-family: Arial;">review</span>意为测评），既然大家很多都是通过亚马逊赚佣金，做的都是亚马逊现有的产品，那亚马逊已经为我们做好了品类划分了，详尽一级<span style="font-family: Arial;">/</span>二级<span style="font-family: Arial;">/</span>三级分类，直接在这些分类里找就好了。</p><p style="text-align:left;">&nbsp;</p><p style="text-align:left;"><span style="font-family: Arial;"></span></p><p><img data-s="300,640" data-type="png" src="<?=\view::src('img/sc/-1/image001.png')?>" style="" class="" data-ratio="0.3638297872340426" data-w="1880"  /></p><p style="text-align:left;"><span style="font-family: Arial;"></span><br  /></p><p style="margin-bottom:16px;text-align:left;">&nbsp;</p><p style="margin-top: 24px;margin-bottom: 8px;text-align: left;"><span style="font-size: 21px;">找到一个</span><span style="font-size: 21px;font-family: Arial;">niche</span><span style="font-size: 21px;">后，如何衡量其</span><strong><span style="font-size: 21px;font-family: SimSun;">竞争程度</span></strong><span style="font-size: 21px;">以及</span><strong><span style="font-size: 21px;font-family: Arial;">niche</span></strong><strong><span style="font-size: 21px;">的大小</span></strong><span style="font-size: 21px;">呢？</span></p><p style="text-align:left;">&nbsp;</p><p style="text-align:left;">关于衡量竞争度，这里推荐两个工具，<span style="font-family: Arial;">longtailpro.com </span>和<span style="font-family: Arial;"> kwfinder.com</span>，他们会通过检索关键词下排名前二十的网站和网站<span style="font-family: Arial;">DA/PA/</span>建站时间等各项数据，量化出一个竞争度数据</p><p><img data-s="300,640" data-type="png" src="<?=\view::src('img/sc/-1/image002.png')?>" style="" class="" data-ratio="0.5014513788098693" data-w="1378"  /></p><p style="margin-top: 24px;margin-bottom: 8px;text-align: left;"><span style="font-size: 21px;">那么</span><span style="font-size: 21px;font-family: Arial;">niche</span><span style="font-size: 21px;">大小呢？</span></p><p style="text-align:left;"><span style="font-family: Arial;">ahrefs.com</span>的关键词工具</p><p style="text-align:left;">要知道，关键词之间是有着一定的从属关系的，比如「<span style="font-family: Arial;">best drone for beginner</span>」就是「<span style="font-family: Arial;">best drone</span>」往下一个细分的关键词，<span style="font-family: Arial;">ahref</span>的这个工具就是帮你搜索出每个根词下面有多少长尾词<span style="font-family: Arial;">,</span>从而得出这个<span style="font-family: Arial;">niche</span>的大小。</p><p style="text-align:left;"><span style="font-family: Arial;"></span></p><p><img data-s="300,640" data-type="png" src="<?=\view::src('img/sc/-1/image003.png')?>" style="" class="" data-ratio="0.4973404255319149" data-w="1880"  /></p><p style="text-align:left;"><span style="font-family: Arial;"></span><br  /></p><p style="text-align:left;"><em><span style="font-size: 13px;">以「</span></em><em><span style="font-size: 13px;font-family: Arial;">best drone</span></em><em><span style="font-size: 13px;">」为词根搜索关联词，发现其下有「</span></em><em><span style="font-size: 13px;font-family: Arial;">bestdrone under 100</span></em><em><span style="font-size: 13px;">」「</span></em><em><span style="font-size: 13px;font-family: Arial;">best beginner drone</span></em><em><span style="font-size: 13px;">」等子词，这样已经可以大致推算出这个</span></em><em><span style="font-size: 13px;font-family: Arial;">niche</span></em><em><span style="font-size: 13px;">的大小了</span></em></p><p style="text-align:left;">&nbsp;</p><p style="text-align:left;">（<span style="font-family: Arial;">ahrefs</span>的关键词难度指标不可信，因为他只是简单地通过外链和自己的评级，十分不准确）</p><p style="margin-top: 24px;margin-bottom: 8px;text-align: left;"><span style="font-size: 21px;">例子</span></p><p style="text-align:left;">这里给大家分享一个靠挑选弱竞争<span style="font-family: Arial;">niche</span>登顶的站，<span style="font-family: Arial;">wiki.ezvid.com</span></p><p style="text-align:left;">我们当时从亚马逊一个品类一个品类刷过来，每当我们每找到一个弱竞争的<span style="font-family: Arial;">niche</span>，发现这家伙早就在那稳稳地占着了（此外还做了<span style="font-family: Arial;">youtube</span>视频，首页被一个站占了三个坑位）。</p><p style="text-align:left;">对于这种弱竞争的词，放眼望去都是小站，如果你稍微做的时间久一点，权威高一点，登顶极为容易。</p><p style="text-align:left;">&nbsp;</p><p style="text-align:left;"><span style="font-family: Arial;"></span></p><p><img data-s="300,640" data-type="png" src="<?=\view::src('img/sc/-1/image004.png')?>" style="" class="" data-ratio="1.6292682926829267" data-w="820"  /></p><p style="text-align:left;"><span style="font-family: Arial;"></span><br  /></p><p style="margin-bottom:16px;text-align:left;">&nbsp;</p><p style="margin-top: 27px;margin-bottom: 8px;text-align: left;"><span style="font-size: 27px;">如何做内容</span></p><p style="text-align:left;">&nbsp;</p><p style="text-align:left;">虽然很多人把『内容为王，外链为皇』挂在嘴边，但是如果没有具体的执行细节，这句话和废话也没什么区别。</p><p style="text-align:left;">而且在我看来，以前单纯靠外链提升排名忽视内容做法非常不可取，因为外链并非长久之计。谷歌每时每刻都在检测用户访问的时间以及访问深度，如果用户进了你的网页，只呆了十秒就跳出的话，那无疑是在告诉谷歌，这个内容很差，那么接着你的排名下降也就不稀奇了。</p><p style="text-align:left;">所以很明显，内容才是保持站点长久的源动力。</p><p style="text-align:left;">&nbsp;<span style="font-size: 21px;">内容</span><span style="font-size: 21px;font-family: SimSun;">结</span><span style="font-size: 21px;">构</span></p><p style="text-align:left;"><span style="font-family: Arial;">niche</span>站的文章有两类：</p><p style="text-align:left;"><span style="font-family: Arial;">money article </span>即通过亚马逊联盟赚取佣金的文章，说白了就是测评文章。</p><p style="text-align:left;"><span style="font-family: Arial;">information article,</span>即一般信息类文章，不能产生收益，大都是<span style="font-family: Arial;">how to </span>的纯知识类文章，但可以引流和吸引外链。</p><p style="text-align:left;">&nbsp;为什么要这样设置？因为<span style="font-family: Arial;">money article</span>想要获得外链很难，国外亚马逊联盟很流行，但是大家对此却很敏感，毕竟用户在你的影响下购买了产品，你对这个产品的好坏是有一定责任的。如果给你做链引流，用户在你文章影响下买了产品却不满意有点助纣为虐的意思，而且给你倒流，赚钱的却是你，所以大多数人对于这种营销意味极强的文章都是一个字<span style="font-family: Arial;"> NO</span>。所以要通过其他的文章来赚取自然外链，此外，这也是一个正常健康的站点该做的。</p><p style="text-align:left;"><br  /></p><p style="text-align:left;">&nbsp;<span style="font-size: 21px;">文章</span><span style="font-size: 21px;font-family: SimSun;">结</span><span style="font-size: 21px;">构</span></p><p style="margin-left:48px;text-align:left;"><span style="font-family: Arial;"></span></p><p><img data-s="300,640" data-type="png" src="<?=\view::src('img/sc/-1/image005.png')?>" style="" class="" data-ratio="1.1226053639846743" data-w="1305"  /></p><p style="margin-left:48px;text-align:left;"><span style="font-family: Arial;"></span><br  /></p><p style="margin-bottom:16px;text-align:left;">&nbsp;<span style="font-size: 21px;">内容</span><span style="font-size: 21px;font-family: SimSun;">产</span><span style="font-size: 21px;">出</span></p><p style="margin-bottom:16px;text-align:left;">对于大多数个人站长来说，其内容生产方式大概就是找个写手，给他一个题目，好了，写吧。写手也是能糊弄就糊弄，亚马逊上随便抄一抄，是否属实和准确也没人管，然后站长再随手把内容弄上去，不用怀疑，几年前相当一部分的内容就是这种质量水平的，用户看了后很难产生价值。这样的文章就算用各种方法做到了首页，其用户访问时间和浏览深度也是很差的，很快就会被刷下来。</p><p style="text-align:left;">&nbsp;那么对于国人，在大家都不是<span style="font-family: Arial;">native speaker</span>的情况下，如何在这种雇佣写手的情况下保证稳定高质量的内容产出？</p><p style="text-align:left;">&nbsp;<span style="font-family: Arial;">1.<span style="font-variant-numeric: normal;font-stretch: normal;font-size: 9px;line-height: normal;">&nbsp;&nbsp;&nbsp; </span></span>基本的英语水平，虽然并不要求你是<span style="font-family: Arial;">native speaker</span>，但是基本的英文阅读和交流是必须的，因为无论交流写手<span style="font-family: Arial;">/</span>发布任务<span style="font-family: Arial;">/</span>买链<span style="font-family: Arial;">...</span>等等都会需要。<span style="font-family: Arial;">2.<span style="font-variant-numeric: normal;font-stretch: normal;font-size: 9px;line-height: normal;">&nbsp;&nbsp;&nbsp; </span></span>大量筛选，建立长期关系。首先，写手们虽然大多都是混子，但是总有金子隐藏在里面，这里就需要大量的筛选，发布一个任务，让写手临时写一篇文章，不要看他们曾经写过的文章，因为那些都是被美化过的，刷评价在任何平台都存在。</p><p style="text-align:left;"><span style="font-family: Arial;">3.<span style="font-variant-numeric: normal;font-stretch: normal;font-size: 9px;line-height: normal;">&nbsp;&nbsp;&nbsp; </span></span>建立长期关系（写手们混，一部分也是因为都是短期关系，写了几篇文章就换个雇主），你可以通过各种画饼的方式来许诺，比如我还有两个站待建设，如果写得好，都给你写。</p><p style="text-align:left;"><span style="font-family: Arial;">4.<span style="font-variant-numeric: normal;font-stretch: normal;font-size: 9px;line-height: normal;">&nbsp;&nbsp;&nbsp; </span></span>自己调研。虽然不是你自己写，但是你的调研却要尽可能细致，不仅为了写出详细的<span style="font-family: Arial;">Table</span>，更是为了有分辨好内容和坏内容的能力，这样，写手写来的文章，你就会知道哪里该改，如何指导他改。</p><p style="margin-bottom:16px;text-align:left;">&nbsp;此外，注意写手来源，写手大致可以分为两拨，印度菲律宾的低质量写手，文章改写和糊弄是常态，生病拖稿也是经常有的事，欧美写手，质量较高，价格也高。我们刚开始的时候雇了个印度写手，我们好心做好了调研，选好了该写的产品，把提纲交给他，当时约定好一周交货，然而第三天的时候，他发来信息：「不好意思，我生病拉，要去医院，不能写了，得等五六天」，当时也是天真，不懂印度人的套路，真的以为他生病了，只能让他拖，结果后来的理由越来越匪夷所系，如：「电脑电池坏了」「电脑上不了网了」「急性肠胃炎」<span style="font-family: Arial;">...</span>就这样，两篇三千字的文章拖了一个月，最后交给我的时候还有理有据，并没有觉得自己做错了什么。</p><p style="text-align:left;">&nbsp;至于菲律宾写手，虽然写的很快，但是他们的文章大概是这样的：「恩，今天我们要介绍的是无人机，无人机非常厉害，在各国都有了很大的发展，并且在非常多的领域有着应用，他的前景极佳，我们也很推荐<span style="font-family: Arial;">....</span>」注意到没有，语句通顺的废话，完完全全的废话。自此以后，我们也再也不敢再找印度菲律宾写手了。</p><p style="text-align:left;">&nbsp;当然，欧美写手其中也有混子，所以真正鉴别他们，找到能够长期合作的还是要靠我上面说的仔细调研。</p><p style="text-align:left;"><span style="font-size: 21px;">例子</span></p><p style="text-align:left;">这里放出一个靠内容致胜的例子：<span style="font-family:Arial;color:black;"><a href="https://www.420beginner.com/">https://www.420beginner.com/</a></span></p><p style="text-align:left;">其实如果同类的站点看多了，这种<span style="font-family: Arial;">review</span>文章看多了，稍微扫一眼就能看出哪些文章是为了占坑位凑出来的，哪些稍微花了点精力做调研，但看起来还是有些不够专业，像是半瓶水的感觉，还有哪些是明显行业专家或者调研下了一番功夫写出来的。</p><p style="margin-bottom:16px;text-align:left;">&nbsp;我们以<span style="font-family: Arial;">420beginner</span>的主页文章「<span style="font-family: Arial;">best led grow lighr</span>」为例<a href="https://www.420beginner.com/"><span style="font-family:Arial;color:black;">https://www.420beginner.com/</span></a></p><p style="margin-bottom:16px;text-align:left;">&nbsp;</p><p style="margin-left:48px;text-align:left;"><span style="font-family: Arial;"></span></p><p><img data-s="300,640" data-type="png" src="<?=\view::src('img/sc/-1/image006.png')?>" style="" class="" data-ratio="1.0075414781297134" data-w="1326"  /></p><p style="margin-left:48px;text-align:left;"><span style="font-family: Arial;"></span><br  /></p><p style="text-align:left;"><span style="font-family: Arial;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>以一个表格开头。列出了功率和覆盖范围两项核心参数</p><p style="text-align:left;">&nbsp;</p><p style="text-align:left;"><span style="font-family: Arial;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span></p><p><img data-s="300,640" data-type="png" src="<?=\view::src('img/sc/-1/image007.png')?>" style="" class="" data-ratio="1.1346153846153846" data-w="1144"  /></p><p style="text-align:left;"><span style="font-family: Arial;"></span><br  /></p><p style="text-align:left;"><span style="font-family: Arial;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></p><p><img data-s="300,640" data-type="png" src="<?=\view::src('img/sc/-1/image008.png')?>" style="" class="" data-ratio="0.41061946902654867" data-w="1130"  /></p><p style="text-align:left;"><span style="font-family: Arial;"></span><br  /></p><p style="text-align:left;"><span style="font-family: Arial;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span></p><p style="text-align:left;"><span style="font-family: Arial;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span>接着，说了两件事，「如何选择<span style="font-family: Arial;"> LED </span>成长灯？列出了一些主要的参数」以及「<span style="font-family: Arial;"> &nbsp;&nbsp;&nbsp;</span>为什么要在<span style="font-family: Arial;">led grow light</span>中选择<span style="font-family: Arial;"> full-spectrum</span>这个品类？」「最后列出了种植大麻所需的除了成长灯之外的器材」</p><p style="text-align:left;"><span style="font-family: Arial;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span></p><p style="text-align:left;"><span style="font-family: Arial;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span></p><p><img data-s="300,640" data-type="png" src="<?=\view::src('img/sc/-1/image009.png')?>" style="" class="" data-ratio="0.7509578544061303" data-w="1305"  /></p><p style="text-align:left;"><span style="font-family: Arial;"></span><br  /></p><p style="text-align:left;"><span style="font-family: Arial;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ok,</span>看他如何介绍产品的，「<span style="font-family: Arial;">Best led grow light for the money</span>」性价比最高的灯，看到没，每个产品有清晰的人群定位，主打高端？主打性价比？专为大场地制作？用户理解起来很容易，也能很快找到适合自己的产品。</p><p style="text-align:left;">&nbsp;</p><p style="text-align:left;"><span style="font-family: Arial;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span></p><p><img data-s="300,640" data-type="png" src="<?=\view::src('img/sc/-1/image010.png')?>" style="" class="" data-ratio="0.5563607085346216" data-w="1242"  /></p><p style="text-align:left;"><span style="font-family: Arial;"></span><br  /></p><p style="text-align:left;">&nbsp;</p><p style="text-align:left;"><span style="font-family: Arial;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>最后，列出了一部分<span style="font-family: Arial;">Q&amp;A</span>周边知识。</p><p style="text-align:left;">&nbsp;</p><p style="text-align:left;"><span style="font-family: Arial;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span>私以为，这就是真正的好内容，有着深度调研，处处为用户着想的好内容，用户看起来清晰明了，一篇文章涵盖了<span style="font-family: Arial;">LED GROW LIGHT</span>这一整个主题。</p><p style="text-align:left;">&nbsp;</p><p style="text-align:left;"><span style="font-family: Arial;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span>这里需要注意的是，首先，其实大家英语水平相差无几，好的也好不到哪去，文章都是外包，你觉得一个不知道从哪找来的写手会做出这种深度的调研么？想要做出这种程度的研究，除了你自己去研究之外，毫无办法。</p><p style="text-align:left;"><span style="font-family: Arial;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span>此外，很多站长看了很多教程，然而无非是照猫画虎，有形无神，哦，要加表格，那我加表格，结果表格为了加而加，表格里的参数都是随便找来的数据，比如笔记本核心的参数是续航和尺寸，因为没有调研支撑，他不了解这东西，随便加了个电脑外壳材质当做参数。</p><p style="text-align:left;"><span style="font-family: Arial;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span>文章其他部分的照猫画虎例子到处都是，如为了做<span style="font-family: Arial;">Pros and Cons</span>而生硬地写出优缺点。。。。所以再说一遍，做内容除了踏踏实实调研之外，没有任何办法。</p><p style="text-align:left;">&nbsp;</p><p style="margin-left:48px;text-align:left;">再看看他的外链，只有<span style="font-family: Arial;">700</span>多条。相较于其他站点动辄<span style="font-family: Arial;">3k-5k</span>的外链量，这点外链可以说是几乎没有故意做过什么外链了。</p><p style="margin-left:48px;text-align:left;"><span style="font-family: Arial;"></span></p><p><img data-s="300,640" data-type="png" src="<?=\view::src('img/sc/-1/image011.png')?>" style="" class="" data-ratio="0.14680851063829786" data-w="1880"  /></p><p style="margin-left:48px;text-align:left;"><span style="font-family: Arial;"></span><br  /></p><p style="margin-bottom:16px;text-align:left;"><br  /><br  /></p><p style="margin-bottom:4px;text-align:left;"><span style="font-size: 35px;font-family: Arial;">SEO</span></p><p style="margin-top: 27px;margin-bottom: 8px;text-align: left;"><span style="font-size: 27px;">站内</span><span style="font-size: 27px;font-family: SimSun;">优</span><span style="font-size: 27px;">化</span></p><p style="text-align:left;">站内优化国外叫做<span style="font-family: Arial;">onpage seo,</span>就是站点本身的优化，对于我们这种文章内容不多的小型亚马逊<span style="font-family: Arial;">niche</span>站，因为内容并内容不多，所以站内优化的工作不多。如结构，重复内容等等<span style="font-family: Arial;">...</span></p><p style="text-align:left;">&nbsp;</p><p style="text-align:left;"><span style="font-family: Arial;">1</span>，从内容出发，比如权威的出站链接，和适时的内链，从用户阅读的角度出发，比如这里需要引入这个品牌的官网，这里需要链接我的另一篇文章介绍如何安装的<span style="font-family: Arial;">...</span>这也间接优化了用户的阅读体验，也做了足够的出站链接和内链。</p><p style="text-align:left;">&nbsp;</p><p style="text-align:left;"><span style="font-family: Arial;">2.</span>硬性标准</p><p style="text-align:left;">&nbsp;<span style="text-indent: 48px;font-family: Arial;">Title / Description /Alt </span><span style="text-indent: 48px;">标签</span></p><p style="text-align:left;"><span style="text-indent: 48px;font-family: Arial;">H1 / H2 / H3 </span><span style="text-indent: 48px;">厘清文章结构</span></p><p style="text-align:left;"><span style="text-indent: 48px;">友好的</span><span style="text-indent: 48px;font-family: Arial;">url</span><span style="text-indent: 48px;">，不要加时间等，直接以文章标题或关键词作为</span><span style="text-indent: 48px;font-family: Arial;">url</span></p><p style="text-align:left;">&nbsp;<span style="text-indent: 48px;">重复内容比例，推荐这个检测工具</span><span style="text-indent: 48px;font-family: Arial;"> http://www.siteliner.com/</span></p><p style="text-align:left;">&nbsp;<span style="text-indent: 48px;">关键词密度与</span><span style="text-indent: 48px;font-family: Arial;">LSI</span><span style="text-indent: 48px;">（</span><span style="text-indent: 48px;font-size: 14px;background: white;">隐性语义索引）</span><span style="text-indent: 48px;">，</span></p><p style="text-align:left;">&nbsp;<span style="background: white;">搜索引擎通过统计特定网页中关键词的位置、密度以及链接。</span><span style="font-family: Verdana;background: white;">AnchorText</span><span style="background: white;">中的关键词甚至</span><span style="font-family: Verdana;background: white;">URL</span><span style="background: white;">中的关键词，从而按照匹配程度给出与用户搜索项相关的结果，这是之前计算机发展水平下搜索引擎对</span><span style="font-family: Verdana;background: white;">“</span><span style="background: white;">向用户提供所需内容</span><span style="font-family: Verdana;background: white;">”</span><span style="background: white;">的最接近模拟</span></p><p style="text-align:left;">&nbsp;<span style="background-color: white;">不仅仅简单地统计、分析网页及链接中的关键词，还将该网页与索引数据库中其他包含相同关键词或部分相同关键词的网页进行比对，以确定不同网页间的语义相关性以及网页与特定关键词间的相关性，同时，将具有高语义相关性的网页进行比对分析，从中找出特定网页中存在关键词的相关项，即找出特定网页中虽然并不存在但与其内容相关的关键词</span></p><p style="margin-bottom:16px;text-align:left;"><br  /><br  /><br  /><br  /></p><p style="text-align:left;"><span style="font-family: Arial;"></span></p><p><img data-s="300,640" data-type="png" src="<?=\view::src('img/sc/-1/image012.png')?>" style="" class="" data-ratio="0.9084905660377358" data-w="1060"  /></p><p style="text-align:left;"><span style="font-family: Arial;"></span><br  /></p><p style="text-align:left;">&nbsp;</p><p style="text-align:left;"><em>拓展阅读：</em><em><span style="font-family: Arial;">https://backlinko.com/on-page-seo</span></em></p><p style="margin-bottom:16px;text-align:left;">&nbsp;</p><p style="margin-top: 27px;margin-bottom: 8px;text-align: left;"><span style="font-size: 27px;">站外</span><span style="font-size: 27px;font-family: SimSun;">优</span><span style="font-size: 27px;">化</span></p><p style="text-align:left;">外链最直接的作用就是传递权重，从而提升你网站的权威，相应的，你的关键词排名也会上涨。衡量外链的两个标准，相关度和网站权威。</p><p style="text-align:left;">&nbsp;</p><p style="text-align:left;">随着谷歌取消<span style="font-family: Arial;">PR</span>这个本用来衡量网站权重的值，<span style="font-family: Arial;">Moz</span>的<span style="font-family: Arial;">DA/PA </span>逐渐被用来衡量网站权威，但是要注意的是，<span style="font-family: Arial;">MOZ</span>的<span style="font-family: Arial;">DA</span>计算方式和谷歌完全不同，<span style="font-family: Arial;">DA</span>只是从一个侧面反映权重。</p><p style="text-align:left;"><br  /></p><p style="text-align:left;"><span style="font-family: Arial;font-size: 21px;">Guest post</span></p><p style="text-align:left;">Guest article,做客文章，你替别的网站写一篇文章，如果被认可，刊登在别人网站上，可以获得反链Guest Infographic ，信息图，也是被用来做做客文章，但是相对于文字的好处是其可以一稿多投，但是因为在谷歌下大家是很忌惮重复内容的，所以一般会要求你写一段unique的description，即独特的一小段描述。</p><p style="text-align:left;">&nbsp;<span style="font-size: 21px;">怎么找？</span></p><p style="text-align:left;">以「<span style="font-family: Arial;">write for us</span>」「<span style="font-family: Arial;">guest post submit</span>」为关键词搜索，并且带上所要搜索的<span style="font-family: Arial;">niche</span>，如：<span style="background-color: white;font-family: Georgia;font-size: 15px;">&nbsp; YourKeyword “guest posts wanted”</span></p><p style="text-align:left;">&nbsp;<em><span style="font-size: 15px;background: white;">更多的细节可以参见这篇文章</span></em><em><span style="font-size: 15px;font-family: Georgia;background: white;"> backlinko.com/the-definitive-guide-to-guest-blogging</span></em></p><p style="text-align:left;">&nbsp;<span style="font-size: 21px;">如何建立</span><span style="font-size: 21px;font-family: SimSun;">联</span><span style="font-size: 21px;">系？</span></p><p style="text-align:left;"><span style="font-size: 15px;background: white;">首先你的文章或者</span><span style="font-size: 15px;font-family: Georgia;background: white;">infographic</span><span style="font-size: 15px;background: white;">需要满足他们的要求，比如他们明确说是网络安全，那么就得是能跟此扯上关系的。</span></p><p style="text-align:left;">&nbsp;<span style="background-color: white;font-size: 15px;">接着，不要直接推销你的文章或者流露出很强的索要链的目的，记住我们的首要目的是建立联系并且为了给他提供优秀的内容。所以先从留言开始做起，让他熟悉你这个人，接着你就可以把准备好的内容发给他，你可以说我是你的忠实读者我对此也有一些自己的想法，然后我写了一篇文章，你看看怎么样？一切其实都是十分自然的。</span></p><p style="text-align:left;">&nbsp;<span style="font-size: 15px;background: white;">这里可以看到我做</span><span style="font-size: 15px;font-family: Georgia;background: white;">outreach</span><span style="font-size: 15px;background: white;">的一个例子</span></p><p style="margin-left:96px;text-align:left;"><span style="font-size: 15px;font-family: Georgia;background: white;"></span></p><p><img data-s="300,640" data-type="png" src="<?=\view::src('img/sc/-1/image013.png')?>" style="" class="" data-ratio="1.2408136482939633" data-w="1524"  /><span style="font-size: 15px;background: white;">只字不提索要外链的事情，只说「我做了几个很棒的</span><span style="font-size: 15px;font-family: Georgia;background: white;">infographic</span><span style="font-size: 15px;background: white;">哦！」「要不要看看」，如果回答是肯定的，他们一般还是会担心这个内容是不是唯一的，之前有没有被发表过，这时候只要回答「没有，您是第一家啊」并且再附上一段唯一的对于</span><span style="font-size: 15px;font-family: Georgia;background: white;">infographic</span><span style="font-size: 15px;background: white;">的描述。</span></p><p style="margin-left:96px;text-align:left;"><span style="font-size: 15px;font-family: Georgia;background: white;"></span></p><p style="text-align:left;">&nbsp;<em><span style="font-size: 15px;background: white;">注意：不要抄袭，任何抄袭都可以被很容易地发现，来自写手的文章可以用</span></em><em><span style="font-size: 15px;font-family: Georgia;background: white;">copyscape.com</span></em><em><span style="font-size: 15px;background: white;">来检测。</span></em></p><p style="text-align:left;">&nbsp;<span style="font-size: 15px;background: white;">刚开始做</span><span style="font-size: 15px;font-family: Georgia;background: white;">guest post</span><span style="font-size: 15px;background: white;">的时候，别说成功率了，就算是愿意回复我们的都很少，因为我们的文章质量太差了，我们对这个领域并不了解，文章内容很陈旧，并没有什么新颖的东西，要知道，一个真心实意想做</span><span style="font-size: 15px;font-family: Georgia;background: white;">guest post</span><span style="font-size: 15px;background: white;">的网站能否采用文章的唯一要点就是你内容的质量，所以结果可想而知。</span></p><p style="text-align:left;">&nbsp;<span style="font-size: 15px;background: white;">后来，我们想了一个办法，大幅提高了</span><span style="font-size: 15px;font-family: Georgia;background: white;">guest post</span><span style="font-size: 15px;background: white;">的成功率，答案就是优化后的</span><span style="font-size: 15px;font-family: Georgia;background: white;">guest infographic </span><span style="font-size: 15px;background: white;">，优化的部分就在于，直接抄别人已经做好的</span><span style="font-size: 15px;font-family: Georgia;background: white;">infographic</span><span style="font-size: 15px;background: white;">，</span></p><p style="text-align:left;">&nbsp;<span style="font-size: 15px;background: white;">方法如下，去</span><span style="font-size: 15px;font-family: Georgia;background: white;">pinterest</span><span style="font-size: 15px;background: white;">找适合你</span><span style="font-size: 15px;font-family: Georgia;background: white;">niche</span><span style="font-size: 15px;background: white;">的</span><span style="font-size: 15px;font-family: Georgia;background: white;">infographic</span><span style="font-size: 15px;background: white;">，比如你是做吸尘器的，那</span><span style="font-size: 15px;font-family: Georgia;background: white;">infographic</span><span style="font-size: 15px;background: white;">可以聚焦在家庭清洁这个领域，要找那些制作精良但是却没有广泛传播的，制作精良是为了保证其质量，没有广泛传播因为那些传播广泛的很容易被人看出来。</span></p><p style="text-align:left;">&nbsp;<span style="font-size: 15px;background: white;">最后我们的测试效果令人满意，成功率达到了</span><span style="font-size: 15px;font-family: Georgia;background: white;">10%-20%</span><span style="font-size: 15px;background: white;">，其中不乏</span><span style="font-size: 15px;font-family: Georgia;background: white;">DA60</span><span style="font-size: 15px;background: white;">以上的大站。</span></p><p style="text-align:left;">&nbsp;<span style="font-size: 15px;font-family: Georgia;background: white;">ok</span><span style="font-size: 15px;background: white;">，你没有花一分钱，「借用」别人做好的</span><span style="font-size: 15px;font-family: Georgia;background: white;">infographic</span><span style="font-size: 15px;background: white;">每个月做成了大几十条链。这些链接如果拿出去卖，少则几十刀，多则上百刀的价值。</span></p><p style="margin-bottom:16px;text-align:left;">&nbsp;<span style="font-size: 15px;font-family: Georgia;background: white;">Tips</span><span style="font-size: 15px;background: white;">：</span><span style="font-size: 15px;font-family: Georgia;background: white;">searchby image </span><span style="font-size: 15px;background: white;">可以帮助你查看一张图片的流行程度。</span></p><p style="margin-bottom:16px;text-align:left;"><br  /><span style="font-family: Arial;font-size: 21px;">Web 2.0</span></p><p style="margin-bottom:16px;text-align:left;">啥叫<span style="font-family: Arial;">web2.0</span>？</p><p style="margin-bottom:16px;text-align:left;"><span style="font-family: Arial;">wix/tumblr/</span>等可以自建一个二级域名的网站，如<span style="font-family: Arial;">jersy.tumblr.com.</span></p><p style="text-align:left;">&nbsp;为什么要用<span style="font-family: Arial;">web2.0</span>做外链？</p><p style="text-align:left;">这类站点<span style="font-family: Arial;">DA</span>极高，也就是说主站有很高的权威，这意味着建立在其上的站点可以很容易起来，从而向你的<span style="font-family: Arial;">niche</span>站传递权重。</p><p style="text-align:left;">&nbsp;如果你去<span style="font-family: Arial;">BHW</span>的卖链区，一个个吹得天花乱坠，什么<span style="font-family: Arial;">authority site… </span>然而其实无非是去<span style="font-family: Arial;">tumblr</span>发几篇文章，并且夹带上你的链，当然这种站点被滥用得也很厉害。</p><p style="text-align:left;">&nbsp;现在多以</p><p style="text-align:left;"><span style="color: black;">&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;<a href="http://www.articlebiz.com/" style="font-family: Arial;">http://www.articlebiz.com/</a></span></p><p style="text-align:left;"><span style="font-family:Arial;color:black;">&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;<a href="https://onmogul.com">https://onmogul.com</a></span></p><p style="text-align:left;">&nbsp;此类还未被滥用的高<span style="font-family: Arial;">DA web2.0</span>网站，文章可以自己做伪原创或者找低价的印度写手（因为这个只是为了外链而存在），并且做上几条<span style="font-family: Arial;">tier2 </span>外链（<span style="font-family: Arial;">tier2 </span>外链，即外链的外链，通常以书签网站为主）</p><p style="margin-bottom:16px;text-align:left;">&nbsp;<span style="font-family: Arial;font-size: 21px;">PBN</span></p><p style="margin-bottom:16px;text-align:left;">所谓<span style="font-family: Arial;">PBN</span>，就是<span style="font-family: Arial;">Private Blog Networks</span>，私有博客网络，可以理解成一种站群。但是<span style="font-family: Arial;">PBN</span>与我们所说的站群又有一定的区别，最主要的区别在于第一个<span style="font-family: Arial;">P</span>，也就是<span style="font-family: Arial;">Private</span>，私有的程度。<span style="background-color: white;">这个私有包含了两个私有的内容，一个是指这个博客网络是属于某一个人私有的，另一个私有，是指这个博客网络当中的成员即每个</span><span style="font-family: Arial;">blog</span><span style="background-color: white;">相对于整个博客网络也是一个私有独立的存在，为了保证其私密性，各个</span><span style="font-family: Arial;">blog</span><span style="background-color: white;">之间前外不要链接，因为一旦链接，就像曹操用铁链连接了大船一样，一个遭殃就会引火烧身。</span></p><p style="margin-bottom:16px;text-align:left;"><span style="font-family: Arial;font-size: 21px;background-color: white;">Tips:</span></p><p style="margin-bottom:16px;text-align:left;"><span style="background-color: white;">自然地做链</span></p><p style="margin-bottom:16px;text-align:left;"><span style="background-color: white;">如果你短时间内增加了大量的链接，那么很可能被视为作弊</span></p><p style="margin-bottom:16px;text-align:left;"><span style="background-color: white;">如果你大部分外链的锚文本都瞄准了一个精准的关键词，那么很可能被视为过度优化。不用说，惩罚免不了的。</span></p><p style="margin-bottom:16px;text-align:left;"><span style="background-color: white;">所以整个过程中要注意发链的速度，节奏以自然缓慢为主。</span></p><p style="margin-bottom:16px;text-align:left;"><span style="background-color: white;">锚文本的多样性，比如品牌名，关键字，裸链等等</span></p><p style="text-align:left;">&nbsp;</p><p style="margin-top: 27px;margin-bottom: 8px;text-align: left;"><span style="font-size: 27px;font-family: SimSun;">竞</span><span style="font-size: 27px;">争</span></p><p style="text-align:left;">很多人都听过<span style="font-family: Arial;">10beasts.com,</span>这个站确实是这两年来被大家讨论最多的一个话题，国内研究的人也很多，所谓的<span style="font-family: Arial;">4</span>个月登顶的神话十分让人羡慕，而且这个站长还好心地放出了他做站的全过程，可谓十分良心，仿佛在暗示那些心痒痒的人，来吧，照我这样做你也可以的！</p><p style="text-align:left;">然而事实完全不是这样，通过分析他的外链，我们发觉了与其所获得流量极其不相称，换句话说，相同的站和文章，你来做绝对做不到这种水平，所以业内很多人也怀疑他用了高级<span style="font-family: Arial;">PBN</span>，并且屏蔽了各大工具的爬虫，外人无法看到。</p><p style="text-align:left;">&nbsp;</p><p style="margin-top: 27px;margin-bottom: 8px;text-align: left;"><span style="font-size: 27px;">盈利周期</span></p><p style="text-align:left;"><strong>沙盒</strong></p><p style="text-align:left;">所谓的『沙盒』是每个新站都逃脱不了的东西，少则三四个月，多则半年，没有流量和排名，这其实就是一种筛选垃圾的方式，看你能否撑得过去。</p><p style="text-align:left;">&nbsp;</p><p style="text-align:left;"><strong>竞争</strong></p><p style="text-align:left;">此外，最主要原因还是竞争加剧，不仅很多个人，大公司也挤进来，很多坑位都熙熙攘攘挤满了人，盈利周期不可避免会被拉长。如果你心里跃跃欲试想做这个，最好还是兼职状态做。</p><p style="text-align:left;">&nbsp;</p><p style="text-align:left;"><strong>算法更新</strong></p><p style="text-align:left;">谷歌数次的算法更新（最近的<span style="font-family: Arial;background: white;">Google Fred</span><span style="background: white;">）</span>都对于激进的联盟网站越来越严格<span style="background:white;">，</span><span style="font-family: Arial;background: white;">moneyarticle</span><span style="background: white;">占比过多（</span><span style="font-family: Arial;background: white;">bestxxx</span><span style="background: white;">类文章占大多数</span><span style="font-family: Arial;background: white;">)</span><span style="background: white;">，针对特定关键词精确优化，亚马逊的出站链接占比过高，在谷歌眼里这些网站盈利目的太过明显，为了挣钱会损害一定的用户体验，</span></p><p style="text-align:left;">&nbsp;</p><p style="margin-top: 27px;margin-bottom: 8px;text-align: left;"><span style="font-size: 27px;font-family: SimSun;">办</span><span style="font-size: 27px;">法</span></p><p style="margin-top: 24px;margin-bottom: 8px;text-align: left;"><span style="font-size: 21px;">自然的文章比例</span></p><p style="text-align:left;"><span style="background: white;">谷歌不喜欢那些只为了推广赚钱而存在的文章和站，所以要注意</span><span style="font-family: Arial;background: white;">money article</span><span style="background: white;">和</span><span style="font-family: Arial;background: white;">infomation article</span><span style="background: white;">这类非</span><span style="font-family: Arial;background: white;">money article</span><span style="background: white;">的占比。</span></p><p style="margin-top: 24px;margin-bottom: 8px;text-align: left;"><span style="font-size: 21px;font-family: SimSun;">卖</span><span style="font-size: 21px;">掉</span></p><p style="text-align:left;"><span style="background: white;">像我们之前说的那个</span><span style="font-family: Arial;background: white;">420beginner.com</span><span style="background: white;">，三个月前，刚以</span><span style="font-family: Arial;background: white;">16.8</span><span style="background: white;">万美金的价格卖掉，大概是</span><span style="font-family: Arial;background: white;">110</span><span style="background: white;">万人民币，一个做了一年半的站，这种归宿也是相当好了。</span></p><p style="text-align:left;">&nbsp;</p><p style="text-align:left;"><span style="background: white;">这里就要说到</span><span style="font-family: Arial;background: white;">niche</span><span style="background: white;">站的价值，这种以白帽</span><span style="font-family: Arial;background: white;">SEO</span><span style="background: white;">做起来的，搜索作为其主要流量来源的，其价值一般是月收入的</span><span style="font-family: Arial;background: white;">20</span><span style="background: white;">倍</span><span style="font-family: Arial;background: white;">-30</span><span style="background: white;">倍。换句话说，如果你一个月能挣</span><span style="font-family: Arial;background: white;">5k</span><span style="background: white;">刀，那么在</span><span style="font-family: Arial;background: white;">flippa</span><span style="background: white;">上卖个</span><span style="font-family: Arial;background: white;">10w-15w</span><span style="background: white;">刀是十分正常的。</span></p><p style="text-align:left;">&nbsp;</p><p style="text-align:left;"><span style="font-family: Arial;background: white;">Niche</span><span style="background: white;">站的高价值与其瞄准的流量和流量渠道有着很大关系，要知道这种亚马逊</span><span style="font-family: Arial;background: white;">niche</span><span style="background: white;">站所瞄准的流量都属于有着很高购买意图的流量，价值和效率都很高，而且搜索引擎的流量一般是比较稳定的，与此做对比的就是电商站，比如这段时间很流行的靠</span><span style="font-family: Arial;background: white;">FB</span><span style="background: white;">广告作为主要流量来源的电商网站，其价格一般只能卖到月收入的</span><span style="font-family: Arial;background: white;">5-7</span><span style="background: white;">倍。</span></p><p style="margin-bottom:16px;text-align:left;">&nbsp;</p><p style="margin-top: 27px;margin-bottom: 8px;text-align: left;"><span style="font-size: 27px;">如何</span><span style="font-size: 27px;font-family: SimSun;">转</span><span style="font-size: 27px;">型</span></p><p style="text-align:left;"><span style="background: white;">事实上，随着移动互联网的冲击，各种大平台的崛起，浏览器这个入口早就不是唯一了，流量入口变得逐渐多样化。</span></p><p style="text-align:left;"><span style="background: white;">而传统网站除了被流量入口被划分之外，我们这种</span><span style="font-family: Arial;background: white;">niche</span><span style="background: white;">站大多极度依赖搜索流量。难以与受众建立持续的联系。</span></p><p style="margin-bottom:16px;text-align:left;">&nbsp;</p><p style="text-align:left;">然而关键词（需求）<span style="font-family: Arial;"> → </span>内容<span style="font-family: Arial;">/</span>工具（解决方式）这种模式依旧有用，站长们之前积累的挖掘关键词及<span style="font-family: Arial;">seo</span>知识在外贸和企业出海上都有很大用处。</p><p style="text-align:left;">&nbsp;</p><p style="text-align:left;">大家都想赶上潮流做风口上的猪，十年前的淘宝是这样，五年前的公众号也是这样，似乎大家都想时光倒转，补上一颗后悔药，然而与其犹犹豫豫踌躇不前，不如迈开步子走下去，很多事并非赶上一个风口那么简单，做比说重要多了，望大家共勉。</p><p style="text-align:left;">&nbsp;</p><p>&nbsp;</p><p><br  /></p>
                </div>
                <script nonce="2126483999" type="text/javascript">
                    var first_sceen__time = (+new Date());

                    if ("" == 1 && document.getElementById('js_content')) {
                        document.getElementById('js_content').addEventListener("selectstart",function(e){ e.preventDefault(); });
                    }


                    (function(){
                        if (navigator.userAgent.indexOf("WindowsWechat") != -1){
                            var link = document.createElement('link');
                            var head = document.getElementsByTagName('head')[0];
                            link.rel = 'stylesheet';
                            link.type = 'text/css';
                            link.href = "//res.wx.qq.com/mmbizwap/zh_CN/htmledition/style/page/appmsg/page_mp_article_improve_winwx31619e.css";
                            head.appendChild(link);
                        }
                    })();
                </script>



                <div class="ct_mpda_wrp" id="js_sponsor_ad_area" style="display:none;"></div>


                <div class="reward_area tc" id="js_preview_reward" style="display:none;">
                    <p id="js_preview_reward_wording" class="tips_global reward_tips" style="display:none;"></p>
                    <p>
                        <a class="reward_access" id='js_preview_reward_link' href="##">赞赏</a>
                    </p>
                </div>
                <div class="reward_qrcode_area reward_area tc" id="js_preview_reward_qrcode" style="display:none;">
                    <p class="tips_global">长按二维码向我转账</p>
                    <p id="js_preview_reward_ios_wording" class="reward_tips" style="display:none;"></p>
                    <span class="reward_qrcode_img_wrp"><img class="reward_qrcode_img" src="//res.wx.qq.com/mmbizwap/zh_CN/htmledition/images/pic/appmsg/pic_reward_qrcode.2x3534dd.png"></span>
                    <p class="tips_global">受苹果公司新规定影响，微信 iOS 版的赞赏功能被关闭，可通过二维码转账支持公众号。</p>
                </div>
            </div>

            <div class="rich_media_tool" id="js_toobar3">
                <div id="js_read_area3" class="media_tool_meta tips_global meta_primary" style="display:none;">阅读 <span id="readNum3"></span></div>

                <span style="display:none;" class="media_tool_meta meta_primary tips_global meta_praise" id="like3">
                    <i class="icon_praise_gray"></i><span class="praise_num" id="likeNum3"></span>
                </span>

                <a id="js_report_article3" style="display:none;" class="media_tool_meta tips_global meta_extra" href="##">投诉</a>

            </div>


        </div>

        <div class="rich_media_area_primary sougou" id="sg_tj" style="display:none"></div>


        <div class="rich_media_area_extra">


            <div class="mpda_bottom_container" id="js_bottom_ad_area"></div>

            <div id="js_iframetest" style="display:none;"></div>

            <div class="rich_media_extra" id="js_preview_cmt" style="display:none">
                <p class="discuss_icon_tips rich_split_tips tr">
                    <a href="##" id="js_preview_cmt_write">写留言<img class="icon_edit" src="//res.wx.qq.com/mmbizwap/zh_CN/htmledition/images/icon/appmsg/icon_edit25ded2.png"></a>
                </p>
            </div>
        </div>


        <div id="js_pc_qr_code" class="qr_code_pc_outer" style="display:none;">
            <div class="qr_code_pc_inner">
                <div class="qr_code_pc">
                    <img id="js_pc_qr_code_img" class="qr_code_pc_img">
                    <p>微信扫一扫<br>关注该公众号</p>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="js_minipro_dialog" style="display:none;">
    <div class="weui-mask"></div>
    <div class="weui-dialog">
        <div class="weui-dialog__bd">即将打开"<span id="js_minipro_dialog_name"></span>"小程序</div>
        <div class="weui-dialog__ft">
            <a id="js_minipro_dialog_cancel" href="javascript:void(0);" class="weui-dialog__btn weui-dialog__btn_default">取消</a>
            <a id="js_minipro_dialog_ok" href="javascript:void(0);" class="weui-dialog__btn weui-dialog__btn_primary">打开</a>
        </div>
    </div>
</div>


<script nonce="2126483999">
    var __DEBUGINFO = {
        debug_js : "//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_wap/debug/console34c264.js",
        safe_js : "//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_wap/safe/moonsafe34c264.js",
        res_list: []
    };
</script>

<script nonce="2126483999" type="text/javascript">
    (function() {
        var totalCount = 0,
            finishCount = 0;

        function _loadVConsolePlugin() {
            window.vConsole = new window.VConsole();
            while (window.vConsolePlugins.length > 0) {
                var p = window.vConsolePlugins.shift();
                window.vConsole.addPlugin(p);
            }
        }

        function _addVConsole(uri, cb) {
            totalCount++;
            var url = '//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/vconsole/' + uri;
            var node = document.createElement('SCRIPT');
            node.type = 'text/javascript';
            node.src = url;
            node.setAttribute('nonce', '2126483999');
            if (cb) {
                node.onload = cb;
            }
            document.getElementsByTagName('head')[0].appendChild(node);
        }
        if (
            (document.cookie && document.cookie.indexOf('vconsole_open=1') > -1)
            || location.href.indexOf('vconsole=1') > -1
        ) {
            window.vConsolePlugins = [];
            _addVConsole('3.0.0/vconsole.min.js', function() {

                _addVConsole('plugin/vconsole-mpopt/1.0.1/vconsole-mpopt.js', _loadVConsolePlugin);
            });
        }
    })();
</script>

<script nonce="2126483999" type="text/javascript">

    if (!window.console) window.console = { log: function() {} };

    if (typeof getComputedStyle == 'undefined') {
        if (document.body.currentStyle) {
            window.getComputedStyle = function(el) {
                return el.currentStyle;
            }
        } else {
            window.getComputedStyle = {};
        }
    }
    (function(){
        window.__zoom = 1;

        (function(){
            var validArr = ","+([0.875, 1, 1.125, 1.25, 1.375]).join(",")+",";
            var match = window.location.href.match(/winzoom=(\d+(?:\.\d+)?)/);
            if (match && match[1]) {
                var winzoom = parseFloat(match[1]);
                if (validArr.indexOf(","+winzoom+",")>=0) {
                    window.__zoom = winzoom;
                }
            }
        })();

        var isIE = false;
        if (typeof version != 'undefined' && version >= 6 && version <= 9) {
            isIE = true;
        }
        var getMaxWith=function(){
            var container = document.getElementById('img-content');
            var max_width = container.offsetWidth;
            var container_padding = 0;
            var container_style = getComputedStyle(container);
            container_padding = parseFloat(container_style.paddingLeft) + parseFloat(container_style.paddingRight);
            max_width -= container_padding;
            var ua = navigator.userAgent.toLowerCase();
            var re = new RegExp("msie ([0-9]+[\.0-9]*)");
            var version;
            if (re.exec(ua) != null) {
                version = parseInt(RegExp.$1);
            }
            var isIE = false;
            if (typeof version != 'undefined' && version >= 6 && version <= 9) {
                isIE = true;
            }
            if (!max_width) {
                max_width = window.innerWidth - 30;
            }
            return max_width;
        };
        var getParentWidth = function(dom){
            var parent_width = 0;
            var parent = dom.parentNode;
            var outerWidth = 0;
            while (true) {
                if(!parent||parent.nodeType!=1) break;
                var parent_style = getComputedStyle(parent);
                if (!parent_style) break;
                parent_width = parent.clientWidth - parseFloat(parent_style.paddingLeft) - parseFloat(parent_style.paddingRight) - outerWidth;
                if (parent_width > 0) break;
                outerWidth += parseFloat(parent_style.paddingLeft) + parseFloat(parent_style.paddingRight) + parseFloat(parent_style.marginLeft) + parseFloat(parent_style.marginRight) + parseFloat(parent_style.borderLeftWidth) + parseFloat(parent_style.borderRightWidth);
                parent = parent.parentNode;
            }
            return parent_width;
        }
        var getOuterW=function(dom){
            var style=getComputedStyle(dom),
                w=0;
            if(!!style){
                w = parseFloat(style.paddingLeft) + parseFloat(style.paddingRight) + parseFloat(style.borderLeftWidth) + parseFloat(style.borderRightWidth);
            }
            return w;
        };
        var getOuterH =function(dom){
            var style=getComputedStyle(dom),
                h=0;
            if(!!style){
                h = parseFloat(style.paddingTop) + parseFloat(style.paddingBottom) + parseFloat(style.borderTopWidth) + parseFloat(style.borderBottomWidth);
            }
            return h;
        };
        var insertAfter = function(dom,afterDom){
            var _p = afterDom.parentNode;
            if(!_p){
                return;
            }
            if(_p.lastChild === afterDom){
                _p.appendChild(dom);
            }else{
                _p.insertBefore(dom,afterDom.nextSibling);
            }
        };
        var getQuery = function(name,url){

            var u  = arguments[1] || window.location.search,
                reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)"),
                r = u.substr(u.indexOf("\?")+1).match(reg);
            return r!=null?r[2]:"";
        };
        (function(){
            var images = document.getElementsByTagName('img');
            var length = images.length;
            var max_width = getMaxWith();
            for (var i = 0; i < length; ++i) {
                var src_ = images[i].getAttribute('data-src');
                var realSrc = images[i].getAttribute('src');
                if (!src_ || realSrc) continue;
                var width_ = 1 * images[i].getAttribute('data-w') || max_width;
                var ratio_ = 1 * images[i].getAttribute('data-ratio');
                var height = 100;
                if (ratio_ && ratio_ > 0) {
                    var img_style = getComputedStyle(images[i]);
                    var init_width = images[i].style.width;

                    if (init_width) {
                        images[i].setAttribute('_width', init_width);
                        if (init_width != 'auto') width_ = parseFloat(img_style.width);
                    }
                    var parent_width = getParentWidth(images[i])||max_width;
                    var width = width_ > parent_width ? parent_width : width_;
                    var img_padding_border = getOuterW(images[i])||0;
                    var img_padding_border_top_bottom = getOuterH(images[i])||0;
                    height = (width - img_padding_border) * ratio_ + img_padding_border_top_bottom;
                    images[i].style.cssText += ";width: " + width + "px !important;";
                    if (isIE) {
                        var url = images[i].getAttribute('data-src');
                        images[i].src = url;
                    } else {
                        if(width > 40 && height > 40){
                            images[i].className += ' img_loading';
                        }
//                        images[i].src = "data:image/gif;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVQImWNgYGBgAAAABQABh6FO1AAAAABJRU5ErkJggg==";
                    }
                } else {
                    images[i].style.cssText += ";visibility: hidden !important;";
                }
                images[i].style.cssText += ";height: " + height + "px !important;";
            }
        })();
        window.__videoDefaultRatio=16/9;
        window.__getVideoWh = function(dom){
            var max_width = getMaxWith(),
                width = max_width,
                ratio_ = dom.getAttribute('data-ratio')*1||(4/3),
                arr = [4/3, 16/9],
                ret = arr[0],
                abs = Math.abs(ret - ratio_);
            for(var j=1,jl=arr.length;j<jl;j++){
                var _abs = Math.abs(arr[j] - ratio_);
                if(_abs<abs){
                    abs = _abs;
                    ret = arr[j];
                }
            }
            ratio_ = ret;
            var parent_width = getParentWidth(dom)||max_width,
                width = width > parent_width ? parent_width : width,
                outerW = getOuterW(dom)||0,
                outerH = getOuterH(dom)||0,
                videoW = width - outerW,
                videoH = videoW/ratio_,
                height = videoH + outerH;
            return {w:width,h:height,vh:videoH,vw:videoW,ratio:ratio_};
        };

        (function(){
            var iframe = document.getElementsByTagName('iframe');
            for (var i=0,il=iframe.length;i<il;i++) {
                var a = iframe[i];
                var src_ = a.getAttribute('src')||a.getAttribute('data-src')||"";
                if(!/http(s)*\:\/\/v\.qq\.com\/iframe\/(preview|player)\.html\?/.test(src_)){
                    continue;
                }
                var vid = getQuery("vid",src_);
                if(!vid){
                    continue;
                }
                vid=vid.replace(/^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g,"");
                a.removeAttribute('src');
                a.style.display = "none";
                var obj = window.__getVideoWh(a),
                    mydiv = document.createElement('img');
                mydiv.className = "img_loading";
                mydiv.src="data:image/gif;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVQImWNgYGBgAAAABQABh6FO1AAAAABJRU5ErkJggg==";
                mydiv.setAttribute("data-vid",vid);
                mydiv.style.cssText = "width: " + obj.w + "px !important;";
                insertAfter(mydiv,a);
                a.style.cssText += ";width: " + obj.w + "px !important;";
                a.setAttribute("width",obj.w);
                if(window.__zoom!=1){
                    a.style.display = "block";
                    mydiv.style.display = "none";
                    a.setAttribute("_ratio",obj.ratio);
                    a.setAttribute("_vid",vid);
                }else{
                    mydiv.style.cssText += "height: " + obj.h + "px !important;";
                    a.style.cssText += "height: " + obj.h + "px !important;";
                    a.setAttribute("height",obj.h);
                }
                a.setAttribute("data-vh",obj.vh);
                a.setAttribute("data-vw",obj.vw);
                a.setAttribute("data-src",location.protocol+"//v.qq.com/iframe/player.html?vid="+ vid + "&width="+obj.vw+"&height="+obj.vh+"&auto=0");
            }
        })();

        (function(){
            if(window.__zoom!=1){
                document.getElementById('page-content').style.zoom = window.__zoom;
                var a = document.getElementById('activity-name');
                var b = document.getElementById('meta_content');
                if(!!a){
                    a.style.zoom = 1/window.__zoom;
                }
                if(!!b){
                    b.style.zoom = 1/window.__zoom;
                }
                var images = document.getElementsByTagName('img');
                for (var i = 0,il=images.length;i<il;i++) {
                    images[i].style.zoom = 1/window.__zoom;
                }
                var iframe = document.getElementsByTagName('iframe');
                for (var i = 0,il=iframe.length;i<il;i++) {
                    var a = iframe[i];
                    a.style.zoom = 1/window.__zoom;
                    var src_ = a.getAttribute('data-src')||"";
                    if(!/http(s)*\:\/\/v\.qq\.com\/iframe\/(preview|player)\.html\?/.test(src_)){
                        continue;
                    }
                    var ratio = a.getAttribute("_ratio");
                    var vid = a.getAttribute("_vid");
                    a.removeAttribute("_ratio");
                    a.removeAttribute("_vid");
                    var vw = a.offsetWidth - (getOuterW(a)||0);
                    var vh = vw/ratio;
                    var h = vh + (getOuterH(a)||0)
                    a.style.cssText += "height: " + h + "px !important;"
                    a.setAttribute("height",h);
                    a.setAttribute("data-src",location.protocol+"//v.qq.com/iframe/player.html?vid="+ vid + "&width="+vw+"&height="+vh+"&auto=0");
                    a.style.display = "none";
                    var parent = a.parentNode;
                    if(!parent){
                        continue;
                    }
                    for(var j=0,jl=parent.children.length;j<jl;j++){
                        var child = parent.children[j];
                        if(child.className.indexOf("img_loading")>=0 && child.getAttribute("data-vid")==vid){
                            child.style.cssText += "height: " + h + "px !important;";
                            child.style.display = "";
                        }
                    }
                }
            }
        })();
    })();
</script>
<script nonce="2126483999" type="text/javascript">

    var not_in_mm_css = "//res.wx.qq.com/mmbizwap/zh_CN/htmledition/style/page/appmsg/not_in_mm36906d.css";
    var windowwx_css = "//res.wx.qq.com/mmbizwap/zh_CN/htmledition/style/page/appmsg/page_mp_article_improve_winwx31619e.css";
    var article_improve_combo_css = "//res.wx.qq.com/mmbizwap/zh_CN/htmledition/style/page/appmsg/page_mp_article_improve_combo39aac6.css";
    var tid = "";
    var aid = "";
    var clientversion = "12020110";
    var appuin = "MzUyMDAzNDYxNw=="||"";

    var source = "1";
    var ascene = "0";
    var subscene = "";
    var abtest_cookie = "";

    var scene = 75;

    var itemidx = "";
    var appmsg_token   = "933_K%2FYauuxjTrG70UuINtAaHWDrsI6LzsUlePJOa3lczR0WBJhFfl29DHiQgM8~";

    var _copyright_stat = "0";
    var _ori_article_type = "";

    var nickname = "易灵微课";
    var appmsg_type = "6";
    var ct = "1512122514";
    var publish_time = "2017-12-01" || "";
    var user_name = "gh_f5acdd877bc5";
    var user_name_new = "";
    var fakeid   = "";
    var version   = "";
    var is_limit_user   = "0";
    var round_head_img = "http://mmbiz.qpic.cn/mmbiz_png/zUIywPicNicRIia2QGiaciaia2ChiaRV1ibaTb43PGypK7ekXdAsp5skpD9NsTLxdeoADCLnhjLya2zhibWIAtqBrB12J6A/0?wx_fmt=png";
    var ori_head_img_url = "http://wx.qlogo.cn/mmhead/Q3auHgzwzM5vHzcJVh0RLcWpsKnE0L0Lh7OHa5R804ic0CMhQ5LH1FQ/132";
    var msg_title = "如何打造价值百万的细分网站";
    var msg_desc = "大家好，我是jersy，前网易PM，因为觉得单纯上班工作越来越无趣，躁动的心总想着搞点事情，便以小白的身份扎";
    var msg_cdn_url = "http://mmbiz.qpic.cn/mmbiz_jpg/zUIywPicNicRJia6lk9Ohicaicf4kJm7qOfeyaia32TCwIt1S2IGYZibhBnvmk1txzCn8GgEElI1kEh5VnWuNJTia9FLoA/0?wx_fmt=jpeg";
    var msg_link = "http://mp.weixin.qq.com/s?__biz=MzUyMDAzNDYxNw==\x26amp;tempkey=OTMzXzhXZDQ4TmVtZzd4dTB2TDVtUlh1MEZiX0FpRlF6YVpoYWpJOWNvcU55QTZvVkUzZS14OW0zZThIcFJ3Z2FzaGFWN2M2blpXRWVyeEZfaE1IQmRlQkdibFB6NVRta0VGdWc5MjlGdUFoXzhWN3VmLUYtbGR1Mk16Z3ZvMnBOYWVhLUp6MU5yQk84aml5eUw4Z1Fxa0ZXUEZieHM5OU55Qnk2bUJ3aWd%2Bfg%3D%3D\x26amp;chksm=79f1c1694e86487fb6bab8a49d6cbed84cbef499ad8b1d23804498909b07b9b3834e37cc566d#rd";
    var user_uin = "58420325"*1;
    var msg_source_url = '';
    var img_format = 'jpeg';
    var srcid = '12010Jjy4wjudNqNXokHyHE0';
    var req_id = '0118CYNacW6Bon4luRhgnDxY';
    var networkType;
    var appmsgid = '' || '100000063'|| "100000063";
    var comment_id = "0" * 1;
    var comment_enabled = "" * 1;
    var is_need_reward = "0" * 1;
    var is_https_res = ("" * 1) && (location.protocol == "https:");
    var msg_daily_idx = "0" || "";
    var profileReportInfo = "" || "";

    var devicetype = "iMac\x26nbsp;MacBookPro12,1\x26nbsp;OSX\x26nbsp;OSX\x26nbsp;10.12.4\x26nbsp;build(16E195)";
    var source_encode_biz = "";
    var source_username = "";

    var reprint_ticket = "";
    var source_mid = "";
    var source_idx = "";

    var show_comment = "0";
    var __appmsgCgiData = {
        show_msg_voice: "0"*1,
        can_use_page : "0"*1,
        is_wxg_stuff_uin : "0"*1,
        card_pos : "",
        copyright_stat : "0",
        source_biz : "",
        hd_head_img : "http://wx.qlogo.cn/mmhead/Q3auHgzwzM5vHzcJVh0RLcWpsKnE0L0Lh7OHa5R804ic0CMhQ5LH1FQ/0"||(window.location.protocol+"//"+window.location.host + "//res.wx.qq.com/mmbizwap/zh_CN/htmledition/images/pic/appmsg/pic_rumor_link.2x264e76.jpg")
    };
    var _empty_v = "//res.wx.qq.com/mmbizwap/zh_CN/htmledition/images/pic/pages/voice/empty26f1f1.mp3";

    var copyright_stat = "0" * 1;

    var pay_fee = "" * 1;
    var pay_timestamp = "";
    var need_pay = "" * 1;

    var need_report_cost = "0" * 1;
    var use_tx_video_player = "0" * 1;
    var appmsg_fe_filter = "contenteditable";

    var friend_read_source = "" || "";
    var friend_read_version = "" || "";
    var friend_read_class_id = "" || "";

    var is_only_read = "1" * 1;
    var read_num = "1" * 1;
    var like_num = "0" * 1;
    var liked = "false" == 'true' ? true : false;
    var is_temp_url = "OTMzX1BCOG5YNHl5cExGdlI3bFdtUlh1MEZiX0FpRlF6YVpoYWpJOWNvcU55QTZvVkUzZS14OW0zZThIcFJ3Z2FzaGFWN2M2blpXRWVyeEZfaE1IQmRlQkdibFB6NVRta0VGdWc5MjlGdUFoXzhWN3VmLUYtbGR1Mk16Z3ZvMnBOYWVhLUp6MU5yQk84aml5eUw4Z05aTjZfZGszSmt2LUQzZElYeGhMZ2d\x26nbsp;fg==" ? 1 : 0;
    var send_time = "1512122903";
    var icon_emotion_switch = "//res.wx.qq.com/mmbizwap/zh_CN/htmledition/images/icon/appmsg/emotion/icon_emotion_switch.2x2f1273.png";
    var icon_emotion_switch_active = "//res.wx.qq.com/mmbizwap/zh_CN/htmledition/images/icon/appmsg/emotion/icon_emotion_switch_active.2x2f1273.png";
    var icon_loading_white = "//res.wx.qq.com/mmbizwap/zh_CN/htmledition/images/icon/common/icon_loading_white2805ea.gif";
    var icon_audio_unread = "//res.wx.qq.com/mmbizwap/zh_CN/htmledition/images/icon/appmsg/audio/icon_audio_unread26f1f1.png";
    var icon_qqmusic_default = "//res.wx.qq.com/mmbizwap/zh_CN/htmledition/images/icon/appmsg/qqmusic/icon_qqmusic_default.2x26f1f1.png";
    var icon_qqmusic_source = "//res.wx.qq.com/mmbizwap/zh_CN/htmledition/images/icon/appmsg/qqmusic/icon_qqmusic_source393e3a.png";
    var icon_kugou_source = "//res.wx.qq.com/mmbizwap/zh_CN/htmledition/images/icon/appmsg/kugou/icon_kugou_source393e3a.png";

    var topic_default_img = '//res.wx.qq.com/mmbizwap/zh_CN/htmledition/images/icon/appmsg/topic/pic_book_thumb.2x2e4987.png';
    var comment_edit_icon = '//res.wx.qq.com/mmbizwap/zh_CN/htmledition/images/icon/appmsg/icon_edit25ded2.png';
    var comment_loading_img = '//res.wx.qq.com/mmbizwap/zh_CN/htmledition/images/icon/common/icon_loading_white2805ea.gif';
    var voice_in_appmsg = {
        "1":"1"
    };
    var wxa_img_alert = "" != 'false';







    var weapp_sn_arr_json = "" || "";


    var ban_scene = "0" * 1;

    var svr_time = "1512123019" * 1;

    var is_transfer_msg = ""*1||0;

    var malicious_title_reason_id = "0" * 1;

    window.wxtoken = "2510987217";





    window.is_login = '1' * 1;

    window.__moon_initcallback = function(){
        if(!!window.__initCatch){
            window.__initCatch({
                idkey : 27611+2,
                startKey : 0,
                limit : 128,
                badjsId: 43,
                reportOpt : {
                    uin : uin,
                    biz : biz,
                    mid : mid,
                    idx : idx,
                    sn  : sn
                },
                extInfo : {
                    network_rate : 0.01,
                    badjs_rate: 0.1
                }
            });
        }
    }
</script>

<script nonce="2126483999" type="text/javascript">
    (function(){
        window.__logClientLog = function(msg){
            try{
                var method;
                if(/(iPhone|iPad|iPod|iOS)/i.test(navigator.userAgent)){
                    method = 'writeLog';
                }else if(/(Android)/i.test(navigator.userAgent)){
                    method = 'log';
                }
                if(!!method)
                    doLog(method, msg);
            }catch(e){
                console.error(e)
                throw e
            }
        }
        function doLog(method, msg){
            if(!!method && !!top.window.WeixinJSBridge && !!top.window.WeixinJSBridge.invoke){
                top.window.WeixinJSBridge.invoke(method, {
                    "level" : 'info',
                    "msg" : "[WechatFe][appmsg]" + msg
                });
            }else{

                setTimeout(function(){
                    if( top.window.document.addEventListener ){
                        top.window.document.addEventListener('WeixinJSBridgeReady', function(){
                            doLog(method,msg)
                        }, false);
                    }else if (top.window.document.attachEvent){
                        top.window.document.attachEvent('WeixinJSBridgeReady', function(){
                            doLog(method, msg)
                        });
                        top.window.document.attachEvent('onWeixinJSBridgeReady', function(){
                            doLog(method, msg)
                        });
                    }
                }, 0)
            }
        }
        window.__moonErrRep = function(src){
            window.__logClientLog(' moon load err ' + src);
        }
        window.__moonSucRep = function(src){
            window.__logClientLog(' moon load suc ' + src);
        }
        window.setTimeout(function(){
            window.__logClientLog(' index.html end, __moonhasinit : ' + window.__moonhasinit);
        }, 500);
    })();
</script>

<script nonce="2126483999">window.__moon_host = 'res.wx.qq.com';window.__moon_mainjs = 'appmsg/index.js';window.moon_map = {"new_video/player.html.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/new_video/player.html39e24c.js","biz_wap/zepto/touch.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_wap/zepto/touch34c264.js","biz_wap/zepto/event.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_wap/zepto/event34c264.js","biz_wap/zepto/zepto.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_wap/zepto/zepto34c264.js","page/pages/video.css":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/style/page/pages/video.css3767b8.js","a/appdialog_confirm.html.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/a/appdialog_confirm.html34f0d8.js","widget/wx_profile_dialog_primary.css":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/style/widget/wx_profile_dialog_primary.css34f0d8.js","appmsg/emotion/caret.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/emotion/caret278965.js","new_video/player.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/new_video/player39e24c.js","a/appdialog_confirm.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/a/appdialog_confirm34c32a.js","biz_wap/jsapi/cardticket.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_wap/jsapi/cardticket34c264.js","biz_common/utils/emoji_panel_data.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_common/utils/emoji_panel_data3518c6.js","biz_common/utils/emoji_data.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_common/utils/emoji_data3518c6.js","appmsg/emotion/textarea.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/emotion/textarea353f34.js","appmsg/emotion/nav.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/emotion/nav278965.js","appmsg/emotion/common.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/emotion/common3518c6.js","appmsg/emotion/slide.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/emotion/slide2a9cd9.js","pages/loadscript.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/pages/loadscript39aac6.js","pages/music_report_conf.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/pages/music_report_conf39aac6.js","pages/report.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/pages/report39dc43.js","pages/player_adaptor.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/pages/player_adaptor39d6ee.js","pages/music_player.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/pages/music_player39dc43.js","appmsg/emotion/dom.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/emotion/dom31ff31.js","appmsg/comment_tpl.html.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/comment_tpl.html36c376.js","biz_wap/utils/fakehash.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_wap/utils/fakehash38c7af.js","biz_common/utils/wxgspeedsdk.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_common/utils/wxgspeedsdk3518c6.js","a/sponsor.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/a/sponsor39e101.js","a/app_card.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/a/app_card393ef4.js","a/ios.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/a/ios393966.js","a/android.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/a/android393966.js","a/profile.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/a/profile31ff31.js","a/cpc_a_tpl.html.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/a/cpc_a_tpl.html3802d9.js","a/sponsor_a_tpl.html.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/a/sponsor_a_tpl.html36c7cf.js","a/a_tpl.html.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/a/a_tpl.html393ef4.js","a/mpshop.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/a/mpshop311179.js","a/wxopen_card.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/a/wxopen_card3a2b93.js","a/card.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/a/card311179.js","biz_wap/utils/position.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_wap/utils/position34c264.js","a/a_report.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/a/a_report393966.js","appmsg/my_comment_tpl.html.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/my_comment_tpl.html36906d.js","appmsg/cmt_tpl.html.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/cmt_tpl.html369d00.js","sougou/a_tpl.html.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/sougou/a_tpl.html2c6e7c.js","appmsg/emotion/emotion.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/emotion/emotion353f34.js","biz_wap/utils/wapsdk.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_wap/utils/wapsdk34c264.js","biz_common/utils/monitor.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_common/utils/monitor3518c6.js","biz_common/utils/report.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_common/utils/report3518c6.js","appmsg/open_url_with_webview.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/open_url_with_webview3145f0.js","biz_common/utils/http.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_common/utils/http3518c6.js","biz_common/utils/cookie.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_common/utils/cookie3518c6.js","appmsg/topic_tpl.html.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/topic_tpl.html31ff31.js","pages/weapp_tpl.html.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/pages/weapp_tpl.html36906d.js","pages/voice_tpl.html.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/pages/voice_tpl.html38518d.js","pages/kugoumusic_ctrl.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/pages/kugoumusic_ctrl393e3a.js","pages/qqmusic_ctrl.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/pages/qqmusic_ctrl39b68c.js","pages/voice_component.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/pages/voice_component39dc43.js","pages/qqmusic_tpl.html.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/pages/qqmusic_tpl.html393e3a.js","new_video/ctl.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/new_video/ctl2d441f.js","a/testdata.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/a/testdata393ef4.js","appmsg/reward_entry.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/reward_entry36906d.js","appmsg/comment.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/comment3944ad.js","appmsg/like.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/like375fea.js","pages/version4video.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/pages/version4video384cba.js","a/a.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/a/a3a2b93.js","rt/appmsg/getappmsgext.rt.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/rt/appmsg/getappmsgext.rt2c21f6.js","biz_wap/utils/storage.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_wap/utils/storage34c264.js","biz_common/tmpl.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_common/tmpl3518c6.js","appmsg/share_tpl.html.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/share_tpl.html36906d.js","appmsg/img_copyright_tpl.html.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/img_copyright_tpl.html2a2c13.js","pages/video_ctrl.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/pages/video_ctrl36ebcf.js","biz_common/ui/imgonepx.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_common/ui/imgonepx3518c6.js","biz_common/utils/respTypes.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_common/utils/respTypes3518c6.js","biz_wap/utils/log.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_wap/utils/log34c264.js","sougou/index.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/sougou/index36913b.js","biz_wap/safe/mutation_observer_report.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_wap/safe/mutation_observer_report34c264.js","appmsg/fereport.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/fereport37b642.js","appmsg/report.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/report3404b3.js","appmsg/report_and_source.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/report_and_source393966.js","appmsg/page_pos.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/page_pos393966.js","appmsg/cdn_speed_report.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/cdn_speed_report3097b2.js","appmsg/wxtopic.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/wxtopic31a3be.js","appmsg/new_index.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/new_index36906d.js","appmsg/weapp.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/weapp39d5b2.js","appmsg/autoread.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/autoread3857fc.js","appmsg/voice.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/voice38518d.js","appmsg/qqmusic.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/qqmusic39dc43.js","appmsg/iframe.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/iframe39ab71.js","appmsg/product.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/product393966.js","appmsg/review_image.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/review_image3944ad.js","appmsg/outer_link.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/outer_link275627.js","appmsg/copyright_report.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/copyright_report2ec4b2.js","appmsg/async.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/async38b7bb.js","biz_wap/ui/lazyload_img.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_wap/ui/lazyload_img36be04.js","biz_common/log/jserr.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_common/log/jserr3518c6.js","appmsg/share.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/share39f74d.js","appmsg/cdn_img_lib.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/cdn_img_lib38b7bb.js","biz_common/utils/url/parse.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_common/utils/url/parse36ebcf.js","page/appmsg/not_in_mm.css":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/style/page/appmsg/not_in_mm.css36906d.js","page/appmsg/page_mp_article_improve_combo.css":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/style/page/appmsg/page_mp_article_improve_combo.css39aac6.js","page/appmsg_new/not_in_mm.css":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/style/page/appmsg_new/not_in_mm.css36f05c.js","page/appmsg_new/combo.css":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/style/page/appmsg_new/combo.css39aac6.js","biz_wap/jsapi/core.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_wap/jsapi/core34c264.js","biz_common/dom/event.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_common/dom/event3a25e9.js","appmsg/test.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/test354009.js","biz_wap/utils/mmversion.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_wap/utils/mmversion34c264.js","appmsg/max_age.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/max_age2fdd28.js","biz_common/dom/attr.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_common/dom/attr3518c6.js","biz_wap/utils/ajax.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_wap/utils/ajax38c31a.js","appmsg/log.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/log300330.js","biz_common/dom/class.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_common/dom/class3518c6.js","biz_wap/utils/device.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_wap/utils/device34c264.js","biz_common/utils/string/html.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_common/utils/string/html3518c6.js","appmsg/index.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/index393966.js"};</script><script nonce="2126483999" type="text/javascript" id="moon_inline" > window.__mooninline=1; window.setTimeout(function() {  function __moonf__(){
        if(!window.__moonhasinit){
            window.__moonhasinit=!0,window.__moonclientlog=[],window.__wxgspeeds&&(window.__wxgspeeds.moonloadedtime=+new Date),
            "object"!=typeof JSON&&(window.JSON={
                stringify:function(){
                    return"";
                },
                parse:function(){
                    return{};
                }
            });
            var e=function(){
                function e(e){
                    try{
                        var o;
                        /(iPhone|iPad|iPod|iOS)/i.test(navigator.userAgent)?o="writeLog":/(Android)/i.test(navigator.userAgent)&&(o="log"),
                        o&&t(o,e);
                    }catch(n){
                        throw console.error(n),n;
                    }
                }
                function t(e,o){
                    e&&top.window.WeixinJSBridge&&top.window.WeixinJSBridge.invoke?top.window.WeixinJSBridge.invoke(e,{
                        level:"info",
                        msg:"[WechatFe][moon]"+o
                    }):setTimeout(function(){
                        top.window.document.addEventListener?top.window.document.addEventListener("WeixinJSBridgeReady",function(){
                            t(e,o);
                        },!1):top.window.document.attachEvent&&(top.window.document.attachEvent("WeixinJSBridgeReady",function(){
                                t(e,o);
                            }),top.window.document.attachEvent("onWeixinJSBridgeReady",function(){
                                t(e,o);
                            }));
                    },0);
                }
                var n;
                localStorage&&JSON.parse(localStorage.getItem("__WXLS__moonarg"))&&"fromls"==JSON.parse(localStorage.getItem("__WXLS__moonarg")).method&&(n=!0),
                    e(" moon init, moon_inline:"+window.__mooninline+", moonls:"+n),function(){
                    var e={},o={},t={};
                    e.COMBO_UNLOAD=0,e.COMBO_LOADING=1,e.COMBO_LOADED=2;
                    var n=function(e,t,n){
                        if(!o[e]){
                            o[e]=n;
                            for(var r=3;r--;)try{
                                moon.setItem(moon.prefix+e,n.toString()),moon.setItem(moon.prefix+e+"_ver",moon_map[e]);
                                break;
                            }catch(i){
                                moon.clear();
                            }
                        }
                    },r=window.alert;
                    window.__alertList=[],window.alert=function(e){
                        r(e),window.__alertList.push(e);
                    };
                    var i=function(e){
                        if(!e||!o[e])return null;
                        var n=o[e];
                        if("function"==typeof n&&!t[e]){
                            var a={},s={
                                exports:a
                            },c=n(i,a,s,r);
                            n=o[e]=c||s.exports,t[e]=!0;
                        }
                        if(".css"===e.substr(-4)){
                            var d=document.getElementById(e);
                            if(!d){
                                d=document.createElement("style"),d.id=e;
                                var _=/url\s*\(\s*\/(\"(?:[^\\\"\r\n\f]|\\[\s\S])*\"|'(?:[^\\'\n\r\f]|\\[\s\S])*'|[^)}]+)\s*\)/g,m=window.testenv_reshost||window.__moon_host||"res.wx.qq.com";
                                n=n.replace(_,"url(//"+m+"/$1)"),d.innerHTML=n,document.getElementsByTagName("head")[0].appendChild(d);
                            }
                        }
                        return n;
                    };
                    e.combo_status=e.COMBO_UNLOAD,e.run=function(){
                        var o=e.run.info,t=o&&o[0],n=o&&o[1];
                        if(t&&e.combo_status==e.COMBO_LOADED){
                            var r=i(t);
                            n&&n(r);
                        }
                    },e.use=function(o,t){
                        window.__wxgspeeds&&(window.__wxgspeeds.seajs_use_time=+new Date),e.run.info=[o,t],
                            e.run();
                    },window.define=n,window.seajs=e;
                }(),function(){
                    if(window.__nonce_str){
                        var e=document.createElement;
                        document.createElement=function(o){
                            var t=e.apply(this,arguments);
                            return"object"==typeof o&&(o=o.toString()),"string"==typeof o&&"script"==o.toLowerCase()&&t.setAttribute("nonce",window.__nonce_str),
                                t;
                        };
                    }
                    window.addEventListener&&window.__DEBUGINFO&&Math.random()<.01&&window.addEventListener("load",function(){
                        var e=document.createElement("script");
                        e.src=__DEBUGINFO.safe_js,e.type="text/javascript",e.async=!0;
                        var o=document.head||document.getElementsByTagName("head")[0];
                        o.appendChild(e);
                    });
                }(),function(){
                    function t(e){
                        return"[object Array]"===Object.prototype.toString.call(e);
                    }
                    function n(e){
                        return"[object Object]"===Object.prototype.toString.call(e);
                    }
                    function r(e){
                        var t=e.stack+" "+e.toString()||"";
                        try{
                            if(window.testenv_reshost){
                                var n="http(s)?://"+window.testenv_reshost,r=new RegExp(n,"g");
                                t=t.replace(r,"");
                            }else t=t.replace(/http(s)?:\/\/res\.wx\.qq\.com/g,"");
                            for(var r=/\/([^.]+)\/js\/(\S+?)\.js(\,|:)?/g;r.test(t);)t=t.replace(r,function(e,o,t,n){
                                return t+n;
                            });
                        }catch(e){
                            t=e.stack?e.stack:"";
                        }
                        var i=[];
                        for(o in u)u.hasOwnProperty(o)&&i.push(o+":"+u[o]);
                        return i.push("STK:"+t.replace(/\n/g,"")),i.join("|");
                    }
                    function i(e){
                        if(!e){
                            var o=window.onerror;
                            window.onerror=function(){},f=setTimeout(function(){
                                window.onerror=o,f=null;
                            },50);
                        }
                    }
                    function a(e,o,t){
                        if(!/^mp\.weixin\.qq\.com$/.test(location.hostname)){
                            var n=[];
                            t=t.replace(location.href,(location.origin||"")+(location.pathname||"")).replace("#wechat_redirect","").replace("#rd","").split("&");
                            for(var r=0,i=t.length;i>r;r++){
                                var a=t[r].split("=");
                                a[0]&&a[1]&&n.push(a[0]+"="+encodeURIComponent(a[1]));
                            }
                            var s=new window.Image;
                            return void(s.src=(o+n.join("&")).substr(0,1024));
                        }
                        var c;
                        if(window.ActiveXObject)try{
                            c=new ActiveXObject("Msxml2.XMLHTTP");
                        }catch(d){
                            try{
                                c=new ActiveXObject("Microsoft.XMLHTTP");
                            }catch(_){
                                c=!1;
                            }
                        }else window.XMLHttpRequest&&(c=new XMLHttpRequest);
                        c&&(c.open(e,o,!0),c.setRequestHeader("cache-control","no-cache"),c.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=UTF-8"),
                            c.setRequestHeader("X-Requested-With","XMLHttpRequest"),c.send(t));
                    }
                    function s(e){
                        return function(o,t){
                            if("string"==typeof o)try{
                                o=new Function(o);
                            }catch(n){
                                throw n;
                            }
                            var r=[].slice.call(arguments,2),a=o;
                            return o=function(){
                                try{
                                    return a.apply(this,r.length&&r||arguments);
                                }catch(e){
                                    throw e.stack&&console&&console.error&&console.error("[TryCatch]"+e.stack),_&&window.__moon_report&&(window.__moon_report([{
                                        offset:O,
                                        log:"timeout_error;host:"+top.location.host,
                                        e:e
                                    }]),i(f)),e;
                                }
                            },e(o,t);
                        };
                    }
                    function c(e){
                        return function(o,t,n){
                            if("undefined"==typeof n)var n=!1;
                            var r=this,a=t||function(){};
                            return t=function(){
                                try{
                                    return a.apply(r,arguments);
                                }catch(e){
                                    throw e.stack&&console&&console.error&&console.error("[TryCatch]"+e.stack),_&&window.__moon_report&&(window.__moon_report([{
                                        offset:y,
                                        log:"listener_error;type:"+o+";host:"+top.location.host,
                                        e:e
                                    }]),i(f)),e;
                                }
                            },a.moon_lid=b,D[b]=t,b++,e.call(r,o,t,n);
                        };
                    }
                    function d(e){
                        return function(o,t,n){
                            if("undefined"==typeof n)var n=!1;
                            var r=this;
                            return t=D[t.moon_lid],e.call(r,o,t,n);
                        };
                    }
                    var _,m,w,l,u,p,f,h=/MicroMessenger/i.test(navigator.userAgent),g=window.define,v=0,y=2,x=4,O=9,j=10;
                    if(window.__initCatch=function(e){
                            _=e.idkey,m=e.startKey||0,w=e.limit,l=e.badjsId,u=e.reportOpt||"",p=e.extInfo||{},
                                p.rate=p.rate||.5;
                        },window.__moon_report=function(e,o){
                            var i=.5;
                            if(p&&p.rate&&(i=p.rate),o&&"number"==typeof o&&(i=o),!(!/mp\.weixin\.qq\.com/.test(location.href)&&!/payapp\.weixin\.qq\.com/.test(location.href)||Math.random()>i||!h||top!=window&&!/mp\.weixin\.qq\.com/.test(top.location.href))&&(n(e)&&(e=[e]),
                                t(e)&&""!=_)){
                                var s="",c=[],d=[],u=[],f=[];
                                "number"!=typeof w&&(w=1/0);
                                for(var g=0;g<e.length;g++){
                                    var v=e[g]||{};
                                    if(!(v.offset>w||"number"!=typeof v.offset||v.offset==x&&p&&p.network_rate&&Math.random()>=p.network_rate)){
                                        var y=1/0==w?m:m+v.offset;
                                        c[g]="[moon]"+_+"_"+y+";"+v.log+";"+r(v.e||{})||"",d[g]=y,u[g]=1;
                                    }
                                }
                                for(var O=0;O<d.length;O++)f[O]=_+"_"+d[O]+"_"+u[O],s=s+"&log"+O+"="+c[O];
                                if(f.length>0){
                                    a("POST",location.protocol+"//mp.weixin.qq.com/mp/jsmonitor?","idkey="+f.join(";")+"&r="+Math.random()+"&lc="+c.length+s);
                                    var i=1;
                                    if(p&&p.badjs_rate&&(i=p.badjs_rate),l&&Math.random()<i){
                                        s=s.replace(/uin\:(.)*\|biz\:(.)*\|mid\:(.)*\|idx\:(.)*\|sn\:(.)*\|/,"");
                                        var j=new Image,D="https://badjs.weixinbridge.com/badjs?id="+l+"&level=4&from="+encodeURIComponent(location.host)+"&msg="+encodeURIComponent(s);
                                        j.src=D.slice(0,1024);
                                    }
                                }
                            }
                        },window.setTimeout=s(window.setTimeout),window.setInterval=s(window.setInterval),
                        Math.random()<.01&&window.Document&&window.HTMLElement){
                        var D={},b=0;
                        Document.prototype.addEventListener=c(Document.prototype.addEventListener),Document.prototype.removeEventListener=d(Document.prototype.removeEventListener),
                            HTMLElement.prototype.addEventListener=c(HTMLElement.prototype.addEventListener),
                            HTMLElement.prototype.removeEventListener=d(HTMLElement.prototype.removeEventListener);
                    }
                    var E=window.navigator.userAgent;
                    if((/ip(hone|ad|od)/i.test(E)||/android/i.test(E))&&!/windows phone/i.test(E)&&window.localStorage&&window.localStorage.setItem){
                        var S=window.localStorage.setItem,I=0;
                        window.localStorage.setItem=function(e,o){
                            if(!(I>=10))try{
                                S.call(window.localStorage,e,o);
                            }catch(t){
                                t.stack&&console&&console.error&&console.error("[TryCatch]"+t.stack),window.__moon_report([{
                                    offset:j,
                                    log:"localstorage_error;"+t.toString(),
                                    e:t
                                }]),I++,I>=3&&window.moon&&window.moon.clear&&moon.clear();
                            }
                        };
                    }
                    window.seajs&&g&&(window.define=function(){
                        for(var o,t=[],n=arguments&&arguments[0],a=0,s=arguments.length;s>a;a++){
                            var c=o=arguments[a];
                            "function"==typeof o&&(o=function(){
                                try{
                                    return c.apply(this,arguments);
                                }catch(o){
                                    throw"string"==typeof n&&console.error("[TryCatch][DefineeErr]id:"+n),o.stack&&console&&console.error&&console.error("[TryCatch]"+o.stack),
                                    _&&window.__moon_report&&(window.__moon_report([{
                                        offset:v,
                                        log:"define_error;id:"+n+";",
                                        e:o
                                    }]),i(f)),e(" [define_error]"+JSON.stringify(r(o))),o;
                                }
                            },o.toString=function(e){
                                return function(){
                                    return e.toString();
                                };
                            }(arguments[a])),t.push(o);
                        }
                        return g.apply(this,t);
                    });
                }(),function(o){
                    function t(e,o,t){
                        return window.__DEBUGINFO?(window.__DEBUGINFO.res_list||(window.__DEBUGINFO.res_list=[]),
                            window.__DEBUGINFO.res_list[e]?(window.__DEBUGINFO.res_list[e][o]=t,!0):!1):!1;
                    }
                    function n(e){
                        var o=new TextEncoder("utf-8").encode(e),t=crypto.subtle||crypto.webkitSubtle;
                        return t.digest("SHA-256",o).then(function(e){
                            return r(e);
                        });
                    }
                    function r(e){
                        for(var o=[],t=new DataView(e),n=0;n<t.byteLength;n+=4){
                            var r=t.getUint32(n),i=r.toString(16),a="00000000",s=(a+i).slice(-a.length);
                            o.push(s);
                        }
                        return o.join("");
                    }
                    function i(e,o,t){
                        if("object"==typeof e){
                            var n=Object.prototype.toString.call(e).replace(/^\[object (.+)\]$/,function(e,o){
                                return o;
                            });
                            if(t=t||e,"Array"==n){
                                for(var r=0,i=e.length;i>r;++r)if(o.call(t,e[r],r,e)===!1)return;
                            }else{
                                if("Object"!==n&&a!=e)throw"unsupport type";
                                if(a==e){
                                    for(var r=e.length-1;r>=0;r--){
                                        var s=a.key(r),c=a.getItem(s);
                                        if(o.call(t,c,s,e)===!1)return;
                                    }
                                    return;
                                }
                                for(var r in e)if(e.hasOwnProperty(r)&&o.call(t,e[r],r,e)===!1)return;
                            }
                        }
                    }
                    var a=o.localStorage,s=document.head||document.getElementsByTagName("head")[0],c=1,d=11,_=12,m=13,w=window.__allowLoadResFromMp?1:2,l=window.__allowLoadResFromMp?1:0,u=w+l,p=window.testenv_reshost||window.__moon_host||"res.wx.qq.com",f=new RegExp("^(http(s)?:)?//"+p);
                    window.__loadAllResFromMp&&(p="mp.weixin.qq.com",w=0,u=w+l);
                    var h=0,g={
                        prefix:"__MOON__",
                        loaded:[],
                        unload:[],
                        clearSample:Math.random()<h,
                        hit_num:0,
                        mod_num:0,
                        version:1003,
                        cacheData:{
                            js_mod_num:0,
                            js_hit_num:0,
                            js_not_hit_num:0,
                            js_expired_num:0,
                            css_mod_num:0,
                            css_hit_num:0,
                            css_not_hit_num:0,
                            css_expired_num:0
                        },
                        init:function(){
                            g.loaded=[],g.unload=[];
                            var e,t,r;
                            if(a){
                                var s="_moon_ver_key_",c=a.getItem(s);
                                c!=g.version&&(g.clear(),a.setItem(s,g.version));
                            }
                            if((-1!=location.search.indexOf("no_moon1=1")||-1!=location.search.indexOf("no_lshttps=1"))&&g.clear(),
                                    a){
                                var d=1*a.getItem(g.prefix+"clean_time"),_=+new Date;
                                if(_-d>=1296e6){
                                    g.clear();
                                    try{
                                        !!a&&a.setItem(g.prefix+"clean_time",+new Date);
                                    }catch(m){}
                                }
                            }
                            i(moon_map,function(i,s){
                                if(t=g.prefix+s,r=!!i&&i.replace(f,""),e=!!a&&a.getItem(t),version=!!a&&(a.getItem(t+"_ver")||"").replace(f,""),
                                        g.mod_num++,r&&-1!=r.indexOf(".css")?g.cacheData.css_mod_num++:r&&-1!=r.indexOf(".js")&&g.cacheData.js_mod_num++,
                                    g.clearSample||!e||r!=version)g.unload.push(r.replace(f,"")),r&&-1!=r.indexOf(".css")?e?r!=version&&g.cacheData.css_expired_num++:g.cacheData.css_not_hit_num++:r&&-1!=r.indexOf(".js")&&(e?r!=version&&g.cacheData.js_expired_num++:g.cacheData.js_not_hit_num++);else{
                                    if("https:"==location.protocol&&window.moon_hash_map&&window.moon_hash_map[s]&&window.crypto)try{
                                        n(e).then(function(e){
                                            window.moon_hash_map[s]!=e&&console.log(s);
                                        });
                                    }catch(c){}
                                    try{
                                        var d="//# sourceURL="+s+"\n//@ sourceURL="+s;
                                        o.eval.call(o,'define("'+s+'",[],'+e+")"+d),g.hit_num++,r&&-1!=r.indexOf(".css")?g.cacheData.css_hit_num++:r&&-1!=r.indexOf(".js")&&g.cacheData.js_hit_num++;
                                    }catch(c){
                                        g.unload.push(r.replace(f,""));
                                    }
                                }
                            }),g.load(g.genUrl());
                        },
                        genUrl:function(){
                            var e=g.unload;
                            if(!e||e.length<=0)return[];
                            var o,t,n="",r=[],i={},a=-1!=location.search.indexOf("no_moon2=1"),s="//"+p;
                            -1!=location.href.indexOf("moon_debug2=1")&&(s="//mp.weixin.qq.com");
                            for(var c=0,d=e.length;d>c;++c){
                                /^\/(.*?)\//.test(e[c]);
                                var _=/^\/(.*?)\//.exec(e[c]);
                                _.length<2||!_[1]||(t=_[1],n=i[t],n?(o=n+","+e[c],o.length>1e3||a?(r.push(n+"?v="+g.version),
                                    n=location.protocol+s+e[c],i[t]=n):(n=o,i[t]=n)):(n=location.protocol+s+e[c],i[t]=n));
                            }
                            for(var m in i)i.hasOwnProperty(m)&&r.push(i[m]);
                            return r;
                        },
                        load:function(e){
                            if(window.__wxgspeeds&&(window.__wxgspeeds.mod_num=g.mod_num,window.__wxgspeeds.hit_num=g.hit_num),
                                !e||e.length<=0)return seajs.combo_status=seajs.COMBO_LOADED,seajs.run(),console.debug&&console.debug("[moon] load js complete, all in cache, cost time : 0ms, total count : "+g.mod_num+", hit num: "+g.hit_num),
                                void window.__moonclientlog.push("[moon] load js complete, all in cache, cost time : 0ms, total count : "+g.mod_num+", hit num: "+g.hit_num);
                            seajs.combo_status=seajs.COMBO_LOADING;
                            var o=0,t=+new Date;
                            window.__wxgspeeds&&(window.__wxgspeeds.combo_times=[],window.__wxgspeeds.combo_times.push(t)),
                                i(e,function(n){
                                    g.request(n,u,function(){
                                        if(window.__wxgspeeds&&window.__wxgspeeds.combo_times.push(+new Date),o++,o==e.length){
                                            var n=+new Date-t;
                                            window.__wxgspeeds&&(window.__wxgspeeds.mod_downloadtime=n),seajs.combo_status=seajs.COMBO_LOADED,
                                                seajs.run(),console.debug&&console.debug("[moon] load js complete, url num : "+e.length+", total mod count : "+g.mod_num+", hit num: "+g.hit_num+", use time : "+n+"ms"),
                                                window.__moonclientlog.push("[moon] load js complete, url num : "+e.length+", total mod count : "+g.mod_num+", hit num: "+g.hit_num+", use time : "+n+"ms");
                                        }
                                    });
                                });
                        },
                        request:function(o,n,r){
                            if(o){
                                n=n||0,o.indexOf("mp.weixin.qq.com")>-1&&((new Image).src=location.protocol+"//mp.weixin.qq.com/mp/jsmonitor?idkey=27613_32_1&r="+Math.random(),
                                    window.__moon_report([{
                                        offset:_,
                                        log:"load_script_from_mp: "+o
                                    }],1));
                                var i=-1;
                                window.__DEBUGINFO&&(__DEBUGINFO.res_list||(__DEBUGINFO.res_list=[]),__DEBUGINFO.res_list.push({
                                    type:"js",
                                    status:"pendding",
                                    start:+new Date,
                                    end:0,
                                    url:o
                                }),i=__DEBUGINFO.res_list.length-1),-1!=location.search.indexOf("no_lshttps=1")&&(o=o.replace("http://","https://"));
                                var a=document.createElement("script");
                                a.src=o,a.type="text/javascript",a.async=!0,a.down_time=+new Date,a.onerror=function(s){
                                    t(i,"status","error"),t(i,"end",+new Date);
                                    var _=new Error(s);
                                    if(n>=0)if(l>n){
                                        var w=o.replace("res.wx.qq.com","mp.weixin.qq.com");
                                        g.request(w,n,r);
                                    }else g.request(o,n,r);else window.__moon_report&&window.__moon_report([{
                                        offset:c,
                                        log:"load_script_error: "+o,
                                        e:_
                                    }],1);
                                    if(n==l-1&&window.__moon_report([{
                                            offset:d,
                                            log:"load_script_error: "+o,
                                            e:_
                                        }],1),-1==n){
                                        var u="ua: "+window.navigator.userAgent+", time="+(+new Date-a.down_time)+", load_script_error -1 : "+o;
                                        window.__moon_report([{
                                            offset:m,
                                            log:u
                                        }],1);
                                    }
                                    window.__moonclientlog.push("moon load js error : "+o+", error -> "+_.toString()),
                                        e("moon_request_error url:"+o);
                                },"undefined"!=typeof moon_crossorigin&&moon_crossorigin&&a.setAttribute("crossorigin",!0),
                                    a.onload=a.onreadystatechange=function(){
                                        t(i,"status","loaded"),t(i,"end",+new Date),!a||a.readyState&&!/loaded|complete/.test(a.readyState)||(t(i,"status","200"),
                                            a.onload=a.onreadystatechange=null,"function"==typeof r&&r());
                                    },n--,s.appendChild(a),e("moon_request url:"+o+" retry:"+n);
                            }
                        },
                        setItem:function(e,o){
                            !!a&&a.setItem(e,o);
                        },
                        clear:function(){
                            a&&(i(a,function(e,o){
                                ~o.indexOf(g.prefix)&&a.removeItem(o);
                            }),console.debug&&console.debug("[moon] clear"));
                        },
                        idkeyReport:function(e,o,t){
                            t=t||1;
                            var n=e+"_"+o+"_"+t;
                            (new Image).src="/mp/jsmonitor?idkey="+n+"&r="+Math.random();
                        }
                    };
                    seajs&&seajs.use&&"string"==typeof window.__moon_mainjs&&seajs.use(window.__moon_mainjs),
                        window.moon=g;
                }(window),window.moon.init();
            };
            e(),!!window.__moon_initcallback&&window.__moon_initcallback(),window.__wxgspeeds&&(window.__wxgspeeds.moonendtime=+new Date);
        }
    }
        __moonf__(); }, 25);</script><script nonce="2126483999" type="text/javascript">
    var real_show_page_time = +new Date();
    if (!!window.addEventListener){
        window.addEventListener("load", function(){
            window.onload_endtime = +new Date();
        });
    }

</script>

</body>
<script nonce="2126483999" type="text/javascript">document.addEventListener("touchstart", function() {},false);</script>
</html>
<!--tailTrap<body></body><head></head><html></html>-->
