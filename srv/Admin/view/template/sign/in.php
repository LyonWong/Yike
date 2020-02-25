<!DOCTYPE html>
<html>
<head>
    <?php $this->tpl('header')?>
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
</div>
<!-- END LOGO -->
<!-- BEGIN LOGIN -->
<div class="content">
    <!-- BEGIN LOGIN FORM -->
    <form class="login-form" onsubmit="return false;">
        <h3 class="form-title">Sign In</h3>
        <div class="alert alert-danger display-hide">
            <button class="close" data-close="alert"></button>
			<span>
			Enter any username and password. </span>
        </div>
        <div class="form-group">
            <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
            <label class="control-label visible-ie8 visible-ie9">Account</label>
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-user"></i></span>
                <input class="form-control form-control-solid placeholder-no-fix" type="text" autocomplete="off" placeholder="Account" name="account" id="account"/>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label visible-ie8 visible-ie9">Password</label>
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                <input class="form-control form-control-solid placeholder-no-fix" type="password" autocomplete="off" placeholder="Password" name="password" id="password"/>
            </div>
        </div>
        <div class="form-actions">
            <div class="has-error"><span class="help-block" id="submit-error"></span></div>
            <button type="submit" class="btn btn-success uppercase float-right" id="submit">Sign In</button>
            <a href="/sign-forgot" id="forget-pa:wssword" class="forget-password float-none">Forgot Password?</a>
        </div>
        <?php if ($this->showCreate == true) { ?>
            <div class="create-account">
                <p>
                    <a href="/sign-prepare" class="uppercase">Create an account</a>
                </p>
            </div>
        <?php } ?>
    </form>
    <!-- END LOGIN FORM -->
    <!-- BEGIN FORGOT PASSWORD FORM -->
    <form class="forget-form"  method="post">
        <h3>Forget Password ?</h3>
        <p>
            Enter your e-mail address below to reset your password.
        </p>
        <div class="form-group">
            <input class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="Email" name="email"/>
        </div>
        <div class="form-actions">
            <button type="button" id="back-btn" class="btn btn-default">Back</button>
            <button type="submit" class="btn btn-success uppercase pull-right">Submit</button>
        </div>
    </form>
    <!-- END FORGOT PASSWORD FORM -->

</div>
<!-- END LOGIN -->

<script type="text/javascript">
    $(function(){
        $('#submit').click(function(){
            var joSummitError = $('#submit-error');
            joSummitError.html('');
            $.post('/sign-in', {
                account: $('#account').val(),
                password: $('#password').val()
            }, function(res) {
                if (res.error == 0) {
                    window.location.href = '<?=$this->callbackURI?>' || '/';
                } else {
                    joSummitError.html("Account or password is not correct.");
                }
            })
        });
    });
</script>

<?php $this->tpl('footer')?>
<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>
