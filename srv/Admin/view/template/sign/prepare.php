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
    GameBeginAdmin
</div>
<!-- END LOGO -->

<div class="content">
    <form class="prepare-form" onsubmit="return false" >
        <h3 class="form-title">Sign Prepare</h3>
        <div class="hint" >
            Enter your Email to prepare sign up
        </div>
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
            <input type="text" class="form-control"  name="email" id="email">
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-success uppercase pull-right" id="submit">Submit</button>
            <div class="help-block" id="submit-info">
            </div>
        </div>
    </form>

</div>

<?php $this->tpl('footer')?>
<script>
    $(function(){
        $('#submit').click(function(){
            var joSubmit = $(this);
            var joSubmitInfo = $('#submit-info');
            joSubmitInfo.parent().removeClass('has-error has-success');
            joSubmitInfo.html('');
            joSubmit.html("Sending...");
            joSubmit.attr('disabled', true);
            $.post('/sign-prepare', {email:$('#email').val()}, function(res) {
                joSubmit.html("submit");
                joSubmit.attr('disabled', false);
                if (res.error == 0) {
                    joSubmitInfo.parent().addClass("has-success");
                    joSubmitInfo.html("Sign Email has been sent, please check your inbox.");
                } else {
                    joSubmitInfo.parent().addClass("has-error");
                    joSubmitInfo.html("Failed to send sign Email, please try again.");
                }
            })
        });
    });
</script>
<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>

