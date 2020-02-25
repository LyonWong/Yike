<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>
        易灵微课优惠券
    </title>
    <style>
        html {
            height: 100%;
        }

        body {
            background: url("<?=\view::src('img/coupon/底图.jpg')?>") no-repeat center center fixed;
            background-size: cover;
            height: 100%;
        }
        .content{
            width: 90%;
            height: 1000px;
            /*height: 695px;*/
            text-align: center;
            margin: 0 auto;
            background-color:#fff;
            opacity:0.9;
        }
        .title{
            font-size: 70px;
            color: #c09f6f;
            padding-top: 54px;
        }

        .lesson-title{
            font-size: 45px;
            color: #999999;
            padding-top: 30px;
            padding-bottom: 30px;
        }
        .detail{
            font-size: 40px;
            color: #333;
            line-height: 60px;
            text-align: left;
            padding-left: 50px;
            padding-right: 50px;
        }
        .button > button {
            display: block;
            border: none;
            left: 0;
            right: 0;
            color: #fff;
            width: 640px;
            background: #c09f6f;
            font-size: 45px;
            text-align: center;
            padding: 20px 0;
            margin: 0 auto;
        }
    </style>
    <?=\view::js('resource/jquery/jquery.min')?>
    <?=\view::js('js/jweixin-1.2.0')?>
</head>
<?= view::js([
    'resource' => [
        'jquery' => ['jquery.min'],
        'base' => ['global']
    ],
]) ?>
<body>
<div style="height: 350px"></div>
<div class="content">
    <div class="title">
        推广课程获取鼓励金
    </div>
    <div class="lesson-title">
        《<?= $this->target['title'] ?? '';?> 》
    </div>
    <div class="detail">
        <ul>
            <li>报名后可创建自己的优惠券，分享给朋友</li>
            <li>朋友领取优惠券后，报名立减<?=$this->discount?>元，您也可获得<?=$this->commission?>元鼓励金</li>
            <li>鼓励金在听课并确认付款72小时后结算，可在账户中心提现至微信
            </li>
        </ul>
    </div>
    <div class="button" style="padding-top: 20px">
        <?php if($this->enrolled){
            echo '<button id="button" onclick="create()">点击创建优惠券</button>';
        }else {
            echo '<button id="button" style="background: #999999">报名后才能参与推广</button>';
        }
        ?>
    </div>
</div>
</body>
</html>
<script type="text/javascript">
    window.alert = function(name){
        var iframe = document.createElement("IFRAME");
        iframe.style.display="none";
        iframe.setAttribute("src", 'data:text/plain,');
        document.documentElement.appendChild(iframe);
        window.frames[0].window.alert(name);
        iframe.parentNode.removeChild(iframe);
    };
    function create(img) {
        var data = {
            'target_sn': '<?=$this->target['sn'] ?? ''?>',
            'img':img
        };
        $.ajax({
            url: "/promote-create-sales",
            type: "post",
            data: data,
            dataType: 'JSON',
            async: false,
            cache: false,
            success: function (data) {
                if (data.error === '0') {
                    if(img) {
                        make_button('button-2', '创建成功,跳转中...',"#c09f6f");
                        location.href = '/promote-img?url='+ encodeURIComponent(data.data);
                    }else {
                        make_button('button', '创建成功',"#c09f6f");
                        location.href = data.data;

                    }
                } else {
                    alert(data.message);
                    return false;
                }
            }
        });
    }
    function make_button(buttonId, html,color) {
        var button = document.getElementById(buttonId);
        button.style.background = color;
        button.innerHTML = html;
    }
</script>