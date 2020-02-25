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
    // delta = Math.round(delta/2);
    // console.log(delta);
    if(delta){
      handle(delta,inputC);
    }
    // if(event.preventDefault)
    //   event.preventDefault();
    // event.returnValue=false;
  };
  if(window.addEventListener){
    window.addEventListener('DOMMouseScroll',wheel,false);
  }
  window.onmousewheel=wheel;
};
var scrolling = 0;
function handle(delta,arr) {
  var num;
  if (scrolling) {
    return
  }
  scrolling ++;
  window.Echo.render()
  setTimeout(function() {
    scrolling = 0;
    window.Echo.render()
  }, 500);
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