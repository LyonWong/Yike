<div class="portlet box blue util-btn-group-margin-bottom-5">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-gift"></i>
            <span>
                发布控制
            </span>
        </div>
        <div class="actions">
            <a href="#" class="btn btn-default btn-release" data-mode="server">后端</a>
            <a href="#" class="btn btn-default btn-release" data-mode="build">前端+后端</a>
            <a href="#" class="btn btn-default btn-release" data-mode="wiki">讲师指南</a>
        </div>
    </div>
    <div id="portlet-body" class="portlet-body util-btn-margin-bottom-5">
        <h4>当前版本：<?=$this->data['info']?></h4>
        <div id="info" class="note note-main hide" style="overflow-y: scroll; max-height: 800px">
        </div>
    </div>
</div>


<script>
    $(function () {
        $('.btn-release').click(function(){
            $('.btn').addClass('disabled');
            var info = $('#info');
            info.addClass('note-main');
            info.removeClass('note-success');
            $.post('./deploy-release', $(this).data(), function(res) {
                if (res.error === '0') {
                    var cursor = 0;
                    info.html('<div>发布开始</div><div>--------</div>');
                    info.removeClass('hide');
                    var intervalId = setInterval(function(){
                        $.get('./deploy-info?cursor='+cursor, function(res){
                            if (res.error === '0') {
                                cursor = res.data.cursor;
                                res.data.info.forEach(function(row){
                                    info.append('<div>'+row+'</div>');
                                });
                                info.scrollTop(info[0].scrollHeight);
                            } else {
                                clearInterval(intervalId);
                                $('.btn').removeClass('disabled');
                                info.append('<div>--------</div><div>发布结束</div>');
                                toastr['success']('发布结束');
                                info.addClass('note-success');
                                info.removeClass('note-main');
                            }
                        });
                    }, 1000);
                } else {
                    toastr['warning'](res.message);
                    $('.btn').removeClass('disabled');
                }
            })
        });

    });
</script>