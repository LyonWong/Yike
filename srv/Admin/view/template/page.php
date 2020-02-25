<?php namespace Admin; ?>
<!DOCTYPE html>
<html>

<head>
    <title><?=wdgtLang::menu(servSession::$scopeKey)?></title>
<?php
    $this->tpl('/header');
    $this->tpl('/src-admin');
?>
</head>

<body class="page-header-fixed page-quick-sidebar-over-content page-style-square">
<?php $this->tpl('page-header')?>

<div class="clearfix"></div>

<div class="page-container">
    <?php $this->tpl('page-sidebar')?>
    <div class="page-content-wrapper">
        <div class="page-frame">
        <div class="page-content" id="page-content">
            <?php $this->tpl($this->page)?>
        </div>
        </div>
    </div>
</div>

<?php $this->tpl('page-footer')?>
<?php $this->tpl('/footer')?>

</body>
</html>