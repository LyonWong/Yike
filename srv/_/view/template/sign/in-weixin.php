<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>易灵微课</title>
  <style>
    .body {
      text-align: center;
      background: url("<?=\view::src('img/sign/sign-weixin.jpg')?>") no-repeat center center fixed;
      background-size: cover;
    }

    .login-box {
      width: 320px;
      height: 514px;
      position: absolute;
      text-align: center;
      left: 0;
      right: 0;
      top: 0;
      bottom: 0;
      margin: auto;
    }

    .qr-box {
      width: 260px;
      height: 260px;
      position: absolute;
      text-align: center;
      left: 0;
      right: 0;
      top: 174px;
      bottom: 0;
      margin: auto;
    }

    .img {
      width: 100%;
      height: 100%;
    }

  </style>
</head>

<body class="body">
<div class="login-box">
  <div class="qr-box">
    <div id="qr-code" class="img"></div>
  </div>
  <img class="img" src="<?= \view::src('img/sign/qr-code.png') ?>" alt="">

</div>
</body>
</html>

<script src="https://res.wx.qq.com/connect/zh_CN/htmledition/js/wxLogin.js" type="text/javascript"></script>
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
  // allow-scripts allow-top-navigation allow-same-origin
  var obj = new WxLogin({
    id: "qr-code",
    appid: "<?=$info['appid'];?>",
    redirect_uri: encodeURIComponent("<?= $info['redirect_uri'];?>"),
    scope: "snsapi_login",
    state: "<?=$info['state'];?>",
    style: 'white',
    href: "<?=$info['href'];?>/css/weixin-sign.css"
  });
</script>
