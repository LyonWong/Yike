<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>易灵微课</title>
    <style>
        body {
            background: url("<?=\view::src('img/sign-weixin.jpg')?>") no-repeat center center fixed;
            background-size: cover;
        }
        .frame {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        .login {
            margin-top: 10rem;
            position: relative;
        }
        #qr-code {
            position: absolute;
            top: 210px;
            left: 24px;
            display: none;
        }
        #link {
            margin-top: 3rem;
            z-index: 99;
        }
        #link a{
            color: #fff;
        }
    </style>
</head>

<body>
<div class="frame">
    <div class="login">
        <img src="<?= \view::src('img/qr-code.png') ?>">
        <div id="qr-code"></div>
    </div>
    <div id="link">
        <a href="/sign-in-email">使用账号密码登录</a>
    </div>
</div>
</body>
</html>

<!--<script src="https://res.wx.qq.com/connect/zh_CN/htmledition/js/wxLogin.js" type="text/javascript"></script>-->
<?php $info = $this->wechatInfo; ?>
<script>
  !function (a, b, c) {
    function d(a) {
      var c = "default";
      a.self_redirect === !0 ? c = "true" : a.self_redirect === !1 && (c = "false");
      var d = b.createElement("iframe"),
        e = "https://open.weixin.qq.com/connect/qrconnect?appid=" + a.appid + "&scope=" + a.scope + "&redirect_uri=" + a.redirect_uri + "&state=" + a.state + "&login_type=jssdk&self_redirect=" + c;
      e += a.style ? "&style=" + a.style : "", e += a.href ? "&href=" + a.href : "", d.src = e, d.frameBorder = "0", d.allowTransparency = "true", d.scrolling = "no", d.width = "300px", d.height = "400px", d.sandbox='allow-scripts allow-top-navigation allow-same-origin';
      var f = b.getElementById(a.id);
      f.innerHTML = "", f.appendChild(d)
    }
    a.WxLogin = d
  }(window, document);
    var obj = new WxLogin({
        id: "qr-code",
        appid: "<?=$info['appid'];?>",
        redirect_uri: encodeURIComponent("<?= $info['redirect_uri'];?>"),
        scope: "snsapi_login",
        state: "<?=$info['state'];?>",
        style: 'white',
        href: "<?=$info['href'];?>/css/weixin-sign.css?z"
    });
    setTimeout(function(){
        document.getElementById('qr-code').style.display = "block"
    }, 100);
</script>
