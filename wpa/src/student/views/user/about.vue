<template>
  <section class="about">
    <dl>
      <p><span style="color: #3C4A55">&bull;</span>&nbsp;易灵微课</p>
      <dd>
        易灵微课是一个基于微信的在线学习平台，由曹政先生投资并创建的厦门易灵网络科技有限公司推出，旨在向用户提供稳定简洁的直播授课服务，为讲师与学员搭建氛围良好的学习课堂。
      </dd>
      <p>&nbsp;&nbsp;</p>
    </dl>
    <dl>
      <p><span style="color: #3C4A55">&bull;</span>&nbsp;项目背景</p>
      <dd>
        在已经过去的16年，知识付费市场迎来了前所未有的爆发性增长，因此，2016年也被称为“知识付费元年”。但是，市场的快速增长还依赖于各行业头部大V的全力推动，知识付费产品则更多侧重于影响力的变现。
        <br>
        <p>&nbsp;&nbsp;</p>
        面对知识付费的汹涌浪潮，我们对其有独特的认识，所以推出“易灵微课”，希望打造一个有所不同的平台。
        <br>
        <p>&nbsp;&nbsp;</p>
        我们不敢说自己的认识和判断是正确的，但我们会尝试做一些探索，为这个市场的规范和未来的健康发展，贡献一些经验，或者教训。不论成败，我们都会积极复盘，分享我们的心得和理念。
      </dd>
      <p>&nbsp;&nbsp;</p>
    </dl>
    <dl>
      <p><span style="color: #3C4A55">&bull;</span>&nbsp;如何听课</p>
      <dd>
        点击公众号底部「课程列表」，即可发现精彩课程。<br><p>&nbsp;&nbsp;</p>
        点击公众号底部「个人中心」，进入页面「已报名课程」，即可查看已报名课程。<br><p>&nbsp;&nbsp;</p>
        已报名课程在讲师开课时会有公众号通知，点击通知详情然后进入课堂即可听课。</dd><p>&nbsp;&nbsp;</p>
    </dl>
    <dl>
      <p><span style="color: #3C4A55">&bull;</span>&nbsp;关于讲师</p>
      <dd>
        无论你是业内大咖还是职场新人，易灵微课都欢迎你成为讲师，因为高屋建瓴的全面指引固然能帮助其他人建立认知，但真实详细的踩坑指南亦有其受众人群。
        <br>
        <p>&nbsp;&nbsp;</p>
        无论你是否具有足够的用户号召力，我们都同样欢迎你成为讲师，因为可无条件退款的课程本质上依靠内容，只要你的课程内容能够有学员认可，那么课程就拥有其独特的价值。
        <br>
        <p>&nbsp;&nbsp;</p>
        只要你相信自己讲授的内容能够帮助到某一些人，让他们学有所获，不一定是技能提升，可以是建立认知，可以是路线引导，也可以是避坑指南，那么你就可以在易灵微课上授课。
      </dd>
      <p>&nbsp;&nbsp;</p>
    </dl>
    <dl>
      <p><span style="color: #3C4A55">&bull;</span>&nbsp;如何开课</p>
      <dd>
        PC浏览器进入链接 https://teacher.yike.fm 可开始讲师注册。<br><p>&nbsp;&nbsp;</p>
        公众号底部个人中心-成为讲师也可开始讲师注册。<br><p>&nbsp;&nbsp;</p>
      </dd>
    </dl>
    <dl>
      <p><span style="color: #3C4A55">&bull;</span>&nbsp;关于退款</p>
      <dd>
        对于学员而言，无条件退款是一种保障，让学员可以放心去尝试感兴趣的课程，在进入课程的1小时内，均可甄别课程是否值得报名，如不满意可直接退款。
        <br>
        注：我们认为1个小时已经足够用来甄别是否值得报名，超过1个小时学员拥有向讲师申请退款的渠道，但是无特殊理由，我们建议讲师不予退款。
        <br>
        <p>&nbsp;&nbsp;</p>
        对于讲师而言，无条件退款是一种保护，让讲师可以将精力集中在课程内容上，不用迎合所有人，觉得课程有价值的学员继续收听，觉得价值不足的学员直接退款，也就不用去担心口碑，不会影响到个人品牌。而从收益的角度讲，退款能够降低学员报名门槛，从而提升总报名人数。
        <br>
        <p>&nbsp;&nbsp;</p>
        对于讲师，退款率及退款人数不会对外公开；对于学员，退款信息不会展示给讲师和其他学员，亦不会影响以后的报名和退款。
      </dd>
      <p>&nbsp;&nbsp;</p>
    </dl>
    <dl>
      <p><span style="color: #3C4A55">&bull;</span>&nbsp;建议反馈</p>
      <dd>此外，易灵微课欢迎各位的意见、建议与批评，您可以直接在易灵微课公众号对话窗口反馈，也可以在个人中心【建议与反馈】子页面进行反馈。</dd>
    </dl>
    <dl>
      <p>&nbsp;&nbsp;</p>
      <dd>&nbsp;&nbsp;</dd>
    </dl>
  </section>
</template>

<script>
  export default{
    name: 'about',
    components: {
    },
    mounted() {

    },
    methods: {
    },
  }
</script>

<style scoped lang="stylus" rel="stylesheet/stylus">
  @import "index.styl";

  .about
    line-height: 1.4;
    padding: 40px;
    px2px(font-size, 28px);
    background:#fff;
    dl
      margin: 0;
      padding: 10px 10px;
      &:after
        content: ".";
        display: block;
        height: 0;
        clear: both;
        visibility: hidden;
      dt,dd
        float: left;
      dt
        width: 180px;
        color: #12b7f5;
      dd
        color: #70788C;
        px2px(font-size, 32px);
        /*width: 480px;*/
      p
        /*padding: 5px 0;*/
        margin: 0;
        padding: 0;
        color: #3C4A55;
        text-align: left;
        margin-bottom 10px;
        outline: none;
        px2px(font-size, 36px);


</style>
