<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>讲师注册</title>
    <style>
        body{
            text-align:center;
            background: url("<?=\view::src('img/sign-weixin.jpg')?>") no-repeat center center fixed;
            background-size:cover;
        }
        .flex {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .item {
            margin: 20px;
        }

        .content {
            margin-top: 15%;
            width: 600px;
        }
        .title {
            font-size: 30px;
            color: #fff;
        }

        .explain {
            font-size: 16px;
            color: #fff;
        }

        .input > input {
            font-size: 16px;
            width: 300px;
            height: 30px;
            padding: 5px 5px;
            text-align: center;
            border: solid 1px #12B7F5;
        }

        .button {
            width: 90%;
        }

        .button > button {
            border: none;
            color: #fff;
            background: #12B7F5;
            font-size: 16px;
            padding: 5px 5px;
            width: 80px;
            height: 42px;
        }


        .success {
            color: #12B7F5;
        }


    </style>
</head>
<?= view::js([
    'resource' => [
        'jquery' => ['jquery.min'],
        'base' => ['global']
    ],
    'js' => ['sweetalert.min']
])?>
<?=   view::css([
    'css'=>['sweetalert']
]);?>
<body class="flex">
<div class="content">
    <div class="logo">
        <img width="" src="<?= view::src('img/logo2.png')?>" alt="">
    </div>
    <div class="item title">
        易灵微课讲师注册
    </div>
    <form class="flex">
        <div class="item input email button">
            <input name='email' id="email" placeholder="请输入邮箱"/><button type="button" id="send">发送验证</button>
        </div>

        <div class="item explain">
            <img width="" style="margin: -1px 0;" src="<?= view::src('img/lx.png')?>" alt="">&nbsp;请访问邮件中的链接,完成验证后继续注册
        </div>
    </form>
</div>

</body>
</html>

<script>
    jQuery(document).ready(function () {
        $('#send').click(function () {
            $("button").css("background","#12B7F5");
            var regex = /^([0-9A-Za-z\-_\.]+)@([0-9a-z]+\.[a-z]{2,3}(\.[a-z]{2})?)$/g;
            var regex = /@/;
            if(!regex.test($("#email").val())) {
                swal({
                    title: '错误提醒',
                    text: "请输入正确的邮箱地址",
                    confirmButtonText: "知道了",
                    showCancleButton:true
                });
                $("#send").html('发送验证');
                return false;
            }
            $("#send").html('发送中...');
            $.post('./sign-sendEmail.api', {
                email: $("#email").val()
            }, function (res) {
                if(res.error==0){
                    $(".note").addClass("success");
                    $("#send").html('发送成功');
                } else {
                    $("button").css("background","#c0c0c0");
                    $("#send").html(res.message);
                }
            })
        });
    });

</script>
