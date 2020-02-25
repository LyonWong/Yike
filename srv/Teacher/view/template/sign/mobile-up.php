<html>
<head>
    <meta charset="UTF-8">
    <title>易灵微课讲师注册</title>
    <style>
        html {
            height: 100%;
        }

        body {
            text-align: center;
            background: url(<?=\view::src('img/sign-weixin.jpg')?>) no-repeat center fixed;
            background-size: cover;
            justify-content: center;
        }

        .flex {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .item {
            margin: 20px;
        }

        .title {
            font-size: 70px;
            color: #fff;
        }

        .input > input {
            font-size: 40px;
            width: 90%;
            height: 100px;
            text-align: center;
        }

        ::-webkit-input-placeholder {
            text-align: center;
            color: #ccc;
        }

        .button {
            width: 90%;

        }

        .button > button {
            border: none;
            color: #fff;
            background: #12B7F5;
            font-size: 45px;
            padding: 20px 40px;
            width: 40%;
            margin-top: 30px;
        }

        .explain {
            font-size: 40px;
            color: #fff;
            text-align: left;
        }

    </style>
</head>
<?= view::js([
    'resource' => [
        'jquery' => ['jquery.min'],
        'base' => ['global']
    ],
])?>

<body class="flex">
<div class="content">
    <div class="logo">
        <img width="20%" src="<?= view::src('img/logo2.png')?>" alt="">
    </div>
    <div class="item title">
        易灵微课讲师注册
    </div>
    <form class="flex">
        <div class="item input email button">
            <input name='email' id="email" placeholder="请输入邮箱"/>
            <button type="button" id="send">发送验证</button>
        </div>

        <div class="item explain">
<!--            <img width="20px" style="margin: -1px 0;" src="--><?//= view::src('img/lx.png')?><!--" alt="">&nbsp;-->
<!--            请访问邮件中的链接，完成验证后继续注册（建议在PC浏览器中操作）-->
          <ul>
            <li>请访问邮件中的链接，完成验证后继续</li>
            <li>建议在PC浏览器中操作</li>
          </ul>
        </div>
    </form>
</div>
</body>
</html>
<script>
    jQuery(document).ready(function () {
        window.alert = function(name){
            var iframe = document.createElement("IFRAME");
            iframe.style.display="none";
            iframe.setAttribute("src", 'data:text/plain,');
            document.documentElement.appendChild(iframe);
            window.frames[0].window.alert(name);
            iframe.parentNode.removeChild(iframe);
        };
        $('#send').click(function () {
            $("button").css("background","#12B7F5");
            var regex = /^([0-9A-Za-z\-_\.]+)@([0-9a-z]+\.[a-z]{2,3}(\.[a-z]{2})?)$/g;
            var regex = /@/
            if(!regex.test($("#email").val())) {
                alert("请输入正确的邮箱地址");
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