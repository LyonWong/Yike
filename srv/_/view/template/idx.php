<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="screen-orientation" content="portrait"/>
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="format-detection" content="telephone=no">
    <title>易灵微课-课堂</title>
    <link href="<?=view::src("static/live/live.css", "_")?>" rel="stylesheet"></head>
</head>
<body>
<div id="app"></div>
<!--引入weixin js sdk-->
<script type="text/javascript" src="https://res.wx.qq.com/open/js/jweixin-1.2.0.js"></script>
<!--引入webim sdk-->
<script type="text/javascript" src="<?=view::src("static/live/_static/live/sdk/spark-md5.min.js", "_")?>"></script>
<script type="text/javascript" src="<?=view::src("static/live/_static/live/sdk/webim.min.js", "_")?>"></script>
<script type="text/javascript" src="<?=view::src("static/live/_static/live/sdk/json2.min.js", "_")?>"></script>
<!--audiojs-->
<script type="text/javascript" src="<?=view::src("static/live/_static/live/js/audiojs/audio.min.js", "_")?>"></script>
<!--recorderjs-->
<script type="text/javascript" src="<?=view::src("static/live/_static/live/js/recorderjs/recorder.min.js", "_")?>"></script>
<!-- built files will be auto injected -->
<script type="text/javascript" src="<?=view::src("static/live/manifest-live.js", "_")?>"></script>
<script type="text/javascript" src="<?=view::src("static/live/vendor-live.js", "_")?>"></script>
<script type="text/javascript" src="<?=view::src("static/live/live.js", "_")?>"></script>
<script type="text/javascript" src="<?=view::src("._.js", "_")?>"></script>
</body>
</html>
