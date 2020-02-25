<html>
<head>
<style>
    .button >button{
        display: block;
        border: none;
        left: 0;
        right: 0;
        bottom: 0;
        color: #fff;
        width: 100%;
        height: 15%;
        background: rgba(0,0,0,0);
        font-size:45px;
        text-align: center;
        padding: 30px 0;
        position:absolute;
    }
    .img {
        position:absolute;
        left: 0;
        top:0;
        width:100%;
        height:100%;
        /*z-index:-1*/
    }
</style>
</head>

<body>
<div class="ad">
    <div class="img">
        <img width="100%" height="100%" src="<?=$this->imgUrl?>">
    </div>
    <div class="button">
        <button onclick="detail()">　</button>
    </div>
</div>
<script>
    function detail() {
        location.href='<?=$this->link?>';
    }
</script>
</body>
</html>