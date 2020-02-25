<template>
  <div class="l-chatbox" :class="{'student':isPC && !isOwner}">
    <div class="box">
      <span class="box-more" v-if="!commentShow || isPC" :class="{'isWX':isWX}">
        <button @click="showModule">
          <span class="iconfont icon-caidan"></span>
        </button>
      </span>
      <div class="box-msg s-msg" v-if="lessonInfo.step!='finish' && lessonInfo.step!='closed' && !fullMember" :class="{'open':commentShow}">
        <button class="comment" @click="showComment" v-if="!commentShow">讨论区</button>
        <input type="text" v-model="msgVal" placeholder="请输入文字..." @focus="focusEvent" @keydown="v_keydown" />
      </div>
      <div class="box-msg c-finish" v-if="(lessonInfo.step=='finish' || lessonInfo.step=='closed') && !fullMember">
        <button class="comment" v-if="!commentShow">讨论区</button>
      </div>
      <div class="box-msg c-finish" v-if="!isPC && fullMember">
        <button class="comment" v-if="!commentShow">讨论区已满</button>
      </div>
      <v-refund></v-refund>
      <button class="box-send" title="Ctrl+Enter或Alt+S" @click="sendMsg" v-if="commentShow && !msgSending && !fullMember">发送</button>
      <button class="box-send sending" v-if="commentShow && msgSending">发送</button>
      <div class="more-choice" v-if="boxMoreShow">
        <div v-if="footerConf && footerConf.menu">
          <button v-for="menu in footerConf.menu" @click="showModule">
            <span class="sItem" @click="backHome" v-if="menu.key == 'home'">回到首页</span>
            <span class="sItem" @click="showEva" v-else-if="menu.key == 'rate'">评价课程</span>
            <span class="sItem" @click="showRefund" v-else-if="menu.key == 'refund'">申请退款</span>
            <span class="sItem" @click="showAdmire" v-else-if="menu.key == 'admire'">赞赏讲师</span>
            <a class="sItem" v-else :href="menu.href">{{menu.text}}</a>
          </button>
        </div>
      </div>
      <!--<div class="more-choice" v-if="boxMoreShow" :class="{'has-refund':(lessonInfo.event != 'refund' && lessonInfo.price && !lessonInfo.refund_info && lessonInfo.refund_mode)}">
        <button @click="backHome">回到首页</button>
        <button @click="showEva">评价课程</button>
        &lt;!&ndash;<button @click="showEva" v-if="!isEvaluate && !lessonInfo.rated && (lessonInfo.event != 'refund')">评价课程</button>&ndash;&gt;
        <button @click="showRefund" v-if="lessonInfo.event != 'refund' && lessonInfo.price && !lessonInfo.refund_info && lessonInfo.refund_mode">申请退款</button>
      </div>-->
    </div>
  </div>
</template>

<script type="text/javascript">
  import { mapState } from 'vuex';
  import { trimStr } from '@lib/js/mUtils';
  import { vSendMsg } from '@live/assets/js/webim_comment';
  import vRefund from '@live/components/chatbox/refund';
  import swal from 'sweetalert';
  // scroll
  var vScroll = null;

  export default
  {
    name: 's-chatbox',
    components: {
      vRefund,
    },
    data() {
      return {
        isPC: isPC,
        isWX: isWeiXin,
        msgVal: '',
        msgSending: false,
      };
    },
    computed: {
      ...mapState([
        'isOwner',
        'commentShow',
        'lessonInfo',
        'studentHost',
        'isEvaluate',
        'boxMoreShow',
        'footerConf',
        'fullMember',
      ])
    },
    created() {
      if(this.isPC){
        this.showComment();
      }
    },
    methods: {
      sendMsg() {
        // 打开发送状态
        if(trimStr(this.msgVal)){
          this.msgSending = true;
        }
        // 开始发送
        vSendMsg(this.msgVal, (err, data) => {
          // 关闭发送状态
          this.msgSending = false;
          //
          if(!err){
            this.msgVal = '';
            this.commentShow || this.showComment();
          }else{
            if(err.ErrorCode == 10007){
              swal({
                  title: '错误提醒',
                  text: '您已被踢出群！现在要将您送回课程列表!',
                  confirmButtonText: "知道了"
                }, ()=>{
                  window.location.href = this.studentHost;
              });
            }else if(err.ErrorCode == 10017){
              swal({
                title: '错误提醒',
                text: '您已被禁言!',
                confirmButtonText: "知道了"
              });
            }else{
              swal({
                title: '错误提醒',
                text: err.SrcErrorInfo,
                confirmButtonText: "知道了"
              });
            }
          }
        });
      },
      showComment() {
        this.$store.commit('UPDATE_COMMETN_SHOW', true);
        // 讨论区滑动
        let count = 0;
        let scHeight = 0;
        this.$nextTick(()=>{
          (function scrollInspector(){
            let cScroll = document.getElementById('comment-body');
            if(cScroll.scrollHeight != scHeight){
              scHeight = cScroll.scrollHeight;
            }
            //
            if(count >10 || (count>1 && cScroll.scrollHeight == scHeight)){
              return (cScroll.scrollTop = cScroll.scrollHeight);
            }
            //
            count++;
            setTimeout(scrollInspector);
          })();
        });
        // 更多菜单
        if(this.boxMoreShow){
          this.$store.commit('UPDATE_BOX_MORE', false);
        }
      },
      showModule() {
        this.$store.commit('UPDATE_BOX_MORE', !this.boxMoreShow);
      },
      showRefund() {
        // 组装
        let params = {
          lesson_sn: this.lessonInfo.sn,
          mode: this.lessonInfo.refund_mode,
          title: this.lessonInfo.title,
          price: this.lessonInfo.price,
          teacher: this.lessonInfo.teacher.name,
        };
        let paramsStr = `lesson_sn=${params.lesson_sn}&origin=live`;
        /*let count = 0;*/
        // 组装
        /*for (let p in params){
          paramsStr = (count++) ? `${paramsStr}&${p}=${encodeURIComponent(params[p])}` : `${p}=${encodeURIComponent(params[p])}`;
        };*/

        // 跳转
        window.location.href = `${this.studentHost}#/course/refund?${paramsStr}`;
      },
      showAdmire() {
        this.$emit('admire');
      },
      showEva() {
        this.$store.commit('UPDATE_EVALUATE_SHOW', true);
        this.$store.commit('UPDATE_BOX_MORE', false);
      },
      backHome() {
        window.location.href = this.studentHost;
      },
      focusEvent() {
        let verion = (navigator.appVersion).match(/OS (\d+)_(\d+)_?(\d+)?/);
        // 是否存在
        if(verion && window.devicePixelRatio == 3 && screen.height == 812 && screen.width == 375){
          return;
        }
        // 开始判断
        try{
          let ver = parseInt(verion[1], 10);
          console.log('ver', ver);
          //
          if(ver && ver < 11){
            setTimeout(()=>{
              document.body.scrollTop = document.body.scrollHeight;
            },400);
          }
        }catch(e){};
      },
      v_keydown(event) {
        // 是否正在发送
        if(this.msgSending)return;
        // 开始
        let e = event || window.event;
        // 快捷键 ctrl+enter
        if(e.ctrlKey && e.keyCode == 13){
          this.sendMsg();
        }
        // 快捷键 alt+s
        if(e.altKey && e.keyCode == 83){
          this.sendMsg();
        }
      },
    },
  };
</script>
<style lang="stylus" rel="stylesheet/stylus">
  @import "index.styl";
  .more-choice
    a
      color: #3c4a55;
</style>
