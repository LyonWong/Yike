<!DOCTYPE html>
<html>
<head lang="en">
  <meta charset="UTF-8">
  <title></title>
  <meta http-equiv="X-UA-COMPATIBLE" content="IE-edge,chrome=1"><!--告诉ie使用新的渲染方式，防止低版本的ie不能使用css3-->
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link href="http://fonts.googleapis.com/css?family=Josefin+Slab:400,700" rel="stylesheet" type="text/css"/>
  <style>
    *{
      margin:0;
      padding:0;
    }

    body{
      font-family:Georia,serif;
      background:#ddd;
      font-weight:bold;
      font-size:15px;
      color:#333;
      overflow: hidden;
      -webkit-font-smoothing:antialiased;
    }
    a{
      text-decoration:none;
      color:#555;
    }
    .clr{
      width:0;
      height:0;
      overflow: hidden;
      clear:both;
      padding:0;
      margin:0;
    }
    .nav{
      width:100%;
      position:absolute;
      left:0;
      bottom:0;
      font-family:"Josefin Slab","Myriad pro" ,serif;
    }
    .nav input,.nav a{
      width:20%;
      height:34px;
      line-height:34px;
      position:fixed;
      bottom:0;
      cursor:pointer;
    }
    .nav input{
      opacity:0;
      z-index:1000;
    }
    .nav a{
      z-index:10;
      font-weight:700;
      font-size:16px;
      background:#e23a6e;
      color:#fff;
      text-align:center;
      text-shadow: 1px 1px 1px rgba(151,24,64,0.2);
    }
    #nav1,#nav1 + a{
      left:0%;
    }
    #nav2,#nav2 + a{
      left:20%;
    }
    #nav3,#nav3 + a{
      left:40%;
    }
    #nav4,#nav4 + a{
      left:60%;
    }
    #nav5,#nav5 + a{
      left:80%;
    }
    .nav input:checked + a,
    .nav input:checked:hover +a{
      background:#821134;
    }
    .nav input:checked + a:after{
      content:"";
      width:0;
      height:0;
      overflow:hidden;
      border:20px solid transparent;
      border-bottom-color:#821134;
      position:absolute;
      bottom:100%;
      left:50%;
      margin-left:-20px;
    }
    .nav input:hover + a{
      background:#AD244f;
    }
    .scroll,.panel{
      width:100%;
      height:100%;
      position:relative;
      text-align:center;
      padding-top:250px;
    }
    .scroll{
      left:0;
      top:0;
      -webkit-transform: translate3d(0, 0, 0);
      -moz-transform: translate3d(0, 0, 0);
      -ms-transform: translate3d(0, 0, 0);
      -o-transform: translate3d(0, 0, 0);
      transform: translate3d(0, 0, 0);
      -webkit-backface-visibility: hidden;
      -moz-backface-visibility: hidden;
      backface-visibility: hidden;
      -webkit-transition: all 0.6s ease-in-out;
      -moz-transition: all 0.6s ease-in-out;
      -o-transition: all 0.6s ease-in-out;
      transition: all 0.6s ease-in-out;
      color:#e23a6e;
      font-weight:bold;
    }
    .panel{
      background:#fff;
      overflow: hidden;
    }
    /*动画*/
    #nav1:checked ~ .scroll #panel1 h1{
      -webkit-animation: moveDown 0.6s ease-in-out 0.2s backwards;
      -o-animation: moveDown 0.6s ease-in-out 0.2s backwards;
      animation: moveDown 0.6s ease-in-out 0.2s backwards;
    }
    #nav2:checked ~ .scroll #panel2 h1{
      -webkit-animation: moveDown 0.6s ease-in-out 0.2s backwards;
      -o-animation: moveDown 0.6s ease-in-out 0.2s backwards;
      animation: moveDown 0.6s ease-in-out 0.2s backwards;
    }
    #nav3:checked ~ .scroll #panel3 h1{
      -webkit-animation: moveDown 0.6s ease-in-out 0.2s backwards;
      -o-animation: moveDown 0.6s ease-in-out 0.2s backwards;
      animation: moveDown 0.6s ease-in-out 0.2s backwards;
    }
    #nav4:checked ~ .scroll #panel4 h1{
      -webkit-animation: moveDown 0.6s ease-in-out 0.2s backwards;
      -o-animation: moveDown 0.6s ease-in-out 0.2s backwards;
      animation: moveDown 0.6s ease-in-out 0.2s backwards;
    }
    #nav5:checked ~ .scroll #panel5 h1{
      -webkit-animation: moveDown 0.6s ease-in-out 0.2s backwards;
      -o-animation: moveDown 0.6s ease-in-out 0.2s backwards;
      animation: moveDown 0.6s ease-in-out 0.2s backwards;
    }
    @keyframes moveDown {
      0%{
        -webkit-transform: translateY(-40px);
        -moz-transform: translateY(-40px);
        -ms-transform: translateY(-40px);
        -o-transform: translateY(-40px);
        transform: translateY(-40px);
        opacity:0;
      }
      100%{
        -webkit-transform: translateY(0);
        -moz-transform: translateY(0);
        -ms-transform: translateY(0);
        -o-transform: translateY(0);
        transform: translateY(0);
        opacity:1;
      }
    }
    .panel p{
      color:#000;
      margin-top:20px;
    }
    #nav1:checked ~ .scroll{
      -webkit-transform: translateY(0%);
      -moz-transform: translateY(0%);
      -ms-transform: translateY(0%);
      -o-transform: translateY(0%);
      transform: translateY(0%);
    }
    #nav2:checked ~ .scroll{
      -webkit-transform: translateY(-100%);
      -moz-transform: translateY(-100%);
      -ms-transform: translateY(-100%);
      -o-transform: translateY(-100%);
      transform: translateY(-100%);
    }
    #nav3:checked ~ .scroll{
      -webkit-transform: translateY(-200%);
      -moz-transform: translateY(-200%);
      -ms-transform: translateY(-200%);
      -o-transform: translateY(-200%);
      transform: translateY(-200%);
    }
    #nav4:checked ~ .scroll{
      -webkit-transform: translateY(-300%);
      -moz-transform: translateY(-300%);
      -ms-transform: translateY(-300%);
      -o-transform: translateY(-300%);
      transform: translateY(-300%);
    }
    #nav5:checked ~ .scroll{
      -webkit-transform: translateY(-400%);
      -moz-transform: translateY(-400%);
      -ms-transform: translateY(-400%);
      -o-transform: translateY(-400%);
      transform: translateY(-400%);
    }
    .icon{
      width:200px;
      height:200px;
      background:#fa96b5;
      -webkit-transform:translateY(-50%) rotate(45deg);
      -moz-transform:translateY(-50%) rotate(45deg);
      -ms-transform:translateY(-50%) rotate(45deg);
      -o-transform:translateY(-50%) rotate(45deg);
      transform:translateY(-50%) rotate(45deg);
      position:absolute;
      left:50%;
      top:0;
      margin-left:-100px;
    }
    [data-icon]:after{
      content:attr(data-icon);
      width:200px;
      height:200px;
      color:#fff;
      font-size:90px;
      text-align:center;
      line-height:200px;
      position:absolute;
      left:18%;
      top:18%;
      -webkit-transform: rotate(-45deg);
      -moz-transform: rotate(-45deg);
      -ms-transform: rotate(-45deg);
      -o-transform: rotate(-45deg);
      transform: rotate(-45deg);
    }
    .panelColor{
      background:#fa96b5;
      color:#fff;
    }
    .panelColor .icon{
      background:#fff;
      color:#fa96b5;
    }
    .panelColor .icon:after{
      color:#fa96b5;
    }
    .scroll .panel h1{
      font-size:60px;
    }
    @media screen and (max-device-width: 520px){

    }
  </style>
</head>
<body>
<div class="container">
  <div class="nav">
    <input type="radio" name="radio-set" checked id="nav1">
    <a href="#panel1">导航1</a>
    <input type="radio" name="radio-set" id="nav2">
    <a href="#panel2">导航2</a>
    <input type="radio" name="radio-set" id="nav3">
    <a href="#panel3">导航3</a>
    <input type="radio" name="radio-set" id="nav4">
    <a href="#panel4">导航4</a>
    <input type="radio" name="radio-set" id="nav5">
    <a href="#panel5">导航5</a>
    <div class="scroll">
      <section class="panel" id="panel1">
        <div class="icon" data-icon="a"></div>
        <h1>Serendipity1</h1>
        <p>you are my sunshine</p>
      </section>
      <section class="panel panelColor" id="panel2">
        <div class="icon" data-icon="b"></div>
        <h1>Serendipity2</h1>
        <p>you are my sunshine</p>
      </section>
      <section class="panel" id="panel3">
        <div class="icon" data-icon="c"></div>
        <h1>Serendipity3</h1>
        <p>you are my sunshine</p>
      </section>
      <section class="panel panelColor" id="panel4">
        <div class="icon" data-icon="d"></div>
        <h1>Serendipity4</h1>
        <p>you are my sunshine</p>
      </section>
      <section class="panel" id="panel5">
        <div class="icon" data-icon="e"></div>
        <h1>Serendipity5</h1>
        <p>you are my sunshine</p>
      </section>

    </div>
  </div>
</div>
<script>
  window.onload= function () {
    var scroll=document.getElementsByClassName("scroll")[0];//ie不兼容，换成id会成功
    var panel=document.getElementsByClassName("panel");//ie不兼容，换成id会成功

    var clientH=window.innerHeight;
    scroll.style.height=clientH+"px";
    for(var i=0;i<panel.length;i++){
      panel[i].style.height=clientH+"px";
    }
    /*下面是关于鼠标滚动*/
    var inputC=document.getElementsByTagName("input");
    var wheel= function (event) {
      var delta=0;
      if(!event)//for ie
        event=window.event;
      if(event.wheelDelta){//ie,opera
        delta=event.wheelDelta/120;
      }else if(event.detail){
        delta=-event.detail/3;
      }
      console.log(delta);
      if(delta){
        handle(delta,inputC);
      }
      if(event.preventDefault)
        event.preventDefault();
      event.returnValue=false;
    };
    if(window.addEventListener){
      window.addEventListener('DOMMouseScroll',wheel,false);
    }
    window.onmousewheel=wheel;
  };
  function handle(delta,arr) {
    var num;
    for(var i=0;i<arr.length;i++){//得到当前checked元素的下标
      if(arr[i].checked){
        num=i;
      }
    }
    if(delta>0 && num>0){//向上滚动
      num--;
      arr[num].checked=true;
    }else if(delta<0 && num<4){//向下滚动
      num++;
      arr[num].checked=true;
    }
  }
</script>
</body>
</html>