<ul>
<?php foreach ($this->list as $item) { ?>
    <li><a href="lesson-live-<?=$item['sn']?>">课程 <?=$item['title']?> | <?=$item['sn']?></a></li>
<?php } ?>
</ul>
