<div class="form-group">
    <div class="input-inline ">
        <div class="input-group date-picker input-daterange " data-date-format='yyyy-mm-dd'>
            <span class="input-group-btn">
                <button class="btn default" type="button"><i class="fa fa-calendar"></i></button>
            </span>
            <input type="text" class="form-control input-date" name="dateStart" value="<?= $this->dateStart ?>">
            <span class="input-group-addon">~</span>
            <input type="text" class="form-control input-date" name="dateEnd" value="<?= $this->dateEnd ?>">
        </div>
    </div>
</div>
