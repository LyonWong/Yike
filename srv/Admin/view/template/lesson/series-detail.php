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
            <div class="caption">系列课详情</div>
        </div>
    </div>
    <div class="portlet-body form portlet-empty">
        <form role="form" class="form-horizontal" id="form-content">
            <div class="form-body">
                <div class="form-group">
                    <label class="col-md-2 control-label">SN</label>
                    <div class="col-md-5">
                        <input id='sn' type="text" class="form-control" name="sn" value="<?=$data['sn']?>" readonly/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">讲师姓名</label>
                    <div class="col-md-5">
                        <input id='teacher-name' type="text" class="form-control" value="<?=$data['teacher']['name']?>" readonly/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">系列课标题</label>
                    <div class="col-md-5">
                        <input id='name' type="text" class="form-control" name="name" value="<?= $data['name'] ?>"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">系列课封面</label>
                    <div class="col-md-5 fileinput-button">
                        <img class="img-s" id="cover-img" src="<?= $data['introduce']['cover']?>" onclick="upload()"/>
                        <input type="file" id="cover" name="cover" onchange="imgPreview(this, 'cover-img')">
                    </div>
                </div>
              <div class="form-group">
                <label class="col-md-2 control-label">首页Banner图片</label>
                <div class="col-md-5 fileinput-button">
                  <img class="img-s" id="banner-img" src="<?= $data['introduce']['banner']?>" onclick="upload()"/>
                  <input type="file" id="banner" name="banner" onchange="imgPreview(this, 'banner-img')">
                </div>
              </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">系列课简介</label>
                    <div class="col-md-5">
<!--                        <textarea class="form-control" rows="10" readonly>--><?//= $data['introduce']['content'] ?><!--</textarea>-->
                        <textarea class="form-control"   rows="24" id="content" name="content"><?= $data['introduce']['content'] ?></textarea>
                    </div>
                    <div class="col-md-5 col-xs-15 full-height" style="height: 500px;overflow:auto">
                        <div  id="content-pre" class="markdown-body"  style="border: 1px solid #e5e5e5;padding: 10px"></div>

                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">系列课价格</label>
                    <div class="col-md-5">
                        <input id='price' type="text" class="form-control" name="price" value="<?= $data['scheme']['price'] ?>"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">讲师分成比例</label>
                    <div class="col-md-5">
                        <input id='share' type="text" class="form-control" name="share" value="<?= $data['scheme']['share'] ?>"/>
                    </div>
                </div>
                <div class="col-md-offset-2">
                    <button type="button" class="btn green" id="submit">保存</button>
                </div>

            </div>
        </form>

    </div>
</div>
<script>
    markdownShow();
    $("#content").bind('input porpertychange',function(){
        markdownShow();
    });
    $('#submit').click(function(){
        $.ajax({
            type: "POST",
            url: "./series-edit",
            enctype: 'multipart/form-data',
            processData:false,
            contentType:false,
            data:new FormData($('#form-content')[0]),
            success:function(data){
                if(data.error == 0) {
                    toastr['success']('修改成功');
                    location.reload();

                } else {
                    toastr['warning'](data.message)
                }
            }
        });
    });

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
    function markdownShow() {


        var about =  document.getElementById("content").value;

        about = about.replace(/\r\n/g, '  \n'); //IE9、FF、chrome
        about = about.replace(/\n/g, '  \n'); //IE7-8
        var md = window.markdownit();

        var content = '<?=$this->about?>';
        console.log(content);
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
        $('#content-pre').html(rst);
    }

</script>
