<div class="form-group">
    <div class="input-inline ">
        <div class="input-group">
            <span class="input-group-addon"><?=$this->prefix?></span>
            <select class="form-control input-field" name="<?=$this->field['name']?>">
                <?=\Admin\wdgtForm::options($this->field['options'], $this->field['value'])?>
            </select>
            <input type="text" class="form-control input-small" name="<?=$this->value['name']?>" value="<?= $this->value['value'] ?>">
        </div>
    </div>
</div>
