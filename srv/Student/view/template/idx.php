<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
  <meta name="screen-orientation" content="portrait"/>
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="format-detection" content="telephone=no">
  <title>易灵微课</title>
<link href="<?=view::src("static/student/student.css", "_")?>" rel="stylesheet"></head>

<body>
<div id="app">
  <style>
    html, body {
      height: 100%;
    }
    #app {
      display: flex; flex-direction: column; justify-content: center; height: 100%;
    }
    #loader {
      display: flex;
      flex-direction: column;
      align-items: center;
      padding-bottom: 128px;
    }

    #loader > img {
      -webkit-animation: animate 6s infinite ease-in-out;
      animation: animate 6s infinite ease-in-out;
    }
    #loader > span {
      padding: 32px;
      color: #2F57DA;
      font-weight: bold;
    }

    @keyframes animate {
      0% { transform: perspective(160px) rotateX(0deg) rotateY(0deg); }
      25% { transform: perspective(160px) rotateX(-180deg) rotateY(0deg); }
      50% { transform: perspective(160px) rotateX(-180deg) rotateY(-180deg); }
      75% { transform: perspective(160px) rotateX(0deg) rotateY(-180deg); }
      100% { transform: perspective(160px) rotateX(0deg) rotateY(0deg); }
    }
    </style>
  <div id="loader">
    <img src="https://assets.yike.local/img/logo/Original_6464@2x.png"/>
    <span>易灵微课</span>
  </div>
</div>
<!-- built files will be auto injected -->
<!--<script type="text/javascript" src="https://res.wx.qq.com/open/js/jweixin-1.2.0.js"></script>-->
<script type="text/javascript" src="<?=view::src("static/live/_static/live/sdk/jweixin-1.2.0.min.js", "_")?>"></script>
<script type="text/javascript" src="<?=view::src("static/student/manifest-student.js", "_")?>"></script>
<script type="text/javascript" src="<?=view::src("static/student/vendor-student.js", "_")?>"></script>
<script type="text/javascript" src="<?=view::src("static/student/student.js", "_")?>"></script>
<script type="text/javascript" src="<?=view::src("_.js", "_")?>"></script>
</body>
</html>
