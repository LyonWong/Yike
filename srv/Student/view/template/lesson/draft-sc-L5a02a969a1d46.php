<!DOCTYPE html>
<html style="-webkit-text-size-adjust: 100%; line-height: 1.60">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0,viewport-fit=cover">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no">


    <script nonce="1913591860" type="text/javascript">
        window.logs = {
            pagetime: {}
        };
        window.logs.pagetime['html_begin'] = (+new Date());
    </script>

    <script nonce="1913591860" type="text/javascript">
        var biz = "MzUyMDAzNDYxNw=="||"";
        var sn = "" || ""|| "6393f1a033c445dd2b5510569449e624";
        var mid = "100000065" || ""|| "100000065";
        var idx = "1" || "" || "1";
        window.__allowLoadResFromMp = true;

    </script>
    <script nonce="1913591860" type="text/javascript">
        var page_begintime=+new Date,is_rumor="",norumor="";
        1*is_rumor&&!(1*norumor)&&biz&&mid&&(document.referrer&&-1!=document.referrer.indexOf("mp.weixin.qq.com/mp/rumor")||(location.href="http://mp.weixin.qq.com/mp/rumor?action=info&__biz="+biz+"&mid="+mid+"&idx="+idx+"&sn="+sn+"#wechat_redirect")),
            document.domain="qq.com";
    </script>
    <script nonce="1913591860" type="text/javascript">
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
    <script nonce="1913591860" type="text/javascript">
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
            window.key = params['key'] || "070759cbe7afbd8257ac3ce379199fe059e58e836859a79e0cf04a4105d98aad06e635c8a8afd3b104af755271b4b7ba00493d26294e0be476a6ddf27f1b8359a32eeafa548a03b45ac6f098e7792e22" || '';
            window.wxtoken = params['wxtoken'] || '';
            window.pass_ticket = params['pass_ticket'] || '';
            window.appmsg_token = "933_ek4AkKzAfdUzAofvP9jLMEyYICBtuj9iQdHKvviQPKyEKyl28A4O8utY7rw~";
        })();

        function wx_loaderror() {
            if (location.pathname === '/bizmall/reward') {
                new Image().src = '/mp/jsreport?key=96&content=reward_res_load_err&r=' + Math.random();
            }
        }

    </script>

    <title>那些形形色色的流量生意</title>

    <style>html{-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;line-height:1.6}body{-webkit-touch-callout:none;font-family:-apple-system-font,"Helvetica Neue","PingFang SC","Hiragino Sans GB","Microsoft YaHei",sans-serif;background-color:#f3f3f3;line-height:inherit}body.rich_media_empty_extra{background-color:#fff}body.rich_media_empty_extra .rich_media_area_primary:before{display:none}h1,h2,h3,h4,h5,h6{font-weight:400;font-size:16px}*{margin:0;padding:0}a{color:#607fa6;text-decoration:none}.rich_media_inner{font-size:16px;word-wrap:break-word;-webkit-hyphens:auto;-ms-hyphens:auto;hyphens:auto}.rich_media_area_primary{position:relative;padding:20px 15px 15px;background-color:#fff}.rich_media_area_primary:before{content:" ";position:absolute;left:0;top:0;width:100%;height:1px;border-top:1px solid #e5e5e5;-webkit-transform-origin:0 0;transform-origin:0 0;-webkit-transform:scaleY(0.5);transform:scaleY(0.5);top:auto;bottom:-2px}.rich_media_area_primary .original_img_wrp{display:inline-block;font-size:0}.rich_media_area_primary .original_img_wrp .tips_global{display:block;margin-top:.5em;font-size:14px;text-align:right;width:auto;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;word-wrap:normal}.rich_media_area_extra{padding:0 15px 0}.rich_media_title{margin-bottom:10px;line-height:1.4;font-weight:400;font-size:24px}.icon_original_tag_primary{display:inline-block;padding:1px .65em;margin-top:-0.2em;vertical-align:middle;line-height:1.4;font-size:12px;border-top-left-radius:.85em 50%;-moz-border-radius-topleft:.85em 50%;-webkit-border-top-left-radius:.85em 50%;border-top-right-radius:.85em 50%;-moz-border-radius-topright:.85em 50%;-webkit-border-top-right-radius:.85em 50%;border-bottom-left-radius:.85em 50%;-moz-border-radius-bottomleft:.85em 50%;-webkit-border-bottom-left-radius:.85em 50%;border-bottom-right-radius:.85em 50%;-moz-border-radius-bottomright:.85em 50%;-webkit-border-bottom-right-radius:.85em 50%;border:1px solid #9e9e9e;color:#8c8c8c}.icon_original_tag_primary.title_tag{background-color:#e94442;border-color:#d04b4e;color:#fff;margin-bottom:.5em;padding:2px .65em;border-top-left-radius:.95em 50%;-moz-border-radius-topleft:.95em 50%;-webkit-border-top-left-radius:.95em 50%;border-top-right-radius:.95em 50%;-moz-border-radius-topright:.95em 50%;-webkit-border-top-right-radius:.95em 50%;border-bottom-left-radius:.95em 50%;-moz-border-radius-bottomleft:.95em 50%;-webkit-border-bottom-left-radius:.95em 50%;border-bottom-right-radius:.95em 50%;-moz-border-radius-bottomright:.95em 50%;-webkit-border-bottom-right-radius:.95em 50%}.rich_media_meta_list{margin-bottom:18px;line-height:20px;font-size:0}.rich_media_meta_list em{font-style:normal}.rich_media_meta{display:inline-block;vertical-align:middle;margin-right:8px;margin-bottom:10px;font-size:16px}.meta_original_tag{display:inline-block;vertical-align:middle;padding:1px .5em;border:1px solid #9e9e9e;color:#8c8c8c;border-top-left-radius:20% 50%;-moz-border-radius-topleft:20% 50%;-webkit-border-top-left-radius:20% 50%;border-top-right-radius:20% 50%;-moz-border-radius-topright:20% 50%;-webkit-border-top-right-radius:20% 50%;border-bottom-left-radius:20% 50%;-moz-border-radius-bottomleft:20% 50%;-webkit-border-bottom-left-radius:20% 50%;border-bottom-right-radius:20% 50%;-moz-border-radius-bottomright:20% 50%;-webkit-border-bottom-right-radius:20% 50%;font-size:15px;line-height:1.1}.meta_enterprise_tag img{width:30px;height:30px!important;display:block;position:relative;margin-top:-3px;border:0}.rich_media_meta_text{color:#8c8c8c}span.rich_media_meta_nickname{display:none}.rich_media_thumb_wrp{margin-bottom:6px}.rich_media_thumb_wrp .original_img_wrp{display:block}.rich_media_thumb{display:block;width:100%}.rich_media_content{overflow:hidden;color:#3e3e3e}.rich_media_content *{max-width:100%!important;box-sizing:border-box!important;-webkit-box-sizing:border-box!important;word-wrap:break-word!important}.rich_media_content p{clear:both;min-height:1em}.rich_media_content em{font-style:italic}.rich_media_content fieldset{min-width:0}.rich_media_content .list-paddingleft-2{padding-left:30px}.rich_media_content blockquote{margin:0;padding-left:10px;border-left:3px solid #dbdbdb}img{height:auto!important}@media screen and (device-aspect-ratio:2/3),screen and (device-aspect-ratio:40/71){.meta_original_tag{padding-top:0}}@media(min-device-width:375px) and (max-device-width:667px) and (-webkit-min-device-pixel-ratio:2){.mm_appmsg .rich_media_inner,.mm_appmsg .rich_media_meta,.mm_appmsg .discuss_list,.mm_appmsg .rich_media_extra,.mm_appmsg .title_tips .tips{font-size:17px}.mm_appmsg .meta_original_tag{font-size:15px}}@media(min-device-width:414px) and (max-device-width:736px) and (-webkit-min-device-pixel-ratio:3){.mm_appmsg .rich_media_title{font-size:25px}}@media only screen and (device-width:375px) and (device-height:812px) and (-webkit-device-pixel-ratio:3) and (orientation:portrait){.rich_media_area_extra{padding-bottom:34px}}@media only screen and (device-width:375px) and (device-height:812px) and (-webkit-device-pixel-ratio:3) and (orientation:landscape){.rich_media_area_primary{padding:20px 59px 15px 59px}.rich_media_area_extra{padding:0 59px 21px 59px}}@media screen and (min-width:1024px){.rich_media{width:740px;margin-left:auto;margin-right:auto}.rich_media_inner{padding:20px}body{background-color:#fff}}@media screen and (min-width:1025px){body{font-family:"Helvetica Neue",Helvetica,"Hiragino Sans GB","Microsoft YaHei",Arial,sans-serif}.rich_media{position:relative}.rich_media_inner{background-color:#fff;padding-bottom:100px}}.radius_avatar{display:inline-block;background-color:#fff;padding:3px;border-radius:50%;-moz-border-radius:50%;-webkit-border-radius:50%;overflow:hidden;vertical-align:middle}.radius_avatar img{display:block;width:100%;height:100%;border-radius:50%;-moz-border-radius:50%;-webkit-border-radius:50%;background-color:#eee}.cell{padding:.8em 0;display:block;position:relative}.cell_hd,.cell_bd,.cell_ft{display:table-cell;vertical-align:middle;word-wrap:break-word;word-break:break-all;white-space:nowrap}.cell_primary{width:2000px;white-space:normal}.flex_cell{padding:10px 0;display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-box-align:center;-webkit-align-items:center;-ms-flex-align:center;align-items:center}.flex_cell_primary{width:100%;-webkit-box-flex:1;-webkit-flex:1;-ms-flex:1;box-flex:1;flex:1}.original_tool_area{display:block;padding:.75em 1em 0;-webkit-tap-highlight-color:rgba(0,0,0,0);color:#3e3e3e;border:1px solid #eaeaea;margin:20px 0}.original_tool_area .tips_global{position:relative;padding-bottom:.5em;font-size:15px}.original_tool_area .tips_global:after{content:" ";position:absolute;left:0;bottom:0;right:0;height:1px;border-bottom:1px solid #dbdbdb;-webkit-transform-origin:0 100%;transform-origin:0 100%;-webkit-transform:scaleY(0.5);transform:scaleY(0.5)}.original_tool_area .radius_avatar{width:27px;height:27px;padding:0;margin-right:.5em}.original_tool_area .radius_avatar img{height:100%!important}.original_tool_area .flex_cell_bd{width:auto;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;word-wrap:normal}.original_tool_area .flex_cell_ft{font-size:14px;color:#8c8c8c;padding-left:1em;white-space:nowrap}.original_tool_area .icon_access:after{content:" ";display:inline-block;height:8px;width:8px;border-width:1px 1px 0 0;border-color:#cbcad0;border-style:solid;transform:matrix(0.71,0.71,-0.71,0.71,0,0);-ms-transform:matrix(0.71,0.71,-0.71,0.71,0,0);-webkit-transform:matrix(0.71,0.71,-0.71,0.71,0,0);position:relative;top:-2px;top:-1px}.weui_loading{width:20px;height:20px;display:inline-block;vertical-align:middle;-webkit-animation:weuiLoading 1s steps(12,end) infinite;animation:weuiLoading 1s steps(12,end) infinite;background:transparent url(data:image/svg+xml;base64,PHN2ZyBjbGFzcz0iciIgd2lkdGg9JzEyMHB4JyBoZWlnaHQ9JzEyMHB4JyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAxMDAgMTAwIj4KICAgIDxyZWN0IHg9IjAiIHk9IjAiIHdpZHRoPSIxMDAiIGhlaWdodD0iMTAwIiBmaWxsPSJub25lIiBjbGFzcz0iYmsiPjwvcmVjdD4KICAgIDxyZWN0IHg9JzQ2LjUnIHk9JzQwJyB3aWR0aD0nNycgaGVpZ2h0PScyMCcgcng9JzUnIHJ5PSc1JyBmaWxsPScjRTlFOUU5JwogICAgICAgICAgdHJhbnNmb3JtPSdyb3RhdGUoMCA1MCA1MCkgdHJhbnNsYXRlKDAgLTMwKSc+CiAgICA8L3JlY3Q+CiAgICA8cmVjdCB4PSc0Ni41JyB5PSc0MCcgd2lkdGg9JzcnIGhlaWdodD0nMjAnIHJ4PSc1JyByeT0nNScgZmlsbD0nIzk4OTY5NycKICAgICAgICAgIHRyYW5zZm9ybT0ncm90YXRlKDMwIDUwIDUwKSB0cmFuc2xhdGUoMCAtMzApJz4KICAgICAgICAgICAgICAgICByZXBlYXRDb3VudD0naW5kZWZpbml0ZScvPgogICAgPC9yZWN0PgogICAgPHJlY3QgeD0nNDYuNScgeT0nNDAnIHdpZHRoPSc3JyBoZWlnaHQ9JzIwJyByeD0nNScgcnk9JzUnIGZpbGw9JyM5Qjk5OUEnCiAgICAgICAgICB0cmFuc2Zvcm09J3JvdGF0ZSg2MCA1MCA1MCkgdHJhbnNsYXRlKDAgLTMwKSc+CiAgICAgICAgICAgICAgICAgcmVwZWF0Q291bnQ9J2luZGVmaW5pdGUnLz4KICAgIDwvcmVjdD4KICAgIDxyZWN0IHg9JzQ2LjUnIHk9JzQwJyB3aWR0aD0nNycgaGVpZ2h0PScyMCcgcng9JzUnIHJ5PSc1JyBmaWxsPScjQTNBMUEyJwogICAgICAgICAgdHJhbnNmb3JtPSdyb3RhdGUoOTAgNTAgNTApIHRyYW5zbGF0ZSgwIC0zMCknPgogICAgPC9yZWN0PgogICAgPHJlY3QgeD0nNDYuNScgeT0nNDAnIHdpZHRoPSc3JyBoZWlnaHQ9JzIwJyByeD0nNScgcnk9JzUnIGZpbGw9JyNBQkE5QUEnCiAgICAgICAgICB0cmFuc2Zvcm09J3JvdGF0ZSgxMjAgNTAgNTApIHRyYW5zbGF0ZSgwIC0zMCknPgogICAgPC9yZWN0PgogICAgPHJlY3QgeD0nNDYuNScgeT0nNDAnIHdpZHRoPSc3JyBoZWlnaHQ9JzIwJyByeD0nNScgcnk9JzUnIGZpbGw9JyNCMkIyQjInCiAgICAgICAgICB0cmFuc2Zvcm09J3JvdGF0ZSgxNTAgNTAgNTApIHRyYW5zbGF0ZSgwIC0zMCknPgogICAgPC9yZWN0PgogICAgPHJlY3QgeD0nNDYuNScgeT0nNDAnIHdpZHRoPSc3JyBoZWlnaHQ9JzIwJyByeD0nNScgcnk9JzUnIGZpbGw9JyNCQUI4QjknCiAgICAgICAgICB0cmFuc2Zvcm09J3JvdGF0ZSgxODAgNTAgNTApIHRyYW5zbGF0ZSgwIC0zMCknPgogICAgPC9yZWN0PgogICAgPHJlY3QgeD0nNDYuNScgeT0nNDAnIHdpZHRoPSc3JyBoZWlnaHQ9JzIwJyByeD0nNScgcnk9JzUnIGZpbGw9JyNDMkMwQzEnCiAgICAgICAgICB0cmFuc2Zvcm09J3JvdGF0ZSgyMTAgNTAgNTApIHRyYW5zbGF0ZSgwIC0zMCknPgogICAgPC9yZWN0PgogICAgPHJlY3QgeD0nNDYuNScgeT0nNDAnIHdpZHRoPSc3JyBoZWlnaHQ9JzIwJyByeD0nNScgcnk9JzUnIGZpbGw9JyNDQkNCQ0InCiAgICAgICAgICB0cmFuc2Zvcm09J3JvdGF0ZSgyNDAgNTAgNTApIHRyYW5zbGF0ZSgwIC0zMCknPgogICAgPC9yZWN0PgogICAgPHJlY3QgeD0nNDYuNScgeT0nNDAnIHdpZHRoPSc3JyBoZWlnaHQ9JzIwJyByeD0nNScgcnk9JzUnIGZpbGw9JyNEMkQyRDInCiAgICAgICAgICB0cmFuc2Zvcm09J3JvdGF0ZSgyNzAgNTAgNTApIHRyYW5zbGF0ZSgwIC0zMCknPgogICAgPC9yZWN0PgogICAgPHJlY3QgeD0nNDYuNScgeT0nNDAnIHdpZHRoPSc3JyBoZWlnaHQ9JzIwJyByeD0nNScgcnk9JzUnIGZpbGw9JyNEQURBREEnCiAgICAgICAgICB0cmFuc2Zvcm09J3JvdGF0ZSgzMDAgNTAgNTApIHRyYW5zbGF0ZSgwIC0zMCknPgogICAgPC9yZWN0PgogICAgPHJlY3QgeD0nNDYuNScgeT0nNDAnIHdpZHRoPSc3JyBoZWlnaHQ9JzIwJyByeD0nNScgcnk9JzUnIGZpbGw9JyNFMkUyRTInCiAgICAgICAgICB0cmFuc2Zvcm09J3JvdGF0ZSgzMzAgNTAgNTApIHRyYW5zbGF0ZSgwIC0zMCknPgogICAgPC9yZWN0Pgo8L3N2Zz4=) no-repeat;-webkit-background-size:100%;background-size:100%}@-webkit-keyframes weuiLoading{0%{-webkit-transform:rotate3d(0,0,1,0deg)}100%{-webkit-transform:rotate3d(0,0,1,360deg)}}@keyframes weuiLoading{0%{-webkit-transform:rotate3d(0,0,1,0deg)}100%{-webkit-transform:rotate3d(0,0,1,360deg)}}.gif_img_wrp{display:inline-block;font-size:0;position:relative;font-weight:400;font-style:normal;text-indent:0;text-shadow:none 1px 1px rgba(0,0,0,0.5)}.gif_img_wrp img{vertical-align:top}.gif_img_tips{background:rgba(0,0,0,0.6)!important;filter:progid:DXImageTransform.Microsoft.gradient(GradientType=0,startColorstr='#99000000',endcolorstr = '#99000000');border-top-left-radius:1.2em 50%;-moz-border-radius-topleft:1.2em 50%;-webkit-border-top-left-radius:1.2em 50%;border-top-right-radius:1.2em 50%;-moz-border-radius-topright:1.2em 50%;-webkit-border-top-right-radius:1.2em 50%;border-bottom-left-radius:1.2em 50%;-moz-border-radius-bottomleft:1.2em 50%;-webkit-border-bottom-left-radius:1.2em 50%;border-bottom-right-radius:1.2em 50%;-moz-border-radius-bottomright:1.2em 50%;-webkit-border-bottom-right-radius:1.2em 50%;line-height:2.3;font-size:11px;color:#fff;text-align:center;position:absolute;bottom:10px;left:10px;min-width:65px}.gif_img_tips.loading{min-width:75px}.gif_img_tips i{vertical-align:middle;margin:-0.2em .73em 0 -2px}.gif_img_play_arrow{display:inline-block;width:0;height:0;border-width:8px;border-style:dashed;border-color:transparent;border-right-width:0;border-left-color:#fff;border-left-style:solid;border-width:5px 0 5px 8px}.gif_img_loading{width:14px;height:14px}i.gif_img_loading{margin-left:-4px}.gif_bg_tips_wrp{position:relative;height:0;line-height:0;margin:0;padding:0}.gif_bg_tips_wrp .gif_img_tips_group{position:absolute;top:0;left:0;z-index:9999}.gif_bg_tips_wrp .gif_img_tips_group .gif_img_tips{top:0;left:0;bottom:auto}.rich_media_global_msg{position:fixed;top:0;left:0;right:0;padding:1em 35px 1em 15px;z-index:2;background-color:#c6e0f8;color:#8c8c8c;font-size:13px}.rich_media_global_msg .icon_closed{position:absolute;right:15px;top:50%;margin-top:-5px;line-height:300px;overflow:hidden;-webkit-tap-highlight-color:rgba(0,0,0,0);background:transparent url(//res.wx.qq.com/mmbizwap/zh_CN/htmledition/images/icon/appmsg/icon_appmsg_msg_closed_sprite.2x2eb52b.png) no-repeat 0 0;width:11px;height:11px;vertical-align:middle;display:inline-block;-webkit-background-size:100% auto;background-size:100% auto}.rich_media_global_msg .icon_closed:active{background-position:0 -17px}.preview_appmsg .rich_media_title{margin-top:1.9em}@media screen and (min-width:1024px){.rich_media_global_msg{position:relative;margin:0 20px}.preview_appmsg .rich_media_title{margin-top:0}}.pages_reset{color:#3e3e3e;line-height:1.6;font-size:16px;font-weight:400;font-style:normal;text-indent:0;letter-spacing:normal;text-align:left;text-decoration:none;white-space:normal}.weapp_element,.weapp_display_element,.mp-miniprogram{display:block;margin:1em 0}.share_audio_context{margin:16px 0}.weapp_text_link{font-size:17px}.weapp_text_link:before{content:'';display:inline-block;line-height:1;background-size:12px 12px;background-repeat:no-repeat;background-image:url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABwAAAAcCAMAAABF0y+mAAAAb1BMVEUAAAB4it11h9x2h9x2h9x2htx8j+R8i+B1h9x2h9x3h92Snv91htt2h9x1h9x4h9x1h9x1h9x2idx1h9t2h9t1htt1h9x1h9x1htx2h9x1h912h9x4h913iN17juOOjuN1iNx2h9t4h958i+B1htvejBiPAAAAJHRSTlMALPLcxKcVEOXXUgXtspU498sx69DPu5+Yc2JeRDwbCYuIRiGBtoolAAAA3ElEQVQoz62S1xKDIBBFWYiFYImm2DWF///G7DJEROOb58U79zi4O8iOo8zuCRfV8EdFgbYE49qFQs8ksJInajOA1wWfYvLcGSueU/oUGBtPpti09uNS68KTMcrQ5jce4kmN/HKn9XVPAo702JEdx9hTUrWUqVrI3KwUmM1NhIWMKdwiGvpGMWZOAj1PZuzAxHwhVSplrajoseBnbyDHAwvrtvKKhdqTtFBkL8wO5ijcsS3G1JMNvQ5mdW7fc0x0+ZcnlJlZiflAomdEyFaM7qeK2JahEjy5ZyU7jC/q/Rz/DgqEuAAAAABJRU5ErkJggg==');vertical-align:middle;font-size:11px;color:#888;border-radius:10px;background-color:#f4f4f4;margin-right:6px;margin-top:-4px;background-position:center;height:20px;width:20px}.weui-mask{position:fixed;z-index:1000;top:0;right:0;left:0;bottom:0;background:rgba(0,0,0,0.6)}.weui-dialog{position:fixed;z-index:5000;width:80%;max-width:300px;top:50%;left:50%;-webkit-transform:translate(-50%,-50%);transform:translate(-50%,-50%);background-color:#fff;text-align:center;border-radius:3px;overflow:hidden}.weui-dialog__hd{padding:1.3em 1.6em .5em}.weui-dialog__title{font-weight:400;font-size:18px}.weui-dialog__bd{padding:0 1.6em .8em;min-height:40px;font-size:15px;line-height:1.3;word-wrap:break-word;word-break:break-all;color:#999}.weui-dialog__bd:first-child{padding:2.7em 20px 1.7em;color:#353535}.weui-dialog__ft{position:relative;line-height:48px;font-size:18px;display:-webkit-box;display:-webkit-flex;display:flex}.weui-dialog__ft:after{content:" ";position:absolute;left:0;top:0;right:0;height:1px;border-top:1px solid #d5d5d6;color:#d5d5d6;-webkit-transform-origin:0 0;transform-origin:0 0;-webkit-transform:scaleY(0.5);transform:scaleY(0.5)}.weui-dialog__btn{display:block;-webkit-box-flex:1;-webkit-flex:1;flex:1;color:#3cc51f;text-decoration:none;-webkit-tap-highlight-color:rgba(0,0,0,0);position:relative}.weui-dialog__btn:active{background-color:#eee}.weui-dialog__btn:after{content:" ";position:absolute;left:0;top:0;width:1px;bottom:0;border-left:1px solid #d5d5d6;color:#d5d5d6;-webkit-transform-origin:0 0;transform-origin:0 0;-webkit-transform:scaleX(0.5);transform:scaleX(0.5)}.weui-dialog__btn:first-child:after{display:none}.weui-dialog__btn_default{color:#353535}.weui-dialog__btn_primary{color:#0bb20c}</style>
    <style>
    </style>
    <!--[if lt IE 9]>
    <link onerror="wx_loaderror(this)" rel="stylesheet" type="text/css" href="//res.wx.qq.com/mmbizwap/zh_CN/htmledition/style/page/appmsg/page_mp_article_improve_pc2c9cd6.css">
    <![endif]-->

</head>
<body id="activity-detail" class="zh_CN mm_appmsg">

<script nonce="1913591860" type="text/javascript">
    var write_sceen_time = (+new Date());
</script>

<div id="js_article" class="rich_media preview_appmsg">

    <div id="js_top_ad_area" class="top_banner"></div>

    <div class="rich_media_inner">
        <div class="rich_media_global_msg">

            <a id="js_close_temp" href="<?=$this->url?>">那些形形色色的流量生意,点击前往课堂</a>        </div>

        <div id="page-content" class="rich_media_area_primary">

            <div id="img-content">

                <h2 class="rich_media_title" id="activity-name">
                    那些形形色色的流量生意                                    </h2>
                <div id="meta_content" class="rich_media_meta_list">

                    <div id="js_profile_qrcode" class="profile_container" style="display:none;">
                        <span class="profile_arrow_wrp" id="js_profile_arrow_wrp">
                            <i class="profile_arrow arrow_out"></i>
                            <i class="profile_arrow arrow_in"></i>
                        </span>
                    </div>
                </div>







                <div class="rich_media_content " id="js_content">






                    <p style="text-align: justify;"><span style="font-size: 16px;"><strong><span style="font-family: 宋体;">个人介绍</span></strong></span></p><hr  /><p style="text-align: justify;"><span style="font-size: 16px;"><strong><span style="font-family: 宋体;"></span></strong></span><br  /></p><p style="text-align: justify;"><span style="font-size: 16px;"><span style="font-family: 宋体;font-size: 14px;"><span style="font-size: 16px;font-family: 宋体;">大家好，我是夜息，前途牛旅游网</span>SEO<span style="font-size: 16px;font-family: 宋体;">负责人，受亦仁邀请，来跟大家分享一下自己的依靠</span><span style="font-size: 16px;font-family: Calibri;">SEO</span><span style="font-size: 16px;font-family: 宋体;">在互联网上的赚钱故事，本人大学专业金属材料，可以说和计算机毫无关系，所以大家完全不用担心自己是否专业对口</span></span><span style="font-family: Calibri;font-size: 14px;">&nbsp;</span><span style="font-size: 16px;font-family: 宋体;">。</span></span></p><p style="text-align: justify;"><span style="font-family: Calibri;font-size: 16px;">&nbsp;</span></p><p style="text-align: justify;"><span style="font-size: 16px;"><strong><span style="font-size: 16px;font-family: 宋体;">个人小站长阶段</span></strong></span></p><hr  /><p style="text-align: justify;"><br  /></p><p style="text-align: justify;"><span style="font-size: 16px;"><span style="font-family: 宋体;font-size: 14px;">09<span style="font-size: 16px;font-family: 宋体;">年毕业的时候，误打误撞进入了</span><span style="font-size: 16px;font-family: Calibri;">SEO</span><span style="font-size: 16px;font-family: 宋体;">行业，到</span><span style="font-size: 16px;font-family: Calibri;">11</span><span style="font-size: 16px;font-family: 宋体;">年年底这个时间段，一直从事中小型站点的</span><span style="font-size: 16px;font-family: Calibri;">SEO</span><span style="font-size: 16px;font-family: 宋体;">工作，</span></span><span style="font-family: Calibri;font-size: 14px;">&nbsp;</span><span style="font-family: 宋体;font-size: 14px;"><span style="font-size: 16px;font-family: 宋体;">在掌握了一定的</span>SEO<span style="font-size: 16px;font-family: 宋体;">技巧之后，我开始考虑怎么利用</span><span style="font-size: 16px;font-family: Calibri;">SEO</span><span style="font-size: 16px;font-family: 宋体;">赚一些外快了。说实话非常简单，就是接</span><span style="font-size: 16px;font-family: Calibri;">SEO</span><span style="font-size: 16px;font-family: 宋体;">单子，帮别人做</span><span style="font-size: 16px;font-family: Calibri;">SEO</span><span style="font-size: 16px;font-family: 宋体;">。毕竟这个模式非常简单，就是出卖自己的劳动力和时间。同时可以提升自己的业务技能，这边我特别反对赚不是自己行业的外快，因为这样做除了赚一些小钱以外，毫无积累，比如去做代购，去开顺风车之类，除非你打算以后做销售或者出租车司机。</span></span></span></p><p style="text-align: justify;"><span style="font-family: Calibri;font-size: 16px;">&nbsp;</span></p><p style="text-align: justify;"><span style="font-family: 宋体;font-size: 16px;text-indent: 28px;">在开始接</span><span style="font-family: 宋体;font-size: 16px;text-indent: 28px;">SEO</span><span style="font-family: 宋体;font-size: 16px;text-indent: 28px;">单子之初，我就设立了几个接单的标准，第一，一定要预付款，。第二，我不会承诺和全额报酬挂钩的目标。至于第一点原因大家都懂，中国人的诚信问题（这边补充一下自己要账的经历）。第二点，一个</span><span style="font-size: 16px;text-indent: 28px;font-family: Calibri;">SEO</span><span style="font-family: 宋体;font-size: 16px;text-indent: 28px;">方案执行的好坏，和技术，产品挂钩，所以我不希望出现我辛辛苦苦做了很多方案，没人做，最后我拿不到一分钱的情况。</span></p><p style="text-align: justify;"><span style="text-indent: 28px;font-family: 宋体;font-size: 16px;"><br  /></span></p><p style="text-align: justify;"><span style="text-indent: 28px;font-family: 宋体;font-size: 16px;">有人可能会问，为什么会有人找你做顾问呢，你又不是什么明星玩家。这边我的技巧很简单，一是有自己思考的观点，二是乐于分享，最终把自己打造成明星玩家，当然这是建立在自身实力的情况下，并不是靠自吹自擂。</span></p><p style="text-align: justify;"><span style="text-indent: 28px;font-family: 宋体;font-size: 16px;"><br  /></span></p><p style="text-align: justify;"><span style="text-indent: 28px;font-family: 宋体;font-size: 16px;">其实从</span><span style="text-indent: 28px;font-family: 宋体;font-size: 16px;">N</span><span style="text-indent: 28px;font-family: 宋体;font-size: 16px;">年前到现在，</span><span style="text-indent: 28px;font-size: 16px;font-family: Calibri;">SEO</span><span style="text-indent: 28px;font-family: 宋体;font-size: 16px;">的文章都是满天飞，但是阅读起来大同小异，可能是干</span><span style="text-indent: 28px;font-size: 16px;font-family: Calibri;">SEO</span><span style="text-indent: 28px;font-family: 宋体;font-size: 16px;">的人太会伪原创了吧，真正优质的内容非常少，于是我逼着自己每周写</span><span style="text-indent: 28px;font-size: 16px;font-family: Calibri;">1~2</span><span style="text-indent: 28px;font-family: 宋体;font-size: 16px;">篇自己工作的心得和关于</span><span style="text-indent: 28px;font-size: 16px;font-family: Calibri;">SEO</span><span style="text-indent: 28px;font-family: 宋体;font-size: 16px;">的思考，比如上面我提到的那些外链工具，我是怎么用的，怎么做效果好。由于我内容的专业性，吸引了不少志同道合的朋友。虽然现在回头看那些文章狗屁不是，但当时在网上还是非常稀缺的东西。</span></p><p style="text-align: justify;"><span style="text-indent: 28px;font-family: 宋体;font-size: 16px;"><br  /></span></p><p style="text-align: justify;"><span style="text-indent: 28px;font-family: 宋体;font-size: 16px;">自己写的文章，除了放在自己博客上以外，也会去</span><span style="text-indent: 28px;font-size: 16px;font-family: Calibri;">seowhy,chinaz,a5</span><span style="text-indent: 28px;font-family: 宋体;font-size: 16px;">上进行投稿，由于内容质量高，最后</span><span style="text-indent: 28px;font-size: 16px;font-family: Calibri;">a5,chinaz</span><span style="text-indent: 28px;font-family: 宋体;font-size: 16px;">的小编都常驻我博客，我一更新，他们就转走了。后面有了知乎，我也挑着上面所有</span><span style="text-indent: 28px;font-size: 16px;font-family: Calibri;">SEO</span><span style="text-indent: 28px;font-family: 宋体;font-size: 16px;">的问题进行了回答。也为自己吸引了很多关注。此外，在</span><span style="text-indent: 28px;font-size: 16px;font-family: Calibri;">QQ</span><span style="text-indent: 28px;font-family: 宋体;font-size: 16px;">上，会有很多人向我咨询</span><span style="text-indent: 28px;font-size: 16px;font-family: Calibri;">SEO</span><span style="text-indent: 28px;font-family: 宋体;font-size: 16px;">问题，我几乎都会一一解答，就这样为了介绍了很多单子，但是由于我接单的标准，并不是所有人的单子都会为我接，但是在我这边不近人情的标准下，还愿意找我做顾问的网站，合作期都比较长，起码都有半年以上，最长了做了</span><span style="text-indent: 28px;font-size: 16px;font-family: Calibri;">3</span><span style="text-indent: 28px;font-family: 宋体;font-size: 16px;">年。我周围也有</span><span style="text-indent: 28px;font-size: 16px;font-family: Calibri;">SEO</span><span style="text-indent: 28px;font-family: 宋体;font-size: 16px;">水平不错，也接单干的小伙伴，但是经常会因为要尾款，忙得焦头烂额。</span></p><p style="text-align: justify;"><span style="text-indent: 28px;font-family: 宋体;font-size: 16px;"><br  /></span></p><p style="text-align: justify;"><span style="text-indent: 28px;font-family: 宋体;font-size: 16px;">关于怎么学习</span><span style="text-indent: 28px;font-family: 宋体;font-size: 16px;">SEO</span><span style="text-indent: 28px;font-family: 宋体;font-size: 16px;">，我建议大家直接阅读搜索引擎的官方文档，如果再深入一些，可以去阅读关于搜索引擎原理，以及自然语言处理方面的书籍和文献。外面那些</span><span style="text-indent: 28px;font-size: 16px;font-family: Calibri;">SEO</span><span style="text-indent: 28px;font-family: 宋体;font-size: 16px;">的奇淫巧技，可以完全不用理会。在</span><span style="text-indent: 28px;font-size: 16px;font-family: Calibri;">SEO</span><span style="text-indent: 28px;font-family: 宋体;font-size: 16px;">刚开始做中小型站点的时候，我这边有</span><span style="text-indent: 28px;font-size: 16px;font-family: Calibri;">2</span><span style="text-indent: 28px;font-family: 宋体;font-size: 16px;">点建议大家可以参考。</span></p><p style="text-align: justify;"><span style="font-family: 宋体;font-size: 16px;text-indent: 28px;"><br  /></span></p><p style="text-align: justify;"><span style="font-family: 宋体;font-size: 16px;text-indent: 28px;">首先外链（可以解释一下外链的意义）的作用，并非是网上鼓吹的那么大，所谓的给我</span><span style="font-family: 宋体;font-size: 16px;text-indent: 28px;">1000</span><span style="font-family: 宋体;font-size: 16px;text-indent: 28px;">外链，我定可上首页也只是一个笑谈。如果这边有做外贸的同学，应该听说过</span><span style="font-size: 16px;text-indent: 28px;font-family: Calibri;">Xrumer,Scrapebox,ZennoPoster</span><span style="font-family: 宋体;font-size: 16px;text-indent: 28px;">这样的外链工具，这些工具当年我们有一个小圈子，可以说是全中国最懂这些软件的人，每天的外链发个几百万上千万就不成问题，一个新站点可以在一周之内，在</span><span style="font-size: 16px;text-indent: 28px;font-family: Calibri;">Yahoo</span><span style="font-family: 宋体;font-size: 16px;text-indent: 28px;">反链上查询就能上百万条，但实际效果并没有这么好。所以大家千万不要觉得</span><span style="font-size: 16px;text-indent: 28px;font-family: Calibri;">SEO</span><span style="font-family: 宋体;font-size: 16px;text-indent: 28px;">外链就是一切。这点不仅是我这样的能日发外链一千万的过来人的经验，也是经过搜索引擎工程师确认的观点。</span></p><p style="text-align: justify;"><span style="font-family: 宋体;font-size: 16px;text-indent: 28px;"><br  /></span></p><p style="text-align: justify;"><span style="font-family: 宋体;font-size: 16px;text-indent: 28px;">如果现在做一个站点，针对</span><span style="font-size: 16px;text-indent: 28px;font-family: Calibri;">baidu</span><span style="font-family: 宋体;font-size: 16px;text-indent: 28px;">做</span><span style="font-size: 16px;text-indent: 28px;font-family: Calibri;">SEO</span><span style="font-family: 宋体;font-size: 16px;text-indent: 28px;">，外链我觉得只需要做一些相关站点的友情链接就可以了，加快爬虫的抓取频率，其他顺其自然就行，举个例子，我们有一个资讯站点的新闻每天阅读量很高，结果我们的资讯会被网友转载到各个论坛里去灌水啥的，自然而然产生了大量外链，所以外链是会随着你站点的知名度，内容质量而自然增加的。</span><span style="font-size: 16px;text-indent: 28px;font-family: Calibri;">Google</span><span style="font-family: 宋体;font-size: 16px;text-indent: 28px;">很久不做了，所以也不知道什么情况。</span></p><p style="text-align: justify;"><span style="font-family: 宋体;font-size: 16px;text-indent: 28px;"><br  /></span></p><p style="text-align: justify;"><span style="font-family: 宋体;font-size: 16px;text-indent: 28px;">第二就是关于内容，我之前做过一个</span><span style="font-family: 宋体;font-size: 16px;text-indent: 28px;">SEO</span><span style="font-family: 宋体;font-size: 16px;text-indent: 28px;">培训，叫</span><span style="font-size: 16px;text-indent: 28px;font-family: Calibri;">ITSEO</span><span style="font-family: 宋体;font-size: 16px;text-indent: 28px;">，有很多学员会来问我，夜老师，我们网站就几百个页面怎么做</span><span style="font-size: 16px;text-indent: 28px;font-family: Calibri;">SEO</span><span style="font-family: 宋体;font-size: 16px;text-indent: 28px;">，老板也不让新做内容。我的建议是你可以换工作。内容数量是一个站点在搜索引擎中表现的重点因素之一，做</span><span style="font-size: 16px;text-indent: 28px;font-family: Calibri;">SEO</span><span style="font-family: 宋体;font-size: 16px;text-indent: 28px;">的时候，千万不要盯着那么几个词，甚至几百个词也是太少，我个人建议，如果一个站点内容少于</span><span style="font-size: 16px;text-indent: 28px;font-family: Calibri;">5</span><span style="font-family: 宋体;font-size: 16px;text-indent: 28px;">万条，那都没什么做</span><span style="font-size: 16px;text-indent: 28px;font-family: Calibri;">SEO</span><span style="font-family: 宋体;font-size: 16px;text-indent: 28px;">的意义。也许有人听说过单页</span><span style="font-size: 16px;text-indent: 28px;font-family: Calibri;">SEO</span><span style="font-family: 宋体;font-size: 16px;text-indent: 28px;">这个词，但是现在已经几乎不可能做一个新站点只有一个单页就能做起来的了。所以一个站点在开展</span><span style="font-size: 16px;text-indent: 28px;font-family: Calibri;">SEO</span><span style="font-family: 宋体;font-size: 16px;text-indent: 28px;">之前，先考虑一下自己站点的内容数量。</span></p><p style="text-align: justify;"><span style="font-family: 宋体;font-size: 16px;text-indent: 28px;"><br  /></span></p><p style="text-align: justify;"><span style="font-family: 宋体;font-size: 16px;text-indent: 28px;">上面说的两点，对于一个中小站点来说都是非常基础和重要的环节，其实其他</span><span style="font-family: 宋体;font-size: 16px;text-indent: 28px;">SEO</span><span style="font-family: 宋体;font-size: 16px;text-indent: 28px;">的什么抓取呀，速度呀，各类标签，都几乎没什么影响。或者说只要顺带做一下就行了。</span></p><p style="text-align: justify;"><span style="text-indent: 28px;font-family: 宋体;font-size: 16px;"><br  /></span></p><p style="text-align: justify;"><span style="text-indent: 28px;font-family: 宋体;font-size: 16px;">总结一下这个阶段，当你在自身行业内，能达到一定高度，并且会稍微宣传一下自身，做个兼职赚点外快是非常非常容易的。所以磨练自身的技能，哪怕公司亏待你，你可以从别的地方找钱。我几乎没见过谁是行业顶尖的情况下还无钱可赚的情况。一个</span><span style="text-indent: 28px;font-family: 宋体;font-size: 16px;">SEO</span><span style="text-indent: 28px;font-family: 宋体;font-size: 16px;">的单子能为我每个月提供</span><span style="text-indent: 28px;font-size: 16px;font-family: Calibri;">1</span><span style="text-indent: 28px;font-family: 宋体;font-size: 16px;">万左右的报酬，我一般同时会进行</span><span style="text-indent: 28px;font-size: 16px;font-family: Calibri;">2~3</span><span style="text-indent: 28px;font-family: 宋体;font-size: 16px;">个单子，每个月有稳定的</span><span style="text-indent: 28px;font-size: 16px;font-family: Calibri;">2w+</span><span style="text-indent: 28px;font-family: 宋体;font-size: 16px;">的外快，当时我上班工资仅仅</span><span style="text-indent: 28px;font-size: 16px;font-family: Calibri;">5000</span><span style="text-indent: 28px;font-family: 宋体;font-size: 16px;">，这对于一个非名校毕业，刚工作</span><span style="text-indent: 28px;font-size: 16px;font-family: Calibri;">1~2</span><span style="text-indent: 28px;font-family: 宋体;font-size: 16px;">年的小伙伴来说，也算一个不错的成绩了。</span></p><p style="text-indent: 28px;text-align: justify;"><span style="font-family: Calibri;font-size: 16px;">&nbsp;</span></p><p style="text-align: justify;"><span style="font-size: 16px;"><strong><span style="font-size: 16px;font-family: 宋体;">互联网流量</span></strong></span></p><hr  /><p style="text-align: justify;"><br  /></p><p style="text-align: justify;"><span style="font-family: 宋体;font-size: 16px;">12年年初的时间进入了途牛旅游网，似乎从草根<span style="font-size: 16px;font-family: Calibri;">SEO</span>，变成了中大型网站的<span style="font-size: 16px;font-family: Calibri;">SEO</span>。在这个阶段的时候，其实对<span style="font-size: 16px;font-family: Calibri;">SEO</span>会有了一个新的认识，不过咱们不是<span style="font-size: 16px;font-family: Calibri;">SEO</span>培训课，这边就不细谈了。我想说的是，如果你有机会进入一个大公司，一定要好好利用大公司的资源。这边倒不是说要大家干一些贪赃枉法的事情。</span></p><p style="text-align: justify;"><span style="font-family: 宋体;font-size: 16px;"><br  /></span></p><p style="text-align: justify;"><span style="font-family: 宋体;font-size: 16px;">举个例子，如果你是一个草根小站长，你去找搜索引擎的工作人员聊天，人家会鸟你吗？会重视你吗？如果你说你是<span style="font-size: 16px;font-family: Calibri;">XX</span>网的，那人家自然会令眼相看，利用大公司的品牌，多结交各种行业人才，这是非常非常重要的。很多其他公司的小伙伴，能给你带来很多赚钱的思路和资源。</span></p><p style="text-align: justify;"><span style="font-family: 宋体;font-size: 16px;"><br  /></span></p><p style="text-align: justify;"><span style="font-family: 宋体;font-size: 16px;">第二个就是利用大公司的资源，去实现一些小公司无法实现的操作，也许你在小公司就光杆司令一个，没有任何预算，甚至想自己买台服务器都没有。但是在大公司却有很多可能，能让你去尝试很多新的东西。</span></p><p style="text-align: justify;"><span style="font-family: 宋体;font-size: 16px;"><br  /></span></p><p style="text-align: justify;"><span style="font-family: 宋体;font-size: 16px;">第三个就是跨界，在小公司可能你一个人承担了SEO，营销，技术，产品的工作，在大公司各个方向都有更牛的大神，多和他们搞基学习，了解互联网的方方面面。</span></p><p style="text-align: justify;"><span style="font-family: Calibri;font-size: 16px;">&nbsp;</span></p><p style="text-align: justify;"><span style="font-family: 宋体;font-size: 16px;">回到主题上，这些东西怎么帮你赚钱呢？这边我举一些例子，曾经我们和某网站达成一个换量的协议，比如A网站每日导流<span style="font-size: 16px;font-family: Calibri;">100000</span>给<span style="font-size: 16px;font-family: Calibri;">B</span>网站，<span style="font-size: 16px;font-family: Calibri;">B</span>网站想办法从各种渠道导流<span style="font-size: 16px;font-family: Calibri;">200000</span>给<span style="font-size: 16px;font-family: Calibri;">A</span>网站，通过自身的合作摸索，我们知道了这个<span style="font-size: 16px;font-family: Calibri;">A</span>网站的流量效果如何，能从什么渠道导流给<span style="font-size: 16px;font-family: Calibri;">A</span>网站。其实<span style="font-size: 16px;font-family: Calibri;">A</span>网站也可以导流给<span style="font-size: 16px;font-family: Calibri;">C</span>网站，也能给<span style="font-size: 16px;font-family: Calibri;">D</span>网站，那我只要和<span style="font-size: 16px;font-family: Calibri;">C</span>网站，<span style="font-size: 16px;font-family: Calibri;">D</span>网站的哥们说一下，你们要不要<span style="font-size: 16px;font-family: Calibri;">A</span>网站的流量，我这边帮你找渠道给<span style="font-size: 16px;font-family: Calibri;">A</span>导流，让我赚一些差价就行。这时候<span style="font-size: 16px;font-family: Calibri;">A</span>网站获得了想要的流量，<span style="font-size: 16px;font-family: Calibri;">C</span>，<span style="font-size: 16px;font-family: Calibri;">D</span>网站也获得了对应的流量，而我赚了一些差价，而我只要做到<span style="font-size: 16px;font-family: Calibri;">C,D</span>网站和我自身公司不是一个行业，完全无竞争就可以了。其实这个例子就是一个信息不对称的钱，核心点就是外人不知道<span style="font-size: 16px;font-family: Calibri;">A</span>网站可以导流出来，以及不知道去哪儿买<span style="font-size: 16px;font-family: Calibri;">A</span>网站的流量，如果你业内同行基友多，你和营销部门关系好了解市场行业，那这样低买高卖的事儿太多太多了。并且几乎都是几个电话的事情，赚钱也非常轻松。</span></p><p style="text-align: justify;"><span style="font-family: Calibri;font-size: 16px;">&nbsp;</span></p><p style="text-align: justify;"><span style="font-family: 宋体;font-size: 16px;">再举个例子，以前我干过一个操作，大型网站批量交换内页链接，当时我认识很多同行，大家都是干SEO的，以前大家换友情链接，都是首页之间互换，最多频道页互换，于是我提出了一个点，我们为什么不能内页都互换呢，比如我是做旅游行业的，我有很多目的地页面，比如北京旅游，泰国旅游等，同行例如携程呀，蚂蜂窝，艺龙网，他们也有很多这样的页面，我们为什么不能这样一一对应的去互换，再细分一下，各种景点也是可以的，这样的链接相关性非常棒，于是我们自己开发一套自动化系统，同时呼吁基友们也一起开发这样的系统，最后我自己做个小网站，也顺带着和大伙一起互换了很多链接，因为传统<span style="font-size: 16px;font-family: Calibri;">SEO</span>观念，大家认为友情链接都是首页的，要看<span style="font-size: 16px;font-family: Calibri;">PR</span>，收录啥的，但是这样内页大批量互换，就没这种要求了，于是顺带着把自己的小站点都带了起来。</span></p><p style="text-align: justify;"><span style="font-family: Calibri;font-size: 16px;">&nbsp;</span></p><p style="text-align: justify;"><span style="font-family: 宋体;font-size: 16px;">其实到了这个阶段，很多赚钱的事情都是把多方资源利用起来的事情，前提是你对整个流量广告市场有比较清晰的认识，知道各家合作方需要什么点，有什么是可以纳为己用的，有什么是可以帮到合作伙伴的。这时候收入其实不算稳定，但是多的时候可能1个月就能赚<span style="font-size: 16px;font-family: Calibri;">30</span>万（突然有大规模流量采购的需求）。少的时候一个月<span style="font-size: 16px;font-family: Calibri;">7~8</span>万（日常还量），很多小伙伴在加入生财有术圈子的时候都会介绍自己的资源，其实如果对行业认识清晰，都不需要做什么项目，把大家资源对接一下就完事，躺着数钱就行。</span></p><p style="text-align: justify;"><span style="font-family: Calibri;font-size: 16px;">&nbsp;</span></p><p style="text-align: justify;"><span style="font-size: 16px;"><strong><span style="font-size: 16px;font-family: 宋体;">个人项目</span></strong></span></p><hr  /><p style="text-align: justify;"><br  /></p><p style="text-align: justify;"><span style="font-family: 宋体;font-size: 16px;">在加入途牛之后，我就几乎逐步把自己的兼职砍掉了，一是太累，二是对自己没提升，就像一个大学生天天做加减乘除一样，做再多也没有意义。后面做了一阵赚信息差的钱之后，其实又陷入了一个瓶颈，就是每个项目的信息差总有一天是会被抹平的，这事情并不是能无限长久，打工的工资又不会让自己过上想过的生活，最终还是考虑要做一些自己的东西。我目前在做的项目主要是</span><a href="http://www.jiankang.com" style="font-family: 宋体;color: rgb(0, 0, 255);text-decoration: underline;font-size: 16px;"><span style="font-family: 宋体;color: rgb(0, 0, 255);text-decoration: underline;font-size: 16px;">www.jiankang.com</span></a><span style="font-family: 宋体;font-size: 16px;">，其实一开始想法非常简单，总是给别人做SEO，为什么不给自己做<span style="font-size: 16px;font-family: Calibri;">SEO</span>。其实这些已经超出外快的范畴了。这里分享一些在创业过程中遇到的一些外快机会。</span></p><p style="text-align: justify;"><span style="font-family: Calibri;font-size: 16px;">&nbsp;</span></p><p style="text-align: justify;"><span style="font-family: 宋体;font-size: 16px;">其实这些机会大都也是投机取巧的办法。但是是建立在一定技术，常识的前提下。比如刷量。马上双11了，各大电商又开始了疯狂买流量的环节，要给淘宝什么的刷量那是比较难的，人家反作弊很严格了，但是很多其他电商没有这样的水平，并且双<span style="font-size: 16px;font-family: Calibri;">11</span>流量大家疯抢，几乎都是不管投入产出比和转化的情况烧钱。这时候刷一笔赚一笔，甚至用网上的类似流量精灵之类的互点工具都可以刷。但是你得知道流量的时间，渠道，地域分布，点击效果，才能刷出最真实的量。同理的<span style="font-size: 16px;font-family: Calibri;">ASO</span>，其实<span style="font-size: 16px;font-family: Calibri;">ASO</span>的工作<span style="font-size: 16px;font-family: Calibri;">90%</span>在刷量上，这都成了多少个刷榜公司了，大家如果手上有资源找不到的都可以找我也行。</span></p><p style="text-align: justify;"><span style="font-family: Calibri;font-size: 16px;">&nbsp;</span></p><p style="text-align: justify;"><span style="font-family: 宋体;font-size: 16px;">再说个很恐怖的东西，大家应该都知道电信的宽带在某些时候经常会给网页中插一下小广告。其实这种玩法都算很正规的了，为什么各大公司要呼吁搞https，就是被劫持怕了呗。今日头条在做<span style="font-size: 16px;font-family: Calibri;">https</span>之前，整个广告位都被劫持，比如本来显示的是一个游戏广告，点一下今日头条收费<span style="font-size: 16px;font-family: Calibri;">8</span>毛，结果被运营商劫持成一个卖壮阳药的，有人能拿到这个资源的话，可以直接对外宣称自己能在今日头条上投放，一个点击<span style="font-size: 16px;font-family: Calibri;">5</span>毛，你说这竞争力大不大？同样的事情可以用来做百度联盟，阿里妈妈。曾经阿里妈妈上每周佣金<span style="font-size: 16px;font-family: Calibri;">top10</span>里面有一大半是各地的运营商。这些都是非常依赖自身的资源和对行业的认识的。</span></p><p style="text-align: justify;"><span style="font-family: 宋体;font-size: 16px;"><br  /></span></p><p style="text-align: justify;"><span style="font-family: 宋体;font-size: 16px;">这边再谈一个网赚广告的思路，咱们平时可以看到很多网站会挂很多看起来非常low的广告，比如同城约会，美女视频，算命占卜，小说阅读，到付手表太阳镜等非常规广告，为什么这些广告一直会在投放，因为他们能赚钱，都是暴利产品！这边有一个赚钱的办法，就是去各个中型网站看，他们挂什么广告，然后就接这种广告的代理，然后去联系其他网站，只要找到一个赚钱的广告，几乎也是躺着数钱。这边不需要你去研究产品模式，优化转化路径，搞定支付通道，说实话这些都是非常麻烦的事情，不符合咱们赚外快的路子，做个代理就行。</span></p><p style="text-align: justify;"><span style="font-family: 宋体;font-size: 16px;"><br  /></span></p><p style="text-align: justify;"><span style="font-family: 宋体;font-size: 16px;">就我了解杭州一家公司，也是代理人家广告产品，半年流水就<span style="font-size: 16px;font-family: Calibri;">2</span>个多亿，转手洗白把公司卖掉，瞬间财务自由了。我自己也做过一阵子，每天<span style="font-size: 16px;font-family: Calibri;">1</span>万收入都很稳定。为什么要去中型网站看，因为小网站没流量，测试不出东西，大网站能接品牌广告，无所谓这些变现。以前专心做<span style="font-size: 16px;font-family: Calibri;">SEO</span>，完全没这方面的意识，现在自己有了项目的营收压力，才发现了新的大陆。虽然也是被人玩烂的东西了，但是起码赚些奶粉钱一点问题都没有。</span></p><p style="text-align: justify;"><span style="font-family: Calibri;font-size: 16px;">&nbsp;</span></p><p style="text-align: justify;"><span style="font-family: 宋体;font-size: 16px;">最后再总结一下，我的赚外快的经历，从出卖自身劳动力，到赚信息差，最后到自己发掘项目。这些我觉得要归功于我的出身，很小的时候接触电脑，以及天赋，对计算机有特殊的热爱，还有运气，碰巧做了SEO，遇到一大堆靠谱的基友，最后还有一点点自己的努力，说实话我这个人比较懒，所以一直也没发大财。有人说你搞培训应该很赚钱，我也很惭愧，虽然招收了几百个学生，说实话钱都给学员发福利开发工具办活动去了，有的学员真的比较困难学费也经常减免打，我们和百度官方都有合作，我都没有利用好这个品牌，自己懒得宣传上课。最后盘算一下居然都没赚几个钱，也许这也是我的弱点，心太软做不了大事。对别人心软，对自己更是心软，执行力巨差。希望今天的分享能给小伙伴们一些启发，相信各位一定能在赚外快这条路上走得比我更好，更远，谢谢大家。</span></p><p><br  /></p>
                </div>
                <script nonce="1913591860" type="text/javascript">
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


<script nonce="1913591860">
    var __DEBUGINFO = {
        debug_js : "//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_wap/debug/console34c264.js",
        safe_js : "//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_wap/safe/moonsafe34c264.js",
        res_list: []
    };
</script>

<script nonce="1913591860" type="text/javascript">
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
            node.setAttribute('nonce', '1913591860');
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

<script nonce="1913591860" type="text/javascript">

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
                        images[i].src = "data:image/gif;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVQImWNgYGBgAAAABQABh6FO1AAAAABJRU5ErkJggg==";
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
<script nonce="1913591860" type="text/javascript">

    var not_in_mm_css = "//res.wx.qq.com/mmbizwap/zh_CN/htmledition/style/page/appmsg/not_in_mm36906d.css";
    var windowwx_css = "//res.wx.qq.com/mmbizwap/zh_CN/htmledition/style/page/appmsg/page_mp_article_improve_winwx31619e.css";
    var article_improve_combo_css = "//res.wx.qq.com/mmbizwap/zh_CN/htmledition/style/page/appmsg/page_mp_article_improve_combo39aac6.css";
    var tid = "";
    var aid = "";
    var clientversion = "12020110";
    var appuin = "MzUyMDAzNDYxNw=="||"";

    var source = "0";
    var ascene = "0";
    var subscene = "";
    var abtest_cookie = "";

    var scene = 75;

    var itemidx = "";
    var appmsg_token   = "933_ek4AkKzAfdUzAofvP9jLMEyYICBtuj9iQdHKvviQPKyEKyl28A4O8utY7rw~";

    var _copyright_stat = "0";
    var _ori_article_type = "";

    var nickname = "易灵微课";
    var appmsg_type = "6";
    var ct = "1512124818";
    var publish_time = "2017-12-01" || "";
    var user_name = "gh_f5acdd877bc5";
    var user_name_new = "";
    var fakeid   = "";
    var version   = "";
    var is_limit_user   = "0";
    var round_head_img = "http://mmbiz.qpic.cn/mmbiz_png/zUIywPicNicRIia2QGiaciaia2ChiaRV1ibaTb43PGypK7ekXdAsp5skpD9NsTLxdeoADCLnhjLya2zhibWIAtqBrB12J6A/0?wx_fmt=png";
    var ori_head_img_url = "http://wx.qlogo.cn/mmhead/Q3auHgzwzM5vHzcJVh0RLcWpsKnE0L0Lh7OHa5R804ic0CMhQ5LH1FQ/132";
    var msg_title = "那些形形色色的流量生意";
    var msg_desc = "个人介绍大家好，我是夜息，前途牛旅游网SEO负责人，受亦仁邀请，来跟大家分享一下自己的依靠SEO在互联网上的";
    var msg_cdn_url = "http://mmbiz.qpic.cn/mmbiz_jpg/zUIywPicNicRJia6lk9Ohicaicf4kJm7qOfeyaia32TCwIt1S2IGYZibhBnvmk1txzCn8GgEElI1kEh5VnWuNJTia9FLoA/0?wx_fmt=jpeg";
    var msg_link = "http://mp.weixin.qq.com/s?__biz=MzUyMDAzNDYxNw==\x26amp;tempkey=OTMzX1Q0eldCVk9UNmRibk9xWDltUlh1MEZiX0FpRlF6YVpoYWpJOWNvcU55QTZvVkUzZS14OW0zZThIcFJ3MFB3TWZLQkk3bkVNc0NsM2lsUU9tOEhQRndFVXN6RWNDY2padXFaNWhoU2pSalNIQWIxNFVDaDZkRlRhUnNqMUVZN1B1QThLQnBmRm9laldnMk9YcTRQdWVYZGJDRXE1TmNHcGgxOS02U1F%2Bfg%3D%3D\x26amp;chksm=79f1c1174e864801e1f438b3feb230351ff1e1a64b6dcf0837ad8fb63e20dd61fba0fa2be947#rd";
    var user_uin = "58420325"*1;
    var msg_source_url = '';
    var img_format = 'jpeg';
    var srcid = '1201Kr5K8JFpCeXvS6ucF8kC';
    var req_id = '0119W1ggjX8ZNp0xKLwIAdTS';
    var networkType;
    var appmsgid = '' || '100000065'|| "100000065";
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
    var read_num = "2" * 1;
    var like_num = "0" * 1;
    var liked = "false" == 'true' ? true : false;
    var is_temp_url = "OTMzX1lCcDRIQ2Izdlo4UjJKeUxtUlh1MEZiX0FpRlF6YVpoYWpJOWNvcU55QTZvVkUzZS14OW0zZThIcFJ3MFB3TWZLQkk3bkVNc0NsM2lsUU9tOEhQRndFVXN6RWNDY2padXFaNWhoU2pSalNIQWIxNFVDaDZkRlRhUnNqMUVZN1B1QThLQnBmRm9laldnMk9YcVhrc1ZocDFuZ19vVnlNSVZ5NWZ3X3d\x26nbsp;fg==" ? 1 : 0;
    var send_time = "1512128233";
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

    var svr_time = "1512129091" * 1;

    var is_transfer_msg = ""*1||0;

    var malicious_title_reason_id = "0" * 1;

    window.wxtoken = "3460008657";





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

<script nonce="1913591860" type="text/javascript">
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

<script nonce="1913591860">window.__moon_host = 'res.wx.qq.com';window.__moon_mainjs = 'appmsg/index.js';window.moon_map = {"new_video/player.html.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/new_video/player.html39e24c.js","biz_wap/zepto/touch.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_wap/zepto/touch34c264.js","biz_wap/zepto/event.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_wap/zepto/event34c264.js","biz_wap/zepto/zepto.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_wap/zepto/zepto34c264.js","page/pages/video.css":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/style/page/pages/video.css3767b8.js","a/appdialog_confirm.html.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/a/appdialog_confirm.html34f0d8.js","widget/wx_profile_dialog_primary.css":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/style/widget/wx_profile_dialog_primary.css34f0d8.js","appmsg/emotion/caret.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/emotion/caret278965.js","new_video/player.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/new_video/player39e24c.js","a/appdialog_confirm.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/a/appdialog_confirm34c32a.js","biz_wap/jsapi/cardticket.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_wap/jsapi/cardticket34c264.js","biz_common/utils/emoji_panel_data.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_common/utils/emoji_panel_data3518c6.js","biz_common/utils/emoji_data.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_common/utils/emoji_data3518c6.js","appmsg/emotion/textarea.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/emotion/textarea353f34.js","appmsg/emotion/nav.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/emotion/nav278965.js","appmsg/emotion/common.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/emotion/common3518c6.js","appmsg/emotion/slide.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/emotion/slide2a9cd9.js","pages/loadscript.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/pages/loadscript39aac6.js","pages/music_report_conf.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/pages/music_report_conf39aac6.js","pages/report.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/pages/report39dc43.js","pages/player_adaptor.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/pages/player_adaptor39d6ee.js","pages/music_player.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/pages/music_player39dc43.js","appmsg/emotion/dom.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/emotion/dom31ff31.js","appmsg/comment_tpl.html.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/comment_tpl.html36c376.js","biz_wap/utils/fakehash.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_wap/utils/fakehash38c7af.js","biz_common/utils/wxgspeedsdk.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_common/utils/wxgspeedsdk3518c6.js","a/sponsor.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/a/sponsor39e101.js","a/app_card.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/a/app_card393ef4.js","a/ios.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/a/ios393966.js","a/android.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/a/android393966.js","a/profile.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/a/profile31ff31.js","a/cpc_a_tpl.html.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/a/cpc_a_tpl.html3802d9.js","a/sponsor_a_tpl.html.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/a/sponsor_a_tpl.html36c7cf.js","a/a_tpl.html.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/a/a_tpl.html393ef4.js","a/mpshop.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/a/mpshop311179.js","a/wxopen_card.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/a/wxopen_card3a2b93.js","a/card.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/a/card311179.js","biz_wap/utils/position.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_wap/utils/position34c264.js","a/a_report.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/a/a_report393966.js","appmsg/my_comment_tpl.html.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/my_comment_tpl.html36906d.js","appmsg/cmt_tpl.html.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/cmt_tpl.html369d00.js","sougou/a_tpl.html.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/sougou/a_tpl.html2c6e7c.js","appmsg/emotion/emotion.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/emotion/emotion353f34.js","biz_wap/utils/wapsdk.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_wap/utils/wapsdk34c264.js","biz_common/utils/monitor.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_common/utils/monitor3518c6.js","biz_common/utils/report.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_common/utils/report3518c6.js","appmsg/open_url_with_webview.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/open_url_with_webview3145f0.js","biz_common/utils/http.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_common/utils/http3518c6.js","biz_common/utils/cookie.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_common/utils/cookie3518c6.js","appmsg/topic_tpl.html.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/topic_tpl.html31ff31.js","pages/weapp_tpl.html.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/pages/weapp_tpl.html36906d.js","pages/voice_tpl.html.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/pages/voice_tpl.html38518d.js","pages/kugoumusic_ctrl.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/pages/kugoumusic_ctrl393e3a.js","pages/qqmusic_ctrl.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/pages/qqmusic_ctrl39b68c.js","pages/voice_component.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/pages/voice_component39dc43.js","pages/qqmusic_tpl.html.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/pages/qqmusic_tpl.html393e3a.js","new_video/ctl.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/new_video/ctl2d441f.js","a/testdata.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/a/testdata393ef4.js","appmsg/reward_entry.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/reward_entry36906d.js","appmsg/comment.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/comment3944ad.js","appmsg/like.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/like375fea.js","pages/version4video.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/pages/version4video384cba.js","a/a.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/a/a3a2b93.js","rt/appmsg/getappmsgext.rt.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/rt/appmsg/getappmsgext.rt2c21f6.js","biz_wap/utils/storage.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_wap/utils/storage34c264.js","biz_common/tmpl.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_common/tmpl3518c6.js","appmsg/share_tpl.html.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/share_tpl.html36906d.js","appmsg/img_copyright_tpl.html.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/img_copyright_tpl.html2a2c13.js","pages/video_ctrl.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/pages/video_ctrl36ebcf.js","biz_common/ui/imgonepx.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_common/ui/imgonepx3518c6.js","biz_common/utils/respTypes.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_common/utils/respTypes3518c6.js","biz_wap/utils/log.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_wap/utils/log34c264.js","sougou/index.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/sougou/index36913b.js","biz_wap/safe/mutation_observer_report.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_wap/safe/mutation_observer_report34c264.js","appmsg/fereport.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/fereport37b642.js","appmsg/report.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/report3404b3.js","appmsg/report_and_source.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/report_and_source393966.js","appmsg/page_pos.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/page_pos393966.js","appmsg/cdn_speed_report.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/cdn_speed_report3097b2.js","appmsg/wxtopic.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/wxtopic31a3be.js","appmsg/new_index.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/new_index36906d.js","appmsg/weapp.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/weapp39d5b2.js","appmsg/autoread.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/autoread3857fc.js","appmsg/voice.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/voice38518d.js","appmsg/qqmusic.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/qqmusic39dc43.js","appmsg/iframe.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/iframe39ab71.js","appmsg/product.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/product393966.js","appmsg/review_image.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/review_image3944ad.js","appmsg/outer_link.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/outer_link275627.js","appmsg/copyright_report.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/copyright_report2ec4b2.js","appmsg/async.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/async38b7bb.js","biz_wap/ui/lazyload_img.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_wap/ui/lazyload_img36be04.js","biz_common/log/jserr.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_common/log/jserr3518c6.js","appmsg/share.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/share39f74d.js","appmsg/cdn_img_lib.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/cdn_img_lib38b7bb.js","biz_common/utils/url/parse.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_common/utils/url/parse36ebcf.js","page/appmsg/not_in_mm.css":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/style/page/appmsg/not_in_mm.css36906d.js","page/appmsg/page_mp_article_improve_combo.css":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/style/page/appmsg/page_mp_article_improve_combo.css39aac6.js","page/appmsg_new/not_in_mm.css":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/style/page/appmsg_new/not_in_mm.css36f05c.js","page/appmsg_new/combo.css":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/style/page/appmsg_new/combo.css39aac6.js","biz_wap/jsapi/core.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_wap/jsapi/core34c264.js","biz_common/dom/event.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_common/dom/event3a25e9.js","appmsg/test.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/test354009.js","biz_wap/utils/mmversion.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_wap/utils/mmversion34c264.js","appmsg/max_age.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/max_age2fdd28.js","biz_common/dom/attr.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_common/dom/attr3518c6.js","biz_wap/utils/ajax.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_wap/utils/ajax38c31a.js","appmsg/log.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/log300330.js","biz_common/dom/class.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_common/dom/class3518c6.js","biz_wap/utils/device.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_wap/utils/device34c264.js","biz_common/utils/string/html.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_common/utils/string/html3518c6.js","appmsg/index.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/index393966.js"};</script><script nonce="1913591860" type="text/javascript" id="moon_inline" > window.__mooninline=1; window.setTimeout(function() {  function __moonf__(){
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
        __moonf__(); }, 25);</script><script nonce="1913591860" type="text/javascript">
    var real_show_page_time = +new Date();
    if (!!window.addEventListener){
        window.addEventListener("load", function(){
            window.onload_endtime = +new Date();
        });
    }

</script>

</body>
<script nonce="1913591860" type="text/javascript">document.addEventListener("touchstart", function() {},false);</script>
</html>
<!--tailTrap<body></body><head></head><html></html>-->
