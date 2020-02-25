<!DOCTYPE html>
<html style="-webkit-text-size-adjust: 100%; line-height: 1.60">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0,viewport-fit=cover">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no">


    <script nonce="1702737268" type="text/javascript">
        window.logs = {
            pagetime: {}
        };
        window.logs.pagetime['html_begin'] = (+new Date());
    </script>

    <script nonce="1702737268" type="text/javascript">
        var biz = "MzUyMDAzNDYxNw=="||"";
        var sn = "" || ""|| "9a020eb2ab96dc6cc1c565e86111c2a7";
        var mid = "100000068" || ""|| "100000068";
        var idx = "1" || "" || "1";
        window.__allowLoadResFromMp = true;

    </script>
    <script nonce="1702737268" type="text/javascript">
        var page_begintime=+new Date,is_rumor="",norumor="";
        1*is_rumor&&!(1*norumor)&&biz&&mid&&(document.referrer&&-1!=document.referrer.indexOf("mp.weixin.qq.com/mp/rumor")||(location.href="http://mp.weixin.qq.com/mp/rumor?action=info&__biz="+biz+"&mid="+mid+"&idx="+idx+"&sn="+sn+"#wechat_redirect")),
            document.domain="qq.com";
    </script>
    <script nonce="1702737268" type="text/javascript">
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
    <script nonce="1702737268" type="text/javascript">
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
            window.key = params['key'] || "cf67e8f56e0f00743df1798b54aadf22be938d8f42ab8e032e23af8df9eb1c1db21f37c6b171644d9e88eb06ef55736c6aac21ac09783b2014a9dfa061c084c096ae6f584089fff39e8c313045f64e81" || '';
            window.wxtoken = params['wxtoken'] || '';
            window.pass_ticket = params['pass_ticket'] || '';
            window.appmsg_token = "933_WEAwpyTK27x4j%2B9DNxn2QLkTuT1Lby-2Dgj8dnUB2mUpbvJf_MyG43NzaiM~";
        })();

        function wx_loaderror() {
            if (location.pathname === '/bizmall/reward') {
                new Image().src = '/mp/jsreport?key=96&content=reward_res_load_err&r=' + Math.random();
            }
        }

    </script>

    <title>亏掉300万，靠一手H5逆袭的游戏生意</title>

    <style>html{-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;line-height:1.6}body{-webkit-touch-callout:none;font-family:-apple-system-font,"Helvetica Neue","PingFang SC","Hiragino Sans GB","Microsoft YaHei",sans-serif;background-color:#f3f3f3;line-height:inherit}body.rich_media_empty_extra{background-color:#fff}body.rich_media_empty_extra .rich_media_area_primary:before{display:none}h1,h2,h3,h4,h5,h6{font-weight:400;font-size:16px}*{margin:0;padding:0}a{color:#607fa6;text-decoration:none}.rich_media_inner{font-size:16px;word-wrap:break-word;-webkit-hyphens:auto;-ms-hyphens:auto;hyphens:auto}.rich_media_area_primary{position:relative;padding:20px 15px 15px;background-color:#fff}.rich_media_area_primary:before{content:" ";position:absolute;left:0;top:0;width:100%;height:1px;border-top:1px solid #e5e5e5;-webkit-transform-origin:0 0;transform-origin:0 0;-webkit-transform:scaleY(0.5);transform:scaleY(0.5);top:auto;bottom:-2px}.rich_media_area_primary .original_img_wrp{display:inline-block;font-size:0}.rich_media_area_primary .original_img_wrp .tips_global{display:block;margin-top:.5em;font-size:14px;text-align:right;width:auto;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;word-wrap:normal}.rich_media_area_extra{padding:0 15px 0}.rich_media_title{margin-bottom:10px;line-height:1.4;font-weight:400;font-size:24px}.icon_original_tag_primary{display:inline-block;padding:1px .65em;margin-top:-0.2em;vertical-align:middle;line-height:1.4;font-size:12px;border-top-left-radius:.85em 50%;-moz-border-radius-topleft:.85em 50%;-webkit-border-top-left-radius:.85em 50%;border-top-right-radius:.85em 50%;-moz-border-radius-topright:.85em 50%;-webkit-border-top-right-radius:.85em 50%;border-bottom-left-radius:.85em 50%;-moz-border-radius-bottomleft:.85em 50%;-webkit-border-bottom-left-radius:.85em 50%;border-bottom-right-radius:.85em 50%;-moz-border-radius-bottomright:.85em 50%;-webkit-border-bottom-right-radius:.85em 50%;border:1px solid #9e9e9e;color:#8c8c8c}.icon_original_tag_primary.title_tag{background-color:#e94442;border-color:#d04b4e;color:#fff;margin-bottom:.5em;padding:2px .65em;border-top-left-radius:.95em 50%;-moz-border-radius-topleft:.95em 50%;-webkit-border-top-left-radius:.95em 50%;border-top-right-radius:.95em 50%;-moz-border-radius-topright:.95em 50%;-webkit-border-top-right-radius:.95em 50%;border-bottom-left-radius:.95em 50%;-moz-border-radius-bottomleft:.95em 50%;-webkit-border-bottom-left-radius:.95em 50%;border-bottom-right-radius:.95em 50%;-moz-border-radius-bottomright:.95em 50%;-webkit-border-bottom-right-radius:.95em 50%}.rich_media_meta_list{margin-bottom:18px;line-height:20px;font-size:0}.rich_media_meta_list em{font-style:normal}.rich_media_meta{display:inline-block;vertical-align:middle;margin-right:8px;margin-bottom:10px;font-size:16px}.meta_original_tag{display:inline-block;vertical-align:middle;padding:1px .5em;border:1px solid #9e9e9e;color:#8c8c8c;border-top-left-radius:20% 50%;-moz-border-radius-topleft:20% 50%;-webkit-border-top-left-radius:20% 50%;border-top-right-radius:20% 50%;-moz-border-radius-topright:20% 50%;-webkit-border-top-right-radius:20% 50%;border-bottom-left-radius:20% 50%;-moz-border-radius-bottomleft:20% 50%;-webkit-border-bottom-left-radius:20% 50%;border-bottom-right-radius:20% 50%;-moz-border-radius-bottomright:20% 50%;-webkit-border-bottom-right-radius:20% 50%;font-size:15px;line-height:1.1}.meta_enterprise_tag img{width:30px;height:30px!important;display:block;position:relative;margin-top:-3px;border:0}.rich_media_meta_text{color:#8c8c8c}span.rich_media_meta_nickname{display:none}.rich_media_thumb_wrp{margin-bottom:6px}.rich_media_thumb_wrp .original_img_wrp{display:block}.rich_media_thumb{display:block;width:100%}.rich_media_content{overflow:hidden;color:#3e3e3e}.rich_media_content *{max-width:100%!important;box-sizing:border-box!important;-webkit-box-sizing:border-box!important;word-wrap:break-word!important}.rich_media_content p{clear:both;min-height:1em}.rich_media_content em{font-style:italic}.rich_media_content fieldset{min-width:0}.rich_media_content .list-paddingleft-2{padding-left:30px}.rich_media_content blockquote{margin:0;padding-left:10px;border-left:3px solid #dbdbdb}img{height:auto!important}@media screen and (device-aspect-ratio:2/3),screen and (device-aspect-ratio:40/71){.meta_original_tag{padding-top:0}}@media(min-device-width:375px) and (max-device-width:667px) and (-webkit-min-device-pixel-ratio:2){.mm_appmsg .rich_media_inner,.mm_appmsg .rich_media_meta,.mm_appmsg .discuss_list,.mm_appmsg .rich_media_extra,.mm_appmsg .title_tips .tips{font-size:17px}.mm_appmsg .meta_original_tag{font-size:15px}}@media(min-device-width:414px) and (max-device-width:736px) and (-webkit-min-device-pixel-ratio:3){.mm_appmsg .rich_media_title{font-size:25px}}@media only screen and (device-width:375px) and (device-height:812px) and (-webkit-device-pixel-ratio:3) and (orientation:portrait){.rich_media_area_extra{padding-bottom:34px}}@media only screen and (device-width:375px) and (device-height:812px) and (-webkit-device-pixel-ratio:3) and (orientation:landscape){.rich_media_area_primary{padding:20px 59px 15px 59px}.rich_media_area_extra{padding:0 59px 21px 59px}}@media screen and (min-width:1024px){.rich_media{width:740px;margin-left:auto;margin-right:auto}.rich_media_inner{padding:20px}body{background-color:#fff}}@media screen and (min-width:1025px){body{font-family:"Helvetica Neue",Helvetica,"Hiragino Sans GB","Microsoft YaHei",Arial,sans-serif}.rich_media{position:relative}.rich_media_inner{background-color:#fff;padding-bottom:100px}}.radius_avatar{display:inline-block;background-color:#fff;padding:3px;border-radius:50%;-moz-border-radius:50%;-webkit-border-radius:50%;overflow:hidden;vertical-align:middle}.radius_avatar img{display:block;width:100%;height:100%;border-radius:50%;-moz-border-radius:50%;-webkit-border-radius:50%;background-color:#eee}.cell{padding:.8em 0;display:block;position:relative}.cell_hd,.cell_bd,.cell_ft{display:table-cell;vertical-align:middle;word-wrap:break-word;word-break:break-all;white-space:nowrap}.cell_primary{width:2000px;white-space:normal}.flex_cell{padding:10px 0;display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-box-align:center;-webkit-align-items:center;-ms-flex-align:center;align-items:center}.flex_cell_primary{width:100%;-webkit-box-flex:1;-webkit-flex:1;-ms-flex:1;box-flex:1;flex:1}.original_tool_area{display:block;padding:.75em 1em 0;-webkit-tap-highlight-color:rgba(0,0,0,0);color:#3e3e3e;border:1px solid #eaeaea;margin:20px 0}.original_tool_area .tips_global{position:relative;padding-bottom:.5em;font-size:15px}.original_tool_area .tips_global:after{content:" ";position:absolute;left:0;bottom:0;right:0;height:1px;border-bottom:1px solid #dbdbdb;-webkit-transform-origin:0 100%;transform-origin:0 100%;-webkit-transform:scaleY(0.5);transform:scaleY(0.5)}.original_tool_area .radius_avatar{width:27px;height:27px;padding:0;margin-right:.5em}.original_tool_area .radius_avatar img{height:100%!important}.original_tool_area .flex_cell_bd{width:auto;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;word-wrap:normal}.original_tool_area .flex_cell_ft{font-size:14px;color:#8c8c8c;padding-left:1em;white-space:nowrap}.original_tool_area .icon_access:after{content:" ";display:inline-block;height:8px;width:8px;border-width:1px 1px 0 0;border-color:#cbcad0;border-style:solid;transform:matrix(0.71,0.71,-0.71,0.71,0,0);-ms-transform:matrix(0.71,0.71,-0.71,0.71,0,0);-webkit-transform:matrix(0.71,0.71,-0.71,0.71,0,0);position:relative;top:-2px;top:-1px}.weui_loading{width:20px;height:20px;display:inline-block;vertical-align:middle;-webkit-animation:weuiLoading 1s steps(12,end) infinite;animation:weuiLoading 1s steps(12,end) infinite;background:transparent url(data:image/svg+xml;base64,PHN2ZyBjbGFzcz0iciIgd2lkdGg9JzEyMHB4JyBoZWlnaHQ9JzEyMHB4JyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAxMDAgMTAwIj4KICAgIDxyZWN0IHg9IjAiIHk9IjAiIHdpZHRoPSIxMDAiIGhlaWdodD0iMTAwIiBmaWxsPSJub25lIiBjbGFzcz0iYmsiPjwvcmVjdD4KICAgIDxyZWN0IHg9JzQ2LjUnIHk9JzQwJyB3aWR0aD0nNycgaGVpZ2h0PScyMCcgcng9JzUnIHJ5PSc1JyBmaWxsPScjRTlFOUU5JwogICAgICAgICAgdHJhbnNmb3JtPSdyb3RhdGUoMCA1MCA1MCkgdHJhbnNsYXRlKDAgLTMwKSc+CiAgICA8L3JlY3Q+CiAgICA8cmVjdCB4PSc0Ni41JyB5PSc0MCcgd2lkdGg9JzcnIGhlaWdodD0nMjAnIHJ4PSc1JyByeT0nNScgZmlsbD0nIzk4OTY5NycKICAgICAgICAgIHRyYW5zZm9ybT0ncm90YXRlKDMwIDUwIDUwKSB0cmFuc2xhdGUoMCAtMzApJz4KICAgICAgICAgICAgICAgICByZXBlYXRDb3VudD0naW5kZWZpbml0ZScvPgogICAgPC9yZWN0PgogICAgPHJlY3QgeD0nNDYuNScgeT0nNDAnIHdpZHRoPSc3JyBoZWlnaHQ9JzIwJyByeD0nNScgcnk9JzUnIGZpbGw9JyM5Qjk5OUEnCiAgICAgICAgICB0cmFuc2Zvcm09J3JvdGF0ZSg2MCA1MCA1MCkgdHJhbnNsYXRlKDAgLTMwKSc+CiAgICAgICAgICAgICAgICAgcmVwZWF0Q291bnQ9J2luZGVmaW5pdGUnLz4KICAgIDwvcmVjdD4KICAgIDxyZWN0IHg9JzQ2LjUnIHk9JzQwJyB3aWR0aD0nNycgaGVpZ2h0PScyMCcgcng9JzUnIHJ5PSc1JyBmaWxsPScjQTNBMUEyJwogICAgICAgICAgdHJhbnNmb3JtPSdyb3RhdGUoOTAgNTAgNTApIHRyYW5zbGF0ZSgwIC0zMCknPgogICAgPC9yZWN0PgogICAgPHJlY3QgeD0nNDYuNScgeT0nNDAnIHdpZHRoPSc3JyBoZWlnaHQ9JzIwJyByeD0nNScgcnk9JzUnIGZpbGw9JyNBQkE5QUEnCiAgICAgICAgICB0cmFuc2Zvcm09J3JvdGF0ZSgxMjAgNTAgNTApIHRyYW5zbGF0ZSgwIC0zMCknPgogICAgPC9yZWN0PgogICAgPHJlY3QgeD0nNDYuNScgeT0nNDAnIHdpZHRoPSc3JyBoZWlnaHQ9JzIwJyByeD0nNScgcnk9JzUnIGZpbGw9JyNCMkIyQjInCiAgICAgICAgICB0cmFuc2Zvcm09J3JvdGF0ZSgxNTAgNTAgNTApIHRyYW5zbGF0ZSgwIC0zMCknPgogICAgPC9yZWN0PgogICAgPHJlY3QgeD0nNDYuNScgeT0nNDAnIHdpZHRoPSc3JyBoZWlnaHQ9JzIwJyByeD0nNScgcnk9JzUnIGZpbGw9JyNCQUI4QjknCiAgICAgICAgICB0cmFuc2Zvcm09J3JvdGF0ZSgxODAgNTAgNTApIHRyYW5zbGF0ZSgwIC0zMCknPgogICAgPC9yZWN0PgogICAgPHJlY3QgeD0nNDYuNScgeT0nNDAnIHdpZHRoPSc3JyBoZWlnaHQ9JzIwJyByeD0nNScgcnk9JzUnIGZpbGw9JyNDMkMwQzEnCiAgICAgICAgICB0cmFuc2Zvcm09J3JvdGF0ZSgyMTAgNTAgNTApIHRyYW5zbGF0ZSgwIC0zMCknPgogICAgPC9yZWN0PgogICAgPHJlY3QgeD0nNDYuNScgeT0nNDAnIHdpZHRoPSc3JyBoZWlnaHQ9JzIwJyByeD0nNScgcnk9JzUnIGZpbGw9JyNDQkNCQ0InCiAgICAgICAgICB0cmFuc2Zvcm09J3JvdGF0ZSgyNDAgNTAgNTApIHRyYW5zbGF0ZSgwIC0zMCknPgogICAgPC9yZWN0PgogICAgPHJlY3QgeD0nNDYuNScgeT0nNDAnIHdpZHRoPSc3JyBoZWlnaHQ9JzIwJyByeD0nNScgcnk9JzUnIGZpbGw9JyNEMkQyRDInCiAgICAgICAgICB0cmFuc2Zvcm09J3JvdGF0ZSgyNzAgNTAgNTApIHRyYW5zbGF0ZSgwIC0zMCknPgogICAgPC9yZWN0PgogICAgPHJlY3QgeD0nNDYuNScgeT0nNDAnIHdpZHRoPSc3JyBoZWlnaHQ9JzIwJyByeD0nNScgcnk9JzUnIGZpbGw9JyNEQURBREEnCiAgICAgICAgICB0cmFuc2Zvcm09J3JvdGF0ZSgzMDAgNTAgNTApIHRyYW5zbGF0ZSgwIC0zMCknPgogICAgPC9yZWN0PgogICAgPHJlY3QgeD0nNDYuNScgeT0nNDAnIHdpZHRoPSc3JyBoZWlnaHQ9JzIwJyByeD0nNScgcnk9JzUnIGZpbGw9JyNFMkUyRTInCiAgICAgICAgICB0cmFuc2Zvcm09J3JvdGF0ZSgzMzAgNTAgNTApIHRyYW5zbGF0ZSgwIC0zMCknPgogICAgPC9yZWN0Pgo8L3N2Zz4=) no-repeat;-webkit-background-size:100%;background-size:100%}@-webkit-keyframes weuiLoading{0%{-webkit-transform:rotate3d(0,0,1,0deg)}100%{-webkit-transform:rotate3d(0,0,1,360deg)}}@keyframes weuiLoading{0%{-webkit-transform:rotate3d(0,0,1,0deg)}100%{-webkit-transform:rotate3d(0,0,1,360deg)}}.gif_img_wrp{display:inline-block;font-size:0;position:relative;font-weight:400;font-style:normal;text-indent:0;text-shadow:none 1px 1px rgba(0,0,0,0.5)}.gif_img_wrp img{vertical-align:top}.gif_img_tips{background:rgba(0,0,0,0.6)!important;filter:progid:DXImageTransform.Microsoft.gradient(GradientType=0,startColorstr='#99000000',endcolorstr = '#99000000');border-top-left-radius:1.2em 50%;-moz-border-radius-topleft:1.2em 50%;-webkit-border-top-left-radius:1.2em 50%;border-top-right-radius:1.2em 50%;-moz-border-radius-topright:1.2em 50%;-webkit-border-top-right-radius:1.2em 50%;border-bottom-left-radius:1.2em 50%;-moz-border-radius-bottomleft:1.2em 50%;-webkit-border-bottom-left-radius:1.2em 50%;border-bottom-right-radius:1.2em 50%;-moz-border-radius-bottomright:1.2em 50%;-webkit-border-bottom-right-radius:1.2em 50%;line-height:2.3;font-size:11px;color:#fff;text-align:center;position:absolute;bottom:10px;left:10px;min-width:65px}.gif_img_tips.loading{min-width:75px}.gif_img_tips i{vertical-align:middle;margin:-0.2em .73em 0 -2px}.gif_img_play_arrow{display:inline-block;width:0;height:0;border-width:8px;border-style:dashed;border-color:transparent;border-right-width:0;border-left-color:#fff;border-left-style:solid;border-width:5px 0 5px 8px}.gif_img_loading{width:14px;height:14px}i.gif_img_loading{margin-left:-4px}.gif_bg_tips_wrp{position:relative;height:0;line-height:0;margin:0;padding:0}.gif_bg_tips_wrp .gif_img_tips_group{position:absolute;top:0;left:0;z-index:9999}.gif_bg_tips_wrp .gif_img_tips_group .gif_img_tips{top:0;left:0;bottom:auto}.rich_media_global_msg{position:fixed;top:0;left:0;right:0;padding:1em 35px 1em 15px;z-index:2;background-color:#c6e0f8;color:#8c8c8c;font-size:13px}.rich_media_global_msg .icon_closed{position:absolute;right:15px;top:50%;margin-top:-5px;line-height:300px;overflow:hidden;-webkit-tap-highlight-color:rgba(0,0,0,0);background:transparent url(//res.wx.qq.com/mmbizwap/zh_CN/htmledition/images/icon/appmsg/icon_appmsg_msg_closed_sprite.2x2eb52b.png) no-repeat 0 0;width:11px;height:11px;vertical-align:middle;display:inline-block;-webkit-background-size:100% auto;background-size:100% auto}.rich_media_global_msg .icon_closed:active{background-position:0 -17px}.preview_appmsg .rich_media_title{margin-top:1.9em}@media screen and (min-width:1024px){.rich_media_global_msg{position:relative;margin:0 20px}.preview_appmsg .rich_media_title{margin-top:0}}.pages_reset{color:#3e3e3e;line-height:1.6;font-size:16px;font-weight:400;font-style:normal;text-indent:0;letter-spacing:normal;text-align:left;text-decoration:none;white-space:normal}.weapp_element,.weapp_display_element,.mp-miniprogram{display:block;margin:1em 0}.share_audio_context{margin:16px 0}.weapp_text_link{font-size:17px}.weapp_text_link:before{content:'';display:inline-block;line-height:1;background-size:12px 12px;background-repeat:no-repeat;background-image:url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABwAAAAcCAMAAABF0y+mAAAAb1BMVEUAAAB4it11h9x2h9x2h9x2htx8j+R8i+B1h9x2h9x3h92Snv91htt2h9x1h9x4h9x1h9x1h9x2idx1h9t2h9t1htt1h9x1h9x1htx2h9x1h912h9x4h913iN17juOOjuN1iNx2h9t4h958i+B1htvejBiPAAAAJHRSTlMALPLcxKcVEOXXUgXtspU498sx69DPu5+Yc2JeRDwbCYuIRiGBtoolAAAA3ElEQVQoz62S1xKDIBBFWYiFYImm2DWF///G7DJEROOb58U79zi4O8iOo8zuCRfV8EdFgbYE49qFQs8ksJInajOA1wWfYvLcGSueU/oUGBtPpti09uNS68KTMcrQ5jce4kmN/HKn9XVPAo702JEdx9hTUrWUqVrI3KwUmM1NhIWMKdwiGvpGMWZOAj1PZuzAxHwhVSplrajoseBnbyDHAwvrtvKKhdqTtFBkL8wO5ijcsS3G1JMNvQ5mdW7fc0x0+ZcnlJlZiflAomdEyFaM7qeK2JahEjy5ZyU7jC/q/Rz/DgqEuAAAAABJRU5ErkJggg==');vertical-align:middle;font-size:11px;color:#888;border-radius:10px;background-color:#f4f4f4;margin-right:6px;margin-top:-4px;background-position:center;height:20px;width:20px}.weui-mask{position:fixed;z-index:1000;top:0;right:0;left:0;bottom:0;background:rgba(0,0,0,0.6)}.weui-dialog{position:fixed;z-index:5000;width:80%;max-width:300px;top:50%;left:50%;-webkit-transform:translate(-50%,-50%);transform:translate(-50%,-50%);background-color:#fff;text-align:center;border-radius:3px;overflow:hidden}.weui-dialog__hd{padding:1.3em 1.6em .5em}.weui-dialog__title{font-weight:400;font-size:18px}.weui-dialog__bd{padding:0 1.6em .8em;min-height:40px;font-size:15px;line-height:1.3;word-wrap:break-word;word-break:break-all;color:#999}.weui-dialog__bd:first-child{padding:2.7em 20px 1.7em;color:#353535}.weui-dialog__ft{position:relative;line-height:48px;font-size:18px;display:-webkit-box;display:-webkit-flex;display:flex}.weui-dialog__ft:after{content:" ";position:absolute;left:0;top:0;right:0;height:1px;border-top:1px solid #d5d5d6;color:#d5d5d6;-webkit-transform-origin:0 0;transform-origin:0 0;-webkit-transform:scaleY(0.5);transform:scaleY(0.5)}.weui-dialog__btn{display:block;-webkit-box-flex:1;-webkit-flex:1;flex:1;color:#3cc51f;text-decoration:none;-webkit-tap-highlight-color:rgba(0,0,0,0);position:relative}.weui-dialog__btn:active{background-color:#eee}.weui-dialog__btn:after{content:" ";position:absolute;left:0;top:0;width:1px;bottom:0;border-left:1px solid #d5d5d6;color:#d5d5d6;-webkit-transform-origin:0 0;transform-origin:0 0;-webkit-transform:scaleX(0.5);transform:scaleX(0.5)}.weui-dialog__btn:first-child:after{display:none}.weui-dialog__btn_default{color:#353535}.weui-dialog__btn_primary{color:#0bb20c}</style>
    <style>
    </style>
    <!--[if lt IE 9]>
    <link onerror="wx_loaderror(this)" rel="stylesheet" type="text/css" href="//res.wx.qq.com/mmbizwap/zh_CN/htmledition/style/page/appmsg/page_mp_article_improve_pc2c9cd6.css">
    <![endif]-->

</head>
<body id="activity-detail" class="zh_CN mm_appmsg">

<script nonce="1702737268" type="text/javascript">
    var write_sceen_time = (+new Date());
</script>

<div id="js_article" class="rich_media preview_appmsg">

    <div id="js_top_ad_area" class="top_banner"></div>

    <div class="rich_media_inner">
        <div class="rich_media_global_msg">

            <a id="js_close_temp" href="<?=$this->url?>">一手H5逆袭的游戏生意,点击前往课堂</a>        </div>

        <div id="page-content" class="rich_media_area_primary">

            <div id="img-content">

                <h2 class="rich_media_title" id="activity-name">
                    亏掉300万，靠一手H5逆袭的游戏生意                                    </h2>
                <div id="meta_content" class="rich_media_meta_list">


                    <div id="js_profile_qrcode" class="profile_container" style="display:none;">
                        <span class="profile_arrow_wrp" id="js_profile_arrow_wrp">
                            <i class="profile_arrow arrow_out"></i>
                            <i class="profile_arrow arrow_in"></i>
                        </span>
                    </div>
                </div>







                <div class="rich_media_content " id="js_content">






                    <p><strong style="font-size: 16px;"><span style="font-family: 微软雅黑;">个人</span></strong><strong style="font-size: 16px;"><span style="font-family: 微软雅黑;">介绍</span></strong><br  /></p><p><span style="font-family: Calibri;font-size: 16px;">&nbsp;</span></p><p><span style="font-family: 宋体;font-size: 16px;">大家好，我是王薄荷，很感谢亦仁的邀请，今天来跟大家分享一下我这些年，在互联网摸爬滚打的故事。其实这几年我起伏得非常厉害，从一开始做量做联盟赚了一些钱，到后面不知天高地厚，创业跌到谷底，直到这两年抓住H5的一个小风口，才算是稳定下来。</span></p><p><span style="font-family: Calibri;font-size: 16px;">&nbsp;</span></p><p><span style="font-size: 16px;"><span style="font-family: 宋体;font-size: 14px;"><span style="font-size: 16px;font-family: 宋体;">今天给大家的分享主要有两个部分，第一部分是我做流量做联盟的一些经验，主要是给大家简单说说一些流量做法。重点还是想说说做转化的一些心得。第二部分是今天主要想跟大家分享的，</span>H5<span style="font-size: 16px;font-family: 宋体;">游戏从</span><span style="font-size: 16px;font-family: Calibri;">0</span><span style="font-size: 16px;font-family: 宋体;">到</span><span style="font-size: 16px;font-family: Calibri;">1</span><span style="font-size: 16px;font-family: 宋体;">的整个过程。希望能对大家有帮助。然后这里我给大家道个歉，由于我们胡建人的普通话大家是清楚的，所以我说得会有些吃力，影响大家体验，非常抱歉。</span></span></span></p><p><span style="font-size: 16px;"><strong><span style="font-size: 16px;font-family: 黑体;"><br  /></span></strong></span></p><p><span style="font-size: 16px;"><strong><span style="font-size: 16px;font-family: 黑体;">我</span></strong><strong><span style="font-size: 16px;font-family: 黑体;">的第一</span></strong><strong><span style="font-size: 16px;font-family: 黑体;">桶金</span></strong></span></p><hr  /><p><span style="font-size: 16px;"><strong><span style="font-size: 16px;font-family: 黑体;"></span></strong></span><br  /></p><p><span style="font-size: 16px;"><span style="font-size: 16px;font-family: 宋体;">我算</span><span style="font-size: 16px;font-family: 宋体;">是做流量起</span><span style="font-size: 16px;font-family: 宋体;">家</span><span style="font-size: 16px;font-family: 宋体;">的，</span><span style="font-size: 16px;font-family: 宋体;">从</span><span style="font-size: 16px;font-family: 宋体;">一开始</span><span style="font-size: 16px;font-family: 宋体;">做</span><span style="font-size: 16px;font-family: 宋体;">量，到后面</span><span style="font-family: 宋体;font-size: 14px;"><span style="font-size: 16px;font-family: 宋体;">做联盟。大概</span>11<span style="font-size: 16px;font-family: 宋体;">年底的时候接触了流量这个圈子，那个时候</span></span><span style="font-size: 16px;font-family: 宋体;">福建这边大牛很多，</span><span style="font-size: 16px;font-family: 宋体;">做</span><span style="font-size: 16px;font-family: 宋体;">量的方式</span><span style="font-size: 16px;font-family: 宋体;">也千</span><span style="font-size: 16px;font-family: 宋体;">奇百怪，</span><span style="font-size: 16px;font-family: 宋体;">印象</span><span style="font-size: 16px;font-family: 宋体;">比较深刻</span><span style="font-size: 16px;font-family: 宋体;">的</span><span style="font-size: 16px;font-family: 宋体;">有</span><span style="font-size: 16px;font-family: 宋体;">像</span><span style="font-size: 16px;font-family: 宋体;">劫持，</span><span style="font-size: 16px;font-family: 宋体;">除了我</span><span style="font-size: 16px;font-family: 宋体;">们常见的</span><span style="font-size: 16px;font-family: 宋体;">运营商</span><span style="font-size: 16px;font-family: 宋体;">劫持，</span><span style="font-size: 16px;font-family: 宋体;">插件</span><span style="font-size: 16px;font-family: 宋体;">劫持等，</span><span style="font-size: 16px;font-family: 宋体;">还有</span><span style="font-size: 16px;font-family: 宋体;">一些明</span><span style="font-size: 16px;font-family: 宋体;">面</span><span style="font-size: 16px;font-family: 宋体;">下的，比如</span><span style="font-size: 16px;font-family: 宋体;">整</span><span style="font-size: 16px;font-family: 宋体;">站劫持，</span><span style="font-size: 16px;font-family: 宋体;">关键词劫持</span><span style="font-size: 16px;font-family: 宋体;">等。</span><span style="font-size: 16px;font-family: 宋体;">这里</span><span style="font-size: 16px;font-family: 宋体;">我给大家简单介绍一下</span></span></p><p><span style="font-family: Calibri;font-size: 16px;">&nbsp;</span></p><p><span style="font-size: 16px;"><span style="font-size: 16px;font-family: 宋体;">当时</span><span style="font-size: 16px;font-family: 宋体;">有一种</span><span style="font-size: 16px;font-family: 宋体;">做法</span><span style="font-size: 16px;font-family: 宋体;">很</span><span style="font-size: 16px;font-family: 宋体;">暴力</span><span style="font-size: 16px;font-family: 宋体;">，</span><span style="font-size: 16px;font-family: 宋体;">就是</span><span style="font-size: 16px;font-family: 宋体;">入侵到一些</span><span style="font-size: 16px;font-family: 宋体;">权重</span><span style="font-size: 16px;font-family: 宋体;">高的网站，</span><span style="font-size: 16px;font-family: 宋体;">利用权重做想要</span><span style="font-size: 16px;font-family: 宋体;">的</span><span style="font-size: 16px;font-family: 宋体;">关键词排名</span><span style="font-size: 16px;font-family: 宋体;">，</span><span style="font-size: 16px;font-family: 宋体;">快</span><span style="font-size: 16px;font-family: 宋体;">的话</span><span style="font-size: 16px;font-family: 宋体;">几天</span><span style="font-size: 16px;font-family: 宋体;">就能</span><span style="font-size: 16px;font-family: 宋体;">排</span><span style="font-size: 16px;font-family: 宋体;">到</span><span style="font-size: 16px;font-family: 宋体;">首位</span><span style="font-size: 16px;font-family: 宋体;">，</span><span style="font-size: 16px;font-family: 宋体;">但是</span><span style="font-size: 16px;font-family: 宋体;">点击</span><span style="font-size: 16px;font-family: 宋体;">关键词</span><span style="font-size: 16px;font-family: 宋体;">却会跳到</span><span style="font-size: 16px;font-family: 宋体;">另一个</span><span style="font-size: 16px;font-family: 宋体;">网站，</span><span style="font-size: 16px;font-family: 宋体;">这种</span><span style="font-size: 16px;font-family: 宋体;">做法</span><span style="font-size: 16px;font-family: 宋体;">多数</span><span style="font-size: 16px;font-family: 宋体;">用于</span><span style="font-size: 16px;font-family: 宋体;">黑产。而</span><span style="font-size: 16px;font-family: 宋体;">那个时候，劫持其实</span><span style="font-size: 16px;font-family: 宋体;">不论</span><span style="font-size: 16px;font-family: 宋体;">是大的</span><span style="font-size: 16px;font-family: 宋体;">互联网</span><span style="font-size: 16px;font-family: 宋体;">公司还是</span><span style="font-size: 16px;font-family: 宋体;">草根</span><span style="font-size: 16px;font-family: 宋体;">阶层，</span><span style="font-size: 16px;font-family: 宋体;">都做</span><span style="font-size: 16px;font-family: 宋体;">得比较多，难</span><span style="font-size: 16px;font-family: 宋体;">怪有</span><span style="font-size: 16px;font-family: 宋体;">人</span><span style="font-size: 16px;font-family: 宋体;">说</span><span style="font-size: 16px;font-family: 宋体;">，</span><span style="font-size: 16px;font-family: 宋体;">整个</span><span style="font-size: 16px;font-family: 宋体;">互联网</span><span style="font-size: 16px;font-family: 宋体;">的</span><span style="font-size: 16px;font-family: 宋体;">发展史就是一部</span><span style="font-size: 16px;font-family: 宋体;">流氓史</span><span style="font-size: 16px;font-family: 宋体;">。</span></span></p><p><span style="font-family: Calibri;font-size: 16px;">&nbsp;</span></p><p><span style="font-size: 16px;"><span style="font-size: 16px;font-family: 宋体;">有人</span><span style="font-family: 宋体;font-size: 14px;"><span style="font-size: 16px;font-family: 宋体;">把</span>SEO<span style="font-size: 16px;font-family: 宋体;">分</span></span><span style="font-size: 16px;font-family: 宋体;">为</span><span style="font-family: 宋体;font-size: 14px;">2<span style="font-size: 16px;font-family: 宋体;">种</span></span><span style="font-size: 16px;font-family: 宋体;">，</span><span style="font-size: 16px;font-family: 宋体;">白</span><span style="font-size: 16px;font-family: 宋体;">帽</span><span style="font-size: 16px;font-family: 宋体;">跟</span><span style="font-size: 16px;font-family: 宋体;">黑帽</span><span style="font-size: 16px;font-family: 宋体;">，</span><span style="font-size: 16px;font-family: 宋体;">所谓黑帽就是</span><span style="font-size: 16px;font-family: 宋体;">利用</span><span style="font-size: 16px;font-family: 宋体;">百度算法的一些</span><span style="font-size: 16px;font-family: 宋体;">漏洞做</span><span style="font-size: 16px;font-family: 宋体;">快速排名</span><span style="font-size: 16px;font-family: 宋体;">。比如</span><span style="font-size: 16px;font-family: 宋体;">刚说的劫持，</span><span style="font-size: 16px;font-family: 宋体;">还有就是像</span><span style="font-size: 16px;font-family: 宋体;">我做的站群</span><span style="font-size: 16px;font-family: 宋体;">，站群顾名思义</span><span style="font-size: 16px;font-family: 宋体;">就是</span><span style="font-size: 16px;font-family: 宋体;">利用</span><span style="font-size: 16px;font-family: 宋体;">程序</span><span style="font-size: 16px;font-family: 宋体;">同时管理几十个</span><span style="font-size: 16px;font-family: 宋体;">甚至几百个网站，</span><span style="font-size: 16px;font-family: 宋体;">有一种做法</span><span style="font-size: 16px;font-family: 宋体;">，利用</span><span style="font-size: 16px;font-family: 宋体;">泛</span><span style="font-size: 16px;font-family: 宋体;">站群和蜘蛛池</span><span style="font-size: 16px;font-family: 宋体;">做快速排名，以地域</span><span style="font-family: Calibri;font-size: 14px;">+<span style="font-size: 16px;font-family: 宋体;">行业词的</span></span><span style="font-size: 16px;font-family: 宋体;">做法完成</span><span style="font-size: 16px;font-family: 宋体;">快速刷屏</span><span style="font-size: 16px;font-family: 宋体;">。关键点</span><span style="font-size: 16px;font-family: 宋体;">就是</span><span style="font-size: 16px;font-family: 宋体;">程序</span><span style="font-size: 16px;font-family: 宋体;">以及关键词。</span><span style="font-size: 16px;font-family: 宋体;">所谓</span><span style="font-size: 16px;font-family: 宋体;">的蜘蛛</span><span style="font-size: 16px;font-family: 宋体;">池就是一种</span><span style="font-size: 16px;font-family: 宋体;">加快</span><span style="font-size: 16px;font-family: 宋体;">收录</span><span style="font-size: 16px;font-family: 宋体;">的</span><span style="font-size: 16px;font-family: 宋体;">程序</span><span style="font-size: 16px;font-family: 宋体;">。</span><span style="font-size: 16px;font-family: 宋体;">这种</span><span style="font-size: 16px;font-family: 宋体;">做法至今有人</span><span style="font-size: 16px;font-family: 宋体;">拿来</span><span style="font-size: 16px;font-family: 宋体;">做</span><span style="font-size: 16px;font-family: 宋体;">一些成单</span><span style="font-size: 16px;font-family: 宋体;">利润较高的行业，</span><span style="font-size: 16px;font-family: 宋体;">例如代孕，今年</span><span style="font-size: 16px;font-family: 宋体;">火起来的</span><span style="font-size: 16px;font-family: 宋体;">小额</span><span style="font-size: 16px;font-family: 宋体;">贷款</span><span style="font-size: 16px;font-family: 宋体;">，办卡</span><span style="font-size: 16px;font-family: 宋体;">办证等；</span></span></p><p><span style="font-family: Calibri;font-size: 16px;">&nbsp;</span></p><p><span style="font-size: 16px;"><span style="font-size: 16px;font-family: 宋体;">最开始我的做法还是比较原始</span><span style="font-size: 16px;font-family: 宋体;">，</span><span style="font-size: 16px;font-family: 宋体;">用</span><span style="font-size: 16px;font-family: 宋体;">的程序</span><span style="font-size: 16px;font-family: 宋体;">也是熟知</span><span style="font-size: 16px;font-family: 宋体;">的</span><span style="font-family: 宋体;font-size: 14px;">Z<span style="font-size: 16px;font-family: 宋体;">博客，</span></span><span style="font-size: 16px;font-family: 宋体;">变现则是做的</span><span style="font-size: 16px;font-family: 宋体;">页游以及</span><span style="font-size: 16px;font-family: 宋体;">一些</span><span style="font-size: 16px;font-family: 宋体;">类似</span><span style="font-size: 16px;font-family: 宋体;">像百合</span><span style="font-size: 16px;font-family: 宋体;">世纪佳缘</span><span style="font-size: 16px;font-family: 宋体;">这种</span><span style="font-family: 宋体;font-size: 14px;">CPA<span style="font-size: 16px;font-family: 宋体;">，</span></span><span style="font-size: 16px;font-family: 宋体;">也就是按注册结算</span><span style="font-size: 16px;font-family: 宋体;">。</span><span style="font-size: 16px;font-family: 宋体;">由于</span><span style="font-size: 16px;font-family: 宋体;">当时</span><span style="font-size: 16px;font-family: 宋体;">的程序</span><span style="font-size: 16px;font-family: 宋体;">还算</span><span style="font-size: 16px;font-family: 宋体;">有效，</span><span style="font-size: 16px;font-family: 宋体;">所以最开始做</span><span style="font-size: 16px;font-family: 宋体;">量还算</span><span style="font-size: 16px;font-family: 宋体;">比较</span><span style="font-size: 16px;font-family: 宋体;">顺利。</span><span style="font-size: 16px;font-family: 宋体;">后面有量</span><span style="font-size: 16px;font-family: 宋体;">了，</span><span style="font-size: 16px;font-family: 宋体;">再</span><span style="font-size: 16px;font-family: 宋体;">加上</span><span style="font-size: 16px;font-family: 宋体;">经常</span><span style="font-size: 16px;font-family: 宋体;">被一些联盟</span><span style="font-size: 16px;font-family: 宋体;">跑</span><span style="font-size: 16px;font-family: 宋体;">单</span><span style="font-size: 16px;font-family: 宋体;">，就</span><span style="font-size: 16px;font-family: 宋体;">开始琢磨</span><span style="font-size: 16px;font-family: 宋体;">着</span><span style="font-size: 16px;font-family: 宋体;">自己</span><span style="font-size: 16px;font-family: 宋体;">找转化端</span><span style="font-size: 16px;font-family: 宋体;">，</span><span style="font-size: 16px;font-family: 宋体;">福建</span><span style="font-size: 16px;font-family: 宋体;">的</span><span style="font-size: 16px;font-family: 宋体;">游戏还算发展</span><span style="font-size: 16px;font-family: 宋体;">比较</span><span style="font-size: 16px;font-family: 宋体;">快</span><span style="font-size: 16px;font-family: 宋体;">，</span><span style="font-size: 16px;font-family: 宋体;">很容易</span><span style="font-size: 16px;font-family: 宋体;">就找到</span><span style="font-size: 16px;font-family: 宋体;">了</span><span style="font-size: 16px;font-family: 宋体;">一些</span><span style="font-size: 16px;font-family: 宋体;">页游</span><span style="font-size: 16px;font-family: 宋体;">公司</span><span style="font-size: 16px;font-family: 宋体;">愿意合作</span><span style="font-size: 16px;font-family: 宋体;">，</span><span style="font-size: 16px;font-family: 宋体;">但是</span><span style="font-size: 16px;font-family: 宋体;">最开始</span><span style="font-size: 16px;font-family: 宋体;">，我</span><span style="font-size: 16px;font-family: 宋体;">量</span><span style="font-size: 16px;font-family: 宋体;">不</span><span style="font-size: 16px;font-family: 宋体;">大，议价</span><span style="font-size: 16px;font-family: 宋体;">能力比较低，</span><span style="font-size: 16px;font-family: 宋体;">所以单价其实</span><span style="font-size: 16px;font-family: 宋体;">比</span><span style="font-size: 16px;font-family: 宋体;">联盟</span><span style="font-size: 16px;font-family: 宋体;">要便宜</span><span style="font-size: 16px;font-family: 宋体;">，甚至有时候</span><span style="font-size: 16px;font-family: 宋体;">都</span><span style="font-size: 16px;font-family: 宋体;">要答应</span><span style="font-size: 16px;font-family: 宋体;">试</span><span style="font-size: 16px;font-family: 宋体;">量。</span></span></p><p><span style="font-family: Calibri;font-size: 16px;">&nbsp;</span></p><p><span style="font-size: 16px;"><span style="font-size: 16px;font-family: 宋体;">当时</span><span style="font-size: 16px;font-family: 宋体;">有个小插曲，</span><span style="font-size: 16px;font-family: 宋体;">那个</span><span style="font-size: 16px;font-family: 宋体;">时候</span><span style="font-size: 16px;font-family: 宋体;">很多游戏</span><span style="font-size: 16px;font-family: 宋体;">为了谋求流量</span><span style="font-size: 16px;font-family: 宋体;">都</span><span style="font-size: 16px;font-family: 宋体;">会</span><span style="font-size: 16px;font-family: 宋体;">去</span><span style="font-size: 16px;font-family: 宋体;">找一些游戏</span><span style="font-size: 16px;font-family: 宋体;">门户</span><span style="font-size: 16px;font-family: 宋体;">站这</span><span style="font-size: 16px;font-family: 宋体;">种，为</span><span style="font-size: 16px;font-family: 宋体;">了搞清楚</span><span style="font-size: 16px;font-family: 宋体;">游戏门户</span><span style="font-size: 16px;font-family: 宋体;">的</span><span style="font-size: 16px;font-family: 宋体;">模式</span><span style="font-size: 16px;font-family: 宋体;">，</span><span style="font-size: 16px;font-family: 宋体;">我</span><span style="font-size: 16px;font-family: 宋体;">就跑去了</span><span style="font-family: 宋体;font-size: 14px;">17173<span style="font-size: 16px;font-family: 宋体;">上</span></span><span style="font-size: 16px;font-family: 宋体;">了</span><span style="font-family: 宋体;font-size: 14px;">3<span style="font-size: 16px;font-family: 宋体;">个</span></span><span style="font-size: 16px;font-family: 宋体;">月班</span><span style="font-size: 16px;font-family: 宋体;">。后来</span><span style="font-size: 16px;font-family: 宋体;">发现，</span><span style="font-size: 16px;font-family: 宋体;">本质</span><span style="font-size: 16px;font-family: 宋体;">的东西</span><span style="font-family: 宋体;font-size: 14px;"><span style="font-size: 16px;font-family: 宋体;">除了</span>SEO<span style="font-size: 16px;font-family: 宋体;">还有</span></span><span style="font-size: 16px;font-family: 宋体;">买量换量</span><span style="font-size: 16px;font-family: 宋体;">以外</span><span style="font-size: 16px;font-family: 宋体;">，重要的是原创，</span><span style="font-size: 16px;font-family: 宋体;">而</span><span style="font-size: 16px;font-family: 宋体;">这些原创</span><span style="font-size: 16px;font-family: 宋体;">很多</span><span style="font-size: 16px;font-family: 宋体;">来源其实是</span><span style="font-size: 16px;font-family: 宋体;">厂商</span><span style="font-size: 16px;font-family: 宋体;">提供</span><span style="font-size: 16px;font-family: 宋体;">。之后我就</span><span style="font-size: 16px;font-family: 宋体;">开始跟厂商</span><span style="font-size: 16px;font-family: 宋体;">要</span><span style="font-size: 16px;font-family: 宋体;">一些攻略</span><span style="font-size: 16px;font-family: 宋体;">以及游戏介绍</span><span style="font-size: 16px;font-family: 宋体;">。</span><span style="font-size: 16px;font-family: 宋体;">站群加上</span><span style="font-size: 16px;font-family: 宋体;">原创内容</span><span style="font-size: 16px;font-family: 宋体;">之后发现还</span><span style="font-size: 16px;font-family: 宋体;">是</span><span style="font-size: 16px;font-family: 宋体;">比较</span><span style="font-size: 16px;font-family: 宋体;">有效，后来就摒弃了</span><span style="font-family: 宋体;font-size: 14px;">Z<span style="font-size: 16px;font-family: 宋体;">博客</span></span><span style="font-size: 16px;font-family: 宋体;">，</span><span style="font-size: 16px;font-family: 宋体;">用</span><span style="font-size: 16px;font-family: 宋体;">游戏</span><span style="font-size: 16px;font-family: 宋体;">门户的</span><span style="font-size: 16px;font-family: 宋体;">那种程序开始批量做站</span><span style="font-size: 16px;font-family: 宋体;">。再</span><span style="font-size: 16px;font-family: 宋体;">到后面</span><span style="font-size: 16px;font-family: 宋体;">甚至做</span><span style="font-size: 16px;font-family: 宋体;">了一个</span><span style="font-size: 16px;font-family: 宋体;">编辑</span><span style="font-size: 16px;font-family: 宋体;">团队</span><span style="font-size: 16px;font-family: 宋体;">来</span><span style="font-size: 16px;font-family: 宋体;">负责内容。</span><span style="font-size: 16px;font-family: 宋体;">之后</span><span style="font-size: 16px;font-family: 宋体;">就是快速复制，</span><span style="font-size: 16px;font-family: 宋体;">流量</span><span style="font-size: 16px;font-family: 宋体;">也涨得</span><span style="font-size: 16px;font-family: 宋体;">飞快</span><span style="font-size: 16px;font-family: 宋体;">。</span><span style="font-size: 16px;font-family: 宋体;">现在</span><span style="font-size: 16px;font-family: 宋体;">你搜一些</span><span style="font-size: 16px;font-family: 宋体;">私服</span><span style="font-size: 16px;font-family: 宋体;">的词，</span><span style="font-size: 16px;font-family: 宋体;">出</span><span style="font-size: 16px;font-family: 宋体;">来的很多</span><span style="font-size: 16px;font-family: 宋体;">站</span><span style="font-size: 16px;font-family: 宋体;">其实用的</span><span style="font-size: 16px;font-family: 宋体;">都</span><span style="font-size: 16px;font-family: 宋体;">是我当时的做法，</span><span style="font-size: 16px;font-family: 宋体;">但是</span><span style="font-size: 16px;font-family: 宋体;">现在做比当时做要</span><span style="font-size: 16px;font-family: 宋体;">难</span><span style="font-size: 16px;font-family: 宋体;">，</span><span style="font-size: 16px;font-family: 宋体;">而且成本</span><span style="font-size: 16px;font-family: 宋体;">也大很多</span><span style="font-size: 16px;font-family: 宋体;">，</span><span style="font-size: 16px;font-family: 宋体;">所以</span><span style="font-size: 16px;font-family: 宋体;">转化</span><span style="font-size: 16px;font-family: 宋体;">也都是黑产居多。</span></span></p><p><span style="font-family: Calibri;font-size: 16px;">&nbsp;</span></p><p><span style="font-size: 16px;"><span style="font-size: 16px;font-family: 宋体;">量大</span><span style="font-size: 16px;font-family: 宋体;">了之后</span><span style="font-size: 16px;font-family: 宋体;">，甚至</span><span style="font-size: 16px;font-family: 宋体;">有不少</span><span style="font-size: 16px;font-family: 宋体;">页游厂商</span><span style="font-size: 16px;font-family: 宋体;">会</span><span style="font-size: 16px;font-family: 宋体;">主动</span><span style="font-size: 16px;font-family: 宋体;">来联系我，</span><span style="font-size: 16px;font-family: 宋体;">我当时就想着做</span><span style="font-size: 16px;font-family: 宋体;">联盟</span><span style="font-size: 16px;font-family: 宋体;">。由于自己</span><span style="font-size: 16px;font-family: 宋体;">做过站长，对于</span><span style="font-size: 16px;font-family: 宋体;">站长</span><span style="font-size: 16px;font-family: 宋体;">痛点很清楚，</span><span style="font-size: 16px;font-family: 宋体;">比方说</span><span style="font-family: Calibri;font-size: 14px;"><span style="font-size: 16px;font-family: 宋体;">扣量，跑单，</span> </span><span style="font-size: 16px;font-family: 宋体;">当时做</span><span style="font-size: 16px;font-family: 宋体;">了很多</span><span style="font-size: 16px;font-family: 宋体;">尝试</span><span style="font-size: 16px;font-family: 宋体;">，比方说前三天日结，</span><span style="font-size: 16px;font-family: 宋体;">之后</span><span style="font-size: 16px;font-family: 宋体;">根据</span><span style="font-size: 16px;font-family: 宋体;">你</span><span style="font-size: 16px;font-family: 宋体;">的</span><span style="font-size: 16px;font-family: 宋体;">收益分</span><span style="font-size: 16px;font-family: 宋体;">层级，</span><span style="font-size: 16px;font-family: 宋体;">此外</span><span style="font-size: 16px;font-family: 宋体;">对于一些</span><span style="font-size: 16px;font-family: 宋体;">量</span><span style="font-size: 16px;font-family: 宋体;">大的站长，我这边还可以提供一些原创以及外链</span><span style="font-family: 宋体;font-size: 14px;"><span style="font-size: 16px;font-family: 宋体;">。从</span>12<span style="font-size: 16px;font-family: 宋体;">年</span></span><span style="font-size: 16px;font-family: 宋体;">到</span><span style="font-family: 宋体;font-size: 14px;">13<span style="font-size: 16px;font-family: 宋体;">年</span></span><span style="font-size: 16px;font-family: 宋体;">一直都是在做量收量，</span><span style="font-size: 16px;font-family: 宋体;">期间</span><span style="font-size: 16px;font-family: 宋体;">也做了一些</span><span style="font-size: 16px;font-family: 宋体;">转化</span><span style="font-size: 16px;font-family: 宋体;">端的优化，</span><span style="font-size: 16px;font-family: 宋体;">比方</span><span style="font-size: 16px;font-family: 宋体;">说开始跟</span><span style="font-size: 16px;font-family: 宋体;">厂商</span><span style="font-size: 16px;font-family: 宋体;">走一些联运，</span><span style="font-size: 16px;font-family: 宋体;">也就是</span><span style="font-size: 16px;font-family: 宋体;">收益分成，</span><span style="font-size: 16px;font-family: 宋体;">巅峰</span><span style="font-size: 16px;font-family: 宋体;">的时候</span><span style="font-size: 16px;font-family: 宋体;">早上醒来，账户上</span><span style="font-size: 16px;font-family: 宋体;">就多了几万</span><span style="font-family: 宋体;font-size: 14px;"><span style="font-size: 16px;font-family: 宋体;">，沾自喜的我甚至都不知道我犯了一个最大的错误。一点也没有意识到手上的量其实是</span>0<span style="font-size: 16px;font-family: 宋体;">留存</span></span><span style="font-size: 16px;font-family: 宋体;">的，一</span><span style="font-size: 16px;font-family: 宋体;">旦</span><span style="font-size: 16px;font-family: 宋体;">排名消失，量也就没了</span><span style="font-size: 16px;font-family: 宋体;">。</span></span></p><p><span style="font-family: Calibri;font-size: 16px;">&nbsp;</span></p><p><span style="font-size: 16px;"><span style="font-family: 宋体;font-size: 14px;">13<span style="font-size: 16px;font-family: 宋体;">年的</span></span><span style="font-size: 16px;font-family: 宋体;">时候</span><span style="font-size: 16px;font-family: 宋体;">百度有</span><span style="font-size: 16px;font-family: 宋体;">一个大更新，</span><span style="font-size: 16px;font-family: 宋体;">做</span><span style="font-size: 16px;font-family: 宋体;">了绿萝算法，</span><span style="font-size: 16px;font-family: 宋体;">更新</span><span style="font-size: 16px;font-family: 宋体;">后我的量</span><span style="font-size: 16px;font-family: 宋体;">没了大半，收益</span><span style="font-size: 16px;font-family: 宋体;">大降，在往后</span><span style="font-size: 16px;font-family: 宋体;">页游</span><span style="font-size: 16px;font-family: 宋体;">的收益</span><span style="font-size: 16px;font-family: 宋体;">也开始</span><span style="font-size: 16px;font-family: 宋体;">走</span><span style="font-size: 16px;font-family: 宋体;">下坡路</span><span style="font-size: 16px;font-family: 宋体;">，</span><span style="font-size: 16px;font-family: 宋体;">一些</span><span style="font-size: 16px;font-family: 宋体;">站长也开始</span><span style="font-size: 16px;font-family: 宋体;">离开</span><span style="font-size: 16px;font-family: 宋体;">。</span><span style="font-family: 宋体;font-size: 14px;">13<span style="font-size: 16px;font-family: 宋体;">年底</span></span><span style="font-size: 16px;font-family: 宋体;">的时候</span><span style="font-size: 16px;font-family: 宋体;">我</span><span style="font-size: 16px;font-family: 宋体;">只好</span><span style="font-size: 16px;font-family: 宋体;">关闭</span><span style="font-size: 16px;font-family: 宋体;">。</span></span></p><p><span style="font-family: Calibri;font-size: 16px;">&nbsp;</span></p><p><span style="font-size: 16px;"><span style="font-family: 宋体;font-size: 14px;"><span style="font-size: 16px;font-family: 宋体;">后来</span>15<span style="font-size: 16px;font-family: 宋体;">年在</span></span><span style="font-size: 16px;font-family: 宋体;">上海认识</span><span style="font-size: 16px;font-family: 宋体;">一个做娱乐</span><span style="font-size: 16px;font-family: 宋体;">站的朋友，</span><span style="font-size: 16px;font-family: 宋体;">我</span><span style="font-size: 16px;font-family: 宋体;">们的做法很</span><span style="font-size: 16px;font-family: 宋体;">像</span><span style="font-size: 16px;font-family: 宋体;">。</span><span style="font-size: 16px;font-family: 宋体;">不同</span><span style="font-size: 16px;font-family: 宋体;">的是，</span><span style="font-size: 16px;font-family: 宋体;">他没有</span><span style="font-size: 16px;font-family: 宋体;">跑去做联盟，</span><span style="font-size: 16px;font-family: 宋体;">而</span><span style="font-size: 16px;font-family: 宋体;">是</span><span style="font-size: 16px;font-family: 宋体;">下了</span><span style="font-size: 16px;font-family: 宋体;">心思</span><span style="font-size: 16px;font-family: 宋体;">做</span><span style="font-size: 16px;font-family: 宋体;">了一个主</span><span style="font-size: 16px;font-family: 宋体;">站，以</span><span style="font-size: 16px;font-family: 宋体;">自己的主</span><span style="font-size: 16px;font-family: 宋体;">站</span><span style="font-size: 16px;font-family: 宋体;">做</span><span style="font-size: 16px;font-family: 宋体;">流量</span><span style="font-size: 16px;font-family: 宋体;">分发</span><span style="font-size: 16px;font-family: 宋体;">。所谓分发</span><span style="font-size: 16px;font-family: 宋体;">就是先把量集中到自己的</span><span style="font-size: 16px;font-family: 宋体;">站点</span><span style="font-size: 16px;font-family: 宋体;">，</span><span style="font-size: 16px;font-family: 宋体;">靠着优质内容做留存，最后再</span><span style="font-size: 16px;font-family: 宋体;">完成转化的这么一个过程。</span></span></p><p><span style="font-family: Calibri;font-size: 16px;">&nbsp;</span></p><p><span style="font-size: 16px;"><span style="font-size: 16px;font-family: 宋体;">这里</span><span style="font-size: 16px;font-family: 宋体;">其实就是</span><span style="font-size: 16px;font-family: 宋体;">内容</span><span style="font-size: 16px;font-family: 宋体;">为王还是</span><span style="font-size: 16px;font-family: 宋体;">流量</span><span style="font-size: 16px;font-family: 宋体;">为王</span><span style="font-size: 16px;font-family: 宋体;">的</span><span style="font-size: 16px;font-family: 宋体;">一个区别，</span><span style="font-size: 16px;font-family: 宋体;">因为</span><span style="font-size: 16px;font-family: 宋体;">当时</span><span style="font-size: 16px;font-family: 宋体;">依靠</span><span style="font-size: 16px;font-family: 宋体;">百度，</span><span style="font-size: 16px;font-family: 宋体;">我</span><span style="font-size: 16px;font-family: 宋体;">并不缺流量，而且</span><span style="font-size: 16px;font-family: 宋体;">从</span><span style="font-size: 16px;font-family: 宋体;">流量</span><span style="font-size: 16px;font-family: 宋体;">到</span><span style="font-size: 16px;font-family: 宋体;">现金的过程也磨合得很顺畅，导致我</span><span style="font-size: 16px;font-family: 宋体;">没有很</span><span style="font-size: 16px;font-family: 宋体;">在意留存。我</span><span style="font-size: 16px;font-family: 宋体;">们</span><span style="font-size: 16px;font-family: 宋体;">回过头来看</span><span style="font-family: 宋体;font-size: 14px;"><span style="font-size: 16px;font-family: 宋体;">，其实当时</span>PC<span style="font-size: 16px;font-family: 宋体;">端</span></span><span style="font-size: 16px;font-family: 宋体;">已经</span><span style="font-size: 16px;font-family: 宋体;">发展</span><span style="font-size: 16px;font-family: 宋体;">得很成熟，</span><span style="font-family: 宋体;font-size: 14px;"><span style="font-size: 16px;font-family: 宋体;">流量就是</span>IT<span style="font-size: 16px;font-family: 宋体;">世界</span></span><span style="font-size: 16px;font-family: 宋体;">的</span><span style="font-size: 16px;font-family: 宋体;">石油</span><span style="font-size: 16px;font-family: 宋体;">没错，</span><span style="font-size: 16px;font-family: 宋体;">但是</span><span style="font-size: 16px;font-family: 宋体;">你要</span><span style="font-size: 16px;font-family: 宋体;">保证</span><span style="font-size: 16px;font-family: 宋体;">这个</span><span style="font-size: 16px;font-family: 宋体;">石油不枯竭</span><span style="font-size: 16px;font-family: 宋体;">，</span><span style="font-size: 16px;font-family: 宋体;">本质</span><span style="font-size: 16px;font-family: 宋体;">上</span><span style="font-size: 16px;font-family: 宋体;">还</span><span style="font-size: 16px;font-family: 宋体;">是要花心思在内容</span><span style="font-size: 16px;font-family: 宋体;">上</span><span style="font-size: 16px;font-family: 宋体;">，产品上</span><span style="font-size: 16px;font-family: 宋体;">。特别</span><span style="font-size: 16px;font-family: 宋体;">是在</span><span style="font-size: 16px;font-family: 宋体;">做量</span><span style="font-size: 16px;font-family: 宋体;">越来越来的今天，</span><span style="font-size: 16px;font-family: 宋体;">满足</span><span style="font-size: 16px;font-family: 宋体;">某个</span><span style="font-size: 16px;font-family: 宋体;">细分市场的</span><span style="font-size: 16px;font-family: 宋体;">需求是一个关键</span><span style="font-size: 16px;font-family: 宋体;">。这个事</span><span style="font-size: 16px;font-family: 宋体;">一直是我的一个遗憾，</span><span style="font-size: 16px;font-family: 宋体;">毕竟</span><span style="font-size: 16px;font-family: 宋体;">一步</span><span style="font-size: 16px;font-family: 宋体;">之差</span><span style="font-size: 16px;font-family: 宋体;">，</span><span style="font-size: 16px;font-family: 宋体;">我</span><span style="font-size: 16px;font-family: 宋体;">朋友靠着他的一个主站</span><span style="font-size: 16px;font-family: 宋体;">拿了</span><span style="font-size: 16px;font-family: 宋体;">投资上市新三板，</span><span style="font-size: 16px;font-family: 宋体;">而</span><span style="font-size: 16px;font-family: 宋体;">我</span><span style="font-size: 16px;font-family: 宋体;">，</span><span style="font-size: 16px;font-family: 宋体;">只</span><span style="font-size: 16px;font-family: 宋体;">能</span><span style="font-size: 16px;font-family: 宋体;">灰溜溜的收场。</span><span style="font-size: 16px;font-family: 宋体;">从这件事情</span><span style="font-size: 16px;font-family: 宋体;">后，</span><span style="font-size: 16px;font-family: 宋体;">我</span><span style="font-size: 16px;font-family: 宋体;">就</span><span style="font-size: 16px;font-family: 宋体;">很少</span><span style="font-size: 16px;font-family: 宋体;">把心思放在流量的挖掘上，而是</span><span style="font-size: 16px;font-family: 宋体;">一直</span><span style="font-size: 16px;font-family: 宋体;">在留存跟转化</span><span style="font-size: 16px;font-family: 宋体;">上下</span><span style="font-size: 16px;font-family: 宋体;">功夫学习</span><span style="font-size: 16px;font-family: 宋体;">，</span><span style="font-size: 16px;font-family: 宋体;">当然</span><span style="font-size: 16px;font-family: 宋体;">并不</span><span style="font-size: 16px;font-family: 宋体;">代表</span><span style="font-size: 16px;font-family: 宋体;">流量获取不重要。</span></span></p><p><span style="font-family: Calibri;font-size: 16px;">&nbsp;</span></p><p><span style="font-size: 16px;"><span style="font-size: 16px;font-family: 宋体;">这里</span><span style="font-size: 16px;font-family: 宋体;">想跟大家聊一聊</span><span style="font-size: 16px;font-family: 宋体;">关于</span><span style="font-size: 16px;font-family: 宋体;">转化，</span><span style="font-size: 16px;font-family: 宋体;">有</span><span style="font-size: 16px;font-family: 宋体;">很多朋友都会问，</span><span style="font-size: 16px;font-family: 宋体;">我</span><span style="font-size: 16px;font-family: 宋体;">手上</span><span style="font-size: 16px;font-family: 宋体;">有量</span><span style="font-size: 16px;font-family: 宋体;">，</span><span style="font-size: 16px;font-family: 宋体;">做</span><span style="font-size: 16px;font-family: 宋体;">联盟收益</span><span style="font-size: 16px;font-family: 宋体;">又</span><span style="font-size: 16px;font-family: 宋体;">很低，</span><span style="font-size: 16px;font-family: 宋体;">不</span><span style="font-size: 16px;font-family: 宋体;">知道</span><span style="font-size: 16px;font-family: 宋体;">做</span><span style="font-size: 16px;font-family: 宋体;">什么事情来变现</span><span style="font-size: 16px;font-family: 宋体;">。首先</span><span style="font-size: 16px;font-family: 宋体;">我</span><span style="font-size: 16px;font-family: 宋体;">分享一张</span><span style="font-size: 16px;font-family: 宋体;">大家之前可能</span><span style="font-size: 16px;font-family: 宋体;">都</span><span style="font-size: 16px;font-family: 宋体;">在</span><span style="font-size: 16px;font-family: 宋体;">朋友圈</span><span style="font-size: 16px;font-family: 宋体;">转发过的图：</span></span></p><p><img data-s="300,640" data-type="jpeg" src="<?=\view::src('img/sc/-4/01.jpg')?>" data-copyright="0" style="" class="" data-ratio="0.75" data-w="256"  /></p><p><span style="font-size: 16px;"><span style="font-size: 16px;font-family: 宋体;">虽然</span><span style="font-size: 16px;font-family: 宋体;">我们习惯说流量，但是本质</span><span style="font-size: 16px;font-family: 宋体;">流量</span><span style="font-size: 16px;font-family: 宋体;">等于人</span><span style="font-size: 16px;font-family: 宋体;">的</span><span style="font-size: 16px;font-family: 宋体;">注意力，</span><span style="font-size: 16px;font-family: 宋体;">而你</span><span style="font-size: 16px;font-family: 宋体;">能</span><span style="font-size: 16px;font-family: 宋体;">获取到</span><span style="font-size: 16px;font-family: 宋体;">的</span><span style="font-size: 16px;font-family: 宋体;">用户的属性</span><span style="font-size: 16px;font-family: 宋体;">，</span><span style="font-size: 16px;font-family: 宋体;">基本</span><span style="font-size: 16px;font-family: 宋体;">决定</span><span style="font-size: 16px;font-family: 宋体;">了</span><span style="font-size: 16px;font-family: 宋体;">你</span><span style="font-size: 16px;font-family: 宋体;">流量价值</span><span style="font-size: 16px;font-family: 宋体;">，</span><span style="font-size: 16px;font-family: 宋体;">但是</span><span style="font-size: 16px;font-family: 宋体;">还要参考</span><span style="font-size: 16px;font-family: 宋体;">一点，</span><span style="font-size: 16px;font-family: 宋体;">就是流量</span><span style="font-size: 16px;font-family: 宋体;">定向</span><span style="font-size: 16px;font-family: 宋体;">，</span><span style="font-size: 16px;font-family: 宋体;">也就是</span><span style="font-size: 16px;font-family: 宋体;">我们</span><span style="font-size: 16px;font-family: 宋体;">说</span><span style="font-size: 16px;font-family: 宋体;">的精准流量</span><span style="font-size: 16px;font-family: 宋体;">，这里</span><span style="font-size: 16px;font-family: 宋体;">我</span><span style="font-size: 16px;font-family: 宋体;">说</span><span style="font-size: 16px;font-family: 宋体;">两个</span><span style="font-size: 16px;font-family: 宋体;">维度</span><span style="font-size: 16px;font-family: 宋体;">，</span><span style="font-size: 16px;font-family: 宋体;">目的性</span><span style="font-size: 16px;font-family: 宋体;">，</span><span style="font-size: 16px;font-family: 宋体;">跟匹配度</span><span style="font-size: 16px;font-family: 宋体;">。</span><span style="font-size: 16px;font-family: 宋体;">这两个</span><span style="font-size: 16px;font-family: 宋体;">维度</span><span style="font-size: 16px;font-family: 宋体;">越高</span><span style="font-size: 16px;font-family: 宋体;">流量价值越大。</span></span></p><p><span style="font-family: 宋体;font-size: 16px;">&nbsp;</span></p><p><span style="font-size: 16px;"><span style="font-size: 16px;font-family: 宋体;">好比通过搜索</span><span style="font-size: 16px;font-family: 宋体;">引擎来的</span><span style="font-size: 16px;font-family: 宋体;">用户</span><span style="font-size: 16px;font-family: 宋体;">，</span><span style="font-size: 16px;font-family: 宋体;">其实访客</span><span style="font-size: 16px;font-family: 宋体;">都是具备</span><span style="font-size: 16px;font-family: 宋体;">很强</span><span style="font-size: 16px;font-family: 宋体;">目的性，</span><span style="font-size: 16px;font-family: 宋体;">也就是</span><span style="font-size: 16px;font-family: 宋体;">主动</span><span style="font-size: 16px;font-family: 宋体;">获取信息，所以</span><span style="font-size: 16px;font-family: 宋体;">这里</span><span style="font-size: 16px;font-family: 宋体;">其实</span><span style="font-size: 16px;font-family: 宋体;">他并不</span><span style="font-size: 16px;font-family: 宋体;">排斥</span><span style="font-size: 16px;font-family: 宋体;">广告，甚至很可能</span><span style="font-size: 16px;font-family: 宋体;">就是</span><span style="font-size: 16px;font-family: 宋体;">为了广告来的，</span><span style="font-size: 16px;font-family: 宋体;">那么这个时候，</span><span style="font-size: 16px;font-family: 宋体;">转化率</span><span style="font-size: 16px;font-family: 宋体;">很高</span><span style="font-size: 16px;font-family: 宋体;">，</span><span style="font-size: 16px;font-family: 宋体;">有限</span><span style="font-size: 16px;font-family: 宋体;">的流量</span><span style="font-size: 16px;font-family: 宋体;">要</span><span style="font-size: 16px;font-family: 宋体;">提高营收</span><span style="font-size: 16px;font-family: 宋体;">，一</span><span style="font-size: 16px;font-family: 宋体;">是</span><span style="font-size: 16px;font-family: 宋体;">做</span><span style="font-size: 16px;font-family: 宋体;">转化收益高的，</span><span style="font-size: 16px;font-family: 宋体;">二</span><span style="font-size: 16px;font-family: 宋体;">是做持续付费能力高</span><span style="font-size: 16px;font-family: 宋体;">的。再</span><span style="font-size: 16px;font-family: 宋体;">结合你的网站内容满足的需求</span><span style="font-size: 16px;font-family: 宋体;">，</span><span style="font-size: 16px;font-family: 宋体;">也就是关键词确定转化方向，</span><span style="font-family: 宋体;font-size: 14px;">&nbsp;<span style="font-size: 16px;font-family: 宋体;">假设</span></span><span style="font-size: 16px;font-family: 宋体;">你</span><span style="font-size: 16px;font-family: 宋体;">的</span><span style="font-size: 16px;font-family: 宋体;">用户留存</span><span style="font-size: 16px;font-family: 宋体;">低</span><span style="font-size: 16px;font-family: 宋体;">，那就</span><span style="font-size: 16px;font-family: 宋体;">做客单价</span><span style="font-size: 16px;font-family: 宋体;">高的，</span><span style="font-size: 16px;font-family: 宋体;">如果</span><span style="font-size: 16px;font-family: 宋体;">留存</span><span style="font-size: 16px;font-family: 宋体;">高，</span><span style="font-size: 16px;font-family: 宋体;">那我选择做持续付费</span><span style="font-size: 16px;font-family: 宋体;">，</span><span style="font-size: 16px;font-family: 宋体;">当然</span><span style="font-size: 16px;font-family: 宋体;">也要</span><span style="font-size: 16px;font-family: 宋体;">考虑</span><span style="font-size: 16px;font-family: 宋体;">用户</span><span style="font-size: 16px;font-family: 宋体;">的</span><span style="font-size: 16px;font-family: 宋体;">消费力</span><span style="font-size: 16px;font-family: 宋体;">。</span></span></p><p><span style="font-family: Calibri;font-size: 16px;">&nbsp;</span></p><p><span style="font-size: 16px;"><span style="font-size: 16px;font-family: 宋体;">而匹配度</span><span style="font-size: 16px;font-family: 宋体;">，</span><span style="font-size: 16px;font-family: 宋体;">就是你</span><span style="font-size: 16px;font-family: 宋体;">做的转化跟</span><span style="font-size: 16px;font-family: 宋体;">你</span><span style="font-size: 16px;font-family: 宋体;">的流量的契合程度，</span><span style="font-size: 16px;font-family: 宋体;">很多</span><span style="font-size: 16px;font-family: 宋体;">开过车</span><span style="font-size: 16px;font-family: 宋体;">跟做过广告</span><span style="font-size: 16px;font-family: 宋体;">投放的</span><span style="font-size: 16px;font-family: 宋体;">都</span><span style="font-size: 16px;font-family: 宋体;">清楚</span><span style="font-size: 16px;font-family: 宋体;">定向，为</span><span style="font-size: 16px;font-family: 宋体;">什么要</span><span style="font-size: 16px;font-family: 宋体;">做定向</span><span style="font-size: 16px;font-family: 宋体;">，就是为了</span><span style="font-size: 16px;font-family: 宋体;">提高转化</span><span style="font-size: 16px;font-family: 宋体;">。</span><span style="font-size: 16px;font-family: 宋体;">这里</span><span style="font-size: 16px;font-family: 宋体;">就需要</span><span style="font-size: 16px;font-family: 宋体;">清楚</span><span style="font-size: 16px;font-family: 宋体;">用户画像，我经常</span><span style="font-size: 16px;font-family: 宋体;">都</span><span style="font-size: 16px;font-family: 宋体;">会在</span><span style="font-size: 16px;font-family: 宋体;">一些诸如</span><span style="font-size: 16px;font-family: 宋体;">艾瑞</span><span style="font-size: 16px;font-family: 宋体;">咨询这种</span><span style="font-size: 16px;font-family: 宋体;">去看一些</span><span style="font-size: 16px;font-family: 宋体;">目前比较赚钱行业</span><span style="font-size: 16px;font-family: 宋体;">的用户画像，</span><span style="font-size: 16px;font-family: 宋体;">简单的</span><span style="font-size: 16px;font-family: 宋体;">说，</span><span style="font-size: 16px;font-family: 宋体;">就是性别</span><span style="font-size: 16px;font-family: 宋体;">地域</span><span style="font-size: 16px;font-family: 宋体;">年龄</span><span style="font-size: 16px;font-family: 宋体;">等等，</span><span style="font-size: 16px;font-family: 宋体;">然后根据</span><span style="font-size: 16px;font-family: 宋体;">这种定向去找</span><span style="font-size: 16px;font-family: 宋体;">一些匹配程度</span><span style="font-size: 16px;font-family: 宋体;">高的</span><span style="font-size: 16px;font-family: 宋体;">，本身</span><span style="font-size: 16px;font-family: 宋体;">不具备</span><span style="font-size: 16px;font-family: 宋体;">定向</span><span style="font-size: 16px;font-family: 宋体;">能力的这种流量</span><span style="font-size: 16px;font-family: 宋体;">，跟</span><span style="font-size: 16px;font-family: 宋体;">信息流相比，</span><span style="font-size: 16px;font-family: 宋体;">流量</span><span style="font-size: 16px;font-family: 宋体;">获取</span><span style="font-size: 16px;font-family: 宋体;">成本</span><span style="font-size: 16px;font-family: 宋体;">差</span><span style="font-size: 16px;font-family: 宋体;">别</span><span style="font-size: 16px;font-family: 宋体;">很大</span><span style="font-size: 16px;font-family: 宋体;">，如果</span><span style="font-size: 16px;font-family: 宋体;">你具备</span><span style="font-size: 16px;font-family: 宋体;">鉴定</span><span style="font-size: 16px;font-family: 宋体;">流量</span><span style="font-size: 16px;font-family: 宋体;">能力</span><span style="font-size: 16px;font-family: 宋体;">，</span><span style="font-size: 16px;font-family: 宋体;">获取成本</span><span style="font-size: 16px;font-family: 宋体;">足以抵消</span><span style="font-size: 16px;font-family: 宋体;">转化</span><span style="font-size: 16px;font-family: 宋体;">偏差</span><span style="font-size: 16px;font-family: 宋体;">。而诸如</span><span style="font-family: Calibri;font-size: 14px;"><span style="font-size: 16px;font-family: 宋体;">像某些微商</span>120</span><span style="font-size: 16px;font-family: 宋体;">块</span><span style="font-size: 16px;font-family: 宋体;">获取一个</span><span style="font-size: 16px;font-family: 宋体;">微信</span><span style="font-size: 16px;font-family: 宋体;">好友，亦或者</span><span style="font-family: 宋体;font-size: 14px;"><span style="font-size: 16px;font-family: 宋体;">小额贷</span>100<span style="font-size: 16px;font-family: 宋体;">块</span></span><span style="font-size: 16px;font-family: 宋体;">左右</span><span style="font-size: 16px;font-family: 宋体;">一个意向</span><span style="font-size: 16px;font-family: 宋体;">用户</span><span style="font-size: 16px;font-family: 宋体;">的</span><span style="font-size: 16px;font-family: 宋体;">转化，</span><span style="font-size: 16px;font-family: 宋体;">只要</span><span style="font-size: 16px;font-family: 宋体;">你能掌握</span><span style="font-size: 16px;font-family: 宋体;">住</span><span style="font-size: 16px;font-family: 宋体;">这种信息差，不一定</span><span style="font-size: 16px;font-family: 宋体;">要</span><span style="font-size: 16px;font-family: 宋体;">手握流量</span><span style="font-size: 16px;font-family: 宋体;">，也</span><span style="font-size: 16px;font-family: 宋体;">能兴风作浪。</span></span></p><p><span style="font-family: Calibri;font-size: 16px;">&nbsp;</span></p><p><span style="font-size: 16px;"><span style="font-size: 16px;font-family: 宋体;">而</span><span style="font-size: 16px;font-family: 宋体;">作</span><span style="font-size: 16px;font-family: 宋体;">为</span><span style="font-size: 16px;font-family: 宋体;">流量主，通过这两</span><span style="font-size: 16px;font-family: 宋体;">个</span><span style="font-size: 16px;font-family: 宋体;">维度</span><span style="font-size: 16px;font-family: 宋体;">来</span><span style="font-size: 16px;font-family: 宋体;">倒推自己适合</span><span style="font-size: 16px;font-family: 宋体;">做</span><span style="font-size: 16px;font-family: 宋体;">的转化，</span><span style="font-size: 16px;font-family: 宋体;">也</span><span style="font-size: 16px;font-family: 宋体;">很适用。</span><span style="font-size: 16px;font-family: 宋体;">流</span><span style="font-size: 16px;font-family: 宋体;">需</span><span style="font-size: 16px;font-family: 宋体;">量的</span><span style="font-size: 16px;font-family: 宋体;">多少是一</span><span style="font-size: 16px;font-family: 宋体;">方面</span><span style="font-size: 16px;font-family: 宋体;">，另一方面</span><span style="font-size: 16px;font-family: 宋体;">，</span><span style="font-size: 16px;font-family: 宋体;">你</span><span style="font-size: 16px;font-family: 宋体;">清楚现在访问你</span><span style="font-size: 16px;font-family: 宋体;">的用户他</span><span style="font-size: 16px;font-family: 宋体;">的需求</span><span style="font-size: 16px;font-family: 宋体;">是什么吗，</span><span style="font-size: 16px;font-family: 宋体;">那么</span><span style="font-size: 16px;font-family: 宋体;">根据这种求我能找到哪些转化</span><span style="font-size: 16px;font-family: 宋体;">方式</span><span style="font-size: 16px;font-family: 宋体;">。</span><span style="font-size: 16px;font-family: 宋体;">又</span><span style="font-size: 16px;font-family: 宋体;">或者</span><span style="font-size: 16px;font-family: 宋体;">我现有的</span><span style="font-size: 16px;font-family: 宋体;">用户的</span><span style="font-size: 16px;font-family: 宋体;">画像</span><span style="font-size: 16px;font-family: 宋体;">他跟</span><span style="font-size: 16px;font-family: 宋体;">哪些</span><span style="font-size: 16px;font-family: 宋体;">赚钱行业</span><span style="font-size: 16px;font-family: 宋体;">的</span><span style="font-size: 16px;font-family: 宋体;">画像重叠，我能不能试</span><span style="font-size: 16px;font-family: 宋体;">一试？而不</span><span style="font-size: 16px;font-family: 宋体;">是一味单纯的找联盟。</span></span></p><p><span style="font-family: Calibri;font-size: 16px;">&nbsp;</span></p><p><span style="font-family: 黑体;font-size: 16px;">&nbsp;</span></p><p><span style="font-size: 16px;"><strong><span style="font-family: 黑体;font-size: 21px;">H5游戏项目</span></strong></span></p><hr  /><p><span style="font-size: 16px;"><strong><span style="font-family: 黑体;font-size: 21px;"></span></strong></span><br  /></p><p><span style="font-size: 16px;"><span style="font-family: 宋体;font-size: 14px;">14<span style="font-size: 16px;font-family: 宋体;">年</span><span style="font-size: 16px;font-family: Calibri;">15</span><span style="font-size: 16px;font-family: 宋体;">年移动</span></span><span style="font-size: 16px;font-family: 宋体;">互联网</span><span style="font-size: 16px;font-family: 宋体;">开始爆发</span><span style="font-size: 16px;font-family: 宋体;">，</span><span style="font-size: 16px;font-family: 宋体;">我</span><span style="font-size: 16px;font-family: 宋体;">也</span><span style="font-size: 16px;font-family: 宋体;">开始</span><span style="font-size: 16px;font-family: 宋体;">了我的</span><span style="font-size: 16px;font-family: 宋体;">踩坑</span><span style="font-size: 16px;font-family: 宋体;">之路，</span><span style="font-size: 16px;font-family: 宋体;">两年多</span><span style="font-size: 16px;font-family: 宋体;">里</span><span style="font-size: 16px;font-family: 宋体;">做</span><span style="font-size: 16px;font-family: 宋体;">了好几个项目，</span><span style="font-size: 16px;font-family: 宋体;">交了很多</span><span style="font-size: 16px;font-family: 宋体;">学费。</span><span style="font-size: 16px;font-family: 宋体;">从</span><span style="font-size: 16px;font-family: 宋体;">公众号到</span><span style="font-family: 宋体;font-size: 14px;">020<span style="font-size: 16px;font-family: 宋体;">到手游</span></span><span style="font-size: 16px;font-family: 宋体;">到</span><span style="font-size: 16px;font-family: 宋体;">直播再到电商，那个</span><span style="font-size: 16px;font-family: 宋体;">时候还是比较</span><span style="font-size: 16px;font-family: 宋体;">浮躁</span><span style="font-size: 16px;font-family: 宋体;">的，</span><span style="font-size: 16px;font-family: 宋体;">比方说当时做</span><span style="font-size: 16px;font-family: 宋体;">了一个</span><span style="font-size: 16px;font-family: 宋体;">针对</span><span style="font-size: 16px;font-family: 宋体;">大学生的一个查</span><span style="font-size: 16px;font-family: 宋体;">成绩的</span><span style="font-size: 16px;font-family: 宋体;">这么一个</span><span style="font-size: 16px;font-family: 宋体;">服务号</span><span style="font-size: 16px;font-family: 宋体;">，</span><span style="font-size: 16px;font-family: 宋体;">短时间内</span><span style="font-size: 16px;font-family: 宋体;">有很多关注。</span><span style="font-size: 16px;font-family: 宋体;">照理</span><span style="font-size: 16px;font-family: 宋体;">说我应该</span><span style="font-size: 16px;font-family: 宋体;">继续挖掘</span><span style="font-size: 16px;font-family: 宋体;">一些</span><span style="font-size: 16px;font-family: 宋体;">需求</span><span style="font-size: 16px;font-family: 宋体;">来完善这个产品，之后再去试转化，去复制。</span><span style="font-size: 16px;font-family: 宋体;">但是</span><span style="font-size: 16px;font-family: 宋体;">当时</span><span style="font-size: 16px;font-family: 宋体;">没有</span><span style="font-size: 16px;font-family: 宋体;">，</span><span style="font-size: 16px;font-family: 宋体;">而</span><span style="font-size: 16px;font-family: 宋体;">是</span><span style="font-size: 16px;font-family: 宋体;">想</span><span style="font-size: 16px;font-family: 宋体;">做</span><span style="font-size: 16px;font-family: 宋体;">一个</span><span style="font-family: Calibri;font-size: 14px;">“</span><span style="font-family: 宋体;font-size: 14px;"><span style="font-size: 16px;font-family: 宋体;">校园</span>58</span><span style="font-family: Calibri;font-size: 14px;">”</span><span style="font-size: 16px;font-family: 宋体;">，</span><span style="font-size: 16px;font-family: 宋体;">把订餐，兼职，</span><span style="font-size: 16px;font-family: 宋体;">二手那些都</span><span style="font-size: 16px;font-family: 宋体;">容纳进去</span><span style="font-size: 16px;font-family: 宋体;">，花</span><span style="font-size: 16px;font-family: 宋体;">了很多资源精力之后理所应当的</span><span style="font-size: 16px;font-family: 宋体;">亏</span><span style="font-size: 16px;font-family: 宋体;">了</span><span style="font-size: 16px;font-family: 宋体;">。</span></span></p><p><span style="font-family: Calibri;font-size: 16px;">&nbsp;</span></p><p><span style="font-size: 16px;"><span style="font-size: 16px;font-family: 宋体;">那段时间</span><span style="font-size: 16px;font-family: 宋体;">算我人生低潮期，</span><span style="font-size: 16px;font-family: 宋体;">之后</span><span style="font-size: 16px;font-family: 宋体;">我</span><span style="font-size: 16px;font-family: 宋体;">就</span><span style="font-size: 16px;font-family: 宋体;">把</span><span style="font-size: 16px;font-family: 宋体;">我</span><span style="font-size: 16px;font-family: 宋体;">剩下的钱买了</span><span style="font-size: 16px;font-family: 宋体;">套</span><span style="font-size: 16px;font-family: 宋体;">房子，</span><span style="font-size: 16px;font-family: 宋体;">刚好那时候有家</span><span style="font-size: 16px;font-family: 宋体;">之前合作过的</span><span style="font-size: 16px;font-family: 宋体;">公司</span><span style="font-size: 16px;font-family: 宋体;">邀请我去上班，我</span><span style="font-size: 16px;font-family: 宋体;">开始了</span><span style="font-size: 16px;font-family: 宋体;">我的职场生涯，</span><span style="font-size: 16px;font-family: 宋体;">期间</span><span style="font-size: 16px;font-family: 宋体;">我学到很多像我这种</span><span style="font-size: 16px;font-family: 宋体;">修野狐禅</span><span style="font-size: 16px;font-family: 宋体;">学不到</span><span style="font-size: 16px;font-family: 宋体;">东西</span><span style="font-size: 16px;font-family: 宋体;">，</span><span style="font-size: 16px;font-family: 宋体;">视野</span><span style="font-size: 16px;font-family: 宋体;">也大</span><span style="font-size: 16px;font-family: 宋体;">了</span><span style="font-size: 16px;font-family: 宋体;">很多。</span></span></p><p><span style="font-family: Calibri;font-size: 16px;">&nbsp;</span></p><p><span style="font-size: 16px;"><span style="font-size: 16px;font-family: 宋体;">比方</span><span style="font-size: 16px;font-family: 宋体;">说</span><span style="font-size: 16px;font-family: 宋体;">数据</span><span style="font-size: 16px;font-family: 宋体;">，</span><span style="font-size: 16px;font-family: 宋体;">游戏</span><span style="font-size: 16px;font-family: 宋体;">公司对数据</span><span style="font-size: 16px;font-family: 宋体;">很敏感</span><span style="font-size: 16px;font-family: 宋体;">。从用户</span><span style="font-size: 16px;font-family: 宋体;">接触</span><span style="font-size: 16px;font-family: 宋体;">面到</span><span style="font-size: 16px;font-family: 宋体;">最终收益</span><span style="font-size: 16px;font-family: 宋体;">的</span><span style="font-size: 16px;font-family: 宋体;">数据埋点</span><span style="font-size: 16px;font-family: 宋体;">就有</span><span style="font-family: 宋体;font-size: 14px;">20<span style="font-size: 16px;font-family: 宋体;">多</span></span><span style="font-size: 16px;font-family: 宋体;">个。</span><span style="font-size: 16px;font-family: 宋体;">因为</span><span style="font-size: 16px;font-family: 宋体;">要尽可能的去提高</span><span style="font-size: 16px;font-family: 宋体;">我</span><span style="font-size: 16px;font-family: 宋体;">的收益，</span><span style="font-size: 16px;font-family: 宋体;">除了</span><span style="font-size: 16px;font-family: 宋体;">流量</span><span style="font-size: 16px;font-family: 宋体;">成本</span><span style="font-size: 16px;font-family: 宋体;">，还有一点就是转化率，</span><span style="font-size: 16px;font-family: 宋体;">那</span><span style="font-size: 16px;font-family: 宋体;">提高转化率</span><span style="font-size: 16px;font-family: 宋体;">的手段</span><span style="font-size: 16px;font-family: 宋体;">就是通过数据来优化，这其实就是后面我做</span><span style="font-family: 宋体;font-size: 14px;">H5<span style="font-size: 16px;font-family: 宋体;">游戏</span></span><span style="font-size: 16px;font-family: 宋体;">的</span><span style="font-size: 16px;font-family: 宋体;">一个</span><span style="font-size: 16px;font-family: 宋体;">壁垒，后面</span><span style="font-size: 16px;font-family: 宋体;">会</span><span style="font-size: 16px;font-family: 宋体;">细说。</span></span></p><p><span style="font-family: Calibri;font-size: 16px;">&nbsp;</span></p><p><span style="font-size: 16px;"><span style="font-family: 宋体;font-size: 14px;"><span style="font-size: 16px;font-family: 宋体;">在</span>1</span><span style="font-family: Calibri;font-size: 14px;">6</span><span style="font-size: 16px;font-family: 宋体;">年初</span><span style="font-size: 16px;font-family: 宋体;">的时候，</span><span style="font-size: 16px;font-family: 宋体;">我</span><span style="font-size: 16px;font-family: 宋体;">到上海跟一家</span><span style="font-size: 16px;font-family: 宋体;">公司签约</span><span style="font-size: 16px;font-family: 宋体;">，</span><span style="font-size: 16px;font-family: 宋体;">那个</span><span style="font-size: 16px;font-family: 宋体;">时候意外知道了</span><span style="font-family: 宋体;font-size: 14px;">H5<span style="font-size: 16px;font-family: 宋体;">游戏</span></span><span style="font-size: 16px;font-family: 宋体;">，</span><span style="font-size: 16px;font-family: 宋体;">简单</span><span style="font-size: 16px;font-family: 宋体;">的说就是</span><span style="font-size: 16px;font-family: 宋体;">手机页游。我</span><span style="font-size: 16px;font-family: 宋体;">当时</span><span style="font-size: 16px;font-family: 宋体;">了解之后</span><span style="font-size: 16px;font-family: 宋体;">迅速明白</span><span style="font-size: 16px;font-family: 宋体;">这个东西</span><span style="font-size: 16px;font-family: 宋体;">的价值</span><span style="font-size: 16px;font-family: 宋体;">，这里</span><span style="font-size: 16px;font-family: 宋体;">简单</span><span style="font-size: 16px;font-family: 宋体;">跟大家说</span><span style="font-size: 16px;font-family: 宋体;">一下。</span></span></p><p><span style="font-family: Calibri;font-size: 16px;">&nbsp;</span></p><p><span style="font-size: 16px;"><span style="font-family: 宋体;font-size: 14px;"><span style="font-size: 16px;font-family: 宋体;">关于</span>H5<span style="font-size: 16px;font-family: 宋体;">，简单</span></span><span style="font-size: 16px;font-family: 宋体;">的说就是</span><span style="font-family: 宋体;font-size: 14px;">HTML<span style="font-size: 16px;font-family: 宋体;">的</span></span><span style="font-size: 16px;font-family: 宋体;">第</span><span style="font-family: 宋体;font-size: 14px;">5<span style="font-size: 16px;font-family: 宋体;">个</span></span><span style="font-size: 16px;font-family: 宋体;">版本，应用</span><span style="font-size: 16px;font-family: 宋体;">比较</span><span style="font-size: 16px;font-family: 宋体;">广泛，</span><span style="font-size: 16px;font-family: 宋体;">大家</span><span style="font-size: 16px;font-family: 宋体;">熟知应该是</span><span style="font-size: 16px;font-family: 宋体;">微信</span><span style="font-family: Calibri;font-size: 14px;"><span style="font-size: 16px;font-family: 宋体;">上传播</span>i</span><span style="font-size: 16px;font-family: 宋体;">很广泛</span><span style="font-size: 16px;font-family: 宋体;">的</span><span style="font-family: 宋体;font-size: 14px;"><span style="font-size: 16px;font-family: 宋体;">一些</span>H5<span style="font-size: 16px;font-family: 宋体;">营销</span></span><span style="font-size: 16px;font-family: 宋体;">案例，</span><span style="font-size: 16px;font-family: 宋体;">因为</span><span style="font-size: 16px;font-family: 宋体;">他很便于</span><span style="font-size: 16px;font-family: 宋体;">在社交媒体</span><span style="font-size: 16px;font-family: 宋体;">上传播，</span><span style="font-size: 16px;font-family: 宋体;">所以我</span><span style="font-size: 16px;font-family: 宋体;">当时觉得</span><span style="font-size: 16px;font-family: 宋体;">，如果</span><span style="font-size: 16px;font-family: 宋体;">是</span><span style="font-family: 宋体;font-size: 14px;">H5<span style="font-size: 16px;font-family: 宋体;">做</span></span><span style="font-size: 16px;font-family: 宋体;">的游戏，应该是有机会的。</span><span style="font-size: 16px;font-family: 宋体;">此外</span><span style="font-size: 16px;font-family: 宋体;">，他的开发</span><span style="font-size: 16px;font-family: 宋体;">门槛也</span><span style="font-size: 16px;font-family: 宋体;">比较低</span><span style="font-size: 16px;font-family: 宋体;">，</span><span style="font-size: 16px;font-family: 宋体;">得益于此，我在</span><span style="font-size: 16px;font-family: 宋体;">后期</span><span style="font-size: 16px;font-family: 宋体;">其实做了很多</span><span style="font-family: 宋体;font-size: 14px;">H5<span style="font-size: 16px;font-family: 宋体;">形式</span></span><span style="font-size: 16px;font-family: 宋体;">的</span><span style="font-size: 16px;font-family: 宋体;">产品</span><span style="font-size: 16px;font-family: 宋体;">来做</span><span style="font-size: 16px;font-family: 宋体;">转化</span><span style="font-size: 16px;font-family: 宋体;">，比方说</span><span style="font-size: 16px;font-family: 宋体;">转化</span><span style="font-size: 16px;font-family: 宋体;">最好的</span><span style="font-size: 16px;font-family: 宋体;">付费</span><span style="font-size: 16px;font-family: 宋体;">算命</span><span style="font-size: 16px;font-family: 宋体;">，</span><span style="font-size: 16px;font-family: 宋体;">付费塔罗牌</span><span style="font-size: 16px;font-family: 宋体;">占卜</span><span style="font-size: 16px;font-family: 宋体;">等</span><span style="font-size: 16px;font-family: 宋体;">。最大</span><span style="font-size: 16px;font-family: 宋体;">的价</span><span style="font-size: 16px;font-family: 宋体;">值</span><span style="font-size: 16px;font-family: 宋体;">是他为</span><span style="font-size: 16px;font-family: 宋体;">一些类似</span><span style="font-size: 16px;font-family: 宋体;">像公众号这种提供了新</span><span style="font-size: 16px;font-family: 宋体;">的收益</span><span style="font-size: 16px;font-family: 宋体;">方式。</span><span style="font-size: 16px;font-family: 宋体;">而</span><span style="font-size: 16px;font-family: 宋体;">目前除了金字塔顶端的</span><span style="font-size: 16px;font-family: 宋体;">公众号</span><span style="font-size: 16px;font-family: 宋体;">之外，</span><span style="font-size: 16px;font-family: 宋体;">大部分</span><span style="font-size: 16px;font-family: 宋体;">公众号的变现方式</span><span style="font-size: 16px;font-family: 宋体;">其实</span><span style="font-size: 16px;font-family: 宋体;">很</span><span style="font-size: 16px;font-family: 宋体;">有限</span><span style="font-size: 16px;font-family: 宋体;">。</span><span style="font-size: 16px;font-family: 宋体;">这里</span><span style="font-size: 16px;font-family: 宋体;">还有很大的挖掘空间，</span><span style="font-size: 16px;font-family: 宋体;">特别</span><span style="font-size: 16px;font-family: 宋体;">是在</span><span style="font-size: 16px;font-family: 宋体;">小程序现在也</span><span style="font-size: 16px;font-family: 宋体;">能接入</span><span style="font-family: 宋体;font-size: 14px;">H5<span style="font-size: 16px;font-family: 宋体;">，</span></span><span style="font-size: 16px;font-family: 宋体;">如果大家有什么好的想法，也可以私下里找我聊聊。</span></span></p><p><span style="font-family: Calibri;font-size: 16px;">&nbsp;</span></p><p><span style="font-size: 16px;"><span style="font-size: 16px;font-family: 宋体;">当时</span><span style="font-size: 16px;font-family: 宋体;">选择做</span><span style="font-family: 宋体;font-size: 14px;">H5<span style="font-size: 16px;font-family: 宋体;">游戏</span></span><span style="font-size: 16px;font-family: 宋体;">是因为</span><span style="font-size: 16px;font-family: 宋体;">觉得游戏的持续</span><span style="font-size: 16px;font-family: 宋体;">付费力是最强的，然后</span><span style="font-size: 16px;font-family: 宋体;">我</span><span style="font-size: 16px;font-family: 宋体;">开始想，</span><span style="font-size: 16px;font-family: 宋体;">我</span><span style="font-size: 16px;font-family: 宋体;">应该是在这条产业链的哪个位置</span><span style="font-size: 16px;font-family: 宋体;">。简单</span><span style="font-size: 16px;font-family: 宋体;">的介绍下</span><span style="font-family: 宋体;font-size: 14px;">H5<span style="font-size: 16px;font-family: 宋体;">游戏</span></span><span style="font-size: 16px;font-family: 宋体;">的产业链</span><span style="font-size: 16px;font-family: 宋体;">，</span><span style="font-size: 16px;font-family: 宋体;">同</span><span style="font-size: 16px;font-family: 宋体;">别的</span><span style="font-size: 16px;font-family: 宋体;">游戏一样，有研发方，发行方，渠道方</span><span style="font-size: 16px;font-family: 宋体;">。研发方做</span><span style="font-size: 16px;font-family: 宋体;">游戏交给</span><span style="font-size: 16px;font-family: 宋体;">发行方</span><span style="font-size: 16px;font-family: 宋体;">代理，</span><span style="font-size: 16px;font-family: 宋体;">发行方再分发给渠道方。</span></span></p><p><span style="font-family: Calibri;font-size: 16px;">&nbsp;</span></p><p><span style="font-size: 16px;"><span style="font-size: 16px;font-family: 宋体;">尽管开发</span><span style="font-size: 16px;font-family: 宋体;">门槛很低，但是我当时不具备去做一个</span><span style="font-family: 宋体;font-size: 14px;">H5<span style="font-size: 16px;font-family: 宋体;">游戏</span></span><span style="font-size: 16px;font-family: 宋体;">的</span><span style="font-size: 16px;font-family: 宋体;">资源</span><span style="font-size: 16px;font-family: 宋体;">，</span><span style="font-size: 16px;font-family: 宋体;">更</span><span style="font-size: 16px;font-family: 宋体;">不要说</span><span style="font-size: 16px;font-family: 宋体;">发行</span><span style="font-size: 16px;font-family: 宋体;">，所以我</span><span style="font-size: 16px;font-family: 宋体;">只能</span><span style="font-size: 16px;font-family: 宋体;">做渠道方。</span><span style="font-size: 16px;font-family: 宋体;">那么</span><span style="font-size: 16px;font-family: 宋体;">流量</span><span style="font-size: 16px;font-family: 宋体;">哪里</span><span style="font-size: 16px;font-family: 宋体;">来呢，</span><span style="font-size: 16px;font-family: 宋体;">我</span><span style="font-size: 16px;font-family: 宋体;">手上</span><span style="font-size: 16px;font-family: 宋体;">除了</span><span style="font-size: 16px;font-family: 宋体;">有一点</span><span style="font-family: 宋体;font-size: 14px;">PC<span style="font-size: 16px;font-family: 宋体;">端</span></span><span style="font-size: 16px;font-family: 宋体;">的量之外，没有公众号，也没有</span><span style="font-size: 16px;font-family: 宋体;">移动量</span><span style="font-size: 16px;font-family: 宋体;">。</span><span style="font-size: 16px;font-family: 宋体;">所以</span><span style="font-size: 16px;font-family: 宋体;">我</span><span style="font-size: 16px;font-family: 宋体;">前期</span><span style="font-size: 16px;font-family: 宋体;">只能先</span><span style="font-size: 16px;font-family: 宋体;">做接口</span><span style="font-size: 16px;font-family: 宋体;">，</span><span style="font-size: 16px;font-family: 宋体;">利用</span><span style="font-size: 16px;font-family: 宋体;">信息差</span><span style="font-size: 16px;font-family: 宋体;">先赚</span><span style="font-size: 16px;font-family: 宋体;">点钱</span><span style="font-size: 16px;font-family: 宋体;">。</span></span></p><p><span style="font-family: Calibri;font-size: 16px;">&nbsp;</span></p><p><span style="font-size: 16px;"><span style="font-family: 宋体;font-size: 14px;">H5<span style="font-size: 16px;font-family: 宋体;">游戏目前</span></span><span style="font-size: 16px;font-family: 宋体;">的分发形式</span><span style="font-size: 16px;font-family: 宋体;">主要</span><span style="font-size: 16px;font-family: 宋体;">是以收益</span><span style="font-size: 16px;font-family: 宋体;">分成为主</span><span style="font-size: 16px;font-family: 宋体;">，</span><span style="font-size: 16px;font-family: 宋体;">你</span><span style="font-size: 16px;font-family: 宋体;">要是</span><span style="font-size: 16px;font-family: 宋体;">流量足够</span><span style="font-size: 16px;font-family: 宋体;">你可以选</span><span style="font-size: 16px;font-family: 宋体;">联运</span><span style="font-size: 16px;font-family: 宋体;">。</span><span style="font-size: 16px;font-family: 宋体;">这里</span><span style="font-size: 16px;font-family: 宋体;">说一下</span><span style="font-family: 宋体;font-size: 14px;">CPS<span style="font-size: 16px;font-family: 宋体;">跟</span></span><span style="font-size: 16px;font-family: 宋体;">联运的</span><span style="font-size: 16px;font-family: 宋体;">不同</span><span style="font-size: 16px;font-family: 宋体;">，</span><span style="font-size: 16px;font-family: 宋体;">本质</span><span style="font-size: 16px;font-family: 宋体;">的区别</span><span style="font-size: 16px;font-family: 宋体;">有</span><span style="font-size: 16px;font-family: 宋体;">两个，</span><span style="font-size: 16px;font-family: 宋体;">支付通道跟分发平台</span><span style="font-size: 16px;font-family: 宋体;">。</span><span style="font-size: 16px;font-family: 宋体;">简单的说</span><span style="font-size: 16px;font-family: 宋体;">，如果是</span><span style="font-family: 宋体;font-size: 14px;">CPS<span style="font-size: 16px;font-family: 宋体;">，</span></span><span style="font-size: 16px;font-family: 宋体;">就是接的</span><span style="font-size: 16px;font-family: 宋体;">对方</span><span style="font-size: 16px;font-family: 宋体;">的</span><span style="font-size: 16px;font-family: 宋体;">分发</span><span style="font-size: 16px;font-family: 宋体;">平台，如果</span><span style="font-size: 16px;font-family: 宋体;">是联运</span><span style="font-size: 16px;font-family: 宋体;">，就是自己</span><span style="font-size: 16px;font-family: 宋体;">手上</span><span style="font-size: 16px;font-family: 宋体;">有分发平台</span><span style="font-size: 16px;font-family: 宋体;">来</span><span style="font-size: 16px;font-family: 宋体;">接入</span><span style="font-size: 16px;font-family: 宋体;">游戏</span><span style="font-size: 16px;font-family: 宋体;">，</span><span style="font-size: 16px;font-family: 宋体;">再</span><span style="font-size: 16px;font-family: 宋体;">做分发，支付通道</span><span style="font-size: 16px;font-family: 宋体;">也在自己手上。</span><span style="font-size: 16px;font-family: 宋体;">而</span><span style="font-size: 16px;font-family: 宋体;">分成</span><span style="font-size: 16px;font-family: 宋体;">比例大概</span><span style="font-size: 16px;font-family: 宋体;">常规</span><span style="font-size: 16px;font-family: 宋体;">是</span><span style="font-family: 宋体;font-size: 14px;">55<span style="font-size: 16px;font-family: 宋体;">，</span><span style="font-size: 16px;font-family: Calibri;">CPS46</span><span style="font-size: 16px;font-family: 宋体;">居多</span></span><span style="font-size: 16px;font-family: 宋体;">，</span><span style="font-size: 16px;font-family: 宋体;">我</span><span style="font-size: 16px;font-family: 宋体;">最高</span><span style="font-size: 16px;font-family: 宋体;">谈下来</span><span style="font-family: Calibri;font-size: 14px;"><span style="font-size: 16px;font-family: 宋体;">的是</span>73</span><span style="font-size: 16px;font-family: 宋体;">，</span><span style="font-size: 16px;font-family: 宋体;">当然前提是我每个月的流水已经</span><span style="font-size: 16px;font-family: 宋体;">接近</span><span style="font-size: 16px;font-family: 宋体;">百万。</span></span></p><p><span style="font-family: Calibri;font-size: 16px;">&nbsp;</span></p><p><span style="font-size: 16px;"><span style="font-size: 16px;font-family: 宋体;">我最开始</span><span style="font-size: 16px;font-family: 宋体;">选择</span><span style="font-family: 宋体;font-size: 14px;"><span style="font-size: 16px;font-family: 宋体;">是</span>CPS<span style="font-size: 16px;font-family: 宋体;">接入</span></span><span style="font-size: 16px;font-family: 宋体;">，</span><span style="font-family: 宋体;font-size: 14px;">CPS<span style="font-size: 16px;font-family: 宋体;">还是</span></span><span style="font-size: 16px;font-family: 宋体;">比较好拿的，</span><span style="font-size: 16px;font-family: 宋体;">联系</span><span style="font-size: 16px;font-family: 宋体;">了几个厂商，</span><span style="font-size: 16px;font-family: 宋体;">就拿到</span><span style="font-size: 16px;font-family: 宋体;">了几个产品，</span><span style="font-size: 16px;font-family: 宋体;">接下</span><span style="font-size: 16px;font-family: 宋体;">的就是</span><span style="font-size: 16px;font-family: 宋体;">流量。一开始</span><span style="font-size: 16px;font-family: 宋体;">是没有</span><span style="font-size: 16px;font-family: 宋体;">注意到</span><span style="font-size: 16px;font-family: 宋体;">公众号这块的，</span><span style="font-size: 16px;font-family: 宋体;">因为当时</span><span style="font-size: 16px;font-family: 宋体;">很多</span><span style="font-family: 宋体;font-size: 14px;">H5<span style="font-size: 16px;font-family: 宋体;">都</span></span><span style="font-size: 16px;font-family: 宋体;">是</span><span style="font-size: 16px;font-family: 宋体;">寄生在</span><span style="font-size: 16px;font-family: 宋体;">一些</span><span style="font-family: 宋体;font-size: 14px;">APP<span style="font-size: 16px;font-family: 宋体;">上</span></span><span style="font-size: 16px;font-family: 宋体;">，</span><span style="font-size: 16px;font-family: 宋体;">像之前</span><span style="font-size: 16px;font-family: 宋体;">饿了么</span><span style="font-size: 16px;font-family: 宋体;">，招商银行</span><span style="font-size: 16px;font-family: 宋体;">信用卡</span><span style="font-family: 宋体;font-size: 14px;">APP<span style="font-size: 16px;font-family: 宋体;">，</span></span><span style="font-size: 16px;font-family: 宋体;">等等。</span><span style="font-family: 宋体;font-size: 14px;"><span style="font-size: 16px;font-family: 宋体;">找</span>APP<span style="font-size: 16px;font-family: 宋体;">合作</span></span><span style="font-size: 16px;font-family: 宋体;">我当时的思路是，找一些变现难，</span><span style="font-size: 16px;font-family: 宋体;">流量最好</span><span style="font-size: 16px;font-family: 宋体;">还杂，</span><span style="font-size: 16px;font-family: 宋体;">做</span><span style="font-size: 16px;font-family: 宋体;">不了定向或者说</span><span style="font-size: 16px;font-family: 宋体;">流量没有</span><span style="font-size: 16px;font-family: 宋体;">价值</span><span style="font-size: 16px;font-family: 宋体;">的</span><span style="font-size: 16px;font-family: 宋体;">产品。</span><span style="font-size: 16px;font-family: 宋体;">一些</span><span style="font-family: Calibri;font-size: 14px;">wifi<span style="font-size: 16px;font-family: 宋体;">工具类就</span></span><span style="font-size: 16px;font-family: 宋体;">属于</span><span style="font-size: 16px;font-family: 宋体;">这种。</span></span></p><p><span style="font-family: Calibri;font-size: 16px;">&nbsp;</span></p><p><span style="font-size: 16px;"><span style="font-size: 16px;font-family: 宋体;">我在几个</span><span style="font-size: 16px;font-family: 宋体;">应用市场</span><span style="font-size: 16px;font-family: 宋体;">找</span><span style="font-family: Calibri;font-size: 14px;"><span style="font-size: 16px;font-family: 宋体;">了大概一百多个</span>APP</span><span style="font-size: 16px;font-family: 宋体;">，能</span><span style="font-size: 16px;font-family: 宋体;">找到</span><span style="font-size: 16px;font-family: 宋体;">商务</span><span style="font-size: 16px;font-family: 宋体;">的大概有</span><span style="font-size: 16px;font-family: 宋体;">七八十个</span><span style="font-size: 16px;font-family: 宋体;">，</span><span style="font-size: 16px;font-family: 宋体;">当时由于</span><span style="font-size: 16px;font-family: 宋体;">有信息差，很多</span><span style="font-size: 16px;font-family: 宋体;">变现</span><span style="font-size: 16px;font-family: 宋体;">难的</span><span style="font-size: 16px;font-family: 宋体;">产品</span><span style="font-size: 16px;font-family: 宋体;">其实</span><span style="font-size: 16px;font-family: 宋体;">都</span><span style="font-size: 16px;font-family: 宋体;">还算比较好谈，</span><span style="font-size: 16px;font-family: 宋体;">所以</span><span style="font-size: 16px;font-family: 宋体;">很快</span><span style="font-size: 16px;font-family: 宋体;">就</span><span style="font-size: 16px;font-family: 宋体;">谈</span><span style="font-size: 16px;font-family: 宋体;">成</span><span style="font-size: 16px;font-family: 宋体;">了几个。</span><span style="font-size: 16px;font-family: 宋体;">因为我</span><span style="font-size: 16px;font-family: 宋体;">没有</span><span style="font-family: 宋体;font-size: 14px;">H5<span style="font-size: 16px;font-family: 宋体;">分发</span></span><span style="font-size: 16px;font-family: 宋体;">程序，所以</span><span style="font-size: 16px;font-family: 宋体;">一开始</span><span style="font-size: 16px;font-family: 宋体;">我只能</span><span style="font-size: 16px;font-family: 宋体;">谎称只能</span><span style="font-size: 16px;font-family: 宋体;">截图</span><span style="font-size: 16px;font-family: 宋体;">来</span><span style="font-size: 16px;font-family: 宋体;">跟对方</span><span style="font-size: 16px;font-family: 宋体;">对</span><span style="font-size: 16px;font-family: 宋体;">数据。</span></span></p><p><span style="font-family: Calibri;font-size: 16px;">&nbsp;</span></p><p><span style="font-size: 16px;"><span style="font-size: 16px;font-family: 宋体;">测试第一天</span><span style="font-size: 16px;font-family: 宋体;">，</span><span style="font-size: 16px;font-family: 宋体;">接入</span><span style="font-size: 16px;font-family: 宋体;">页面的</span><span style="font-family: 宋体;font-size: 14px;">UV8600<span style="font-size: 16px;font-family: 宋体;">，新增</span></span><span style="font-size: 16px;font-family: 宋体;">玩家</span><span style="font-family: 宋体;font-size: 14px;">839<span style="font-size: 16px;font-family: 宋体;">人</span></span><span style="font-size: 16px;font-family: 宋体;">，</span><span style="font-family: 宋体;font-size: 14px;"><span style="font-size: 16px;font-family: 宋体;">充值数</span>643<span style="font-size: 16px;font-family: 宋体;">块</span></span><span style="font-size: 16px;font-family: 宋体;">。</span><span style="font-size: 16px;font-family: 宋体;">数据</span><span style="font-size: 16px;font-family: 宋体;">我记得</span><span style="font-size: 16px;font-family: 宋体;">很清楚，验证可行之后</span><span style="font-size: 16px;font-family: 宋体;">，剩下的就是快速</span><span style="font-size: 16px;font-family: 宋体;">抢市场。那</span><span style="font-size: 16px;font-family: 宋体;">段时间</span><span style="font-size: 16px;font-family: 宋体;">我辞职</span><span style="font-size: 16px;font-family: 宋体;">出来，并勾搭了一个</span><span style="font-size: 16px;font-family: 宋体;">朋友到</span><span style="font-size: 16px;font-family: 宋体;">上海开始</span><span style="font-size: 16px;font-family: 宋体;">做</span><span style="font-size: 16px;font-family: 宋体;">这块</span><span style="font-size: 16px;font-family: 宋体;">，</span><span style="font-size: 16px;font-family: 宋体;">到上海的原因是因为当时很多</span><span style="font-family: 宋体;font-size: 14px;">H5<span style="font-size: 16px;font-family: 宋体;">游戏</span></span><span style="font-size: 16px;font-family: 宋体;">公司都在这边</span><span style="font-size: 16px;font-family: 宋体;">。</span></span></p><p><span style="font-family: Calibri;font-size: 16px;">&nbsp;</span></p><p><span style="font-size: 16px;"><span style="font-size: 16px;font-family: 宋体;">我花了</span><span style="font-family: Calibri;font-size: 14px;">4</span><span style="font-size: 16px;font-family: 宋体;">万</span><span style="font-size: 16px;font-family: 宋体;">块</span><span style="font-size: 16px;font-family: 宋体;">请人</span><span style="font-size: 16px;font-family: 宋体;">开发了一套</span><span style="font-size: 16px;font-family: 宋体;">分发</span><span style="font-size: 16px;font-family: 宋体;">程序，</span><span style="font-size: 16px;font-family: 宋体;">然后</span><span style="font-size: 16px;font-family: 宋体;">开始谈一些</span><span style="font-size: 16px;font-family: 宋体;">联运</span><span style="font-size: 16px;font-family: 宋体;">合作，联运跟</span><span style="font-family: 宋体;font-size: 14px;">CPS<span style="font-size: 16px;font-family: 宋体;">还</span></span><span style="font-size: 16px;font-family: 宋体;">是不一样的，</span><span style="font-size: 16px;font-family: 宋体;">刚开始</span><span style="font-size: 16px;font-family: 宋体;">其实不是特别好谈。尤其</span><span style="font-size: 16px;font-family: 宋体;">是</span><span style="font-size: 16px;font-family: 宋体;">一些</span><span style="font-family: 宋体;font-size: 14px;"><span style="font-size: 16px;font-family: 宋体;">有</span>IP<span style="font-size: 16px;font-family: 宋体;">的</span></span><span style="font-size: 16px;font-family: 宋体;">产品，</span><span style="font-family: 宋体;font-size: 14px;"><span style="font-size: 16px;font-family: 宋体;">好比如像后面的《</span>RO<span style="font-size: 16px;font-family: 宋体;">仙境》、等基本</span></span><span style="font-size: 16px;font-family: 宋体;">都是</span><span style="font-size: 16px;font-family: 宋体;">有</span><span style="font-size: 16px;font-family: 宋体;">独家代理</span><span style="font-size: 16px;font-family: 宋体;">。当时</span><span style="font-size: 16px;font-family: 宋体;">找了几款</span><span style="font-size: 16px;font-family: 宋体;">转化</span><span style="font-size: 16px;font-family: 宋体;">比较好</span><span style="font-size: 16px;font-family: 宋体;">的老产品</span><span style="font-size: 16px;font-family: 宋体;">，</span><span style="font-size: 16px;font-family: 宋体;">以牺牲</span><span style="font-size: 16px;font-family: 宋体;">分成</span><span style="font-size: 16px;font-family: 宋体;">的</span><span style="font-size: 16px;font-family: 宋体;">办法</span><span style="font-size: 16px;font-family: 宋体;">谈</span><span style="font-size: 16px;font-family: 宋体;">下</span><span style="font-size: 16px;font-family: 宋体;">联运</span><span style="font-size: 16px;font-family: 宋体;">合作。</span></span></p><p><span style="font-family: Calibri;font-size: 16px;">&nbsp;</span></p><p><span style="font-size: 16px;"><span style="font-size: 16px;font-family: 宋体;">有</span><span style="font-size: 16px;font-family: 宋体;">了自己的平台之后</span><span style="font-size: 16px;font-family: 宋体;">就</span><span style="font-size: 16px;font-family: 宋体;">开始</span><span style="font-size: 16px;font-family: 宋体;">找</span><span style="font-size: 16px;font-family: 宋体;">量，</span><span style="font-size: 16px;font-family: 宋体;">后来有家</span><span style="font-size: 16px;font-family: 宋体;">厂商</span><span style="font-size: 16px;font-family: 宋体;">公布</span><span style="font-size: 16px;font-family: 宋体;">自己的</span><span style="font-size: 16px;font-family: 宋体;">月</span><span style="font-size: 16px;font-family: 宋体;">流水</span><span style="font-size: 16px;font-family: 宋体;">破</span><span style="font-size: 16px;font-family: 宋体;">千万的数据，</span><span style="font-size: 16px;font-family: 宋体;">那段时间</span><span style="font-size: 16px;font-family: 宋体;">明显感觉</span><span style="font-size: 16px;font-family: 宋体;">谈</span><span style="font-size: 16px;font-family: 宋体;">合作</span><span style="font-size: 16px;font-family: 宋体;">变得</span><span style="font-size: 16px;font-family: 宋体;">很难，</span><span style="font-size: 16px;font-family: 宋体;">能</span><span style="font-size: 16px;font-family: 宋体;">谈下来要么没有</span><span style="font-size: 16px;font-family: 宋体;">量</span><span style="font-size: 16px;font-family: 宋体;">，</span><span style="font-size: 16px;font-family: 宋体;">要</span><span style="font-size: 16px;font-family: 宋体;">么</span><span style="font-size: 16px;font-family: 宋体;">就是</span><span style="font-size: 16px;font-family: 宋体;">假量。</span><span style="font-size: 16px;font-family: 宋体;">我开始想着新</span><span style="font-size: 16px;font-family: 宋体;">的出路</span><span style="font-size: 16px;font-family: 宋体;">。也</span><span style="font-size: 16px;font-family: 宋体;">算偶然，</span><span style="font-size: 16px;font-family: 宋体;">刚</span><span style="font-size: 16px;font-family: 宋体;">好有个做公众号的朋友过来</span><span style="font-size: 16px;font-family: 宋体;">问我有</span><span style="font-size: 16px;font-family: 宋体;">没有什么好的转化方式</span><span style="font-size: 16px;font-family: 宋体;">，</span><span style="font-size: 16px;font-family: 宋体;">我当时就想着公众号应该也能做这块。</span></span></p><p><span style="font-family: Calibri;font-size: 16px;">&nbsp;</span></p><p><span style="font-size: 16px;"><span style="font-size: 16px;font-family: 宋体;">公众号</span><span style="font-size: 16px;font-family: 宋体;">能够外链的地方</span><span style="font-size: 16px;font-family: 宋体;">有</span><span style="font-size: 16px;font-family: 宋体;">菜单栏，</span><span style="font-size: 16px;font-family: 宋体;">阅读</span><span style="font-size: 16px;font-family: 宋体;">原文</span><span style="font-family: 宋体;font-size: 14px;"><span style="font-size: 16px;font-family: 宋体;">，如果把</span>H5<span style="font-size: 16px;font-family: 宋体;">放到</span></span><span style="font-size: 16px;font-family: 宋体;">菜单栏行不行？</span><span style="font-size: 16px;font-family: 宋体;">当天就直接</span><span style="font-size: 16px;font-family: 宋体;">在</span><span style="font-size: 16px;font-family: 宋体;">朋友</span><span style="font-size: 16px;font-family: 宋体;">的</span><span style="font-size: 16px;font-family: 宋体;">一个</span><span style="font-size: 16px;font-family: 宋体;">娱乐号</span><span style="font-size: 16px;font-family: 宋体;">接入了菜单栏</span><span style="font-size: 16px;font-family: 宋体;">，</span><span style="font-size: 16px;font-family: 宋体;">粉丝</span><span style="font-family: Calibri;font-size: 14px;">20</span><span style="font-size: 16px;font-family: 宋体;">万</span><span style="font-size: 16px;font-family: 宋体;">，</span><span style="font-size: 16px;font-family: 宋体;">菜单栏</span><span style="font-family: Calibri;font-size: 14px;"><span style="font-size: 16px;font-family: 宋体;">点击率</span>1.2%<span style="font-size: 16px;font-family: 宋体;">，</span></span><span style="font-size: 16px;font-family: 宋体;">新增</span><span style="font-family: Calibri;font-size: 14px;">200</span><span style="font-size: 16px;font-family: 宋体;">多，充值</span><span style="font-size: 16px;font-family: 宋体;">只有</span><span style="font-family: 宋体;font-size: 14px;">60<span style="font-size: 16px;font-family: 宋体;">块</span></span><span style="font-size: 16px;font-family: 宋体;">，</span><span style="font-size: 16px;font-family: 宋体;">尽管数据</span><span style="font-size: 16px;font-family: 宋体;">比较惨，但</span><span style="font-size: 16px;font-family: 宋体;">是因为</span><span style="font-size: 16px;font-family: 宋体;">转化环节比较多，我知道是可以优化的</span><span style="font-size: 16px;font-family: 宋体;">。</span></span></p><p><span style="font-family: Calibri;font-size: 16px;">&nbsp;</span></p><p><span style="font-size: 16px;"><span style="font-size: 16px;font-family: 宋体;">优化的</span><span style="font-size: 16px;font-family: 宋体;">前提是做好</span><span style="font-size: 16px;font-family: 宋体;">数据统计</span><span style="font-size: 16px;font-family: 宋体;">，</span><span style="font-size: 16px;font-family: 宋体;">最直接</span><span style="font-size: 16px;font-family: 宋体;">的</span><span style="font-size: 16px;font-family: 宋体;">就是转化节点，</span><span style="font-size: 16px;font-family: 宋体;">比方说一个公众号</span><span style="font-family: 宋体;font-size: 14px;"><span style="font-size: 16px;font-family: 宋体;">粉丝是</span>10<span style="font-size: 16px;font-family: 宋体;">万</span></span><span style="font-size: 16px;font-family: 宋体;">，</span><span style="font-size: 16px;font-family: 宋体;">头条</span><span style="font-size: 16px;font-family: 宋体;">阅读</span><span style="font-size: 16px;font-family: 宋体;">是</span><span style="font-size: 16px;font-family: 宋体;">多少，</span><span style="font-size: 16px;font-family: 宋体;">菜单栏</span><span style="font-size: 16px;font-family: 宋体;">点击</span><span style="font-size: 16px;font-family: 宋体;">是</span><span style="font-size: 16px;font-family: 宋体;">多少，</span><span style="font-size: 16px;font-family: 宋体;">进入</span><span style="font-size: 16px;font-family: 宋体;">游戏</span><span style="font-size: 16px;font-family: 宋体;">之后</span><span style="font-size: 16px;font-family: 宋体;">新增是多少，</span><span style="font-size: 16px;font-family: 宋体;">之后活跃</span><span style="font-size: 16px;font-family: 宋体;">是多少，</span><span style="font-size: 16px;font-family: 宋体;">充值</span><span style="font-size: 16px;font-family: 宋体;">是多少</span><span style="font-size: 16px;font-family: 宋体;">。比方说当时</span><span style="font-size: 16px;font-family: 宋体;">测试下来，</span><span style="font-size: 16px;font-family: 宋体;">后台</span><span style="font-size: 16px;font-family: 宋体;">显示</span><span style="font-family: 宋体;font-size: 14px;"><span style="font-size: 16px;font-family: 宋体;">有</span>1200<span style="font-size: 16px;font-family: 宋体;">个</span></span><span style="font-size: 16px;font-family: 宋体;">人</span><span style="font-size: 16px;font-family: 宋体;">点击</span><span style="font-size: 16px;font-family: 宋体;">菜单，</span><span style="font-size: 16px;font-family: 宋体;">为</span><span style="font-size: 16px;font-family: 宋体;">什么</span><span style="font-size: 16px;font-family: 宋体;">新增</span><span style="font-family: Calibri;font-size: 14px;"><span style="font-size: 16px;font-family: 宋体;">只有</span>200</span><span style="font-size: 16px;font-family: 宋体;">个，很大</span><span style="font-size: 16px;font-family: 宋体;">可能是账号</span><span style="font-size: 16px;font-family: 宋体;">体系</span><span style="font-size: 16px;font-family: 宋体;">有问题，</span><span style="font-size: 16px;font-family: 宋体;">另外也</span><span style="font-size: 16px;font-family: 宋体;">得想办法</span><span style="font-size: 16px;font-family: 宋体;">提高菜单栏</span><span style="font-size: 16px;font-family: 宋体;">的点击率。</span><span style="font-size: 16px;font-family: 宋体;">之后</span><span style="font-size: 16px;font-family: 宋体;">又做了几篇推文，</span><span style="font-size: 16px;font-family: 宋体;">顶着被</span><span style="font-size: 16px;font-family: 宋体;">封的</span><span style="font-size: 16px;font-family: 宋体;">风险</span><span style="font-size: 16px;font-family: 宋体;">做了一些活动，</span><span style="font-size: 16px;font-family: 宋体;">数据</span><span style="font-size: 16px;font-family: 宋体;">开始好看了起来。</span></span></p><p><span style="font-family: Calibri;font-size: 16px;">&nbsp;</span></p><p><span style="font-size: 16px;"><span style="font-size: 16px;font-family: 宋体;">从</span><span style="font-size: 16px;font-family: 宋体;">那之后，我的主要流量</span><span style="font-size: 16px;font-family: 宋体;">来源</span><span style="font-size: 16px;font-family: 宋体;">就是公众号，</span><span style="font-size: 16px;font-family: 宋体;">由于</span><span style="font-size: 16px;font-family: 宋体;">两个行业</span><span style="font-size: 16px;font-family: 宋体;">信息差比较大</span><span style="font-size: 16px;font-family: 宋体;">，</span><span style="font-size: 16px;font-family: 宋体;">所以当时找合作</span><span style="font-size: 16px;font-family: 宋体;">也算还可以，</span><span style="font-size: 16px;font-family: 宋体;">团队</span><span style="font-size: 16px;font-family: 宋体;">也从</span><span style="font-family: 宋体;font-size: 14px;">3<span style="font-size: 16px;font-family: 宋体;">个</span></span><span style="font-size: 16px;font-family: 宋体;">人发展</span><span style="font-family: 宋体;font-size: 14px;"><span style="font-size: 16px;font-family: 宋体;">到了</span>9<span style="font-size: 16px;font-family: 宋体;">个。跟</span></span><span style="font-size: 16px;font-family: 宋体;">公众号的</span><span style="font-size: 16px;font-family: 宋体;">合作</span><span style="font-size: 16px;font-family: 宋体;">难点</span><span style="font-size: 16px;font-family: 宋体;">主要</span><span style="font-size: 16px;font-family: 宋体;">是不少</span><span style="font-size: 16px;font-family: 宋体;">公众号其实很</span><span style="font-size: 16px;font-family: 宋体;">拒绝</span><span style="font-family: 宋体;font-size: 14px;">CPS<span style="font-size: 16px;font-family: 宋体;">的结算</span></span><span style="font-size: 16px;font-family: 宋体;">方式，</span><span style="font-size: 16px;font-family: 宋体;">所以很多</span><span style="font-size: 16px;font-family: 宋体;">时候</span><span style="font-size: 16px;font-family: 宋体;">都</span><span style="font-size: 16px;font-family: 宋体;">得先去买推文，</span><span style="font-size: 16px;font-family: 宋体;">然后让公众号</span><span style="font-size: 16px;font-family: 宋体;">看到推文的收益。</span><span style="font-size: 16px;font-family: 宋体;">再</span><span style="font-size: 16px;font-family: 宋体;">跟他说</span><span style="font-size: 16px;font-family: 宋体;">我</span><span style="font-size: 16px;font-family: 宋体;">只需要你菜单栏的位置，</span><span style="font-size: 16px;font-family: 宋体;">你</span><span style="font-size: 16px;font-family: 宋体;">就可以有</span><span style="font-size: 16px;font-family: 宋体;">源源不断</span><span style="font-size: 16px;font-family: 宋体;">的流水。</span></span></p><p><span style="font-family: Calibri;font-size: 16px;">&nbsp;</span></p><p><span style="font-size: 16px;"><span style="font-size: 16px;font-family: 宋体;">那段</span><span style="font-size: 16px;font-family: 宋体;">时间也</span><span style="font-size: 16px;font-family: 宋体;">开始盘算着</span><span style="font-size: 16px;font-family: 宋体;">屯</span><span style="font-size: 16px;font-family: 宋体;">自己的量，毕竟</span><span style="font-size: 16px;font-family: 宋体;">以前</span><span style="font-size: 16px;font-family: 宋体;">摔</span><span style="font-size: 16px;font-family: 宋体;">过</span><span style="font-size: 16px;font-family: 宋体;">跟头</span><span style="font-size: 16px;font-family: 宋体;">，</span><span style="font-size: 16px;font-family: 宋体;">所以</span><span style="font-size: 16px;font-family: 宋体;">收入上去</span><span style="font-size: 16px;font-family: 宋体;">之后，也开始盘算</span><span style="font-size: 16px;font-family: 宋体;">买</span><span style="font-size: 16px;font-family: 宋体;">一些公众号的事情。</span><span style="font-size: 16px;font-family: 宋体;">不论</span><span style="font-size: 16px;font-family: 宋体;">是</span><span style="font-size: 16px;font-family: 宋体;">买</span><span style="font-size: 16px;font-family: 宋体;">推文</span><span style="font-size: 16px;font-family: 宋体;">还是</span><span style="font-size: 16px;font-family: 宋体;">买</span><span style="font-size: 16px;font-family: 宋体;">号</span><span style="font-size: 16px;font-family: 宋体;">，</span><span style="font-size: 16px;font-family: 宋体;">都</span><span style="font-size: 16px;font-family: 宋体;">需要对</span><span style="font-size: 16px;font-family: 宋体;">公众号有</span><span style="font-size: 16px;font-family: 宋体;">足够的了解</span><span style="font-size: 16px;font-family: 宋体;">。这块</span><span style="font-size: 16px;font-family: 宋体;">当时也交了点学费。</span></span></p><p><span style="font-family: Calibri;font-size: 16px;">&nbsp;</span></p><p><span style="font-size: 16px;"><span style="font-size: 16px;font-family: 宋体;">怎么</span><span style="font-size: 16px;font-family: 宋体;">判断一个</span><span style="font-size: 16px;font-family: 宋体;">公众号的</span><span style="font-size: 16px;font-family: 宋体;">量是</span><span style="font-size: 16px;font-family: 宋体;">不</span><span style="font-size: 16px;font-family: 宋体;">是真实，以及</span><span style="font-size: 16px;font-family: 宋体;">这个</span><span style="font-size: 16px;font-family: 宋体;">公众号的大概情况。</span><span style="font-size: 16px;font-family: 宋体;">由于合作的号</span><span style="font-size: 16px;font-family: 宋体;">比较多，所以</span><span style="font-size: 16px;font-family: 宋体;">我</span><span style="font-size: 16px;font-family: 宋体;">能拿到</span><span style="font-size: 16px;font-family: 宋体;">不少后台</span><span style="font-size: 16px;font-family: 宋体;">数据</span><span style="font-size: 16px;font-family: 宋体;">，那个</span><span style="font-size: 16px;font-family: 宋体;">时候</span><span style="font-size: 16px;font-family: 宋体;">做</span><span style="font-size: 16px;font-family: 宋体;">了个简单的数据库，</span><span style="font-size: 16px;font-family: 宋体;">就是</span><span style="font-size: 16px;font-family: 宋体;">把</span><span style="font-size: 16px;font-family: 宋体;">公众号</span><span style="font-size: 16px;font-family: 宋体;">分类，</span><span style="font-size: 16px;font-family: 宋体;">再把</span><span style="font-size: 16px;font-family: 宋体;">数据</span><span style="font-size: 16px;font-family: 宋体;">导入</span><span style="font-size: 16px;font-family: 宋体;">，</span><span style="font-size: 16px;font-family: 宋体;">统计</span><span style="font-size: 16px;font-family: 宋体;">转化</span><span style="font-size: 16px;font-family: 宋体;">节点</span><span style="font-size: 16px;font-family: 宋体;">数据，</span><span style="font-size: 16px;font-family: 宋体;">这样</span><span style="font-size: 16px;font-family: 宋体;">来分析</span><span style="font-size: 16px;font-family: 宋体;">不同品类的</span><span style="font-size: 16px;font-family: 宋体;">号的</span><span style="font-size: 16px;font-family: 宋体;">转化节点</span><span style="font-size: 16px;font-family: 宋体;">。</span><span style="font-size: 16px;font-family: 宋体;">比方说</span><span style="font-size: 16px;font-family: 宋体;">，常规的</span><span style="font-size: 16px;font-family: 宋体;">娱乐号</span><span style="font-size: 16px;font-family: 宋体;">，</span><span style="font-size: 16px;font-family: 宋体;">阅读</span><span style="font-size: 16px;font-family: 宋体;">比是多少，</span><span style="font-size: 16px;font-family: 宋体;">菜单点击率</span><span style="font-size: 16px;font-family: 宋体;">是多少，</span><span style="font-size: 16px;font-family: 宋体;">头条</span><span style="font-size: 16px;font-family: 宋体;">阅读量</span><span style="font-size: 16px;font-family: 宋体;">跟当日新增</span><span style="font-size: 16px;font-family: 宋体;">粉丝的</span><span style="font-size: 16px;font-family: 宋体;">比值</span><span style="font-size: 16px;font-family: 宋体;">等等。</span><span style="font-size: 16px;font-family: 宋体;">如果转化节点</span><span style="font-size: 16px;font-family: 宋体;">数值波动很大，</span><span style="font-size: 16px;font-family: 宋体;">并且</span><span style="font-size: 16px;font-family: 宋体;">波动频繁，</span><span style="font-size: 16px;font-family: 宋体;">那十有八九都</span><span style="font-size: 16px;font-family: 宋体;">是</span><span style="font-size: 16px;font-family: 宋体;">要</span><span style="font-size: 16px;font-family: 宋体;">小心的。</span></span></p><p><span style="font-family: Calibri;font-size: 16px;">&nbsp;</span></p><p><span style="font-size: 16px;"><span style="font-size: 16px;font-family: 宋体;">由于</span><span style="font-size: 16px;font-family: 宋体;">这块做得比较到位，</span><span style="font-size: 16px;font-family: 宋体;">后来</span><span style="font-size: 16px;font-family: 宋体;">我不得不专门找了两个伙伴来</span><span style="font-size: 16px;font-family: 宋体;">专职负责</span><span style="font-size: 16px;font-family: 宋体;">数据</span><span style="font-size: 16px;font-family: 宋体;">的</span><span style="font-size: 16px;font-family: 宋体;">整理</span><span style="font-size: 16px;font-family: 宋体;">，有</span><span style="font-family: Calibri;font-size: 14px;"><span style="font-size: 16px;font-family: 宋体;">这块数据的帮助，</span> CPS</span><span style="font-size: 16px;font-family: 宋体;">结算</span><span style="font-size: 16px;font-family: 宋体;">的难点也算可以</span><span style="font-size: 16px;font-family: 宋体;">解决</span><span style="font-size: 16px;font-family: 宋体;">，</span><span style="font-size: 16px;font-family: 宋体;">只要</span><span style="font-size: 16px;font-family: 宋体;">数据</span><span style="font-size: 16px;font-family: 宋体;">尚可</span><span style="font-size: 16px;font-family: 宋体;">，我愿意承担</span><span style="font-size: 16px;font-family: 宋体;">风险</span><span style="font-size: 16px;font-family: 宋体;">来测试转化。</span><span style="font-size: 16px;font-family: 宋体;">也</span><span style="font-size: 16px;font-family: 宋体;">可以针对不同公众号推不同产品</span><span style="font-size: 16px;font-family: 宋体;">提高</span><span style="font-size: 16px;font-family: 宋体;">转化，</span><span style="font-size: 16px;font-family: 宋体;">比方说针对</span><span style="font-size: 16px;font-family: 宋体;">女性的，</span><span style="font-size: 16px;font-family: 宋体;">针对</span><span style="font-size: 16px;font-family: 宋体;">宠物的</span><span style="font-size: 16px;font-family: 宋体;">等等</span><span style="font-size: 16px;font-family: 宋体;">。</span><span style="font-size: 16px;font-family: 宋体;">甚至</span><span style="font-size: 16px;font-family: 宋体;">可以自己</span><span style="font-size: 16px;font-family: 宋体;">开发</span><span style="font-size: 16px;font-family: 宋体;">一些转化</span><span style="font-size: 16px;font-family: 宋体;">产品</span><span style="font-size: 16px;font-family: 宋体;">，</span><span style="font-size: 16px;font-family: 宋体;">这类</span><span style="font-size: 16px;font-family: 宋体;">产品围绕</span><span style="font-size: 16px;font-family: 宋体;">需求，</span><span style="font-size: 16px;font-family: 宋体;">尽可能吸金能力</span><span style="font-size: 16px;font-family: 宋体;">强</span><span style="font-size: 16px;font-family: 宋体;">一些，</span><span style="font-size: 16px;font-family: 宋体;">因为持续</span><span style="font-size: 16px;font-family: 宋体;">能力没有游戏强</span><span style="font-size: 16px;font-family: 宋体;">，</span><span style="font-size: 16px;font-family: 宋体;">大部分都是一次性转化。</span><span style="font-size: 16px;font-family: 宋体;">很难</span><span style="font-size: 16px;font-family: 宋体;">做持续付费。</span></span></p><p><span style="font-family: Calibri;font-size: 16px;">&nbsp;</span></p><p><span style="font-size: 16px;"><span style="font-size: 16px;font-family: 宋体;">当时转化</span><span style="font-size: 16px;font-family: 宋体;">最高</span><span style="font-size: 16px;font-family: 宋体;">的</span><span style="font-size: 16px;font-family: 宋体;">是游戏相关的公众号，因为都是直接受众。</span><span style="font-size: 16px;font-family: 宋体;">那个</span><span style="font-size: 16px;font-family: 宋体;">时候我</span><span style="font-size: 16px;font-family: 宋体;">就想主播</span><span style="font-size: 16px;font-family: 宋体;">肯定是一个可以爆发的点。</span><span style="font-size: 16px;font-family: 宋体;">所以开始</span><span style="font-size: 16px;font-family: 宋体;">接触</span><span style="font-size: 16px;font-family: 宋体;">主播</span><span style="font-size: 16px;font-family: 宋体;">。好在</span><span style="font-size: 16px;font-family: 宋体;">交友</span><span style="font-size: 16px;font-family: 宋体;">广泛，</span><span style="font-size: 16px;font-family: 宋体;">刚好</span><span style="font-size: 16px;font-family: 宋体;">认识一个</span><span style="font-size: 16px;font-family: 宋体;">做</span><span style="font-size: 16px;font-family: 宋体;">电商的朋友，主要</span><span style="font-size: 16px;font-family: 宋体;">流量来源</span><span style="font-size: 16px;font-family: 宋体;">是</span><span style="font-size: 16px;font-family: 宋体;">游戏</span><span style="font-size: 16px;font-family: 宋体;">主播，于是开始</span><span style="font-size: 16px;font-family: 宋体;">谈合作。那个</span><span style="font-size: 16px;font-family: 宋体;">时候我才真正知道什么叫爆发，</span><span style="font-size: 16px;font-family: 宋体;">接入</span><span style="font-size: 16px;font-family: 宋体;">之后，一个</span><span style="font-family: 宋体;font-size: 14px;">50<span style="font-size: 16px;font-family: 宋体;">万</span></span><span style="font-size: 16px;font-family: 宋体;">粉丝的号，</span><span style="font-family: 宋体;font-size: 14px;"><span style="font-size: 16px;font-family: 宋体;">第一个月流水</span>4<span style="font-size: 16px;font-family: 宋体;">万</span></span><span style="font-size: 16px;font-family: 宋体;">，第二个</span><span style="font-family: 宋体;font-size: 14px;"><span style="font-size: 16px;font-family: 宋体;">月</span>10<span style="font-size: 16px;font-family: 宋体;">万</span></span><span style="font-size: 16px;font-family: 宋体;">，第三</span><span style="font-size: 16px;font-family: 宋体;">个月</span><span style="font-family: Calibri;font-size: 14px;"><span style="font-size: 16px;font-family: 宋体;">达到</span>30</span><span style="font-size: 16px;font-family: 宋体;">多万</span><span style="font-size: 16px;font-family: 宋体;">。</span><span style="font-size: 16px;font-family: 宋体;">主播</span><span style="font-size: 16px;font-family: 宋体;">本身是一个</span><span style="font-size: 16px;font-family: 宋体;">品牌</span><span style="font-size: 16px;font-family: 宋体;">，</span><span style="font-size: 16px;font-family: 宋体;">具备</span><span style="font-size: 16px;font-family: 宋体;">号召力，</span><span style="font-size: 16px;font-family: 宋体;">如果做好自己</span><span style="font-size: 16px;font-family: 宋体;">的接触面</span><span style="font-size: 16px;font-family: 宋体;">矩阵</span><span style="font-size: 16px;font-family: 宋体;">，</span><span style="font-size: 16px;font-family: 宋体;">营收</span><span style="font-size: 16px;font-family: 宋体;">难以想象。</span><span style="font-size: 16px;font-family: 宋体;">甚至</span><span style="font-size: 16px;font-family: 宋体;">到后面</span><span style="font-size: 16px;font-family: 宋体;">有</span><span style="font-size: 16px;font-family: 宋体;">一些主播没有这块能力，我都开始免费帮他</span><span style="font-size: 16px;font-family: 宋体;">去</span><span style="font-size: 16px;font-family: 宋体;">做，</span><span style="font-size: 16px;font-family: 宋体;">有</span><span style="font-size: 16px;font-family: 宋体;">主播的影响力，</span><span style="font-size: 16px;font-family: 宋体;">做</span><span style="font-size: 16px;font-family: 宋体;">号还是</span><span style="font-size: 16px;font-family: 宋体;">比较快</span><span style="font-size: 16px;font-family: 宋体;">的，</span><span style="font-size: 16px;font-family: 宋体;">大概两三个月</span><span style="font-size: 16px;font-family: 宋体;">就能到</span><span style="font-size: 16px;font-family: 宋体;">十万</span><span style="font-size: 16px;font-family: 宋体;">的粉丝。</span></span></p><p><span style="font-family: Calibri;font-size: 16px;">&nbsp;</span></p><p><span style="font-size: 16px;"><span style="font-size: 16px;font-family: 宋体;">到今年</span><span style="font-family: Calibri;font-size: 14px;">6</span><span style="font-size: 16px;font-family: 宋体;">月</span><span style="font-size: 16px;font-family: 宋体;">的时候，</span><span style="font-family: 宋体;font-size: 14px;">H5<span style="font-size: 16px;font-family: 宋体;">游戏</span></span><span style="font-size: 16px;font-family: 宋体;">平台</span><span style="font-size: 16px;font-family: 宋体;">已经</span><span style="font-size: 16px;font-family: 宋体;">遍地开花了，甚至</span><span style="font-size: 16px;font-family: 宋体;">每天</span><span style="font-size: 16px;font-family: 宋体;">都有新面孔出现</span><span style="font-size: 16px;font-family: 宋体;">，竞争</span><span style="font-size: 16px;font-family: 宋体;">开始激烈，</span><span style="font-size: 16px;font-family: 宋体;">最</span><span style="font-size: 16px;font-family: 宋体;">直观的就是</span><span style="font-size: 16px;font-family: 宋体;">分成</span><span style="font-size: 16px;font-family: 宋体;">比例</span><span style="font-size: 16px;font-family: 宋体;">越来</span><span style="font-size: 16px;font-family: 宋体;">越透明，</span><span style="font-family: 宋体;font-size: 14px;"><span style="font-size: 16px;font-family: 宋体;">从</span>55<span style="font-size: 16px;font-family: 宋体;">到</span><span style="font-size: 16px;font-family: Calibri;">64</span><span style="font-size: 16px;font-family: 宋体;">，</span></span><span style="font-size: 16px;font-family: 宋体;">甚至</span><span style="font-size: 16px;font-family: 宋体;">前阵子</span><span style="font-family: Calibri;font-size: 14px;">37W</span><span style="font-size: 16px;font-family: 宋体;">的</span><span style="font-size: 16px;font-family: 宋体;">大天使之剑都</span><span style="font-size: 16px;font-family: 宋体;">为</span><span style="font-size: 16px;font-family: 宋体;">了冲流水，全渠道</span><span style="font-family: 宋体;font-size: 14px;">73<span style="font-size: 16px;font-family: 宋体;">开</span></span><span style="font-size: 16px;font-family: 宋体;">。</span></span></p><p><span style="font-family: Calibri;font-size: 16px;">&nbsp;</span></p><p><span style="font-size: 16px;"><span style="font-family: 宋体;font-size: 14px;"><span style="font-size: 16px;font-family: 宋体;">现在</span>H5<span style="font-size: 16px;font-family: 宋体;">游戏</span></span><span style="font-size: 16px;font-family: 宋体;">这块</span><span style="font-size: 16px;font-family: 宋体;">市场其实</span><span style="font-size: 16px;font-family: 宋体;">你如果</span><span style="font-size: 16px;font-family: 宋体;">背后没有资本</span><span style="font-size: 16px;font-family: 宋体;">支持很难</span><span style="font-size: 16px;font-family: 宋体;">去玩</span><span style="font-size: 16px;font-family: 宋体;">，</span><span style="font-size: 16px;font-family: 宋体;">根本</span><span style="font-size: 16px;font-family: 宋体;">原因是</span><span style="font-size: 16px;font-family: 宋体;">大玩家</span><span style="font-size: 16px;font-family: 宋体;">抢流量</span><span style="font-size: 16px;font-family: 宋体;">，很多大公司</span><span style="font-size: 16px;font-family: 宋体;">都在做自己的</span><span style="font-family: 宋体;font-size: 14px;">H5<span style="font-size: 16px;font-family: 宋体;">游戏</span></span><span style="font-size: 16px;font-family: 宋体;">平台，</span><span style="font-size: 16px;font-family: 宋体;">也就是</span><span style="font-size: 16px;font-family: 宋体;">游戏盒子。</span><span style="font-size: 16px;font-family: 宋体;">如果大家</span><span style="font-size: 16px;font-family: 宋体;">手上有</span><span style="font-size: 16px;font-family: 宋体;">能</span><span style="font-size: 16px;font-family: 宋体;">做的</span><span style="font-family: 宋体;font-size: 14px;">H5<span style="font-size: 16px;font-family: 宋体;">游戏</span></span><span style="font-size: 16px;font-family: 宋体;">的流量</span><span style="font-size: 16px;font-family: 宋体;">，现在的</span><span style="font-size: 16px;font-family: 宋体;">分成其实能到很高</span><span style="font-size: 16px;font-family: 宋体;">，</span><span style="font-size: 16px;font-family: 宋体;">但是</span><span style="font-size: 16px;font-family: 宋体;">像</span><span style="font-size: 16px;font-family: 宋体;">我做</span><span style="font-size: 16px;font-family: 宋体;">联运</span><span style="font-size: 16px;font-family: 宋体;">分发，</span><span style="font-size: 16px;font-family: 宋体;">就</span><span style="font-size: 16px;font-family: 宋体;">很难。</span><span style="font-family: 宋体;font-size: 14px;"><span style="font-size: 16px;font-family: 宋体;">但是</span>H5<span style="font-size: 16px;font-family: 宋体;">这块</span></span><span style="font-size: 16px;font-family: 宋体;">的</span><span style="font-size: 16px;font-family: 宋体;">挖掘</span><span style="font-size: 16px;font-family: 宋体;">空间很大，</span><span style="font-size: 16px;font-family: 宋体;">今年</span><span style="font-size: 16px;font-family: 宋体;">的</span><span style="font-family: 宋体;font-size: 14px;">H5<span style="font-size: 16px;font-family: 宋体;">小说</span></span><span style="font-size: 16px;font-family: 宋体;">以及</span><span style="font-size: 16px;font-family: 宋体;">棋牌</span><span style="font-size: 16px;font-family: 宋体;">都是围绕微信端</span><span style="font-size: 16px;font-family: 宋体;">流量</span><span style="font-size: 16px;font-family: 宋体;">闷声赚大钱。</span><span style="font-size: 16px;font-family: 宋体;">只要</span><span style="font-size: 16px;font-family: 宋体;">能</span><span style="font-size: 16px;font-family: 宋体;">找</span><span style="font-size: 16px;font-family: 宋体;">到</span><span style="font-size: 16px;font-family: 宋体;">合适</span><span style="font-size: 16px;font-family: 宋体;">的</span><span style="font-size: 16px;font-family: 宋体;">转化</span><span style="font-size: 16px;font-family: 宋体;">，</span><span style="font-size: 16px;font-family: 宋体;">依托于公众号</span><span style="font-size: 16px;font-family: 宋体;">，</span><span style="font-size: 16px;font-family: 宋体;">小程序</span><span style="font-size: 16px;font-family: 宋体;">，甚至</span><span style="font-size: 16px;font-family: 宋体;">微信群</span><span style="font-size: 16px;font-family: 宋体;">，</span><span style="font-size: 16px;font-family: 宋体;">朋友圈</span><span style="font-size: 16px;font-family: 宋体;">，</span><span style="font-size: 16px;font-family: 宋体;">收益不</span><span style="font-size: 16px;font-family: 宋体;">比</span><span style="font-size: 16px;font-family: 宋体;">做微商</span><span style="font-size: 16px;font-family: 宋体;">差</span><span style="font-size: 16px;font-family: 宋体;">。</span></span></p><p><span style="font-family: Calibri;font-size: 16px;">&nbsp;</span></p><p><span style="font-size: 16px;"><span style="font-size: 16px;font-family: 宋体;">其实大家</span><span style="font-size: 16px;font-family: 宋体;">回过头去看，</span><span style="font-size: 16px;font-family: 宋体;">我</span><span style="font-size: 16px;font-family: 宋体;">算是一个不学</span><span style="font-size: 16px;font-family: 宋体;">无</span><span style="font-size: 16px;font-family: 宋体;">术的人，什么都懂一点，</span><span style="font-size: 16px;font-family: 宋体;">但其实</span><span style="font-size: 16px;font-family: 宋体;">什么都不精。</span><span style="font-size: 16px;font-family: 宋体;">很多时候真正</span><span style="font-size: 16px;font-family: 宋体;">让我赚钱的是信息差</span><span style="font-size: 16px;font-family: 宋体;">和</span><span style="font-size: 16px;font-family: 宋体;">执行力</span><span style="font-size: 16px;font-family: 宋体;">。我做</span><span style="font-size: 16px;font-family: 宋体;">事情</span><span style="font-size: 16px;font-family: 宋体;">一开始</span><span style="font-size: 16px;font-family: 宋体;">我不会去想着</span><span style="font-size: 16px;font-family: 宋体;">我</span><span style="font-size: 16px;font-family: 宋体;">没有什么，而是</span><span style="font-size: 16px;font-family: 宋体;">想着</span><span style="font-size: 16px;font-family: 宋体;">我</span><span style="font-size: 16px;font-family: 宋体;">有</span><span style="font-size: 16px;font-family: 宋体;">什么，我</span><span style="font-size: 16px;font-family: 宋体;">还</span><span style="font-size: 16px;font-family: 宋体;">需要什么</span><span style="font-size: 16px;font-family: 宋体;">，怎么</span><span style="font-size: 16px;font-family: 宋体;">用我有的</span><span style="font-size: 16px;font-family: 宋体;">去找</span><span style="font-size: 16px;font-family: 宋体;">到我没有的资源。</span><span style="font-size: 16px;font-family: 宋体;">很多</span><span style="font-size: 16px;font-family: 宋体;">朋友在拿</span><span style="font-size: 16px;font-family: 宋体;">我</span><span style="font-size: 16px;font-family: 宋体;">福建口音</span><span style="font-size: 16px;font-family: 宋体;">开玩笑</span><span style="font-size: 16px;font-family: 宋体;">的</span><span style="font-size: 16px;font-family: 宋体;">时候</span><span style="font-size: 16px;font-family: 宋体;">，</span><span style="font-size: 16px;font-family: 宋体;">也</span><span style="font-size: 16px;font-family: 宋体;">会问我</span><span style="font-size: 16px;font-family: 宋体;">，你</span><span style="font-size: 16px;font-family: 宋体;">们福建</span><span style="font-size: 16px;font-family: 宋体;">人</span><span style="font-size: 16px;font-family: 宋体;">做生意是不是有什么诀窍，</span><span style="font-size: 16px;font-family: 宋体;">其实本质上</span><span style="font-size: 16px;font-family: 宋体;">就是一个</span><span style="font-size: 16px;font-family: 宋体;">字</span><span style="font-size: 16px;font-family: 宋体;">试</span><span style="font-size: 16px;font-family: 宋体;">，一次</span><span style="font-size: 16px;font-family: 宋体;">不行，那我就</span><span style="font-size: 16px;font-family: 宋体;">十次</span><span style="font-size: 16px;font-family: 宋体;">，</span><span style="font-size: 16px;font-family: 宋体;">百次</span><span style="font-size: 16px;font-family: 宋体;">，总有一次能</span><span style="font-size: 16px;font-family: 宋体;">混出来</span><span style="font-size: 16px;font-family: 宋体;">。爱拼</span><span style="font-size: 16px;font-family: 宋体;">才会</span><span style="font-size: 16px;font-family: 宋体;">赢不是在说敢去赌，而是敢去一次次的</span><span style="font-size: 16px;font-family: 宋体;">尝试</span><span style="font-size: 16px;font-family: 宋体;">跟失败。</span><span style="font-size: 16px;font-family: 宋体;">好</span><span style="font-size: 16px;font-family: 宋体;">了，</span><span style="font-size: 16px;font-family: 宋体;">今天分享</span><span style="font-size: 16px;font-family: 宋体;">差不多就到这里，希望能帮到</span><span style="font-size: 16px;font-family: 宋体;">各位</span><span style="font-size: 16px;font-family: 宋体;">，</span><span style="font-size: 16px;font-family: 宋体;">也</span><span style="font-size: 16px;font-family: 宋体;">祝各位财源广进，网</span><span style="font-size: 16px;font-family: 宋体;">运昌隆</span><span style="font-size: 16px;font-family: 宋体;">，</span><span style="font-size: 16px;font-family: 宋体;">谢谢</span><span style="font-size: 16px;font-family: 宋体;">各位。</span></span></p><p><span style="font-family: Calibri;font-size: 16px;">&nbsp;</span></p><p><span style="font-family: Calibri;font-size: 16px;">&nbsp;</span></p><p><br  /></p>
                </div>
                <script nonce="1702737268" type="text/javascript">
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


<script nonce="1702737268">
    var __DEBUGINFO = {
        debug_js : "//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_wap/debug/console34c264.js",
        safe_js : "//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_wap/safe/moonsafe34c264.js",
        res_list: []
    };
</script>

<script nonce="1702737268" type="text/javascript">
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
            node.setAttribute('nonce', '1702737268');
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

<script nonce="1702737268" type="text/javascript">

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
<script nonce="1702737268" type="text/javascript">

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
    var appmsg_token   = "933_WEAwpyTK27x4j%2B9DNxn2QLkTuT1Lby-2Dgj8dnUB2mUpbvJf_MyG43NzaiM~";

    var _copyright_stat = "0";
    var _ori_article_type = "";

    var nickname = "易灵微课";
    var appmsg_type = "6";
    var ct = "1512128027";
    var publish_time = "2017-12-01" || "";
    var user_name = "gh_f5acdd877bc5";
    var user_name_new = "";
    var fakeid   = "";
    var version   = "";
    var is_limit_user   = "0";
    var round_head_img = "http://mmbiz.qpic.cn/mmbiz_png/zUIywPicNicRIia2QGiaciaia2ChiaRV1ibaTb43PGypK7ekXdAsp5skpD9NsTLxdeoADCLnhjLya2zhibWIAtqBrB12J6A/0?wx_fmt=png";
    var ori_head_img_url = "http://wx.qlogo.cn/mmhead/Q3auHgzwzM5vHzcJVh0RLcWpsKnE0L0Lh7OHa5R804ic0CMhQ5LH1FQ/132";
    var msg_title = "亏掉300万，靠一手H5逆袭的游戏生意";
    var msg_desc = "个人介绍 大家好，我是王薄荷，很感谢亦仁的邀请，今天来跟大家分享一下我这些年，在互联网摸爬滚打的故事。其实这";
    var msg_cdn_url = "http://mmbiz.qpic.cn/mmbiz_jpg/zUIywPicNicRJia6lk9Ohicaicf4kJm7qOfeyN2HjIcnOdZxfJdgvCdynTicUwWpppqUvibib7qIFUK8r5PH6jVtZ5F8Xw/0?wx_fmt=jpeg";
    var msg_link = "http://mp.weixin.qq.com/s?__biz=MzUyMDAzNDYxNw==\x26amp;tempkey=OTMzX1JVbWRHNTY3MWE1TFNHREFtUlh1MEZiX0FpRlF6YVpoYWpJOWNvcU55QTZvVkUzZS14OW0zZThIcFJ3TkpxQlhTVTRQNUlvVXYwRDBJaHd2TXBsMmhoUGpYMHRuMWtWQnRUUTVXQmtlaUIxWk5yWnZOSDNLWXV2LXZySXJxb2Fybl9ET1dYM0N3RTlaUE44bzB3YWFQeWVUNldKVGlldklfTEZtU2d%2Bfg%3D%3D\x26amp;chksm=79f1c1124e864804730d30598d9ed76d0071bf2c620183a1eec13c278fca0ee0e1b4f55da730#rd";
    var user_uin = "58420325"*1;
    var msg_source_url = '';
    var img_format = 'jpeg';
    var srcid = '1201wBb4JdXQ5bl5q7IiXvWU';
    var req_id = '0120F7fTOQepNhOPXen4PwLX';
    var networkType;
    var appmsgid = '' || '100000068'|| "100000068";
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
    var is_temp_url = "OTMzX01vVlJuczZxdnFWa1A0ZlFtUlh1MEZiX0FpRlF6YVpoYWpJOWNvcU55QTZvVkUzZS14OW0zZThIcFJ3TkpxQlhTVTRQNUlvVXYwRDBJaHd2TXBsMmhoUGpYMHRuMWtWQnRUUTVXQmtlaUIxWk5yWnZOSDNLWXV2LXZySXJxb2Fybl9ET1dYM0N3RTlaUE44b0RRbTBkYTd2U0xQZE1DNEloQ3p1Ynd\x26nbsp;fg==" ? 1 : 0;
    var send_time = "1512130267";
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

    var svr_time = "1512130475" * 1;

    var is_transfer_msg = ""*1||0;

    var malicious_title_reason_id = "0" * 1;

    window.wxtoken = "1026937856";





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

<script nonce="1702737268" type="text/javascript">
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

<script nonce="1702737268">window.__moon_host = 'res.wx.qq.com';window.__moon_mainjs = 'appmsg/index.js';window.moon_map = {"new_video/player.html.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/new_video/player.html39e24c.js","biz_wap/zepto/touch.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_wap/zepto/touch34c264.js","biz_wap/zepto/event.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_wap/zepto/event34c264.js","biz_wap/zepto/zepto.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_wap/zepto/zepto34c264.js","page/pages/video.css":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/style/page/pages/video.css3767b8.js","a/appdialog_confirm.html.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/a/appdialog_confirm.html34f0d8.js","widget/wx_profile_dialog_primary.css":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/style/widget/wx_profile_dialog_primary.css34f0d8.js","appmsg/emotion/caret.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/emotion/caret278965.js","new_video/player.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/new_video/player39e24c.js","a/appdialog_confirm.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/a/appdialog_confirm34c32a.js","biz_wap/jsapi/cardticket.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_wap/jsapi/cardticket34c264.js","biz_common/utils/emoji_panel_data.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_common/utils/emoji_panel_data3518c6.js","biz_common/utils/emoji_data.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_common/utils/emoji_data3518c6.js","appmsg/emotion/textarea.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/emotion/textarea353f34.js","appmsg/emotion/nav.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/emotion/nav278965.js","appmsg/emotion/common.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/emotion/common3518c6.js","appmsg/emotion/slide.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/emotion/slide2a9cd9.js","pages/loadscript.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/pages/loadscript39aac6.js","pages/music_report_conf.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/pages/music_report_conf39aac6.js","pages/report.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/pages/report39dc43.js","pages/player_adaptor.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/pages/player_adaptor39d6ee.js","pages/music_player.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/pages/music_player39dc43.js","appmsg/emotion/dom.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/emotion/dom31ff31.js","appmsg/comment_tpl.html.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/comment_tpl.html36c376.js","biz_wap/utils/fakehash.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_wap/utils/fakehash38c7af.js","biz_common/utils/wxgspeedsdk.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_common/utils/wxgspeedsdk3518c6.js","a/sponsor.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/a/sponsor39e101.js","a/app_card.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/a/app_card393ef4.js","a/ios.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/a/ios393966.js","a/android.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/a/android393966.js","a/profile.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/a/profile31ff31.js","a/cpc_a_tpl.html.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/a/cpc_a_tpl.html3802d9.js","a/sponsor_a_tpl.html.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/a/sponsor_a_tpl.html36c7cf.js","a/a_tpl.html.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/a/a_tpl.html393ef4.js","a/mpshop.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/a/mpshop311179.js","a/wxopen_card.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/a/wxopen_card3a2b93.js","a/card.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/a/card311179.js","biz_wap/utils/position.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_wap/utils/position34c264.js","a/a_report.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/a/a_report393966.js","appmsg/my_comment_tpl.html.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/my_comment_tpl.html36906d.js","appmsg/cmt_tpl.html.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/cmt_tpl.html369d00.js","sougou/a_tpl.html.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/sougou/a_tpl.html2c6e7c.js","appmsg/emotion/emotion.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/emotion/emotion353f34.js","biz_wap/utils/wapsdk.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_wap/utils/wapsdk34c264.js","biz_common/utils/monitor.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_common/utils/monitor3518c6.js","biz_common/utils/report.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_common/utils/report3518c6.js","appmsg/open_url_with_webview.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/open_url_with_webview3145f0.js","biz_common/utils/http.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_common/utils/http3518c6.js","biz_common/utils/cookie.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_common/utils/cookie3518c6.js","appmsg/topic_tpl.html.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/topic_tpl.html31ff31.js","pages/weapp_tpl.html.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/pages/weapp_tpl.html36906d.js","pages/voice_tpl.html.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/pages/voice_tpl.html38518d.js","pages/kugoumusic_ctrl.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/pages/kugoumusic_ctrl393e3a.js","pages/qqmusic_ctrl.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/pages/qqmusic_ctrl39b68c.js","pages/voice_component.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/pages/voice_component39dc43.js","pages/qqmusic_tpl.html.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/pages/qqmusic_tpl.html393e3a.js","new_video/ctl.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/new_video/ctl2d441f.js","a/testdata.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/a/testdata393ef4.js","appmsg/reward_entry.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/reward_entry36906d.js","appmsg/comment.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/comment3944ad.js","appmsg/like.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/like375fea.js","pages/version4video.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/pages/version4video384cba.js","a/a.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/a/a3a2b93.js","rt/appmsg/getappmsgext.rt.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/rt/appmsg/getappmsgext.rt2c21f6.js","biz_wap/utils/storage.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_wap/utils/storage34c264.js","biz_common/tmpl.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_common/tmpl3518c6.js","appmsg/share_tpl.html.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/share_tpl.html36906d.js","appmsg/img_copyright_tpl.html.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/img_copyright_tpl.html2a2c13.js","pages/video_ctrl.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/pages/video_ctrl36ebcf.js","biz_common/ui/imgonepx.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_common/ui/imgonepx3518c6.js","biz_common/utils/respTypes.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_common/utils/respTypes3518c6.js","biz_wap/utils/log.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_wap/utils/log34c264.js","sougou/index.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/sougou/index36913b.js","biz_wap/safe/mutation_observer_report.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_wap/safe/mutation_observer_report34c264.js","appmsg/fereport.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/fereport37b642.js","appmsg/report.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/report3404b3.js","appmsg/report_and_source.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/report_and_source393966.js","appmsg/page_pos.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/page_pos393966.js","appmsg/cdn_speed_report.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/cdn_speed_report3097b2.js","appmsg/wxtopic.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/wxtopic31a3be.js","appmsg/new_index.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/new_index36906d.js","appmsg/weapp.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/weapp39d5b2.js","appmsg/autoread.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/autoread3857fc.js","appmsg/voice.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/voice38518d.js","appmsg/qqmusic.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/qqmusic39dc43.js","appmsg/iframe.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/iframe39ab71.js","appmsg/product.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/product393966.js","appmsg/review_image.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/review_image3944ad.js","appmsg/outer_link.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/outer_link275627.js","appmsg/copyright_report.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/copyright_report2ec4b2.js","appmsg/async.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/async38b7bb.js","biz_wap/ui/lazyload_img.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_wap/ui/lazyload_img36be04.js","biz_common/log/jserr.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_common/log/jserr3518c6.js","appmsg/share.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/share39f74d.js","appmsg/cdn_img_lib.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/cdn_img_lib38b7bb.js","biz_common/utils/url/parse.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_common/utils/url/parse36ebcf.js","page/appmsg/not_in_mm.css":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/style/page/appmsg/not_in_mm.css36906d.js","page/appmsg/page_mp_article_improve_combo.css":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/style/page/appmsg/page_mp_article_improve_combo.css39aac6.js","page/appmsg_new/not_in_mm.css":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/style/page/appmsg_new/not_in_mm.css36f05c.js","page/appmsg_new/combo.css":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/style/page/appmsg_new/combo.css39aac6.js","biz_wap/jsapi/core.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_wap/jsapi/core34c264.js","biz_common/dom/event.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_common/dom/event3a25e9.js","appmsg/test.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/test354009.js","biz_wap/utils/mmversion.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_wap/utils/mmversion34c264.js","appmsg/max_age.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/max_age2fdd28.js","biz_common/dom/attr.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_common/dom/attr3518c6.js","biz_wap/utils/ajax.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_wap/utils/ajax38c31a.js","appmsg/log.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/log300330.js","biz_common/dom/class.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_common/dom/class3518c6.js","biz_wap/utils/device.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_wap/utils/device34c264.js","biz_common/utils/string/html.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/biz_common/utils/string/html3518c6.js","appmsg/index.js":"//res.wx.qq.com/mmbizwap/zh_CN/htmledition/js/appmsg/index393966.js"};</script><script nonce="1702737268" type="text/javascript" id="moon_inline" > window.__mooninline=1; window.setTimeout(function() {  function __moonf__(){
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
        __moonf__(); }, 25);</script><script nonce="1702737268" type="text/javascript">
    var real_show_page_time = +new Date();
    if (!!window.addEventListener){
        window.addEventListener("load", function(){
            window.onload_endtime = +new Date();
        });
    }

</script>

</body>
<script nonce="1702737268" type="text/javascript">document.addEventListener("touchstart", function() {},false);</script>
</html>
<!--tailTrap<body></body><head></head><html></html>-->
