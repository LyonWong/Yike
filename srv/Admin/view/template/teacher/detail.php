<?php
$profile = $this->profile;
$detail = $this->detail;
$datum = $detail['datum'];
$info = $detail['info'];

?>
<?php $about = str_replace(["\r\n","\n"], '\n', $datum['about']);
$about = str_replace("'","\'",$about);
?>
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
            <div class="caption">讲师详情</div>
        </div>
    </div>
    <div class="portlet-body form portlet-empty">
        <form role="form" class="form-horizontal" id="form-content">
            <div class="form-body">
                <div class="form-group">
                    <label class="col-md-2 control-label">UID</label>
                    <div class="col-md-5">
                        <input id='uid' type="text" class="form-control" name="uid" value="<?= $this->tuid ?>" readonly/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">TUSN</label>
                    <div class="col-md-5">
                        <input id='sn' type="text" class="form-control" name="sn" value="<?= $profile['sn'] ?>" readonly/>
                    </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">昵称</label>
<!--                    <div class="col-md-5">-->
<!--                        <span class="form-control">--><?//= $profile['name'] ?><!--</span>-->
<!--                    </div>-->
                    <div class="col-md-5">
                        <input id='name' type="text" class="form-control" name="name" value="<?= $profile['name'] ?>"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">头像</label>
                    <div class="col-md-5 fileinput-button">

                        <img class="img-s" id="avatar" src="<?= $profile['avatar'] ?>" onclick="upload()"/>
                        <input type="file" id="cover" name="cover" onchange="imgPreview(this)">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">认证情况</label>
                    <div class="col-md-5">
                        <span class="form-control"><?= $detail['i_status'] ?></span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">邮箱</label>
                    <div class="col-md-5">
                        <input id='email' type="text" class="form-control" name="email" value="<?= $info['email'] ?>" readonly/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">手机</label>
                    <div class="col-md-5">
                        <input id='telephone' type="text" class="form-control" name="telephone" value="<?= $info['telephone'] ?>" readonly/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">关注公众号</label>
                    <div class="col-md-5">
                        <div class="form-control"> <?= $info['subscribe'] ? '是' : '否'?> </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">简介</label>
<!--                    <input class="control-label"onclick="">点击预览</input>-->
                    <div class="col-md-5" style="height: 500px;overflow:auto">
                        <textarea class="form-control"   rows="24" id="about" name="about"><?= $datum['about'] ?></textarea>
                    </div>

                    <div class="col-md-5 col-xs-15 full-height" style="height: 500px;overflow:auto">
                        <div  id="about-pre" class="markdown-body"  style="border: 1px solid #e5e5e5;padding: 10px"></div>

                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">加入时间</label>
                    <div class="col-md-5">
                        <div class="form-control"> <?= $detail['tms_create']?> </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">信息更新时间</label>
                    <div class="col-md-5">
                        <div class="form-control"> <?= $detail['tms_update']?> </div>
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
    mackdownShow();
    $("#about").bind('input porpertychange',function(){
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
                    location.reload();

                } else {
                    toastr['warning'](data.message)
                }
            }
        });
    });

    function imgPreview(fileDom){
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
            var img = document.getElementById("avatar");
            //图片路径设置为读取的图片
            img.src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
    function mackdownShow() {
        var about =  $("#about").val();
        about = about.replace("\n", "  \n");
        var md = window.markdownit();

        var content = '<?=$about?>';console.log(content);
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
        $('#about-pre').html(rst);
    }
</script>