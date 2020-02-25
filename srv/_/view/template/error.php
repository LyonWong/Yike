<!DOCTYPE html>
<html>
<head>
  <meta charset=utf-8>
  <meta name=viewport content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no">
  <title>易灵微课</title>
  <style>
    html {
      height: 100%;
    }
    body {
      position: relative;
      margin: 0 auto;
      height: 100%;
      background: #f0eff5;
      -webkit-font-smoothing: antialiased;
      -moz-osx-font-smoothing: grayscale;
      font-family: sans-serif;
      display: flex;
      flex-direction: column;
    }
    .head {
      height: 1rem;
      text-align: center;
      font-size: .4rem;
      line-height: 1rem;
      background: #fff;
      border-bottom: 1px solid rgba(0,0,0,0.3);
    }
    .content {
      padding-top: 1rem;
      display: flex;
      flex-direction: column;
      align-items: center;
      background: #fafafa;
      font-size: .3rem;
      color: #333;
      flex-grow: 1;
    }
    .message {
      padding: .5rem;
      background: #f0f0f0;
      width: 6rem;
    }
    .link {
      padding: 1rem;
      font-size: .24rem;
    }
    .foot {
      position: fixed;
      bottom: 0;
      background: #fff;
      height: 1rem;
      line-height: 1rem;
      width: 7.5rem;
      color: #333;
      text-align: center;
    }
  </style>
</head>
<body>
<div class="head">
  出错了(´｡• ᵕ •｡`)
</div>
<div class="content">
<!--  <div class="code"></div>-->
  <div class="message">错误[<?=$this->code?>]: <?=$this->message?></div>
  <?php if ($this->link) { ?>
    <div class="link"><a href="<?=$this->link?>">继续前往</a></div>
  <?php } ?>
</div>
<div class="foot">
  <div>有疑问请加小助手微信[yike-01]咨询</div>
</div>
<?=view::js('js/utils')?>
<script type="text/javascript">
  screenAdaptor(750);
</script>
</body>
</html>