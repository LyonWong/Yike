<style>
    h2 {
        font-family: cursive;
        margin-bottom: 30px;
    }
    .history-sign {
        padding: 30px 50px;
        background-color: #414763;
        /*text-align: center;*/
        font-size: 25px;
        color: #fff;
        cursor: pointer;
    }
</style>
<h2>Welcome to E-Stats</h2>
<?php if (!$this->list) { ?>
    <p>If you don't have any authority, please contact developer@gamebegin.com</p>
<?php } ?>
<div class="row">
    <?php foreach ($this->list as $key => $cnt) {
        $item = $this->dict[$key]; ?>
        <div class="col-md-3">
            <div class="history-sign" data-path="<?= $item['path'] ?>">
                <?php foreach ($item['names'] as $i => $name) {?>
                <div><?=str_repeat('　', $i).'├─ '.$name?></div>
                <?php }?>
            </div>
        </div>
    <?php } ?>
</div>
<script type="text/javascript">
    $('.history-sign').click(function () {
        window.location.href = $(this).data('path');
    });
</script>
     
