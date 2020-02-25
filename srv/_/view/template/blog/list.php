<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>全部知识文章_干货分享_易灵微课</title>
  <meta name="keywords" content="知识,知识文章,职场,工作方法,新媒体,个人品牌,实用技能,搜索,能力提升,营销,终身学习">
  <meta name="description" content="易灵微课知识文章是一个综合职场、工作方法、实用技能、品牌营销等干货分享的平台，专注于通过知识付费服务让用户通过终身学习提升能力">
    <?= \view::css('css/global') ?>
    <?= \view::css('css/blog') ?>
</head>
<body>
<div class="blog-list">
    <?php foreach ($this->list as $item) { ?>
      <a class="blog-item flex-row" href="<?="./blog-view-$item[sn]"?>" target="_blank">
        <div class="cover">
          <img src="<?= $item['setting']['cover'] ?>"/>
        </div>
        <div class="datum flex-col">
          <div class="datum-head font-bold"><?= $item['title'] ?></div>
          <div class="datum-body flex-row">
          </div>
          <div class="datum-foot flex-row">
            <div class="tags"><?= $item['tags'] ?></div>
            <div class="time"><?= $item['tms_update'] ?></div>
          </div>
        </div>
      </a>
    <?php } ?>
</div>
<div class="blog-turn flex-row">
<!--    --><?php //if ($this->prev) echo "<a href='$this->prev'>上一页</a>" ?>
<!--    --><?php //if ($this->next) echo "<a href='$this->next'>下一页</a>" ?>
<!--    --><?php //if (!$this->prev && !$this->next) echo "<a href='./blog-list'>回首页</a>"?>
  <a href="<?=$this->turn?>">换一页</a>
</div>
<?= \view::js('js/utils') ?>
<?=\view::js('js/foot')?>
<script type="text/javascript">
  screenAdaptor(750, function (screen) {
    return screen.width < screen.height ? screen.width : '750'
  })
</script>
</body>
</html>
