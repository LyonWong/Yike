<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <meta name="keywords" content="知识,知识文章,职场,工作方法,新媒体,个人品牌,实用技能,搜索,能力提升,营销,终身学习">
  <meta name="description" content="易灵微课知识文章是一个综合职场、工作方法、实用技能、品牌营销等干货分享的平台，专注于通过知识付费服务让用户通过终身学习提升能力">
  <title>易灵微课</title>
  <?=\view::css('css/index')?>
</head>
<body>
<div class="container">
  <div class="nav">
    <img id='logo-text' src="<?=view::src('img/index/logo-text.png')?>"/>
    <a class="nav-a nav-3" href="/lesson/">课程</a>
    <a class="nav-a nav-2" href="/blog">资讯</a>
    <a class="nav-a nav-1" href="<?=$this->teacher_url?>">讲师</a>
    <input type="radio" name="radio-set" checked id="nav1">
<!--    <a class="nav-a" href="#panel1">首页</a>-->
    <label for="nav1"></label>
    <input type="radio" name="radio-set" id="nav2">
<!--    <a class="nav-a" href="#panel2">课程</a>-->
    <label for="nav2"></label>
    <input type="radio" name="radio-set" id="nav3">
<!--    <a class="nav-a" href="#panel3">文章</a>-->
    <label for="nav3"></label>
    <input type="radio" name="radio-set" id="nav4">
<!--    <a class="nav-a" href="#panel4">特性</a>-->
    <label for="nav4"></label>
    <input type="radio" name="radio-set" id="nav5">
<!--    <a class="nav-a" href="#panel5">关于</a>-->
    <label for="nav5"></label>
    <div class="scroll">
      <section class="panel" id="panel1">
        <div class="box box-home" style="background-image: url(<?=view::src('img/index/bg-01.png')?>)">
<!--          <div class="box-mask-home"></div>-->
          <img id="slogan" src="<?=view::src('img/index/slogan.png')?>"/>
          <div class="entry">
            <a href="<?=$this->lesson_url?>">开始学习</a>
            <a href="<?=$this->teacher_url?>">讲师后台</a>
          </div>
        </div>
      </section>
      <section class="panel panelColor" id="panel2">
        <div class="box box-lesson" style="background-image: url(<?=view::src('img/index/bg-02.png')?>)">
          <div class="box-mask-lesson"></div>
          <div class="banner"></div>
          <div class="flex-lesson">
          <?php foreach ($this->lessonList as $lesson) { ?>
            <a class="item" target="_blank" href="<?=$lesson['profile']['type'] == 'series' ? "/lesson/series/{$lesson['profile']['sn']}" : "/lesson/detail/{$lesson['profile']['sn']}"?>">
              <div class="cover">
                <img data-echo="<?=$lesson['profile']['cover']?>!preview"/>
              </div>
              <div class="desc">
                <div class="title"><?=$lesson['profile']['title']?></div>
                <div class="teacher">讲师: <?=$lesson['profile']['teacher']['name']?></div>
                <div class="info">
                  <div class="enrollment"><?=$lesson['profile']['enrollment']?>人 已报名</div>
                  <div class="price">￥<?=$lesson['profile']['price']?></div>
                </div>
              </div>
            </a>
          <?php } ?>
          </div>
        </div>
      </section>
      <section class="panel" id="panel3">
        <div class="box box-blog" style="background-image: url(<?=view::src('img/index/bg-03.png')?>)">
          <div class="box-mask-blog"></div>
          <div class="banner"></div>
          <div class="flex-blog">
            <?php foreach ($this->blogList as $blog) { ?>
              <a class="item" target="_blank" href="<?="/blog-view-$blog[sn]"?>">
                <div class="cover">
                  <img data-echo="<?=$blog['setting']['cover']?>!preview"/>
                </div>
                <div class="title"><?=$blog['title']?></div>
                <div class="desc">
                  <div class="tag"><?=$blog['tags']?></div>
                  <div class="time"><?=strToDate($blog['tms_update'], 'Y-m-d')?></div>
                </div>
              </a>
            <?php } ?>
          </div>
        </div>
      </section>
      <section class="panel" id="panel4">
        <div class="box box-feature" style="background-image: url(<?=view::src('img/index/bg-04.png')?>">
          <div class="box-mask-feature"></div>
          <div class="banner"></div>
          <div class="flex-feature">
            <img class="item" src="<?=\view::src('img/index/F01.png')?>"/>
            <img class="item" src="<?=\view::src('img/index/F02.png')?>"/>
            <img class="item" src="<?=\view::src('img/index/F03.png')?>"/>
          </div>
        </div>
      </section>
      <section class="panel" id="panel5">
        <div class="box box-about" style="background-image: url(<?=view::src('img/index/bg-05.png')?>)">
          <div class="box-mask-about"></div>
          <div class="banner"></div>
          <div class="flex-about">
            <div class="contact">
              <div class="info">
                <div id="scan">
                  <div>扫一扫</div>
                  <span>关注易灵微课公众号</span>
                  <div id="scan-tip"></div>
                </div>
                <div>
                  <div class="con-span">
                    <img src="<?=\view::src('img/index/wechaticon.png')?>"/>
                    <span>微信客服　yike-01</span>
                  </div>
                  <div class="con-span">
                    <img src="<?=\view::src('img/index/mailicon.png')?>"/>
                    <span>合作邮箱　corp@yike.local</span>
                  </div>
                </div>
              </div>
              <img id="qr-squre" src="<?=\view::src('img/index/qr-squre.png')?>"/>
            </div>
            <img id="mockup" src="<?=\view::src('img/index/iPhoneMockup.png')?>"/>
          </div>
          <div class="bottom">
            <div>Corpright <?=$this->info['copyright']?></div>
            <div><?=$this->info['beian']?></div>
          </div>
        </div>
      </section>
    </div>
  </div>
</div>
<?=\view::js('js/index')?>
<?=\view::js('js/foot')?>
<?=\view::js('js/echo')?>
<script>
  (function() {
    var hm = document.createElement("script");
    hm.src = "https://hm.baidu.com/hm.js?0a0aac37343b546ea47c4b07f07a1426";
    var s = document.getElementsByTagName("script")[0];
    s.parentNode.insertBefore(hm, s);
  })();
  Echo.init({
    offset: 0,//离可视区域多少像素的图片可以被加载
    throttle: 0 //图片延时多少毫秒加载
  });
</script>
</body>
</html>
