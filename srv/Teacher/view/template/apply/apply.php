<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>讲师认证</title>
</head>
<?= view::js([
    'resource' => [
        'jquery' => ['jquery.min'],
        'base' => ['global']
    ],
    'js' => ['sweetalert.min']
]) ?>
<?=   view::css([
    'css'=>['sweetalert']
]);?>
<style>
    body{
        background: #EFEFF4;
        font-family: "Microsoft YaHei UI"
    }
    h1 {
        margin: 60px 0 20px;
        text-align: center;
    }
    h5 {
        font-size: 20px;
        color: #3C4A55;
        margin-bottom:0px
    }
    p{
        line-height:150%;
    }
    ft12 {
        font-size: 12px;
    }
    textarea {
        width: 450px;
        height: 200px;
        border: 1px solid #E6EAF2;
        border-radius:4px;
        resize : none;
        outline: none;
    }
    .gray {
        color: #aaa;
    }
    .title {
        text-align: center;
        color: #3C4A55;
        font-size: 20px;
    }
    .content {
        margin: 0 auto;
        padding: 70px;
        width: 780px;
        height: 750px;
        background: #fff;
    }
    .control {
        padding-bottom: 10px;
    }
    .control:after {
        content: ".";
        display: block;
        height: 0;
        clear: both;
        visibility: hidden;
    }
    .control>* {
        float: left;
    }
    .control .word {
        text-align: right;
        width: 200px;
        margin-right: 20px;
        color: #3C4A55;
    }
    .control .word span {
        color: #FB617F;
    }
    .control .text input{
        padding: 5px;
        border: 1px solid #E6EAF2;
        border-radius:4px;
        width: 219px;
        outline: none;
    }
    .send{
        color: #12B7F5;
        text-decoration: none;
        border: none;
        background-color:#fff
    }
    .sub{
        color: #12B7F5;
        margin-left: 350px;
        border: none;
        background-color:#fff


    }
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
    img {
        border-radius:4px;
    }
    a:hover, a:visited, a:link, a:active {
        color: black;
        text-decoration:none;
    }
    .protocol-dialog {
        position: fixed;
        display: flex;
        left: 0;
        right: 0;
        top: 0;
        bottom: 0;
        flex-direction: column;
        justify-content: center;
        background-color: rgba(0,0,0,0.5);
    }
    .protocol-dialog .dialog-box {
        margin: 0 auto;
        padding: 30px 66px;
        width: 560px;
        background: #fff;
        font-size: 16px;
    }
    .protocol-dialog .box-tit {
        text-align: center;
        color: #3C4A55;
        font-size: 24px;
        font-family:Microsoft YaHei;

    }
    .protocol-dialog .box-con {
        padding: 18px 0;
        height: 354px;
        border-top: 1px solid #E6EAF2;
        border-bottom: 1px solid #E6EAF2;
        overflow-x: hidden;
        overflow-y: auto;

    }
    .box-submit{
        outline：none;
        margin-left: 235px;
        border-style:none;
        padding:6px 26px;
        line-height:24px;
        color:#fff;
        font:16px "Microsoft YaHei", Verdana, Geneva, sans-serif;
        cursor:pointer;
        -webkit-border-radius:4px;
        -moz-border-radius:4px;
        border-radius:18px;/*边框圆角*/
        background-color:#12b7f5;

    }
    .box-submit:hover {
        background-color:#88dbfa;}
    .list-span {
        display: inline-block;
        padding-bottom: 8px;
    }
</style>
<body>
<h1>
    <img width="400px" src="<?= view::src('img/logo.png')?>" alt="">
</h1>
<p class="title">欢迎来到易灵微课讲师平台，请完善您的认证信息</p>
<form class="content" id="content">
    <div class="control">
        <div class="word">
            <span>*</span>&nbsp;昵称
        </div>
        <div class="text">
            <input name="name" id="name" type="text" value="<?=$this->profile['name']?>"/>
        </div>
    </div>

    <div class="control">
        <div class="word" style="margin-top: 50px;">
            <span>*</span>&nbsp;头像
        </div>
        <div class="text">
            <img id="avatar" width="119px" height="119px" src="<?=$this->profile['avatar']?>" alt="">
            <div align="center">
        <span class="btn btn-success fileinput-button">
            <span style="color: #12B7F5;font-size: 16px;">更改头像</span>
            <input type="file" id="file" name="cover" onchange="imgPreview(this)">
        </span>
            </div>
        </div>

    </div>

    <div class="control">
        <div class="word" style="margin-top: 50px;">
            <span>*</span>&nbsp;关注公众号
        </div>
        <div class="text">
            <img id="avatar" width="119px" height="119px" src="<?= view::src('img/mp_qrcode.jpg')?>" alt="">
            <?php $subscribePic = $this->subscribe ? 'img/勾-绿.png' : 'img/勾-灰.png';?>
            <img id="subScribe" width="20px" height="20px" src="<?= view::src($subscribePic)?>" alt=""> <span id="subScribe_text"><?=$this->subscribe ? '已关注' : '未关注（若检测失败，请重新关注公众号）';?></span>
        </div>
    </div>

    <div class="control">
        <div class="word">
            <span>*</span>&nbsp;邮箱
        </div>
        <div class="text">
            <input name="email" type="text" value="<?=$this->email?>" readonly/>
        </div>
    </div>

    <div class="control">
        <div class="word">
            <span>*</span> 手机号
        </div>
        <div class="text">
            <input name="telephone" type="text" id="telephone" value="" placeholder=""/>
            <!--            <a href="" onclick="sendSms()">获取验证码</a>-->
            <input name="code" style="width: 59px;" type="text" id="code" placeholder="验证码"/>
            <input class="send" style="border:none;width: 99px;font-size: 16px;" id="send" type="button" value="获得验证码" onclick="sendSms()"/>
        </div>
        <div class="text" style="margin-left: 220px">
            <span class="gray ft12" style="font-size: 12px">手机号仅用于必要时与您联系</span>
        </div>
    </div>

    <div class="control">
        <div class="word">
            <span>*</span>&nbsp;个人简介
        </div>
        <div class="text">
            <textarea name="about" id="about"><?=$this->datum['about'] ?? ''?></textarea><br />
            <span class="gray ft12" style="font-size: 14px">限300字以内</span>
        </div>
    </div>
    <div class="control">
        <div class="text" style="margin-left: 220px">
            <input type="checkbox" id="vehicle" name="vehicle" value="Bike" style="width: 16px;">
            <span class="">我已阅读并同意<a href="javascript:;" onclick="showDialog()"><span class="send">《易灵微课讲者协议》</span></a></span>
        </div>
    </div>
    <div class="control">
        <div class="text">
            <input type="button" style="outline: none;width:90px;color:#fff;border: none;font-size: 16px;border-radius:17px;background-color: #12B7F5" value="提交" onclick="sub()" class="sub" align="center"/>
        </div>
        <input type="hidden" value="<?=$this->tokenStatus?>" id="tokenStatus">
        <input type="hidden" value="<?=$this->token?>" id="token">
    </div>
</form>

<div id="bg" class="bg" style="display:none;"></div>
<div id="popDiv2" class="mydiv" style="display:none;">
    <div id="icon" class="icon" style="width: 30px;margin-left: 110px;margin-top: 10px;margin-bottom: 5px">
        <img src="<?= view::src('img/勾-绿.png')?>"  width="100%"/>
    </div>
    <div class="title" id="title">
        注册成功
    </div>
    <hr>
    <a href="/" onclick="closeDiv('popDiv2')">前往后台<span id="time"></span></a>
</div>
<div id="bg" class="bg" style="display:none;"></div>
<div id="dialog" class="protocol-dialog" style="display:none;">
    <!--         <div id="dialog" class="protocol-dialog" >
     -->            <div class="dialog-box">
        <h4 class="box-tit">《易灵微课讲者协议》</h4>
        <div class="box-con">
            <p >
                欢迎你使用易灵微课产品（简称「易灵微课」或「本产品」）。<br>
                为申请在易灵微课开课的资格，你应当阅读并遵守《易灵微课讲者协议》、易灵微课的各项制度规范等。<br>
                请你务必审慎阅读、充分理解各条款内容，你使用本产品，即视为你已阅读并同意上述协议、规则等的约束。如果你对本协议的任何条款表示异议，你可以选择不使用本产品。<br>
                未成年人应在法定监护人的陪同下阅读本协议。
            </p>
            <h5>一、定义</h5>
            <p>
                <span class="list-span">1.易灵微课学员用户：指经微信授权登录后，付费使用易灵微课产品的个人，简称为「学员用户」或「学员」</span><br>
                <span class="list-span">2.易灵微课讲者用户：指经微信授权登录后，申请在易灵微课平台中以语音、文字或图片等未来可能出现的媒介方式，向其他用户分享自己的知识、经验和见解的个人，简称为「你」或「讲者」。</span><br>
                <span class="list-span">3.易灵微课：是指厦门易灵网络科技有限公司及其相关服务可能存在的运营关联单位。</span><br>
                <span class="list-span">4.易灵微课用户：是指使用易灵微课产品的学员用户和讲者用户。</span>
            </p>
            <h5>二、关于易灵微课</h5>
            <p>
                <span class="list-span">1.易灵微课是一种基于微信的网络课程服务。易灵微课作为网络服务提供者，为学员用户实现自愿付费参加易灵微课的行为提供相应技术支持。支付结算渠道的功能、代收代付功能均由第三方支付平台微信支付提供，相应渠道技术服务成本由第三方支付平台扣除。</span><br>
                <span class="list-span">2. 为持续运营易灵微课，易灵微课保留向每节易灵微课收取服务费用的权利，服务费用的收取方式以你在使用本产品时收到的通知、提示信息为准。易灵微课发出的有关收费的通知、提示信息均为本协议的组成部分，你若在收到有关收费的各类通知或提示信息后仍然使用本服务，则视为你已无条件同意按照相关通知、提示信息所约定的收费标准和收费条件向易灵微课支付服务费用。</span>
            </p>
            <h5>三、易灵微课讲者规范</h5>
            <p>
                <span class="list-span">1. 你理解并同意，申请在易灵微课开讲的资格，你需要按国家法律法规、行政规章等相关规定完成易灵微课帐号的实名认证。</span>
                <br>
                <span class="list-span">2. 你理解并同意，你应在课程简介、课程中提供真实的、合法的、准确的、有效的专业背景和个人简介，供学员参考。</span>
                <br>
                <span class="list-span">3. 你理解并同意，你作为易灵微课的讲者，应该与发布该课程的微信帐号用户以及该帐号实名认证的用户是同一人，不得有「代讲」、「替讲」等行为。邀请他人「合讲」，应该在发布课程的简介时向学员说明。</span>
                <br>
                <span class="list-span">4. 你理解并同意，讲者在课程中提供的专业信息，应向学员说明信息来源，增加必要的免责提示或声明。</span>
                <br>
                <span class="list-span">5. 你理解并同意，讲者应该保持信息的中立性，在课程主讲过程中不应以任何形式推广与讲者有直接及间接利益关系的第三方。讲者不应以任何形式推广法律、行政法规规定禁止生产、销售的商品或者提供的服务，以及禁止或限制推广的商品和服务，包括但不限于医疗、药品、医疗器械、农药、兽药、保健药品、处方药、烟草等。</span>
                <br>
                <span class="list-span">6. 你理解并同意，为保持易灵微课的专业性和内容质量，讲者不应在易灵微课课堂、简介宣传易灵微课以外其他收费的活动、产品和课程等。</span>
                <br>
                <span class="list-span">7. 你理解并同意，讲者不应有诱导易灵微课用户参与课程的行为，包括但不限于在易灵微课简介中提及参与课程赠与书籍、邀请参与除易灵微课以外的线上或线下活动、派发产品的试用名额等行为，但可作为「福利」在课堂内给予用户。讲者本人对此行为的一切纠纷及后果承担责任。</span>
                <br>
                <span class="list-span">8. 你理解并同意，在易灵微课中不得推广任何与易灵微课有竞争关系的产品或服务信息。</span>
                <br>
                <span class="list-span">9. 你不得利用易灵微课帐号或本产品服务制作、上载、复制、发布、传播如下法律、法规和政策禁止的内容：</span><br>
            <P style="padding-left:30px;">
                ① 反对宪法所确定的基本原则的；<br>
                ② 危害国家安全，泄露国家秘密，颠覆国家政权，破坏国家统一的；<br>
                ③ 损害国家荣誉和利益的；<br>
                ④  煽动民族仇恨、民族歧视，破坏民族团结的；<br>
                ⑤ 破坏国家宗教政策，宣扬邪教和封建迷信的；<br>
                ⑥  散布谣言，扰乱社会秩序，破坏社会稳定的；<br>
                ⑦ 散布淫秽、色情、赌博、暴力、凶杀、恐怖或者教唆犯罪的；<br>
                ⑧ 侮辱或者诽谤他人，侵害他人合法权益的；<br>
                ⑨ 不遵守法律法规底线、社会主义制度底线、国家利益底线、公民合法权益底线、社会公共秩序底线、道德风尚底线和信息真实性底线的「七条底线」要求的；<br>
                ⑩ 含有法律、行政法规禁止的其他内容的信息。<br>
            </P>
            <span class="list-span">10. 你理解并同意，如你违反上述条款，易灵微课有权：取消你的易灵微课举办资格，立即暂停、中止、停止或永久拒绝你对本产品的使用，并按照相关协议、规则对你做出处理；同时，易灵微课有权要求你缴纳由此产生的违约金或损害赔偿金或从课程收入中扣除。</span>
            <br>
            <span class="list-span">11. 你理解并同意，易灵微课有权根据宣传、推广活动的需要决定课程的优惠活动（包含但不限于优惠券、限时降价、打包销售等），价格调整幅度低于原价 50% 的优惠活动不对讲者做单独通知，具体优惠活动信息以活动页面上的公告公示为准。</span>
            <br>
            <span class="list-span">12. 你理解并同意，讲者所传播的信息内容相关的法律责任由讲者自行承担，如因此给易灵微课或第三方造成损害的，讲者应当依法予以赔偿。</span>
            <br>
            <span class="list-span">13. 你理解并同意，为了保证用户权益，如出现以下情况将进行相应处理：</span>
            <P style="padding-left:30px;">
                ① 在学员付费购买易灵微课的入场资格后，由于讲者个人原因（包括但不限于身体因素、个人网络状况等）导致易灵微课延期或取消，讲者需提前至少 5 个工作日通知易灵微课。易灵微课将在 48 小时内协助讲者发布易灵微课延期或中止的通知，并在必要情况下协助讲者退款，由此造成的损失由讲者独立承担。 <br>
                ② 易灵微课一旦开讲，讲者应本着对学员认真负责的态度完成本次易灵微课，并达到易灵微课发布时承诺的开讲时长。如由于讲者个人原因导致易灵微课中断或未达到承诺时长的，讲者应独立承担由此造成的全部损失，并立即与易灵微课工作人员取得联系，协商退款事宜或延期开讲。 <br>
                ③ 如因易灵微课的技术原因导致的易灵微课延期或中断，易灵微课有权与讲者协商调整举行时间，由此造成的退款责任由易灵微课承担。                        </P>

            <span class="list-span">14. 你理解并同意，为参与课程在1小时内的学员用户提供无理由退款权利，为参与课程超过1小时的学员用户提供申述退款的权利。</span>
            <br>
            <span class="list-span">15. 你理解并同意，为了保障易灵微课质量，讲者必须保证课程时长至少不低于30分钟，分享过程中需有语音、文字等内容输出，保证一定的信息总量。</span>
            <br>
            <span class="list-span">16. 你理解并同意，讲者不应在课程中发送会引流用户至其他产品或服务的二维码或链接，诱导用户参与跟本产品存在商业竞争的活动、产品、课程。</span>
            </p>
            <h5>四、易灵微课讲者处罚规范</h5>
            <p>
                <span class="list-span"><strong>1. 讲者无故迟到或无故推迟课程开讲时间 </strong></span><br>
                为保障用户权益，和保障易灵微课使用体验，除遇不可抗力，讲者如有特殊情况需推迟课程，必须提前 5 个工作日进行沟通，如无故迟到、推迟课程开讲时间，将会受到一定处罚。<br>

            <p style="padding-left:30px;"> 迟到超过 15 分钟后，课程将被下架。 <br>
            </p>
            <span class="list-span"><strong>2. 发布不实信息</strong> </span><br>
            为保持课程的专业性和内容质量，讲者发布与课程描述严重不符、或课程内容存在不实信息等时，将会受到一定处罚。发布不实信息主要表现形式为： <br>
            <p style="padding-left:30px;">①课程大纲简介中存在虚假内容宣传、与课程内容严重不符等行为。 <br>
                ②主讲发布的课程内容存在严重事实性错误，并存在误导他人等行为。 <br></p>
            <span class="list-span">发布以上不实信息的行为，一经运营人工审核属实，则全场退款。易灵微课保留追究讲者相关法律责任的一切权利。 </span><br>
            <span class="list-span"><strong>3. 发布扰乱课堂秩序内容</strong></span><br>
            <span class="list-span">为了维护课程秩序，易灵微课讲者不得发布违反国家法律法规、政治敏感、泄露个人隐私、人身攻击、恶意营销等内容。<br>
                发布的内容，一经举报，情节严重情况下，会直接关停课程，全场退款，冻结易灵微课讲者资格。</span><br>

            <span class="list-span"><strong>4. 内容抄袭</strong></span><br>
            为尊重和保护知识产权，禁止讲者使用未经他人授权的作品。必须保证在本产品上发表的属于原创或已获权利人授权的内容（包括但不限于文字、音频、图片等）。<br>
            <span class="list-span">在本产品上发布抄袭他人的内容，一经举报和运营人工审核属实，将取消易灵微课讲者资格。</span><br>

            <span class="list-span"><strong>5. 资质不符</strong></span><br>
            为保障用户权益，讲者在申请金融、医学、心理学、法律等专业领域课程时，需提供符合相关领域的资质证明，无相关资质开讲相关专业领域课程将受到一定处罚。<br>
            <span class="list-span">讲者发布的内容中存在资质不符情况，将根据情节严重程度，冻结易灵微课讲者申请资格，全场退款。</span><br>

            <span class="list-span"><strong>6. 恶意推广</strong></span><br>
            为保障课程信息的中立性，易灵微课禁止讲者与直接或间接利益关系的第三方在本产品中进行恶意推广。恶意推广主要表现形式为：<br>
            <p style="padding-left:30px;">① 强制、诱导、雇佣他人参与课程行为（具体可参考易灵微课讲者规范第 7 条）。<br>
                ② 通过人工或机器手段非正常渠道扩大课程参与人数或提高课程评价分值。<br>
                ③ 讲者发布的内容中存在恶意推广情况，将根据情节严重程度，冻结易灵微课讲者资格。<br>
            </p>
            <span class="list-span">注* 非正常渠道包括但不限于：在其他的易灵微课中强制推广有利益相关的易灵微课。</span><br>

            <span class="list-span"><strong>7. 竞品推广</strong></span><br>
            为了保障易灵微课基本权益，讲者需遵守本产品的使用规则，禁止使用不正当的方式损害易灵微课基本权益，不正当使用本产品的主要表现形式为：<br>
            <p style="padding-left:30px;">① 以任何形式鼓励、促使或诱导他人参与本产品的商业竞争活动、产品、课程等。<br>
                ② 通过二维码、链接等方式诱导他人加入或参与易灵微课以外的其他产品。<br>
                ③ 讲者在使用本产品过程中不正当使用易灵微课产品，将根据情节严重程度，冻结易灵微课讲者资格。<br>
            </p>
            注* 对于违反以上规范的行为，易灵微课将保留追究讲者法律责任的权利。以上规范将持续更新并以本协议约定的通知方式送达。<br>
            </p>
            <h5>五、关于易灵微课</h5>
            <p>
                <strong>1. 令人反感的内容</strong><br>
                课程简介、大纲和内容不应包括具有攻击性、侮辱性、恐吓性、不顾及他人感受、令人不安、惹人厌恶或低俗不堪的内容。此类内容的示例有：<br>
            <p style="padding-left:30px;">① 诽谤或恶意内容，包括有关宗教、种族、性取向、性别或其他目标群体的引用或评论，特别是当该课程很可能对特定的个人或团体造成伤害时。<br>
                ② 人类或动物遭到杀害、残害、酷刑、虐待的写实描绘，或者鼓励暴力的内容。课程内容阐述中的范例讲解不应针对特定种族、文化、真实存在的政府或企业，或是任何其他真实存在的实体。<br>
                ③ 鼓励非法使用武器或危险物品的描述，或者涉及军火购买的描述。<br>
                ④ 过于色情的内容，特指「对性器官或性活动的露骨描述或展示」，目的在于刺激性快感，而非带来美学价值或触发情感。<br>
                ⑤ 具有煽动性的宗教评论，或者对宗教文本进行错误或误导性的引用。<br>
                ⑥ 虚假信息和功能，其中包括不准确的设备数据或用于恶作剧/开玩笑的功能。<br></p>

            <strong>2. 与人身伤害相关的内容</strong><br>
            如课程的主题涉及鼓励、引导造成人身伤害的内容，我们保留拒绝通过该课程申请的权利。例如：<br>
            <p style="padding-left:30px;">① 如申请主题与医学相关的课程，则此课程可能面临更加严格的审核。课程中可能引用的数据或信息需注明信息来源，严禁在授课过程向学员提供诊断或治疗。<br>
                ② 课程内容不得教唆违法犯罪，不得传授犯罪方法。<br>
                ③ 课程内容不得鼓励非法使用毒品或过量摄入酒精；不得向未成年人以任何形式传播使用毒品、酒精或烟草的内容；严禁为毒品销售提供便利。<br>
                ④ 课程内容不得鼓励酒后驾车等其他危害他人人身安全、危害公共安全的鲁莽行为。<br>
            </p>

            <strong>3. 准确的内容描述</strong><br>
            学员应当知道他们在购买或收听你的课程时会得到什么，请确保课程的简介、大纲和相关预告能准确反映该课程所涉及的内容和体验。<br>
            <p style="padding-left:30px;">① 请勿在课程中以任何形式推广与讲者有直接及间接利益关系的第三方产品或服务。<br>
                ② 请勿在课程中宣传易灵微课以外的其他收费的活动、产品和课程。<br>
                ③ 请尽量选择一个清晰简明、独一无二的课程标题，我们不接受将商标术语、具体品牌名称或其他不相关短语嵌入到标题中的行为。<br>
                ④ 为了保证优质的课程体验，请确保不在课程中包含其他直播平台或语音互动平台的名称、图标或图像。<br></p>

            <strong>4. 知识产权</strong><br>
            尊重知识产权作为易灵微课运营的基本原则之一。讲者在本产品上发表的全部原创内容（包括但不限于文字、音频、图片等）著作权均归讲者本人所有。未经讲者授权许可，学员用户不得以任何载体或形式使用讲者的内容。<br>
            <p style="padding-left:30px;">① 你理解并同意，讲者在使用易灵微课产品时发表的全部原创内容（包括但不限于文字、音频、图片等），不得在其他网络平台演讲时使用，亦不得在其他网络平台上以相同的形式使用或传播。为了更好地促进知识的传播和分享，你对发布在易灵微课上的全部内容，授予易灵微课永久的、免费的、不可撤销的、非独家使用许可。易灵微课有权免费将讲者发表的内容，以任何可能的形式用于易灵微课各种形态的产品和服务上，包括但不限于网站以及发表的应用或其他互联网产品。易灵微课讲者用户独家授权易灵微课享有以自己名义就针对讲者内容的侵权行为进行维权并独立追究侵权者责任的权利。<br>
                ② 你理解并同意，在同等条件下，易灵微课优先享有讲者课程内容延伸权利（包括但不限于出版权、改编权、汇编权、翻译权、表演权、摄制权、广播权等）。</p>
            易灵微课属于独立自主开发完成，本产品的著作权、商标权、专利权、商业秘密等知识产权、其他相关权利均独立归属易灵微课所有。<br>
            </p>
            <h5>六、活动更改</h5>
            <p>
                <span class="list-span">1. 在学员付费购买课程的观看资格后，由于讲者个人原因（包括但不限于身体因素、个人网络状况等）导致课程延期或取消，讲者需提前至少5个工作日通知易灵微课。易灵微课将协助讲者发布课程延期或中止的通知，并在必要情况下协助讲者退款，由此造成的损失由讲者独立承担。</span><br>
                <span class="list-span">2.课程一旦开讲，讲者应本着对学员认真负责的态度完成本次课程，并达到课程发布时承诺的开讲时长。如由于讲者个人原因导致课程中断或未达到承诺时长的，讲者应独立承担由此造成的全部损失，并立即与易灵微课工作人员取得联系，协商退款事宜或延期开讲。</span><br>
                <span class="list-span">3. 如因技术原因导致的课程延期或中断，易灵微课有权与讲者协商调整举行时间，由此造成的退款责任由易灵微课承担。</span><br>
                <span class="list-span">4.课程筹备和开讲过程中，如讲者违反前述［易灵微课讲者规范］中规定的内容，易灵微课有权立即停止或中断课程，由此造成的损失由讲者自行承担。</span><br>
                <span class="list-span">5.易灵微课有权决定开启、关闭或暂停课程「结束后进入」的功能。</span><br>

            </p>
            <h5>七、关于支付</h5>
            <p>
                <span class="list-span">1. 你理解并同意易灵微课目前支持的支付形式仅包括：微信支付这一第三方支付平台，一切结算遵守第三方支付平台的规定及相关协议、规则。因你错误绑定等你方原因而导致的本产品使用异常，如应付或应收费用无法转账、转账失败、资金丢失、结算延迟、转至错误账户等任何后果和损失，均由你自行承担。</span><br>
                <span class="list-span">2. 你理解并同意，对所有课程收入，在扣除礼券优惠金额以及第三方支付平台相关手续费后，易灵微课将收取相应的平台服务费。平台服务费会根据本产品的政策进行调整。</span><br>

                <span class="list-span">3. 依法纳税是每一个公民、企业应尽的义务。对于从易灵微课平台获得的应纳税所得，你应及时依法向税务主管机关申报纳税。根据国家法律法规政策，如税务机关要求易灵微课平台作为代扣代缴义务人的，易灵微课平台可在用户对课程收益提现前，扣除应纳税款，代为缴纳。</span><br>
            </p>
            <h5>八、关于通知</h5>
            <p>
                关于本产品的各种规则、通知、提示等信息，易灵微课可能会以网页公告、网页提示、电子邮箱、手机短信、公众号提示等方式中的一种或多种，向你送达。该等信息一经易灵微课采取前述任何一种方式公布或发送，即视为你已经接受并同意，对你产生约束力。若你不接受的，你可以立即停止使用本产品。<br>
            </p>
            <h5>九、免责条款</h5>
            <p>
                <span class="list-span">1.易灵微课仅提供相应的网络技术支持，课程均由讲者录制、上传及发布，包括录制音频、背景音乐、曲库、内容等均由讲者自行提供并由承担全部法律责任。</span><br>
                <span class="list-span">2.易灵微课将尽可能保存课程产生的合法内容，但不承诺永久保存上述内容，讲者应自行就前述内容备份。</span><br>
                <span class="list-span">3.易灵微课在目前技术水平下，确保服务的连贯性和安全性，最大程度地保障本产品的正常运行。但由于不可抗力、病毒、木马、黑客攻击、系统不稳定、通信线路故障、第三方服务瑕疵、政府行为等原因可能导致的运行中断、数据丢失以及其他的损失和风险，是当时行业技术水平所无法避免的且易灵微课无法控制的，由此给你造成的产品延迟和暂缓、数据或信息丢失等损失，你同意放弃追究易灵微课的责任。</span><br>
                <span class="list-span">4.易灵微课对于讲者或机构在课程中涉及到发布经济利益相关的预测信息不承担任何责任。讲者或机构在发布此类信息时需要谨慎考虑，并且应当在课程中的显要位置对用户进行风险提示，如果因为预测信息产生经济或其他情况的纠纷，由讲者或机构自行承担全部责任。</span><br>
                <span class="list-span">5.易灵微课对讲者或机构在课程中涉及到盗刷、套现等违规操作造成的订单及经济损失不承担任何责任。</span><br>
                <span class="list-span">6. 讲者或机构在课程中发布的抄袭或剽窃他人的内容被举报时，易灵微课仅作为服务商提供双方沟通的平台，不承担任何讲者抄袭或剽窃他人内容的责任，所有造成的经济损失和法律责任均由讲者自行承担。</span><br>
            </p>
            <h5>十、其他</h5>
            <p>
                <span class="list-span">1. 你一经使用本协议下任何服务，即视为你已阅读并同意接受本协议及上述内容的约束。易灵微课有权在必要时单方修改本协议或上述内容，相关内容变更后，如果你继续使用本产品，即视为你已接受修改后的相关内容。如果你不接受修改后的相关内容，应当停止使用相关产品。</span><br>
                <span class="list-span">2. 易灵微课保护用户的个人信息。易灵微课将按照本协议的规定收集、使用、存储和分享你的个人信息，通过技术手段、强化内部管理等办法充分保护你的个人隐私信息。</span><br>
                <span class="list-span">3. 本协议所有条款的标题仅为阅读方便，本身并无实际涵义，不能作为本协议涵义解释的依据。</span><br>
                <span class="list-span">4. 本协议的成立、生效、履行、解释及纠纷解决，适用中华人民共和国大陆地区法律。</span><br>
                <span class="list-span">5. 因本协议引起的或与本协议有关的任何争议，各方应友好协商解决；协商不成的，你同意将纠纷或争议提交至易灵微课所在地有管辖权的人民法院管辖。</span><br>
            </p>
        </div>
        <p ><button class="box-submit" onclick="hideDialog()">确定</button></p>
    </div>
</div>
<script>
    window.onload = function(){
        var tokenStatus = document.getElementById("tokenStatus").value;
        if(tokenStatus != 1) {
            swal({
                title: '错误提醒',
                text: 'token不存在或已失效',
                confirmButtonText: "知道了",
                showCancleButton:true,
            });
            return false;
        }
    };
    function showDialog() {
        var dialog = document.getElementById('dialog');
        dialog.style.display = 'block';
    }
    function hideDialog() {
        var dialog = document.getElementById('dialog');
        dialog.style.display = 'none';
    }

    function sendSms() {
        var telephone = document.getElementById("telephone").value;
        var data = {
            'phone_number':telephone,
            'channel':'bind'
        };

        $.ajax({
            url:"/sender-sms",
            type:"post",
            data:data,
            dataType: 'JSON',
            async: false,
            cache: false,
            success:function(data){
                if(data.error == 0) {
                    time(document.getElementById("send"));
                } else {
                    swal({
                        title: '错误提醒',
                        text: data.message,
                        confirmButtonText: "知道了",
                        showCancleButton:true,
                    });
                    return false;
                }
            }
        });
    }
    function sub() {
        if(document.getElementById("name").value == ''){
            swal({
                title: '错误提醒',
                text: '昵称不能为空',
                confirmButtonText: "知道了",
                showCancleButton:true,
            });
            return false;
        }
        var nameLength = fucCheckLength(document.getElementById("name").value);
        if(nameLength > 18) {
            swal({
                title: '错误提醒',
                text: '昵称18个字符以内',
                confirmButtonText: "知道了",
                showCancleButton:true,
            });
            return false;
        }
        if(document.getElementById("about").value == ''){
            swal({
                title: '错误提醒',
                text: '简介不能为空',
                confirmButtonText: "知道了",
                showCancleButton:true,
            });
            return false;
        }
        var about = document.getElementById("about").value;
        if(about.length > 300) {
            swal({
                title: '错误提醒',
                text: '个人简介300字以内',
                confirmButtonText: "知道了",
                showCancleButton:true
            });
            return false;
        }
        if(document.getElementById("telephone").value == ''){
            swal({
                title: '错误提醒',
                text: '手机号不能为空',
                confirmButtonText: "知道了",
                showCancleButton:true,
            });
            return false;
        }
        if(document.getElementById('vehicle').checked!=true) {
            swal({
                title: '错误提醒',
                text: '请查看《易灵微课讲者协议》',
                confirmButtonText: "知道了",
                showCancleButton:true,
            });
            return false;
        }
        var form = new FormData(document.getElementById("content"));
        var token =  document.getElementById("token").value;
        form.append('token',token);
        var n = true;
        if(document.getElementById("telephone").value != ''){
            var code = document.getElementById("code").value;
            var checkSms = {
                "channel":'bind',
                'code':code
            };

            $.ajax({
                url:"/sender-checkSms",
                type:"post",
                data:checkSms,
                dataType: 'JSON',
                async: false,
                cache: false,
                success:function(data){
                    if(data.error == 0) {
                    } else {
                        swal({
                            title: '错误提醒',
                            text: data.message,
                            confirmButtonText: "知道了",
                            showCancleButton:true,
                        });
                        n = false;
                    }
                }
            });
        }
        if(!n) {
            return false;
        }
        $.ajax({
            url:"/apply-submit",
            type:"post",
            data:form,
            processData:false,
            contentType:false,
            success:function(data){
                if(data.error == 0) {
                    swal({
                        title: '',
                        text: '注册成功',
                        timer:5000,
                        showConfirmButton: false,
                        confirmButtonText: "前往后台"
                    });
                    location.href='/';
                } else {
                    swal({
                        title: '错误提醒',
                        text: data.message,
                        confirmButtonText: "知道了",
                        showCancleButton:true,
                    });
                    return false;
                }

            }
        });
    }
    var x = 3;
    function go()
    {
        x--;
        if(x>0){
            document.getelementsbyclassname("confirm").innerHTML= '前往后台(' + x + ')';  //每次设置的x的值都不一样了。
        }else{
            location.href='/';
        }
    }
    var wait=60;
    function time(o) {
        if (wait == 0) {
            o.removeAttribute("disabled");
            o.value="获取验证码";
            wait = 60;
        } else {

            o.setAttribute("disabled", true);
            o.value="重新发送(" + wait + ")";
            wait--;
            setTimeout(function() {
                    time(o)
                },
                1000)
        }
    }
    function imgPreview(fileDom){
        //判断是否支持FileReader
        if (window.FileReader) {
            var reader = new FileReader();
        } else {
            swal({
                title: '错误提醒',
                text: '您的设备不支持图片预览功能，如需该功能请升级您的设备！',
                confirmButtonText: "知道了",
                showCancleButton:true,
            });
            return false;
        }

        //获取文件
        var file = fileDom.files[0];
        var imageType = /^image\//;
        //是否是图片
        if (!imageType.test(file.type)) {
            swal({
                title: '错误提醒',
                text: '请选择图片！',
                confirmButtonText: "知道了",
                showCancleButton:true,
            });
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
    function fucCheckLength(strTemp) {
        var i,sum;
        sum=0;
        for(i=0;i<strTemp.length;i++) {
            if ((strTemp.charCodeAt(i)>=0) && (strTemp.charCodeAt(i)<=255)) {
                sum=sum+1;
            }else {
                sum=sum+2;
            }
        }
        return sum;
    }
    $(document).ready(function () {
        c = setInterval("aa()",5000);
    });
    var subScribe_s = 'img/勾-绿.png';
    function aa(){
        var url_s = "<?=view::src('')?>";
        url_s=url_s.substring(0,url_s.length-1);
        $.ajax({
            url:"/apply-subscribe",
            async: false,
            cache: false,
            success:function(data){
                if(data.data.subscribe == 1) {
                    subScribe_s = 'img/勾-绿.png';
                    document.getElementById('subScribe_text').innerHTML = '已关注';
                    window.clearInterval(c);
                } else {
                    subScribe_s = 'img/勾-灰.png';
                    document.getElementById('subScribe_text').innerHTML = '未关注（若检测失败，请重新关注公众号）';
                }
                document.getElementById("subScribe").src=url_s + subScribe_s;
            }
        });
    }
</script>
</body>
</html>
