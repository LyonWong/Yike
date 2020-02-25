<template>
  <!-- live header -->
  <div class="live-header">
    <span class="title" v-if="!isOwner">听课指南</span>
    <span class="title clearfix" v-if="isOwner">
      授课须知
      <a class="handle-fold" href="javascript:;" @click="toggleFold">{{isFold?"展开":"折叠"}}</a>
    </span>
    <ul class="header-notice" v-if="!isOwner">
      <li>可展开讨论区与其他学员交流</li>
      <li>语音正在播放时，点击&nbsp;<i class="iconfont icon-laba"></i> 可定位到播放位置</li>
      <li>课程内容可能消耗一定流量，建议在WiFi环境观看收听</li>
      <li>可在PC上访问 https://yike.fm 听课。 点击【开始学习】>【已购】 可查看已报名课程。推荐使用Chrome浏览器</li>
      <li>讲师在本产品上发表的全部原创内容（包括但不限于文字、音频、图片等）著作权均归讲者本人所有。未经讲者授权许可，学员不得以任何载体或形式使用讲者的内容。</li>
    </ul>
    <ul class="header-notice" v-if="isOwner" :class="{'l-unfold':isFold}">
      <li>授课内容不得违反国家法律法规，易灵微课可能在未告知的情况下配合有关部门进行审查</li>
      <li>如确认讲师在课堂发布与法律法规相抵触的政治、情色、宗教及其他违规内容，本平台有权直接终止课程，删除课程记录，对全体报名学员实行全额退款</li>
      <li style="padding-bottom: 10px">讲师在本产品上发表的全部原创内容（包括但不限于文字、音频、图片等）著作权均归讲者本人所有。未经讲者授权许可，学员不得以任何载体或形式使用讲者的内容，如讲师需要，可以授权本平台进行相关内容维权</li>
    </ul>
    <span class="title clearfix" v-if="isOwner">
      授课提醒
    </span>
    <ul class="header-notice" v-if="isOwner" :class="{'l-unfold':isFold}">
      <li>录音建议使用麦克风并保持环境安静，语音时长范围为3秒至3分钟</li>
      <li>发送图片大小上限为5M，截图可直接在输入区粘贴发送</li>
      <li>授课过程中，超过1小时未发布新消息，课程将自动完结</li>
      <li>课程内容讲授完成，可选择进入课后交流状态或直接完结课程
        <ul>
        <li style="list-style-type: circle;">开启课后交流可继续与学员交流与讨论，或补充课程内容</li>
        <li style="list-style-type: circle;">课后交流默认时长为72小时，此期间讲师可直接结束授课</li>
        <li style="list-style-type: circle;">结束课程则意味着关闭所有发言功能，课程进入回放模式</li>
      </ul>
      </li>
    </ul>

  </div>
</template>

<script>
  import {mapState} from 'vuex';

  export default
  {
    name: 'l-header',
    computed: {
      ...mapState([
        'isOwner',
        'lessonInfo',
      ]),
    },
    data() {
      return {
        isFold: true,
      };
    },
    created() {
      this.isFold = (this.lessonInfo.form == 'im') ? true : false;
    },
    methods: {
      toggleFold() {
        this.isFold = !this.isFold;
      },
    },
  };
</script>

<style scoped lang="stylus" rel="stylesheet/stylus">
  @import "index.styl";

  .title
    .handle-fold
      float: right;
      color: #12b7f5;
      text-decoration: none;
  .header-notice
    .icon-laba
      padding: 5px;
      color: #fff;
      background: #12b7f5;
      border-radius: 50%;
      -webkit-border-radius: 50%;
      px2px(font-size, 32px);
    &.l-unfold
      display: none;
  .is-pc
    .header-notice
      .icon-laba
        padding: 2px;
</style>
