<html>
<head>
    <title>连接断开</title>
    <meta name="viewport" content="width=device-width,initial-scale=0.5">
    <style>
        body {
            background: #efeff4;
        }

        .box {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .info {
            font-size: 28px;
            color: #828298;
            margin: 20px;
        }

        .btn {
            text-decoration: none;
            margin: 20px;
            font-size: 32px;
            color: #fff;
            background: #12b7f5;
            width: 210px;
            height: 70px;
            border-radius: 35px;
        }
        .btn:active {
            background: #80d3f5;
        }

    </style>
</head>
<body class="box">
    <div class="box"><img src="<?= \view::src('img/live/client-error.png') ?>"</div>
    <div class="box info">
        <div>您在其他终端登录</div>
        <div>本终端已下线</div>
    </div>
    <a class="box btn" href="<?=$this->url?>">
        点击重连
    </a>
</body>
</html>