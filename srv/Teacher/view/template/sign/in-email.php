<?php $this->tpl('header') ?>
<style>
    body {
        background: url("<?=\view::src('img/sign-weixin.jpg')?>") no-repeat center center fixed;
        background-size: cover;
    }

    .frame {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
    }

    .title {
        padding: 10rem 0 3rem;
    }
    .extra, .extra a{
        color: #fff;
        text-align: center;
    }
    .extra > div {
        padding: 0.5rem;
    }

    form {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
    }

    input {
        padding: 0.3rem;
        margin: 0.5rem;
        font-size: 1.5rem;
        width: 100%;
    }
</style>

<div class="frame">
    <div class="title">
        <img class="img" src="<?= \view::src('img/logo-head.png') ?>" alt="">
    </div>
    <div class="form">
        <form method="post" action="/sign-in">
            <input title="email" placeholder="邮箱账户" name="account">
            <input type="password" title="password" name="password" placeholder="密码">
            <input type="submit" value="登录"/>
        </form>
    </div>
    <div class="extra">
        <div>
            <span class="hint"><?=$this->hint?></span>
        </div>
        <div>
            <a href="/sign-forget">忘记密码</a>
        </div>
        <div>
            <a href="/sign-in-weixin">微信登录</a>
        </div>
    </div>
</div>
<?=\view::js('resource/jquery/jquery.min')?>
<?php $this->tpl('footer') ?>

