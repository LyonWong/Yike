<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>长按保存图片</title>
    <style>
        html {
            height: 100%;
        }
        body {
            margin: 0;
            display: flex;
            height: 100%;
            flex-direction: column;
            align-content: flex-end;
        }
        .container {
            background-color: #444444;
            border: none;
            flex-grow: 1;
            padding: 20px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        .footer {
            font-size: 40px;
            padding: 0 50px;
        }
        .button {
            font-size: 40px;
            padding-top: 30px;
            text-align: center;
            border-top: solid #E0E0E0 1px;
        }
        .button > button {
            -webkit-appearance: none;
            -moz-appearance: none;
            -o-appearance: none;
            appearance: none;
            background-color: #fff;
            border: none;
            align-content: center;
            padding: 10px 40px;
            margin-left: 20px;
            font-size: 40px;
            color: #12B7F5;
            border-radius: 15px;
        }

        button:focus {
            border: solid #0b94ea 2px;
        }
        button.active {
            border: solid #0b94ea 2px;
            width: 144px !important;
            height: 144px !important;
        }
        #imgBox {
            color: #fff;
        }

    </style>
</head>
<?= view::js([
    'resource' => [
        'jquery' => ['jquery.min'],
        'base' => ['global']
    ],
    'js/jweixin-1.2.0',
]) ?>
<body>
<div class="container">
    <div id="imgBox" align="center">加载中...</div>
</div>
<div class="button">
    <button type="button" class="active" style='width:140px;height:140px;background: url("<?=\view::src('img/sc/1.1.png')?>")'  onclick="hecheng(1)"></button>
    <button type="button" style='width:140px;height:140px;background: url("<?=\view::src('img/sc/2.1.png')?>")' onclick="hecheng(2)"></button>
    <button type="button" style='width:140px;height:140px;background: url("<?=\view::src('img/sc/3.1.png')?>")' onclick="hecheng(3)"></button>
    <button type="button" style='width:140px;height:140px;background: url("<?=\view::src('img/sc/4.1.png')?>")' onclick="hecheng(4)"></button>
    <button type="button" style='width:140px;height:140px;background: url("<?=\view::src('img/sc/5.1.png')?>")' onclick="hecheng(5)"></button>
</div>
<div class="footer">
    <ul>
        <li><strong>长按保存图片</strong>，分享并邀请好友报名</li>
        <li>您每成功邀请一位好友报名将获得<?=$this->commission?>元奖励；</li>
        <li>奖金在好友听课72小时后结算，可在“个人中心”提现至微信零钱。</li>
    </ul>
</div>
<script>
    var imgHeight = $('.container').height() - 20;

    $(window).resize(function(){
        var height = $('.container').height() - 20;
        $('#imgprev').css("height", height + 'px');
    });
    $('button').click(function(){
        $('button').removeClass('active');
        $(this).addClass('active');
    });

    var data=[
        '<?=\view::src('img/sc/1.jpeg')?>',
        '<?=$this->avatar?>',
        '<?=$this->qr_url?>'
    ];
    var userName = '<?=$this->user['name']?>';
    var base64=[];
    hecheng(1);

    function hecheng(number){
        data[0] = '<?=$this->prefix?>' + '/img/sc/' + number + '.jpeg?v=1';
        draw(function(){
            document.getElementById('imgBox').innerHTML='<img id="imgprev" style="height:'+imgHeight+'px" src="'+base64[0]+'">';
            base64 = [];
        })
    }
    function draw(fn){
        var  c = document.createElement("canvas");
        ctx=c.getContext('2d');
        c.width=648;
        c.height=1151;
        ctx.rect(0,0,c.width,c.height);
        ctx.fillStyle='#fff';
//        ctx.fill();
        function drawing(n){

            var img=new Image;
            if(n == 0){
                console.log(data[0]);
                img.crossOrigin = 'Anonymous'; //解决跨域
                img.src=data[n];
                img.onload=function(){
                    ctx.drawImage(img,0,0,648,1158);
                    drawing(n+1);//递归
                }
            }else if (n == 1) {
                img.crossOrigin = 'Anonymous'; //解决跨域
                img.src=data[n];
                img.onload=function(){
//                    var pattern = ctx.createPattern(img, "no-repeat");
//                    ctx.arc(43, 43, 0, 0, 2 * Math.PI);
//                    ctx.arc(86, 86, 86, 0, 2 * Math.PI);
//                    ctx.clip(); //裁剪上面的圆形
                    ctx.drawImage(img,75,636,110,110);
//                    ctx.fillStyle = pattern;
//                    ctx.fill();
                    drawing(n+1);//递归
                }
            }else if (n == 2) {
                img.crossOrigin = 'Anonymous'; //解决跨域
                img.src=data[n];
                img.onload=function(){
                    ctx.drawImage(img,66,812,179,179);
                    drawing(n+1);//递归
                }
            }else if (n == 3) {
                var name = userName;
                console.log(name);
                var fontSize = 23;
                ctx.textAlign="left";
                ctx.font = "bold " + fontSize + "px Courier New";
                ctx.fillStyle = "#fff";
                ctx.fillText(name, 120, 780);
                drawing(n+1);//递归
            } else{
                //保存生成作品图片
                base64.push(c.toDataURL("image/jpeg",0.8));
                fn();
            }
        }
        drawing(0);

    }
    wx.config({
        debug: false, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
        appId: '<?=$this->wxConfig['appId'] ?? ''?>', // 必填，公众号的唯一标识
        timestamp: '<?=$this->wxConfig['timestamp'] ?? ''?>', // 必填，生成签名的时间戳
        nonceStr: '<?=$this->wxConfig['nonceStr'] ?? ''?>', // 必填，生成签名的随机串
        signature: '<?=$this->wxConfig['signature'] ?? ''?>',// 必填，签名，见附录1
        jsApiList: ['onMenuShareAppMessage', 'onMenuShareTimeline'] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
    });
    wx.ready(function(){
        wx.onMenuShareTimeline({
            title: '<?='我推荐-'.$this->user['name']?>', // 分享标题
            link: '<?=$this->url?>', // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
            imgUrl: '<?=$this->cover?>', // 分享图标
            success: function () {
                // 用户确认分享后执行的回调函数
            },
            cancel: function () {
                // 用户取消分享后执行的回调函数
            }
        });
        wx.onMenuShareAppMessage({
            title: '<?='我推荐-'.$this->user['name']?>', // 分享标题
            link: '<?=$this->url?>', // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
            desc: '分享得鼓励金',
            imgUrl: '<?=$this->cover?>', // 分享图标
            type: '', // 分享类型,music、video或link，不填默认为link
            dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
            success: function () {
                // 用户确认分享后执行的回调函数
            },
            cancel: function () {
                // 用户取消分享后执行的回调函数
            }
        });
    });
</script>
</body>
</html>