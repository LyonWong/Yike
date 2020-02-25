<!DOCTYPE html>
<html>
<head>
    <?php $this->tpl('header') ?>
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="login">
<!-- BEGIN SIDEBAR TOGGLER BUTTON -->
<div class="menu-toggler sidebar-toggler">
</div>
<!-- END SIDEBAR TOGGLER BUTTON -->
<!-- BEGIN LOGO -->
<div class="logo">
    <!--        <img src="../../assets/admin/layout/img/logo-big.png" alt=""/>-->
    YiKe
</div>
<!-- END LOGO -->

<div class="content">
    <form class="signup-form form-horizontal" onsubmit="return false">
        <h3 class="form-title">Sign Up</h3>
        <div class="form-group">
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-user"></i></span>
                <input type="text" class="form-control" value="<?= $this->email ?>" disabled name="account"
                       id="account"/>
            </div>
        </div>
        <!--        <div class="form-group">-->
        <!--            <label class="col-md-3 control-label">Name</label>-->
        <!--            <div class="col-md-9">-->
        <!--                <input type="text" class="form-control" placeholder="Username" name="name" id="name">-->
        <!--            </div>-->
        <!--        </div>-->
        <div class="form-group">
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                <input type="password" class="form-control" placeholder="Enter your Password" name="password"
                       id="password">
            </div>
            <div class="col-md-11 pull-right">
                <p class="hint">No less than 6 characters, at least one of them is non-numeric.</p>
            </div>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-success uppercase pull-right" id="submit">Submit</button>
            <div class="has-error"><span class="help-block" id="error"></span></div>
        </div>
    </form>

</div>
<?php $this->tpl('footer') ?>
<script>
    $(function () {
        //准备jq对象
        var items = ['password', 'submit', 'error'];
        var jo = {};
        $.each(items, function (i, v) {
            jo[v] = $('#' + v);
        });
        
        //判断账号token
        if (!'<?=$this->email?>') {
            jo.error.html("Invalid Account or Token");
            jo.submit.attr("disabled", "disabled");
        }
        
        //提交注册
        jo.submit.click(function () {
            jo.error.html();
            var formData = {
                token: '<?=$this->token?>',
                password: jo.password.val()
            };
            $.post('/sign-up', formData, function (res) {
                if (res.error == 0) {
                    //成功后跳转到登录页
                    $('form div').remove();
                    $('form').append(
                        '<div class="alert alert-success">' +
                        'Sign Up Successfully. Redirecting to <a href="/sign-in">Sign-in</a>...' +
                        '</div>');
                    setTimeout("window.location.href = '/sign-in';", 2000);
                } else {
                    jo.error.html(res.data);
                }
            })
        })
    });
</script>
<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>
