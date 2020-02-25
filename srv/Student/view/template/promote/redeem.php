<html>
<head>
    <meta charset="UTF-8">
    <title>易灵微课优惠券兑换</title>
    <style>
        html {
            height: 100%;
        }

        body {
            text-align: center;
            background: url(<?=\view::src('img/coupon/background.jpg')?>) no-repeat center fixed;
            background-size: cover;
            justify-content: center;
        }

        .flex {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .cover {
            width: 90%;
            min-height: 80%;
            padding: 20px;
            background: rgba(255, 255, 255, 0.9);
            justify-content: space-between;
        }

        .item {
            margin: 20px;
        }

        .title {
            font-size: 70px;
            color: #c09f6f;
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
            background: #c09f6f;
            font-size: 45px;
            padding: 30px 40px;
            width: 80%;
        }

        .qrcode {
        }
    </style>
</head>
<body class="flex">
<div class="cover flex">
    <div class="item title">
        易灵微课优惠券
    </div>
    <form class="flex" action="/promote-receive">
        <div class="item input">
            <input name='sn' placeholder="请输入优惠码"/>
        </div>
        <div class="item button">
            <button>兑换领取</button>
        </div>
    </form>
    <div class="item qrcode">
        <img src="<?= view::src('img/index/qrcode.png', '_') ?>"/>
        <div>识别二维码，关注易灵微课公众号</div>
    </div>
</div>
</body>
</html>