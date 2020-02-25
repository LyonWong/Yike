<html>
<head>
    <meta charset="UTF-8">
    <title>易灵微课-好友助力</title>
<!--    <meta name="viewport" content="width=device-width,initial-scale=1.0">-->
    <?= view::css('css/promote-haggle') ?>
</head>
<body>

<div class="layout flex flex-col">
    <div class="ticket upper">
        <div class="title-main">易灵微课　助TA听课</div>
        <div class="blank-bar-20"></div>
        <div class="title-vice">一个篱笆三个桩，一堂微课大家帮</div>
        <div class="blank-bar-50"></div>
        <div class="blank-bar-50"></div>
        <div class="info">
            <div class="info-title">
                <div class="flex flex-row">
                <span class="user-refer">
                    <img src="<?= $this->user['avatar'] ?>"/>
                    <span><?= $this->user['name'] ?> </span>
                </span>
                    <span>想要报名</span>
                </div>
            </div>
            <div class="info-lesson">
                <?= $this->lesson['title'] ?>
            </div>
            <div class="info-teacher">
                <span>讲师：<?= $this->lesson['teacher']['name'] ?></span>
            </div>
            <?php if ($this->user['sn'] == $this->usn) { ?>
              <div class="info-button">
                <button id="enroll">现在报名</button>
              </div>
            <?php } else { ?>
              <div class="info-button">
                <button id="helpHaggle">助TA听课</button>
              </div>
            <?php } ?>
            <div class="info-link">
                <?php if ($this->user['sn'] == $this->usn) { ?>
                  <span><?= ($n = $this->haggle['init']['n'] - $this->haggle['number']) ? "还差 $n 位好友达到最大助力" : '最大助力达成' ?></span>
                <?php } else { ?>
                  <a href="<?=$this->detailUrl?>">
                    查看课程
                  </a>
                <?php } ?>
            </div>
        </div>
    </div>
    <div class="sever flex flex-row">
        <div class="sever-addon sever-pre-addon"></div>
        <div class="sever-rod">
            <div class="sever-bar"></div>
            <div class="sever-hole"></div>
            <div class="sever-bar"></div>
        </div>
        <div class="sever-addon sever-suf-addon"></div>
    </div>
    <div class="ticket lower flex flex-col">
        <div class="help">
            <div class="title-vice">
                <span><?=$this->haggle['number']?> 位好友共帮省了</span>
                <span class="highlight">￥<?= $this->haggle['discount'] ?></span>
            </div>
            <div class="blank-bar-20"></div>
            <div class="desc">
                <del>原价 ￥<?= $this->lesson['price'] ?></del>
                <span class="highlight">　现价 ￥<?= round($this->lesson['price'] - $this->haggle['discount'], 2) ?></span>
                <span>&nbsp;&nbsp;最多可省 ￥<?= ($this->haggle['init']['range'][1]*$this->haggle['init']['n']/100)?></span>
            </div>
            <div class="blank-bar-20"></div>
            <div class="helpers">
                <?php foreach ($this->helpers as $helper) { ?>
                    <div class="helper flex">
                        <div class="user-refer">
                            <img src="<?= $helper['avatar'] ?>"/>
                            <span><?= $helper['name'] ?></span>
                        </div>
                        <div class="help-result">
                            <span>帮省了</span>
                            <span class="highlight">￥<?= $helper['value'] ?></span>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
        <div class="rule">
            <div class="rule-title flex flex-row">
                <span class="rule-line rule-pre-line"></span>
                <span>　活动规则　</span>
                <span class="rule-line rule-suf-line"></span>
            </div>
            <div class="rule-list flex">
                <ul>
                    <li>点击右上角微信菜单[···]邀请好友为听课助力</li>
                    <li>单个课程只可助力一次，每人每天可助力3次</li>
                    <li>点击【助TA听课】可助好友减免一定金额的报名费</li>
                    <?php if($this->haggle['init']['nb_gift']??null) echo "<li>新用户为好友助力后，可获得".($this->haggle['init']['nb_gift']/100)."元代金券</li>"?>
                </ul>
            </div>
        </div>
        <div class="foot">
          <img src="<?= \view::src("img/yike-fm.png")?>"/>
          <div class="desc">关注易灵微课公众号，获取更多优质课程</div>
        </div>
    </div>
</div>

<div class="mask hide flex flex-col btn-pop-close" id="pop-mask">
    <div class="pop flex flex-col">
      <a class="btn btn-pop-close btn-close">&times</a>
        <div id="pop-head" class="pop-head"></div>
        <div id="pop-body" class="pop-body"></div>
        <div id="pop-img-success" class="pop-img hide">
            <img src="<?= \view::src('img/coupon/haggle-success.png') ?>"/>
        </div>
        <div id="pop-img-failure" class="pop-img hide">
            <img src="<?= \view::src('img/coupon/haggle-failure.png') ?>"/>
        </div>
        <div id="pop-tail" class="pop-foot">
        </div>
        <div id="pop-foot" class="pop-foot">
            <button class="btn-pop-close">知道了</button>
<!--            <button class="btn-pop-link hide"><a href="/">前往挑选课程</a></button>-->
        </div>
    </div>
</div>
<?php if($this->haggle['number'] == 0 && $this->user['sn'] == $this->usn && $this->startup==0) {?>
    <div class="mask flex flex-col" id="pop-note" onclick="$('#pop-note').hide()">
      <div>点击右上角微信菜单<span>···</span>邀请好友助力</div>
      <div class="btn">知道了</div>
    </div>
<?php } ?>


<?= \view::js([
    'resource/jquery/jquery.min',
    'js/jweixin-1.2.0',
//    'js/utils'
]) ?>

<script>
      // screenAdaptor(750, function(screen) {
      //   return screen.width < screen.height ? screen.width : '750'
      // })
    $(function () {
        var popHead = $('#pop-head');
        var popBody = $('#pop-body');
        var popTail = $('#pop-tail');
        if (<?=($this->haggle['number'] == 0 && $this->user['sn'] == $this->usn) ? 0 : 0?>) {
          popHead.html('还没有好友来帮忙');
          popBody.html('点击右上角微信菜单[···]分享邀请好友助力吧');
          $('#pop-img-success').removeClass('hide');
          $('#pop-mask').removeClass('hide');
        }
        $('#helpHaggle').click(function () {
            $('.pop-img').addClass('hide');
            $.post('./promote-haggle-help', {
                'sn': '<?=$this->sn?>'
            }, function (res) {
                if (res.error === '0') {
                    popHead.html("成功帮<?=$this->user['name']?>省了￥" + res.data.haggle);
                    $('#pop-img-success').removeClass('hide');
                    if (res.data.reward) {
                      popBody.html("<div>同时您获得" + res.data.reward + "元新人代金券</div>");
                      // popTail.html("<div><a href='/' style='text-decoration: solid'>去挑选感兴趣的课程</a></div>");
                      $('button.btn-pop-close').html("<div><a href='/' style='color: #fff'>去看看有什么课程</a></div>")
                    } else {
                      $('button.btn-pop-close').html("<div><a href='<?=$this->detailUrl?>' style='color: #fff'>我也要学</a></div>")
                      // $('.btn-pop-close').click(function () {
                      //   location.reload();
                      // })
                    }
                } else {
                    popHead.html("助力失败");
                    popBody.html(res.message);
                    $('#pop-img-failure').removeClass('hide');
                }
                $('#pop-mask').removeClass('hide');
            })
        });
        $('.btn-pop-close').click(function () {
            $('#pop-mask').addClass('hide');
        });
        $('.pop').click(function () {
            event.stopPropagation();
        });
        $('#enroll').click(function () {
            $.post('./promote-draw', {
                'sn': '<?=$this->sn?>',
                'force': true
            }, function (res) {
                if (res.error === '0') {
                    location.href = res.data
                } else {
                    popHead.html("使用助力券失败");
                    popBody.html(res.message);
                    $('#pop-mask').removeClass('hide');
                    $('#pop-img-failure').removeClass('hide');
                }
            })
        });

        wx.config({
            debug: false, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
            appId: '<?=$this->wxConfig['appId'] ?? ''?>', // 必填，公众号的唯一标识
            timestamp: '<?=$this->wxConfig['timestamp'] ?? ''?>', // 必填，生成签名的时间戳
            nonceStr: '<?=$this->wxConfig['nonceStr'] ?? ''?>', // 必填，生成签名的随机串
            signature: '<?=$this->wxConfig['signature'] ?? ''?>',// 必填，签名，见附录1
            jsApiList: ['onMenuShareAppMessage', 'onMenuShareTimeline'] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
        });
        wx.ready(function(){
            wx.onMenuShareTimeline({
                title: '<?=$this->user['name']?>的报名费能省多少，就看你了', // 分享标题
                link: '<?=$this->wxConfig['link']?>', // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
                imgUrl: '<?=\view::src('img/icon_108.png')?>', // 分享图标
                success: function () {
                    // 用户确认分享后执行的回调函数
                },
                cancel: function () {
                    // 用户取消分享后执行的回调函数
                }
            });
            wx.onMenuShareAppMessage({
                title: '<?=$this->user['name']?>的报名费能省多少，就看你了', // 分享标题
                desc: '<?= '我想报名易灵微课《'.$this->lesson['title'].'》，来帮我助力吧~' ?>', // 分享描述
                link: '<?=$this->wxConfig['link']?>', // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
                imgUrl: '<?=\view::src('img/icon_108.png')?>', // 分享图标
                type: '', // 分享类型,music、video或link，不填默认为link
                dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
                success: function () {
                    // 用户确认分享后执行的回调函数
                },
                cancel: function () {
                    // 用户取消分享后执行的回调函数
                }
            });
        });
    })
</script>
</body>
</html>
