<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
  <title>易灵微课-<?=$this->profile['title']?></title>
  <style>
    html {
      background: #f7f7f7;
    }
    body {
      margin: 0 auto;
    }
    .head {
      display: flex;
      justify-content: space-between;
    }
    .title {
      z-index: 9;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 1rem;
      margin: 0;
      line-height: 1rem;
      text-align: center;
      background: #fff;
      box-shadow: 0 0 5px #999;
    }
    .content {
      margin: 1.1rem 0;
      background: #fff;
      border: 1px solid #999;
    }
    .row {
      padding: .1rem .3rem;
    }
    .row .head {
      display: flex;
    }
    .row .body {
      padding: .2rem 0;
      border-bottom: 1px solid #aaa;

    }
    .row:last-child .body {
      border: none;
    }
    img, audio, video {
      max-width: 100%;
    }
  </style>
</head>
<body>
  <h1 class="title">
    <?=$this->profile['title']?>
  </h1>
  <div class="content">
    <?php foreach ($this->data as $row) { $content = $row['content'][0]; ?>
        <div class="row">
          <div class="head">
            <div class="name"> <?=$row['accountNick']?> </div>
            <div class="time"> <?=$row['tms']?> </div>
          </div>
          <div class="body">
              <?php
              switch ($content['MsgType']) {
                case 'TIMTextElem';
                  echo "<span>{$content['MsgContent']['Text']}</span>";
                  break;
                case 'TIMCustomElem':
                  $msg = $content['MsgContent'];
                  switch ($msg['Desc']) {
                      case 'IMAGE':
                        echo "<img src='". preg_replace('#\?.*$#', '', $msg['Data']) ."'/>";
                        break;
                      case 'SOUND':
                        echo "<audio src='$msg[Data]' controls/>";
                        break;
                      case 'VIDEO':
                        echo "<video src='". preg_replace('#\.m3u8$#', '', $msg['Data']) . "' controls/>";
                        break;
                      case 'QUOTE':
                          echo "<span>$msg[Data]</span>";
                        break;
                  }
                  break;
              }
              ?>

          </div>
        </div>
    <?php } ?>
  </div>
<script type="text/javascript">
  function screenAdaptor(designWidth, fit) {
    function f() {
      var d = document;
      var b = d.body;
      var s = b.style;
      s.maxWidth = fit ? fit(window.screen) +'px' : designWidth + 'px';
      s.fontSize = getComputedStyle(b)['font-size'];
      d.documentElement.style.fontSize = (b.offsetWidth * 100 / designWidth) + 'px';
    }
    f();
    window.addEventListener('onorientationchange' in window ? 'orientationchange' : 'resize', f, false);
  }
  screenAdaptor(750);
</script>
</body>
</html>