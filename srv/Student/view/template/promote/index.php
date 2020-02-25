<html>
<head>
    <meta charset="UTF-8">
    <title>易灵微课</title>
    <?= view::css('css/promote') ?>
</head>
<body>

<div class="layout flex flex-col">
    <div class="ticket upper">
        <div class="title-main">易灵微课优惠活动</div>
        <div class="blank-bar-20"></div>
        <div class="btn-group">
            <button class="btn-main <?=$this->target['lesson_id'] ? '': 'hide'?>" id="btn-haggle">邀请好友帮砍价</button>
            <button class="btn-main <?=$this->enrolled ? '': 'btn-disabled'?>" id="btn-reward">赠送好友特价券</button>
            <button class="btn-main <?=$this->enrolled ? '': 'btn-disabled'?>" id="btn-sales"> 推广课程赚佣金 </button>
        </div>
    </div>
    <div class="ticket lower flex flex-col">
       <div class="rule">
            <div class="rule-title flex flex-row">
                <span class="rule-line rule-pre-line"></span>
                <span>　活动说明　</span>
                <span class="rule-line rule-suf-line"></span>
            </div>
            <div class="rule-list flex">
                <ul>
                    <li <?=$this->target['lesson_id'] ? '' : 'class="hide"'?>>报名前邀请好友砍价，可获得最高40%的折扣，砍掉的金额会作为鼓励金赠送给好友，可用于报名任意课程</li>
                    <li>报名后可获得3张7折特价券，可分享给好友使用</li>
                    <li>参与推广课程可获得佣金，在听课后72小时结算，可提现</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="mask hide flex flex-col btn-pop-close" id="pop-mask">
    <div class="pop flex flex-col">
        <div id="pop-head" class="pop-head"></div>
        <div id="pop-body" class="pop-body"></div>
        <div id="pop-img-success" class="pop-img hide">
            <img src="<?=\view::src('img/coupon/haggle-success.png')?>"/>
        </div>
        <div id="pop-img-failure" class="pop-img hide">
            <img src="<?=\view::src('img/coupon/haggle-failure.png')?>"/>
        </div>
        <div id="pop-foot" class="pop-foot">
            <button class="btn-pop-close">知道了</button>
        </div>
    </div>
</div>


<?= \view::js('resource/jquery/jquery.min') ?>

<script>
    $(function () {
        var popHead = $('#pop-head');
        var popBody = $('#pop-body');
        $('#btn-haggle').click(function(){
            $.post('./promote-haggle-init', {
                'target_sn': '<?=$this->target_sn?>'
            }, function(res) {
                if (res.error === '0') {
                    location.href = '/promote-haggle?sn='+res.data;
                } else {
                    popHead.html("启动砍价失败");
                    popBody.html(res.message);
                    $('#pop-img-failure').removeClass('hide');
                    $('#pop-mask').removeClass('hide');
                }
            })
        });
        $('#btn-reward').click(function(){
            $.post('./promote-create-reward', {
                'target_sn': '<?=$this->target_sn?>'
            }, function(res){
                if (res.error === '0') {
                    location.href = res.data;
                } else {
                    popHead.html("生成特价券失败");
                    popBody.html(res.message);
                    $('#pop-img-failure').removeClass('hide');
                    $('#pop-mask').removeClass('hide');
                }
            })
        });
        $('#btn-sales').click(function(){
            if ($(this).hasClass('btn-disabled')) {
                popHead.html("参与失败");
                popBody.html("报名后才能参与推广");
                $('#pop-img-failure').removeClass('hide');
                $('#pop-mask').removeClass('hide');
            } else {
                location.href= '/promote-sales?target_sn=<?=$this->target_sn?>';
            }
        });

        $('.btn-pop-close').click(function () {
            $('#pop-mask').addClass('hide');
        });
        $('.pop').click(function () {
            event.stopPropagation();
        });
    })
</script>
</body>
</html>