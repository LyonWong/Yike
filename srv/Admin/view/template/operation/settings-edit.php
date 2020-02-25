<?php namespace Admin;
$detail = $this->detail?>
<div class="portlet box blue util-btn-group-margin-bottom-5">
  <div class="portlet-title">
    <div class="caption">
      <div class="caption">设置编辑</div>
    </div>
    <div class="actions">
      <a href="./settings-list" class="btn btn-default">返回列表</a>
    </div>
  </div>
  <div class="portlet-body form portlet-empty">
    <form role="form" id="form" class="form-horizontal" method="post" onsubmit="return check()" action="./settings-edit?id=<?=$detail['id']??0?>">
      <div class="form-body">
        <div class="form-group">
          <label class="col-md-2 control-label">类型</label>
          <div class="col-md-5">
            <select name="type" class="form-control">
                <?=wdgtForm::options($this->dict, $this->types[$detail['i_type']]??null)?>
            </select>
          </div>
        </div>
        <div class="form-group">
          <label class="col-md-2 control-label">条目</label>
          <div class="col-md-5">
            <input class="form-control" name="item" value="<?= $detail['item'] ?? null ?>"/>
          </div>
        </div>
        <div class="form-group">
          <label class="col-md-2 control-label">备注</label>
          <div class="col-md-5">
            <input class="form-control" name="remark" value="<?= $detail['remark'] ?? null ?>"/>
          </div>
        </div>
        <div class="form-group">
          <label class="col-md-2 control-label">资料</label>
          <div class="col-md-5">
            <textarea class="form-control" rows="15" name="datum" required id="datum"><?=json_encode($detail['datum'], JSON_UNESCAPED_UNICODE)?></textarea>
          </div>
        </div>
        <div class="col-md-offset-2">
          <button type="submit" class="btn green" id="submit">保存</button>
          <button type="button" class="btn default" onclick="format()">格式化</button>
        </div>
      </div>
    </form>
  </div>
</div>
<script>
  var objDatum = $('#datum');
  function format() {
    try {
      objDatum.val(JSON.stringify(JSON.parse(objDatum.val()), null, '  '))
    } catch (e) {
      alert(e)
    }
  }
  function check() {
    try {
      return JSON.parse(objDatum.val());
    } catch (e) {
      alert(e);
      return false
    }
  }
  format();
</script>