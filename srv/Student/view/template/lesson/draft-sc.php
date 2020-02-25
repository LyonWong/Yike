<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>生财有术系列课文字版本内容</title>
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
            /*width: 100%;*/
            /*padding: 10%;*/
            padding-bottom: 10%;
            padding-top: 10%;
            font-size: 45px;
            text-align: center;
            /*text-align: left;*/
        }
        .button > button {
            border: none;
            color: #fff;
            width: 100%;
            font-size: 45px;
            padding: 10px 0;
            margin-bottom: 30px;
            border-radius:8px;
        }

        .icon {
            padding-top: 10%;
        }
        .list {
            font-size: 45px;
            /*padding-left: 10%;*/
            align-items: center;
            text-align: center;


        }
        a {
            padding-bottom: 20px;
            padding-top: 20px;
        }
        a:hover, a:visited, a:link, a:active {
            color: #0b94ea;
            text-decoration:none;
            /*color:#000;text-decoration:underline;*/
        }

         li {
            padding-bottom: 40px;
            padding-top: 40px;
             list-style-type:none;
             align-items: center;

        }
    </style

</head>
<body class="flex">
<div class="flex" style="width: 90%">
    <div class="icon">
        <img src="<?=\view::src('img/wait.png')?>">
    </div>

    <div class="explain">
        <span>查看文字版本课程内容</span>
    </div>
    <div class="list flex">
            <a href="<?=$this->url[0]?>">《如何打造价值百万的细分网站》</a>
           <a href="<?=$this->url[1]?>">《那些形形色色的流量生意》</a>
           <a href="<?=$this->url[2]?>">《年流水过千万的移民公司》</a>
          <a href="<?=$this->url[3]?>">《亏掉300万，靠一手H5逆袭的游戏生意》</a>

    </div>



</div>
</body>

</html>