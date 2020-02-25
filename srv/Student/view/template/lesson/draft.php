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
        .flex {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .explain {
            width: 100%;
            padding: 10%;
            font-size: 45px;
        }
        .button > button {
            border: none;
            color: #fff;
            width: 100%;
            font-size: 45px;
            padding: 20px 0;
            margin-bottom: 30px;
            border-radius:8px;
        }

        .icon {
            padding-top: 10%;
        }


    </style

</head>
<body class="flex">
<div class="flex" style="width: 90%">
    <div class="icon">
        <img src="<?=\view::src('img/wait.png')?>">
    </div>
    <?php if($this->type == 2) {?>

    <div class="explain flex">
        <span>您尚未进入课堂，</span>
        <span>无法直接查看文字稿；</span>
        <span>如查看内容，</span>
        <span>会触发一小时无条件退款计时。</span>
    </div>
    <div class="button" style="width: 90%">
        <button id="button" onclick="javascrtpt:window.location.href='<?= $this->url[0]?>'" style="background: #09bb07">查看内容</button>
        <button id="button" onclick="javascrtpt:window.location.href='<?= $this->url[1]?>'" style="background:#F0F0F0;color:#707070">进入课堂</button>
    </div>
    <?php }?>
    <?php if($this->type == 1) {?>
        <div class="explain flex">
            <span>您尚未报名该课程</span>
        </div>
        <div class="button" style="width: 90%">
            <button id="button" onclick="javascrtpt:window.location.href='<?= $this->url[0]?>'" style="background: #09bb07">点击前往报名</button>
        </div>
    <?php }?>



</div>
</body>

</html>