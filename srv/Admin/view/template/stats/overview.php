<?php namespace Admin;?>
<div class="page-bar ">
    <form class="form-inline" role="form">
        <?= wdgtPicker::dateRange($this->query['dateStart'], $this->query['dateEnd']) ?>
        <?= wdgtForm::submit() ?>
    </form>
</div>
<div class="row">
    <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
        <div class="dashboard-stat blue-madison">
            <div class="visual">
                <i class="fa fa-comments"></i>
            </div>
            <div class="details">
                <div class="number">
                    <?=$this->summary['user.signin.unique']??null?>
                </div>
                <div class="desc">
                    累计用户
                </div>
            </div>
            <a class="more" href="#">
                View more <i class="m-icon-swapright m-icon-white"></i>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
        <div class="dashboard-stat blue-madison">
            <div class="visual">
                <i class="fa fa-comments"></i>
            </div>
            <div class="details">
                <div class="number">
                    <?=$this->summary['teacher.lesson.count']??null?>
                </div>
                <div class="desc">
                    累计课程
                </div>
            </div>
            <a class="more" href="#">
                View more <i class="m-icon-swapright m-icon-white"></i>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
        <div class="dashboard-stat blue-madison">
            <div class="visual">
                <i class="fa fa-comments"></i>
            </div>
            <div class="details">
                <div class="number">
                    <?=$this->summary['lesson.access.unique']??null?>
                </div>
                <div class="desc">
                    累计听课
                </div>
            </div>
            <a class="more" href="#">
                View more <i class="m-icon-swapright m-icon-white"></i>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
        <div class="dashboard-stat blue-madison">
            <div class="visual">
                <i class="fa fa-comments"></i>
            </div>
            <div class="details">
                <div class="number">
                    <?=$this->summary['lesson.income.sum']??null?>
                </div>
                <div class="desc">
                    累计收入
                </div>
            </div>
            <a class="more" href="#">
                View more <i class="m-icon-swapright m-icon-white"></i>
            </a>
        </div>
    </div>

</div>

<div class="portlet box blue">
    <div class="portlet-title">
        <div class="caption">趋势</div>
        <div class="actions">
            <div class="input-group" style="width:300px">
                <span class="input-group-addon"><i class="fa fa-filter"></i></span>
                <input id="filter" class="form-control" placeholder="Filter"/>
            </div>
        </div>
    </div>
    <div class="portlet-body portlet-compact">
        <div>
            <div class="datatable-hide-search">
                <table id="table-trigger-list" class="table table-bordered table-hover">
                    <thead>
                    <tr>
                        <th>日期</th>
                        <th>用户</th>
                        <th>开课</th>
                        <th>听课</th>
                        <th>收入</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($this->period as $date => $item) { ?>
                        <tr>
                            <td><?=$date?></td>
                            <td><?=$item['user.signin.unique']??null?></td>
                            <td><?=$item['teacher.lesson.count']??null?></td>
                            <td><?=$item['lesson.access.unique']??null?></td>
                            <td><?=$item['lesson.income.sum']??null?></td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>