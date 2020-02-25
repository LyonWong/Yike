<html>
<head>
    <title><?=$this->info['title']?></title>
    <style>
        body, html,input{ margin: 0}
        /*body {width: 1200px;}*/
        .frm {width:100%; position: relative}
        .slide img {width:100%; }
        /*.slide { margin-bottom:90px;}*/
        /*.flex-row {display:flex; justify-content: space-around;background-color: #3c4a55;}*/
        .babber {position: absolute;color: #fff;font-size: 35px;top: 24%;left: 55.25%;}
        .logo {position: absolute;width:15%;left: 13.3%;top:7%;}
        .phone {position: absolute;width:35%;left: 10.3%;top:32%;}
        .qrcode {position: absolute;width:14.75%;left: 55.25%;top: 40.3%}
        .babber1 {position: absolute;top:37.4%;left: 52.2%;}
        .babber2 {position: absolute;top:45.92%;left: 15.75%;}
        .babber3 {position: absolute;top:48.2%;left: 52.2%;}
        .title {font-size:26px;color: #3c4a55;}
        .explain {font-size: 16px;color: #9ca7c1}
        input {
            outline:none;
            border:none;
            cursor:pointer;
        }
        .btn1 {
            position: absolute;
            top: 48.3%;
            box-shadow: 1px 5px 15px  rgba(2,115,158, 0.48);
            background: #fff;
        }
        .btn1:active {
            position: absolute;
            top: 48.3%;
            background: #c7dbef;

        }
        .button {
            left: 73.25%;
            background-size:contain;
            padding: 4px 4%;
            font-size: 22px;
            border-radius: 18px;
            color: #333;
            outline: none;
            -webkit-appearance: none;
        }
        .btn2 {
            position: absolute;
            top: 60.3%;
            box-shadow: 1px 5px 15px  rgba(2,115,158, 0.48);
            background: #fff;
        }
        .btn2:active {
            position: absolute;
            top: 60.3%;
            background: #c7dbef;
        }
        .bo {
            position: absolute;
            left: 55.25%;
            top: 78.3%;
            width: 377px;
            height: 75px;
            background: url(<?=\view::src('img/index/xuxian.png')?>)  no-repeat;
            font-size: 20px;
            color: #fff;

        }
        .bo * {
            display:inline-block;vertical-align:middle;
        }
    </style>
</head>
<body>
<div id="brief" class="frm slide">
    <img src="<?=\view::src('img/index/bak.png')?>"/>
    <div class="logo">
        <img width="100%" src="<?=\view::src('img/index/logo.png')?>"/>
    </div>
    <div class="babber">
        <span style="font-size: 40px"><strong>只为「价值」买单</strong></span>
    </div>
    <div class="phone">
        <img width="100%" src="<?=\view::src('img/index/phone.png')?>">
    </div>
    <div class="qrcode">
        <img width="100%" src="<?=\view::src('img/index/mp.png')?>">
    </div>

    <div class="button1">
        <input class="btn1 button" type="button" onclick="javascrtpt:window.location.href='/lesson/'" value="课程首页">
        <input class="btn2  button" type="button" onclick="javascrtpt:window.location.href='<?=$this->teacherUrl?>'" value="讲师后台">
    </div>
    <div class="bo">
        <img src="<?=\view::src('img/index/sao.icon.png')?>" style="width: 20px;padding-left: 30px;padding-top: 27.5px;padding-right: 15px">
        <span style="padding-top: 27.5px;">扫一扫，关注易灵微课公众号</span>
    </div>

</div>

<div id="brief" class="frm slide">
    <img src="<?=\view::src('img/index/1.png')?>"/>
    <div class="babber1">
        <span class="title">无忧退款</span><br>
        <span class="explain">听课一小时内无条件退款，任性试听</span>
    </div>
</div>
<div id="brief" class="frm slide">
    <img src="<?=\view::src('img/index/2.png')?>"/>
    <div class="babber2">
        <span class="title">简洁高效</span><br>
        <span class="explain">界面清晰明了，授课与讨论互不干扰</span>
    </div>
</div>
<div id="brief" class="frm slide">
    <img src="<?=\view::src('img/index/3.png')?>"/>
    <div class="babber3">
        <span class="title">便捷可靠</span><br>
        <span class="explain">依托微信，随时随地享受高品质课程</span>
    </div>
</div>

<div class="flex-row" style="background-color: #3c4a55;text-align: center;color: #fff;font-size: 16px;padding: 40px">
    <div>联系邮箱 corp@yike.local</div>
    Copyright <?=$this->info['copyright']?>　｜　<?=$this->info['beian']?>　|　<a href="./blog" style="color: #fff; text-decoration: none" target="_blank">文章</a>
</div>
<?=\view::js('js/foot')?>
<script>
  (function() {
    var hm = document.createElement("script");
    hm.src = "https://hm.baidu.com/hm.js?0a0aac37343b546ea47c4b07f07a1426";
    var s = document.getElementsByTagName("script")[0];
    s.parentNode.insertBefore(hm, s);
  })();
</script>
</body>
</html>
