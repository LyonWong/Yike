<?php $data = $this->data; ?>
<?= \view::js('markdown-it.min'); ?>
<?= \view::css('github-markdown'); ?>

<div class="portlet box blue">
    <div class="portlet-title">
        <div class="caption">
            <div class="caption">课程审核</div>
        </div>
    </div>
    <div class="portlet-body form portlet-empty">
        <form role="form" class="form-horizontal">
            <div class="form-body">
                <div class="form-group">
                    <label class="col-md-2 control-label">讲师姓名</label>
                    <div class="col-md-5">
                        <span class="form-control"><a href="/teacher/detail?tusn=<?=$data['teacher']['sn']?>"><?=$data['teacher']['name']?></a></span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">课程标题</label>
                    <div class="col-md-5">
                        <div class="form-control">
                            <?=$data['title']?>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">课程Id</label>
                    <div class="col-md-5">
                        <div class="form-control">
                            <?=$data['id']?>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">课程SN</label>
                    <div class="col-md-5">
                        <div class="form-control">
                            <?=$data['sn']?>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">公开展示</label>
                    <div class="col-md-5">
                        <div class="form-control">
                            <?= $data['isPublic'] ?'是' :'否' ?>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">课程分类</label>
                    <div class="col-md-5">
                        <div class="form-control">
                            <a href="/lesson/series-detail?series_sn=<?=$data['categoryInfo']['sn']?>"><?= $data['categoryInfo']['name'] ?></a>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">课程封面</label>
                    <div class="col-md-5">
                       <img src="<?="$data[cover]?imageView2/0/w/400"?>"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">课程简介</label>
                    <div class="col-md-5">
<!--                        <textarea class="form-control" rows="10" readonly>--><?//= $data['brief'] ?><!--</textarea>-->
                        <div  id="introduce-content" class="markdown-body" style="border: 1px solid #e5e5e5;padding: 10px"></div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">课程价格</label>
                    <div class="col-md-5">
                        <div class="form-control">
                            <?=$data['price']?>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">开课时间</label>
                    <div class="col-md-5">
                        <div class="form-control">
                            <?=$data['plan']['dtm_start']?>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">预计课时</label>
                    <div class="col-md-5">
                        <div class="form-control">
                            <?=$data['plan']['duration']?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-actions ">
                <div class="form-group">
                    <label class="col-md-2 control-label" for="remark">备注</label>
                    <div class="col-md-5">
                        <input id='remark' type="text" class="form-control" name="remark" placeholder="拒绝请填写理由"/>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-offset-2">
                        <button type="button" class="btn green" id="act-pass">通过</button>
                        <button type="button" class="btn red" id="act-deny">拒绝</button>
                    </div>
                </div>
            </div>
        </form>
        <script>
            jQuery(document).ready(function () {
                $('#act-pass').click(function(){
                    $.post('./review-pass', {
                        lesson_sn: '<?=$this->data['sn']?>',
                        ticket_id: '<?=$this->ticket_id?>',
                    }, function(res){
                        if (res.error == '0') {
                            toastr['success']('审核通过')
                        } else {
                            toastr['warning'](res.message)
                        }
                    })
                });
                $('#act-deny').click(function(){
                    $.post('./review-deny', {
                        lesson_sn: '<?=$this->data['sn']?>',
                        ticket_id: '<?=$this->ticket_id?>',
                        reason: $("#remark").val()
                    }, function (res) {
                        if (res.error == '0') {
                            toastr['success']('已拒绝')
                        } else {
                            toastr['warning'](res.message)
                        }
                    })
                })
            });
            var md = window.markdownit();
            <?php $content = str_replace(["\n","\r\n","\r"], '  \n', $data['brief']);
            $content = str_replace("'","\'",$content);
            ?>
            var content = '<?=$content?>';
            var rst = md.render(content);
            ///////////////
            // 解析 table
            ///////////////
            var tcode = new RegExp(/(\|(?:[^\r\n\|]+\|)+)(?:\r?\n|\r)\|(?:[-—]+\|)+((?:(?:\r?\n|\r)(?:\|(?:[^\n\r\|]+\|)+))+)/,'gu'), curT = 1;
            while(curT !== null){
                curT=tcode.exec(rst);
                if(curT !== null){
                    console.log(curT[2].split(/\r?\n|\r/));
                    var rows = curT[2].split(/\r?\n|\r/).filter(function(a){return a.length === 0 ? false : true;}), rowtrs = '<table><thead><tr><td>'+curT[1].split('|').slice(1,-1).join('</td><td>')+'</td></tr></thead><tbody>';
                    console.log(rows);
                    for(var i in rows){
                        rowtrs += '<tr><td>'+rows[i].split('|').slice(1,-1).join("</td><td>")+'</td></tr>';
                    }
                    rowtrs += '</tbody></table>';
                    rst = rst.replace(curT[0], rowtrs);
                }
            }
            //            document.getElementById("introduce-content").innerHTML = rst;

            $('#introduce-content').html(rst);
        </script>
    </div>
</div>
