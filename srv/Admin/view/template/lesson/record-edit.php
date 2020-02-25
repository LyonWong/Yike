<?php namespace Admin;
$data = $this->data;
$content = $data['content'];?>
<div class="portlet box blue util-btn-group-margin-bottom-5">
  <div class="portlet-title">
    <div class="caption">
      <div class="caption">课程内容编辑</div>
    </div>
    <div class="actions">
<!--      <a href="./record?lesson_sn=--><?//=$this->lessonSn?><!--" class="btn btn-default">返回列表</a>-->
    </div>
  </div>
  <div class="portlet-body form portlet-empty">
    <form role="form" class="form-horizontal" method="post" action="./record-edit?lesson_sn=<?=$this->lessonSn?>&form=<?=$this->form?>&id=<?=$data['id']??0?>">
      <div class="form-body">
        <div class="form-group">
          <label class="col-md-2 control-label">类型</label>
          <div class="col-md-5">
            <select class="form-control" name="content[type]" id="content-type">
              <?=wdgtForm::options([
                  $this->type => $this->type,
                  'markdown' => 'Markdown',
                  'image' => '图片',
                  'audio' => '音频',
                  'video' => '视频',
              ], $this->type)?>
            </select>
          </div>
        </div>
        <?php switch ($this->type) {
          case 'markdown': ?>
        <div class="form-group">
          <label class="col-md-2 control-label">文本</label>
          <div class="col-md-5">
            <textarea class="form-control" rows="30" name="content[text]" required><?= $content['text']??null ?></textarea>
          </div>
        </div>
        <?php break; case 'image': case 'audio':?>
            <div class="form-group">
              <label class="col-md-2 control-label">地址</label>
              <div class="col-md-5">
                <input class="form-control" name="content[src]" placeholder="资源地址" value="<?= $content['src'] ?? null ?>" required/>
              </div>
            </div>
            <div class="form-group">
              <label class="col-md-2 control-label">描述</label>
              <div class="col-md-5">
                <textarea class="form-control" rows="15" name="content[text]" placeholder="文本说明"><?= $content['text'] ?? null ?></textarea>
              </div>
            </div>
        <?php break;case 'video': ?>
          <div class="form-group">
            <label class="col-md-2 control-label">地址</label>
            <div class="col-md-5">
              <input class="form-control" name="content[src]" placeholder="资源地址" value="<?= $content['src'] ?? null ?>" required/>
            </div>
          </div>
          <div class="form-group">
            <label class="col-md-2 control-label">封面</label>
            <div class="col-md-5">
              <input class="form-control" name="content[poster]" placeholder="图片地址" value="<?= $content['poster'] ?? null ?>"/>
            </div>
          </div>
            <div class="form-group">
              <label class="col-md-2 control-label">描述</label>
              <div class="col-md-5">
                <textarea class="form-control" rows="15" name="content[text]" placeholder="文本说明"><?= $content['text'] ?? null ?></textarea>
              </div>
            </div>
          </div>
        <?php break;case 'video': ?>
        <?php break;default: ?>
          <div class="form-group">
            <label class="col-md-2 control-label">内容</label>
            <div class="col-md-5">
              <textarea class="form-control" rows="30" name="content" required id="content-json"></textarea>
            </div>
          </div>
            <script>
              $('#content-json').val(JSON.stringify(<?=json_encode($content)?>, null, 2))
            </script>
        <?php } ?>
        <div class="col-md-offset-2">
          <button type="submit" class="btn green" id="conf_submit">保存</button>
        </div>
      </div>
    </form>
  </div>
</div>
<script>
  $('#content-type').change(function(){
    location.href = location.href + '&type=' + $(this).val()
  })
</script>