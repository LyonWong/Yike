<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="screen-orientation" content="portrait"/>
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="format-detection" content="telephone=no">
    <title>登录</title>
</head>
<body>

<style>
    #app {
       font-size: 64px;
    }
</style>

<div id="app">
测试1
</div>

<?=\view::js(['js/jweixin-1.3.0'])?>
<script>
    console.log(window.__wxjs_environment === 'miniprogram');
    var callBackUrl = '<?= $this->url;?>'
    //
    wx.miniProgram.navigateTo({url: '../../../page/component/index?url=' + callBackUrl})
    // document.getElementById('app').innerText = `${wx}`;
</script>
</body>
</html>
