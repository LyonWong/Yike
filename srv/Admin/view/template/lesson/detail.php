<?php $data = $this->data; ?>
<?= \view::js('markdown-it.min'); ?>
<?= \view::css('github-markdown'); ?>

<style>
    .fileinput-button {
        position: relative;
        display: inline-block;
        overflow: hidden;
    }
    .fileinput-button input{
        position:absolute;
        right: 0px;
        top: 0px;
        opacity: 0;
        -ms-filter: 'alpha(opacity=0)';
        font-size: 200px;

    }
</style>
<div class="portlet box blue">
    <div class="portlet-title">
        <div class="caption">
            <div class="caption">课程详情</div>
        </div>
    </div>
    <div class="portlet-body form portlet-empty">
        <form class="form-horizontal" id="form-content">
            <div class="form-body">
                <div class="form-group">
                    <label class="col-md-2 control-label">课程ID</label>
                    <div class="col-md-5">
                        <!--                        <span class="form-control">--><?//= $data['sn'] ?><!--</span>-->
                        <input id='sn' type="text" class="form-control" name="sn" value="<?= $data['id'] ?>" readonly/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">课程SN</label>
                    <div class="col-md-5">
<!--                        <span class="form-control">--><?//= $data['sn'] ?><!--</span>-->
                        <input id='sn' type="text" class="form-control" name="sn" value="<?= $data['sn'] ?>" readonly/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">讲师姓名</label>
                    <div class="col-md-5">
                        <span class="form-control"><?= $data['teacher']['name'] ?></span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">课程标题</label>
                    <div class="col-md-5">
                        <input id='title' type="text" class="form-control" name="title" value="<?= $data['title'] ?>"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">课程图片</label>
                    <div class="col-md-5 fileinput-button">
                        <img id='cover-old'  class="" name="cover-old" src="<?= $data['cover'] ?>" style="width: 250px"/>
                        <input type="file" id="cover" name="cover" onchange="imgPreview(this, 'cover-old')">
                    </div>
                </div>
              <div class="form-group">
                <label class="col-md-2 control-label">Banner图片</label>
                <div class="col-md-5 fileinput-button">
                  <img id='banner-old'  class="" name="banner-old" src="<?= $data['banner'] ?>" style="width: 250px"/>
                  <input type="file" id="banner" name="banner" onchange="imgPreview(this, 'banner-old')">
                </div>
              </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">形式</label>
                    <div class="col-md-5">
                        <input id='form' type="text" class="form-control"  value="<?= $data['form'] ?>" readonly/>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-2 control-label">公开展示</label>
                    <div class="col-md-5">
                        <select id="isPublic" name="isPublic" aria-controls="datatable_products" class="form-control input-xsmall input-sm input-inline">
                            <option   value="1" <?= $data['isPublic']? "selected" : ''?>>是</option>
                            <option   value="0" <?= !$data['isPublic']? "selected" : ''?>>否</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">课程分类</label>
                    <div class="col-md-5">
                        <div class="form-control">
                            <a href="/lesson/series-detail?series_sn=<?=$data['categoryInfo']['sn']??''?>"><?= $data['categoryInfo']['name']??'' ?></a>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">课程简介</label>
                    <div class="col-md-5" style="height: 500px;overflow:auto">
                        <textarea class="form-control" rows="24" id="brief" name="brief"><?= $data['brief'] ?></textarea>
                    </div>
                    <div class="col-md-5"  style="height: 500px;overflow:auto">
                        <div  id="brief-pre" class="markdown-body" style="border: 1px solid #e5e5e5;padding: 10px"></div>

                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">课程价格</label>
                    <div class="col-md-5">
                        <input id='price' type="text" class="form-control" name="price" value="<?= $data['price'] ?>"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">开课时间</label>
                    <div class="col-md-5">
                        <input id='dtm_start' type="text" class="form-control" name="dtm_start" value="<?= $data['plan']['dtm_start'] ?>"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">预计课时</label>
                    <div class="col-md-5">
                        <input id='duration' type="text" class="form-control" name="duration" value="<?= $data['plan']['duration'] ?>"/>
                    </div>
                </div>
                <div class="col-md-offset-2">
                  <button type="button" class="btn green" id="submit">保存</button>
                  <button type="button" class="btn red" id="block" data-toggle="modal" data-target="#block-modal">封禁</button>
                </div>

            </div>
        </form>
    </div>

  <div class="modal fade" id="block-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="exampleModalLabel">封禁下架</h4>
        </div>
        <div class="modal-body">
          <form>
            <div class="form-group">
              <input type="text" class="form-control" id="reason" placeholder="封禁原因">
              <span style="color: #999;">封禁原因将通知给讲师</span>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
          <button type="button" class="btn btn-primary" id="set">确认</button>
        </div>
      </div>
    </div>
  </div>
  </div>

</div>
<script>
    function imgPreview(fileDom, dom){
        //判断是否支持FileReader
        if (window.FileReader) {
            var reader = new FileReader();
        } else {
            alert('您的设备不支持图片预览功能，如需该功能请升级您的设备！');
            return false;
        }

        //获取文件
        var file = fileDom.files[0];
        var imageType = /^image\//;
        //是否是图片
        if (!imageType.test(file.type)) {
            alert('请选择图片！');
            return false;
        }
        //读取完成
        reader.onload = function(e) {
            //获取图片dom
            var img = document.getElementById(dom);
            //图片路径设置为读取的图片
            img.src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
    jQuery(document).ready(function () {

        mackdownShow();
        $("#brief").bind('input porpertychange',function(){
            mackdownShow();
        });

        $('#submit').click(function(){
            $.ajax({
                type: "POST",
                url: "./detail-edit",
                enctype: 'multipart/form-data',
                processData:false,
                contentType:false,
                data:new FormData($('#form-content')[0]),
                success:function(data){
                    if(data.error == 0) {
                        toastr['success']('修改成功');
//                        location.reload();

                    } else {
                        toastr['warning'](data.message)
                    }
                }
            });
        });

        function mackdownShow() {
            var about =  $("#brief").val();
            about = about.replace(/\r\n/g, '  \n'); //IE9、FF、chrome
            about = about.replace(/\n/g, '  \n'); //IE7-8
            var md = window.markdownit();

            var rst = md.render(about);
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
            console.log(rst);
            $('#brief-pre').html(rst);
        }
    });

    $('#block-modal').on('show.bs.modal', function (event) {
      // var button = $(event.relatedTarget) // Button that triggered the modal
      // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
      // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
      // var modal = $(this)
//        modal.find('.modal-body input').val(name)
    });

    $('#set').click(function(){
      $.post('./detail-block', {
        sn: "<?=$data['sn']?>",
        reason: $('#reason').val()
      }, function(res) {
        if (res.error === '0') {
          toastr['success']('封禁成功')
        } else {
          toastr['warning']('封禁失败')
        }
      })
    })


</script>
