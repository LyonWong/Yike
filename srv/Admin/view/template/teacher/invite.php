<div class="portlet box blue">
    <div class="portlet-title">
        <div class="caption">
            <div class="caption">讲师邀请</div>
        </div>
    </div>
    <div class="portlet-body form portlet-empty">
        <form role="form" class="form-horizontal">
            <div class="form-body">
                <div class="form-group">
                    <label class="col-md-2 control-label">讲师邮箱</label>
                    <div class="col-md-5">
                        <input type="text" class="form-control email" placeholder="email">
                    </div>
                </div>
            </div>
            <div class="form-actions ">
                <div class="row">
                    <div class="col-md-offset-2">
                        <button type="button" class="btn green" id="act-accept">发送邀请</button>
                    </div>
                </div>
            </div>
        </form>
        <script>
            jQuery(document).ready(function () {
                $('#act-accept').click(function(){
                    var email = $(".email").val();
                    if ( email == '') {
                        alert('请将信息填写完整');
                        return false;
                    }
                    var emailreg = /^([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/;
                    if(!emailreg.test(email)) {
                        alert('提示\n\n请输入有效的E_mail！');
                        return false;
                    }
                    var formData = {
                        email: email
                    };

                    $.post('./invite-invite', formData, function(res){
                        if (res.error == 0) {
                            msg = 'success';
                        } else {
                            msg = 'failed';
                        }
                        $('form').append(
                            '<div class="alert alert-success">' +
                            '发送注册邀请' + msg + '：Email:' + email +
                            '</div>');

                    })
                });
            });
        </script>
    </div>
</div>