<template>
  <ul class="evaluate-list">
    <li v-for="list in lists">
      <div class="e-textures clearfix">
        <div class="e-img pull-left">
          <img :src="list.user.avatar" />
        </div>
        <div class="e-text pull-left">
          <div v-text="list.user.name"></div>
          <!--<div v-text="list.tms"></div>-->
        </div>
      </div>
      <p class="e-comment break-word" v-text="list.remark"></p>
      <div class="e-time" v-text="list.tms"></div>
      <div class="e-reply" v-if="list.reply">
        <div class="reply">
          <div>讲师回复</div>
          <span v-text="list.reply"></span>
        </div>
        <div v-text="timeFormat(list.tms_reply)"></div>
      </div>
      <div class="e-star">
        <v-star :mode="2" :number="list.score"></v-star>
      </div>
    </li>
  </ul>
</template>

<script>
    import vStar from '@student/components/star.vue';

    export default{
      name: 'evaluate-list',
      components: {
        vStar,
      },
      props: {
        lists: {
          type: Array
        },
      },
      methods: {
        timeFormat(value){
          return value.replace(/^(\d{4})-(\d{1,2})-(\d{1,2}) (\d{2}):(\d{2}):(\d{2})$/,'$1-$2-$3 $4:$5');
        },
      },
    };
</script>

<style scoped lang="stylus" rel="stylesheet/stylus">
  @import '~@lib/css/index.styl';

  .evaluate-list
    margin-top: 20px;
    padding: 31px;
    background: #fff;
    list-style-type: none;
    /*max-height: 100px;
    overflow-x: hidden;
    overflow-y: auto;*/
    li
      position: relative;
      margin-bottom: 32px;
      border-bottom: 1px solid #E6EAF2;
      .e-textures
        background: #fff;
        .e-img
          margin-right: 10px;
          img
            width: 60px;
            height: 60px;
            border-radius: 50%;
            -webkit-border-radius: 50%;
        .e-text
          :first-child
            color: #808388;
          :last-child
            padding-top: 15px;
            color: #808388;
            px2px(font-size, 26px);
      .e-reply
        .reply
          >:first-child
            margin: 10px 0;
            padding-left: 15px;
            color: #808388;
            border-left: 5px solid #12b7f5;
            px2px(font-size, 29px);
            px2px(line-height, 29px);
        >:last-child
          padding-top: 15px;
          color: #ccd2dc;
          px2px(font-size, 26px);
      .e-comment
        margin: 15px 0;
        px2px(line-height, 40px);
      .e-time
        padding-bottom: 15px;
        color: #ccd2dc;
        px2px(font-size, 26px);
      .e-star
        position: absolute;
        right: 0;
        top: 0;
    li:last-child
      margin-bottom: 0;
      border-bottom: 0 none;
</style>
