<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>易灵微课</title>
    <style>
        .body{
            text-align:center;
            background: url("<?=\view::src('img/sign-weixin.jpg')?>") no-repeat center center fixed;
            background-size:cover;
        }
        .login-box{
            width: 320px;
            height: 514px;
            position: absolute;
            text-align: center;
            left:0;
            right:0;
            top: 0;
            bottom: 0;
            margin: auto;
        }
        .qr-box{
            width: 260px;
            height: 260px;
            position: absolute;
            text-align: center;
            left:0;
            right:0;
            top: 174px;
            bottom: 0;
            margin: auto;
        }
        .img{
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
    <img class="img" src="<?=\view::src('img/qr-code.png')?>" alt="">

</div>
</body>
</html>

<script src="https://res.wx.qq.com/connect/zh_CN/htmledition/js/wxLogin.js" type="text/javascript"></script>
<?php  $info = $this->wechatInfo;?>
<script>
    var obj = new WxLogin({
        id: "qr-code",
        appid: "<?=$info['appid'];?>",
        redirect_uri: encodeURIComponent("<?= $info['redirect_uri'];?>"),
        scope: "snsapi_login",
        state: "<?=$info['state'];?>",
        style:'white',
        href: "<?=$info['href'];?>/css/weixin-sign.css?z"
    });
</script>
