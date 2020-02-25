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
        a {
            text-decoration: none;
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
            font-size: 2rem;
            padding: 0 50px;
        }

        .button {
            font-size: 40px;
            padding-top: 30px;
            text-align: center;
            border-top: solid #E0E0E0 1px;
            max-height: 200px;
            display: -webkit-box;
            overflow-x: scroll;
            -webkit-overflow-scrolling:touch;
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

        .button > div {
            padding: 0 1.5rem;
        }
        .button > div > button {
            background-size: cover;
            border-radius: 15px;
            width: 9rem;
            height: 9rem;
        }

        button:focus {
            border: solid #0b94ea 4px;
        }

        button.active {
            border: solid #0b94ea 4px;
        }

        #imgBox {
            color: #fff;
        }
    </style>
</head>
<?= view::js([
    'resource' => [
        'jquery' => ['jquery.min', 'jquery.ext'],
        'base' => ['global']
    ],
    'js/jweixin-1.2.0',
]) ?>
<body>
<div class="container">
    <div id="imgBox" align="center">加载中...</div>
</div>
<div class="button">
    <?php foreach ($this->menu_icon as $k => $v) { ?>
        <div>
            <button type="button" class="<?php echo $k == 0 ? 'active' : ''; ?>" style='background-image: url("<?= $v ?>")' onclick="hecheng(<?= $k ?>)"></button>
        </div>
    <?php } ?>
</div>
<div class="footer">
    <ul>
        <li><strong>长按保存图片</strong>，分享并邀请好友报名。点此<a href="/?v=1#/course/rank/<?=$this->target_sn?>">查看邀请榜</a></li>
        <li>您每成功邀请一位好友报名将获得<?= $this->commission ?>元奖励；</li>
        <li>奖金在好友听课72小时后结算，可在“个人中心”提现至微信零钱。</li>
    </ul>
</div>
<script>
    var imgHeight = $('.container').height() - 20;

    $(window).resize(function () {
        var height = $('.container').height() - 20;
        $('.imgprev').css("height", height + 'px');
    });
    $('button').click(function () {
        $('button').removeClass('active');
        $(this).addClass('active');
    });

    var base64 = [];
//    var listData = eval('<?//=json_encode($this->list_data)?>//');
    var listData = eval("<?=str_replace('"','\"',json_encode($this->list_data))?>");
//    console.log(listData);
    hecheng(0);

    function hecheng(number) {
        draw(function () {
            document.getElementById('imgBox').innerHTML = '<img class="imgprev" id="imgprev-'+number+'"  style="height:' + imgHeight + 'px" src="' + base64[0] + '">';
            base64 = [];
            $('#imgprev-'+number).press(function(){
              $.get('/-receptor', {
                event: 'card-press',
                target: '<?=$this->target_sn?>',
                promote: '<?=$this->promote?>',
                number: number
              })
            }, 500)
        }, number)
    }
    function draw(fn, number) {
        var data = listData[number];
        var c = document.createElement("canvas");
        ctx = c.getContext('2d');
        c.width = data[0][4];
        c.height = data[0][5];
        ctx.rect(0, 0, c.width, c.height);
        ctx.fillStyle = '#fff';
        var len = data.length;

        function drawing(n) {
            if (n < len) {
                if (data[n][0] < 2) {
                    //图片
                    var img = new Image;
                    img.crossOrigin = 'Anonymous'; //解决跨域
                    img.src = data[n][1];
                    img.onload = function () {
                        ctx.drawImage(img, data[n][2], data[n][3], data[n][4], data[n][5]);
                        drawing(n + 1);//递归
                    }
                } else {
                    //文字
                    var text = data[n][1];
                    var fontSize = data[n][2];
//                    ctx.textAlign="center";
//                    ctx.font = "bold " + fontSize + "px Courier New";
//                    ctx.fillStyle = data[n][3];
//                    ctx.fillText(text, data[n][4], data[n][5],data[n][6]);
                    if (data[n][7] == 'vertical') {
                        ctx.save();
                        ctx.font = "bold " + fontSize + "px Courier New";
                        ctx.fillStyle = data[n][3];
                        ctx.textAlign = data[n][6];
                        ctx.translate(data[n][4], data[n][5]);
                        ctx.rotate(90 * Math.PI / 180);
                        ctx.fillText(text, 0, 0);
                        ctx.restore();

                    } else {
                        draw_long_text(text, ctx, data[n][2], data[n][3], data[n][4], data[n][5], data[n][6]);

                    }
                    drawing(n + 1);//递归
                }
            } else {
                //保存生成作品图片
                base64.push(c.toDataURL("image/jpeg", 0.8));
                fn();
            }
        }

        drawing(0);

    }

    function draw_long_text(longtext, cxt, fontSize, fillStyle, begin_width, begin_height, textAlign) {
        var linelenght = 35;  //一行字数
        var count = 0;
        var stringLenght = longtext.length;
        var newtext = longtext.split("");
        cxt.textAlign = textAlign;
        cxt.font = "bold " + fontSize + "px Courier New";
        cxt.fillStyle = fillStyle;
        writeTextOnCanvas(cxt, 45, linelenght, longtext, parseInt(begin_width), parseInt(begin_height));

        //ctx_2d        getContext("2d") 对象
        //lineheight    段落文本行高
        //bytelength    设置单字节文字一行内的数量
        //text          写入画面的段落文本
        //startleft     开始绘制文本的 x 坐标位置（相对于画布）
        //starttop      开始绘制文本的 y 坐标位置（相对于画布）
        function writeTextOnCanvas(ctx_2d, lineheight, bytelength, text, startleft, starttop) {
            function getTrueLength(str) {//获取字符串的真实长度（字节长度）
                var len = str.length, truelen = 0;
                for (var x = 0; x < len; x++) {
                    if (str.charCodeAt(x) > 128) {
                        truelen += 2;
                    } else {
                        truelen += 1;
                    }
                }
                return truelen;
            }

            function cutString(str, leng) {//按字节长度截取字符串，返回substr截取位置
                var len = str.length, tlen = len, nlen = 0;
                for (var x = 0; x < len; x++) {
                    if (str.charCodeAt(x) > 128) {
                        if (nlen + 2 < leng) {
                            nlen += 2;
                        } else {
                            tlen = x;
                            break;
                        }
                    } else {
                        if (nlen + 1 < leng) {
                            nlen += 1;
                        } else {
                            tlen = x;
                            break;
                        }
                    }
                }
                return tlen;
            }

            for (var i = 1; getTrueLength(text) > 0; i++) {
                var tl = cutString(text, bytelength);

                ctx_2d.fillText(text.substr(0, tl).replace(/^\s+|\s+$/, ""), startleft, (i - 1) * lineheight + starttop);
                text = text.substr(tl);
            }
        }
    }
    wx.config({
        debug: false, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
        appId: '<?=$this->wxConfig['appId'] ?? ''?>', // 必填，公众号的唯一标识
        timestamp: '<?=$this->wxConfig['timestamp'] ?? ''?>', // 必填，生成签名的时间戳
        nonceStr: '<?=$this->wxConfig['nonceStr'] ?? ''?>', // 必填，生成签名的随机串
        signature: '<?=$this->wxConfig['signature'] ?? ''?>',// 必填，签名，见附录1
        jsApiList: ['onMenuShareAppMessage', 'onMenuShareTimeline'] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
    });
    wx.ready(function () {
        wx.onMenuShareTimeline({
            title: "<?='我推荐-' . $this->user['name']?>", // 分享标题
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
            title: "<?='我推荐-' . $this->user['name']?>", // 分享标题
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
