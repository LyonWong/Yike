<html>
<head>
    <style>
        body {
            background: #000;
            display: flex;
            justify-content: center;
            align-items: center;
        }
    </style>
</head>
<body>
    <a href="<?=$this->url?>">
        <img src="<?=\view::src("img/advertise/jumbotron/$this->key.jpg")?>" />
    </a>
    <script type="text/javascript" src="<?=view::src("_.js", "_")?>"></script>
</body>
</html>

