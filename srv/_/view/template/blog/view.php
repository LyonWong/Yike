<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title><?=$this->setting['title']?:"易灵微课-$this->title"?></title>
    <?php if($this->setting['keywords']) echo "<meta name='keywords' Content='{$this->setting['keywords']}'>"?>
    <?php if($this->setting['description']) echo "<meta name='description' Content='{$this->setting['description']}'>"?>
    <?=\view::css('css/global')?>
    <?=\view::css('css/blog')?>
</head>
<body>
<div class="blog-view">
  <div class="cover">
    <img src="<?=$this->setting['cover']??''?>"/>
  </div>
  <div class="title">
    <h1><?=$this->title?></h1>
  </div>
  <div class="content">
    <div class="markdown"><?=$this->markdown?></div>
  </div>
</div>
<?=\view::js('js/utils')?>
<?=\view::js('js/foot')?>
<script type="text/javascript">
  screenAdaptor(750, function(screen) {
    return screen.width < screen.height ? screen.width : '750'
  })
</script>
</body>
</html