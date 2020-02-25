<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>
    </title>
    <style>
        html {
            height: 100%;
        }
        body {
            text-align: center;
            background: url("<?=\view::src('img/coupon/底图.jpg')?>") no-repeat center center fixed;
            background-size: cover;
            justify-content: center;
        }

        .flex {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .coupon {
            width: 96%;
            text-align: center;
            margin: 0 auto;
        }
        .veins1 {
            height: 93px;
            width: 100%;
            margin-top: 90px;

        }
        .veins2 {
            height: 140px;
            width: 100%;
        }
        .content1{
            height: 293px;
            width: 100%;
            background-color:#fff;
            opacity:0.9;
            margin: -1px 0;

        }
        .description {
            font-size: 40px;
            color: #999999;
            height: 80px;
        }
        .share-name {
            color: #c09f6f;
            padding: 10px
        }
        .price-all{
            height: 224px;
            position: relative;
        }
        .lesson-price {
            font-size: 160px;
            color: #c09f6f;
            font-weight:bold;
            text-align: right;
            width: 50%;
        }
        .lesson-desc {
          font-size: 160px;
          color: #c09f6f;
          font-weight:bold;
        }
        #price {
            font-size: 48px;
            color: #c09f6f;

        }
        .lesson-price-old {
            font-size: 30px;
            color: #c09f6f;
            font-weight:bold;
            position: absolute;
            top: 35%;
            text-align: right;
            padding-left: 55%;
        }
        .lesson-price-new {
            font-size: 30px;
            color: #c09f6f;
            font-weight:bold;
            position: absolute;
            top: 55%;
            text-align: right;
            padding-left: 55%;
        }
        .lesson-shuxian {
            border-left:1px #c09f6f solid
            font-weight:bold;
            position: absolute;
            text-align: center;
            margin: 0 auto;
            left: 0;
            bottom: 0;
            right: 0;
            height: 50%;
            top:25%;
            width:3px;
        }
        .lesson-dis{
            color: #999999;
            font-size: 40px;
            height: 100px;
        }
        .lesson-title {
            font-size: 60px;
            color: #333333;
            height: 170px;
        }
        .button > button {
            border: none;
            color: #fff;
            width: 60%;
            background: #c09f6f;
            font-size: 45px;
            padding: 30px 0;
            margin-bottom: 80px;
            padding-top: 40px;
        }
        .direction-img{ height: 60px;  width: 100%;position: absolute;margin-top: 10px;

        }
        .direction-title{
            color: #999999;
            width: 100%;
            font-size: 40px;
            height: 60px;
            position: absolute;

        }
        .direction-list{
            color: #999999;
            left: 150px;
            font-size: 40px;
            text-align: left;
            line-height: 60px;
            position: absolute;
            padding-top: 80px;

        }
        #share-guide {
            position: fixed;
            top: 0;
            text-align: center;
            background: rgba(0,0,0,0.7);
            color: #fff;
            font-size: 2.6rem;
            padding: 3rem 0;
            width: 100%;
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
<body class="flex">
<?php $target = $this->target;
$info = $this->info;
$wxConfig = $this->shareConfig;?>
<div class="coupon flex">
    <div class="veins1">
        <img src="<?=\view::src('img/coupon/veins1.png')?> "  width="100%" height="100%">
    </div>
    <div class="content1">
        <div class="description">
            来自
            <?php if ($info['user']['avatar']) { ?>
                <img src="<?= $info['user']['avatar'] ?>" width="60px"
                     style="border-radius:60px;vertical-align: middle;border-radius: 60px;">
            <?php } ?>
            <span class="share-name"><?= $info['user']['name'] ??'' ?></span>
            的易灵微课优惠券
        </div>
        <div class="price-all">
            <?php if ($info['type'] == 'audition') { ?>
                <div class="lesson-desc">限时试听</div>
            <?php } else { ?>
            <div class="lesson-price" id="lesson-price">
                <?= $info['discount'] ? '<span id="price">￥</span>' . $info['discount'].'&nbsp;' : '' ?>
            </div>
            <div class="lesson-shuxian">
                <img src="<?=\view::src('img/coupon/xian.png')?>" width="100%" height="100%">
            </div>
            <div class="lesson-price-old" >
                原　价：<?=$target['price']? '￥'.$target['price']: ''?>
            </div>
            <div class="lesson-price-new" >
                用券后：<?=$target['price']? '￥'.round(($target['price'] - $info['discount']),2): ''?>
            </div>
            <?php } ?>
        </div>
    </div>
    <div class="veins2">
        <img src="<?=\view::src('img/coupon/veins2.png')?> "  width="100%" height="100%">
    </div>
    <div class="content1" style="height: 350px">
        <div class="lesson-dis">
            报名以下课程可用
        </div>
        <div class="lesson-title" id="lesson-title" onclick="javascript:window.location.href='<?=$target['href']?>'">
            <?= $target['title'] ? '《' . $target['title'] . '》' : '' ?>
        </div>
    </div>
    <div class="content1" style="height: 600px">
        <div class="button">
            <?php if($this->check && $this->check == $this->psn) {?>
                <button id="button"  onclick="javascript:window.location.href='<?=$target['href']?>'">已领取，点击前往课程</button>
            <?php } elseif ($info['quantity'] <= 0){ ?>
                <button id="button" onclick="javascript:window.location.href='<?=$target['href']?>'">来晚啦，点击查看课程</button>
            <?php }else{?>
                <button id="button" onclick="draw()">领取并前往报名</button>
            <?php }?>
        </div>
        <div class="direction-img">
            <img src="<?=\view::src('img/coupon/1.png')?>" width="80%">
        </div>
        <div class="direction-title">使用说明</div>
        <div class="direction-list">
            <ul>
                <li>领取优惠券后报名课程可自动抵扣</li>
                <li>同一课程的优惠券只能保留一张</li>
                <?php
                $tips = [];
                if ($info['quantity'] !== '') {
                    $tips[] = "剩余".max(0,$info['quantity'])."张";
                }
                if ($info['expire']) {
                    $tips[] = "有效期至$info[expire]";
                }
                $h = round(($info['duration'] ?: SECONDS_DAY*3 ) / SECONDS_HOUR, 1);
                $tips[] = "领券后 $h 小时内有效";
                $tip = implode('，', $tips);
                echo "<li>$tip</li>";
                ?>
            </ul>
        </div>
    </div>
</div>
<?php if ($this->info['uid'] == $this->uid) { ?>
    <div id="share-guide">
        点击右上角菜单，将优惠券分享给好友
    </div>
<?php } ?>
<script>
    var u = navigator.userAgent, app = navigator.appVersion;
    var isiOS = !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/);
    if(!isiOS) {
        var lessonPrice = document.getElementById('lesson-price');
        lessonPrice.style.marginTop = '40px';
    }
</script>
</body>
</html>
<script type="text/javascript">
    window.onload = function () {
        window.confirm = function (message) {
            var iframe = document.createElement("IFRAME");
            iframe.style.display = "none";
            iframe.setAttribute("src", 'data:text/plain,');
            document.documentElement.appendChild(iframe);
            var alertFrame = window.frames[0];
            var result = alertFrame.window.confirm(message);
            iframe.parentNode.removeChild(iframe);
            return result;
        };
        <?php if (!$info['type']) { ?>
        make_button('button', '优惠卷已失效',"#cccccc");
        <?php } elseif (is_int($info['quantity']) && $info['quantity'] <= 0) {?>
        make_button('button', '已经被领完啦',"#cccccc");
        <?php } ?>
        wx.config({
            debug: false, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
            appId: '<?=$wxConfig['appId'] ?? ''?>', // 必填，公众号的唯一标识
            timestamp: '<?=$wxConfig['timestamp'] ?? ''?>', // 必填，生成签名的时间戳
            nonceStr: '<?=$wxConfig['nonceStr'] ?? ''?>', // 必填，生成签名的随机串
            signature: '<?=$wxConfig['signature'] ?? ''?>',// 必253
            // 填，签名，见附录1
            jsApiList: ['onMenuShareAppMessage', 'onMenuShareTimeline'] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
        });
        wx.ready(function(){
            wx.onMenuShareTimeline({
                title: '<?= '易灵微课优惠券，《'.$target['title'].'》报名优惠￥'.$info['discount']?>', // 分享标题
                link: '<?=$this->url?>', // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
                imgUrl: '<?=\view::src('img/coupon/icon.png')?>', // 分享图标
                success: function () {
                    // 用户确认分享后执行的回调函数
                },
                cancel: function () {
                    // 用户取消分享后执行的回调函数
                }
            });
            wx.onMenuShareAppMessage({
                title: "来自<?=$info['user']['name']?>的易灵微课优惠券", // 分享标题
                desc: '<?= '《'.$target['title'].'》报名优惠￥'.$info['discount'] ?>', // 分享描述
                link: '<?=$this->url?>', // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
                imgUrl: '<?=\view::src('img/coupon/icon.png')?>', // 分享图标
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
    };

    function make_button(buttonId, html,color) {
        var button = document.getElementById(buttonId);
        button.style.background = color;
        button.innerHTML = html;
        button.disabled = true;
    }
    function draw(force) {
        if (<?=$this->uid?> == <?=$this->info['uid']?>) {
          if(window.confirm('不能领取自己的优惠券哦，前往查看课程吗？')) {
            window.location.href='<?=$this->target['href']?>';
          }
          return
        }
        var data = {
            'sn': '<?=$this->sn?>',
            'force':force
        };
        $.ajax({
            url: "/promote-draw",
            type: "post",
            data: data,
            dataType: 'JSON',
            async: false,
            cache: false,
            success: function (data) {
                if (data.error == 0) {
                    make_button('button', '领取成功，跳转中...',"#c09f6f");
                    location.href = data.data;
                } else if (data.error == 3){
                    if(window.confirm('您此前已领取过优惠券，是否用将其替代？')) {
                        draw(true)
                    }else {

                    }
                }else {
                    make_button('button', data.message,'#cccccc');
                    return false;
                }
            }
        });
    }
    $('#share-guide').click(function(){
        $(this).hide();
    })
</script>
