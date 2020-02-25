<template>
  <ul class="evaluate-list">
    <li v-for="(list, index) in lists">
      <div class="e-textures clearfix">
        <div class="e-img pull-left">
          <img :src="list.user.avatar" />
        </div>
        <div class="e-text pull-left">
          <!--<div class="e-star">-->
            <!--<v-star :mode="2" :number="list.score"></v-star>-->
          <!--</div>-->
          <div class="text-name" v-text="list.user.name"></div>
          <v-star :mode="2" :number="list.score"></v-star>
          <p class="e-comment break-word" v-text="list.remark"></p>
          <a class="e-answer" ref="eAnswer" href="javascript:;" @click="showAnswer(index)" style="display:block;" v-show="!list.reply">回复</a>
          <div class="e-timer" v-text="list.tms"></div>
          <div class="e-textarea" ref="textarea" style="display:none;">
            <textarea ref="eText"></textarea>
            <div class="button">
              <span @click="cancleAnswer(index)">取消</span>
              <span @click="sendAnswer(index, list.cursor)">发送</span>
            </div>
          </div>
          <div class="e-reply" v-if="list.reply">
            <p class="reply">
              <span>[讲师回复]&nbsp;:</span>
              <span v-text="list.reply"></span>
            </p>
            <div class="e-timer" v-text="list.tms_reply"></div>
          </div>
        </div>
      </div>
    </li>
  </ul>
</template>

<script>
    import vStar from '@teacher/components/star.vue';
    import { trimStr } from '@lib/js/mUtils';

    export default{
      name: 'evaluate-list',
      components: {
        vStar
      },
      props: {
        lists: {
          type: Array
        }
      },
      data() {
        return {

        }
      },
      methods: {
        showAnswer(index) {
          this.$refs.eAnswer[index].style.display = 'none';
          this.$refs.textarea[index].style.display = 'block';
        },
        cancleAnswer(index) {
          this.$refs.eAnswer[index].style.display = 'block';
          this.$refs.textarea[index].style.display = 'none';
        },
        sendAnswer(index, cursor) {
          let text = trimStr(this.$refs.eText[index].value);
          if(!text){
            return;
          };
          let body = {
            cursor,
            text,
          };
          this.$store.dispatch('fetchLessonRate', body).then((data)=>{
            // 成功回复
            this.$refs.eAnswer[index].style.display = 'block';
            this.$refs.textarea[index].style.display = 'none';
            this.$parent.fetchEvaList();
            /*swal({
              title: '',
              text: '回复成功',
              confirmButtonText: "知道了"
            }, ()=>{
              this.$refs.eAnswer[index].style.display = 'block';
              this.$refs.textarea[index].style.display = 'none';
              this.$parent.fetchEvaList();
            });*/

          }, (err)=>{
            return swal({
              title: '错误提醒',
              text: err.message || '网络链接失败',
              confirmButtonText: "知道了"
            }, ()=>{});
          });
        },
      },
    };
</script>

<style scoped lang="stylus" rel="stylesheet/stylus">
  .evaluate-list
    padding: 31px;
    background: #fff;
    list-style-type: none;
    li
      position: relative;
      .e-textures
        background: #fff;
        .e-img
          margin-right: 10px;
          img
            width: 40px;
            height: 40px;
            border-radius: 50%;
            -webkit-border-radius: 50%;
        .e-text
          position: relative;
          width: 800px;
          :first-child
            color: #aaa;
            padding-bottom: 5px;
          .e-timer
            padding-bottom: 20px;
            color: #CCD2DC;
            px2px(font-size, 26px);
          .e-textarea
            textarea
              padding: 5px;
              width: 100%;
              height: 70px;
              border: 0 none;
              color: #3c4a55;
              background: #f4f4f8;
              outline: none;
            .button
              padding: 10px 0;
              text-align: right;
              span
                display: inline-block;
                padding: 3px 20px;
                color: #3c4a55;
                cursor: pointer;
                &:last-child
                  color: #12b7f5;
              span+span
                border-left: 1px solid #e7e7ec;
          .e-answer
            position: absolute;
            right: 0;
            color: #3c4a55;
            &:hover
              color: #12b7f5;
          .e-reply
            margin-top: -10px;
            border-top: 1px dotted #e6eaf2;
            .reply
              >:first-child
                color: #12b7f5;
      .e-comment
        width: 820px;
        line-height: 20px;
      .e-star
        position: absolute;
        right: 0;
        top: 0;
    li+li
      padding-top: 30px;
      border-top: 1px solid #e6eaf2;
</style>
