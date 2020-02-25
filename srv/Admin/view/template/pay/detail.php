<?php
$detail = $this->detail;
$order = $detail['order'];
$refund = $detail['refund'];
$lesson = $detail['lesson'];
$student = $detail['student'];
?>
<div class="portlet box blue">
    <div class="portlet-title">
        <div class="caption">
            <div class="caption">订单详情</div>
        </div>
    </div>
    <div class="portlet-body form portlet-empty">
        <form role="form" class="form-horizontal">
            <div class="form-body">
                <div class="form-group">
                    <label class="col-md-2 control-label">订单号</label>
                    <div class="col-md-5">
                        <span class="form-control"><?= $order['sn'] ?></span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">订单状态</label>
                    <div class="col-md-5">
                        <span class="form-control"><?= $order['i_status'] ?></span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">订单金额</label>
                    <div class="col-md-5">
                        <span class="form-control">￥<?= $order['order_amount'] ?></span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">订单生成时间</label>
                    <div class="col-md-5">
                        <span class="form-control"> <?= $order['tms_create'] ?> </span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">支付方式</label>
                    <div class="col-md-5">
                        <div class="form-control"> <?= $order['i_pay_way'] ?> </div>
                    </div>
                </div>
                <?php if($refund !=null) {?>
                <div class="form-group">
                    <label class="col-md-2 control-label">退款单号</label>
                    <div class="col-md-5">
                        <div class="form-control"> <?= $refund['sn']?> </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">退款状态</label>
                    <div class="col-md-5">
                        <div class="form-control"> <?= $refund['i_status']?> </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">退款时间</label>
                    <div class="col-md-5">
                        <div class="form-control"> <?= $refund['tms_update']?> </div>
                    </div>
                </div>
                <?php } ?>
                <div class="form-group">
                    <label class="col-md-2 control-label">退款用户</label>
                    <div class="col-md-5">
                        <div class="form-control"> <?= $student['name']?> </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">相关课程</label>
                    <div class="col-md-5">
                        <div class="form-control"> <a target="_blank" href="/lesson/detail?lesson_sn=<?= $lesson['sn']?>"><?= $lesson['title']?></a> </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>