<?php namespace Admin;
$data = $this->data?>
<div class="portlet box blue util-btn-group-margin-bottom-5">
  <div class="portlet-title">
    <div class="caption">
      <div class="caption">文章编辑</div>
    </div>
    <div class="actions">
      <a href="./list" class="btn btn-default">返回列表</a>
    </div>
  </div>
  <div class="portlet-body form portlet-empty">
    <form role="form" class="form-horizontal" method="post" action="./edit?id=<?=$data['id']??0?>">
      <div class="form-body">
        <div class="form-group">
          <label class="col-md-2 control-label">标题</label>
          <div class="col-md-5">
            <input class="form-control" name="title" required value="<?= $data['title']??null ?>"/>
          </div>
        </div>
        <div class="form-group">
          <label class="col-md-2 control-label">封面</label>
          <div class="col-md-5">
            <input class="form-control" name="setting[cover]" value="<?= $data['setting']['cover'] ?? null ?>"/>
          </div>
        </div>
        <div class="form-group">
          <label class="col-md-2 control-label">分类</label>
          <div class="col-md-5">
            <select name="category" class="form-control">
              <?=wdgtForm::options($this->category, $data['category']??'_')?>
            </select>
          </div>
        </div>
        <div class="form-group">
          <label class="col-md-2 control-label">标签</label>
          <div class="col-md-5">
            <input class="form-control" name="tags" placeholder="空格分隔" value="<?= $data['tags']??null ?>"/>
          </div>
        </div>
        <div class="form-group">
          <label class="col-md-2 control-label">权重</label>
          <div class="col-md-5">
            <input class="form-control" name="weight" value="<?= $data['weight']??0 ?>"/>
          </div>
        </div>
        <div class="form-group">
          <label class="col-md-2 control-label">title</label>
          <div class="col-md-5">
            <input class="form-control" name="setting[title]" placeholder="HTML页面title, 默认为[易灵微课-{文章标题}]" value="<?= $data['setting']['title'] ?? null ?>"/>
          </div>
        </div>
        <div class="form-group">
          <label class="col-md-2 control-label">keywords</label>
          <div class="col-md-5">
            <input class="form-control" name="setting[keywords]" placeholder="HTML页面关键词，以`,`分隔" value="<?= $data['setting']['keywords'] ?? null ?>"/>
          </div>
        </div>
        <div class="form-group">
          <label class="col-md-2 control-label">description</label>
          <div class="col-md-5">
            <input class="form-control" name="setting[description]" placeholder="HTML页面描述" value="<?= $data['setting']['description'] ?? null ?>"/>
          </div>
        </div>
        <div class="form-group">
          <label class="col-md-2 control-label">正文</label>
          <div class="col-md-5">
            <textarea class="form-control" rows="30" name="content" required><?= $data['content']??null ?></textarea>
          </div>
        </div>
        <div class="col-md-offset-2">
          <button type="submit" class="btn green" id="conf_submit">保存</button>
        </div>
      </div>
    </form>
  </div>
</div>
<script>
</script>