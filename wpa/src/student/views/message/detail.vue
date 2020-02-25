<template>
  <section ref="message-detail" class="message-detail">
    <div class="tab clearfix">
      <div class="pull-left">
        <a href="javascript:;" @click="historyBack"><i class="iconfont icon-open-left"></i>返回</a>
      </div>
      <div class="pull-right">
        <router-link :to="{name:'messageDiscuss'}" replace>
          <i class="iconfont icon-shouye"></i>
          交流
        </router-link>
      </div>
    </div>
    <div class="content" v-if="items.length">
      <scroller :on-infinite="infinite" noDataText="没有更多留言" ref="my_scroller">
        <dicuss-list :mark="'landlord'" :items="landlordItems" @callback="callback" @startReply="startReply" @startReport="startReport" @startLike="startLike" @endLike="endLike" @menuChange="menuChange" v-if="curModel != 'chain'"></dicuss-list>
        <div class="child" :class="{'has-border':curModel != 'chain'}">
          <div class="child-title" v-if="curModel != 'chain'">
            {{curModel == 'reply'?`${landlordItems[0].stats.reply}条回复`:'相关留言'}}
          </div>
          <div class="content">
            <dicuss-list :items="items" @callback="callback" @startReport="startReport" @startLike="startReplyLike" @endLike="endReplyLike" @menuChange="menuChange"></dicuss-list>
          </div>
        </div>
      </scroller>
    </div>
    <div class="content" v-if="!items.length && landlordItems.length && curModel != 'chain'">
      <dicuss-list :mark="'landlord'" :items="landlordItems" @callback="callback" @startReply="startReply" @startReport="startReport" @startLike="startLike" @endLike="endLike" @menuChange="menuChange"></dicuss-list>
    </div>
    <loading :show="showLoading"></loading>
    <!--图片预览-->
    <message-preview v-show="previewImg" :preview="previewImg"></message-preview>
    <!--回复、留言框-->
    <dicuss-reply :name="name" :types="type" :cursor="cursor" :genre="genre" @callback="callback" @replyCallback="replyCallback" v-if="showReply"></dicuss-reply>
    <!--举报-->
    <dicuss-report :cursor="curReportCursor" @callback="callbackReport" v-if="showReport"></dicuss-report>
    <!--底部菜单、按钮-->
    <div class="lesson-more" v-if="showMore">
      <div class="more-container">
        <div @click="backToHome">回到首页</div>
        <div @click="backToCenter">个人中心</div>
      </div>
    </div>
    <m-button :text="text" :callBack="showCallReply" @showMenu="showMenu" v-if="landlordItems.length"></m-button>
  </section>
</template>

<script>
  import { mapGetters } from 'vuex';
  import mButton from './button.vue';
  import dicussList from './list.vue';
  import dicussReply from './reply.vue';
  import dicussReport from './report.vue';
  import messagePreview from './preview.vue';
  import Loading from '@student/components/loading';

  export default{
    name: 'message-detail',
    components: {
      Loading,
      mButton,
      dicussList,
      dicussReply,
      dicussReport,
      messagePreview,
    },
    data() {
      return{
        showLoading: false,
        previewImg: '',
        showReply: false,
        showReport: false,
        showMore: false,
        landlordItems: [],
        items: [],
        name: '',
        type: '',
        cursor: '',
        genre: 2,
        text: '回复...',
        curModel: 'reply',
        curReportCursor: '',
      }
    },
    created() {
      // 开始加载数据
      this.loadData();
    },
    mounted() {
      let detail = this.$refs['message-detail'];
      //
      this.$nextTick(()=>{
        setTimeout(()=>{
          detail.style.height = `${window.innerHeight}px`;
        });
      });
    },
    watch: {
      '$route': 'loadData' //切换路由，调用reloadData方法
    },
    methods: {
      loadData() {
        let query = this.$route.query;
        // 赋值
        this.curModel = query.model || 'reply';
        // 开始初始化加载
        if(query && query.model){
          // 开始分类
          switch (query.model){
            case 'chain':
              this.startChainInit();
              break;
            case 'assoc':
              this.startInit();
              break;
            case 'reply':
            default:
              this.startInit();
          }
        }else{
          this.startInit();
        }
      },
      startInit(){
        let params = this.$route.params;
        params.target = params.cursor;
        // 清空
        this.items = [];
        // 获取用户信息
        this.showLoading = true;
        this.$store.dispatch('fetchBoardFocus', {...params}).then((data) => {
          this.showLoading = false;
          this.landlordItems = [data];
          // 改变文字
          this.text = `回复${data.user.name}...`;
          this.infinite();
          console.log('success');
        }, (err) => {
          //
          this.showLoading = false;
          swal({
            title: '错误提醒',
            text: err.message,
            confirmButtonText: "知道了"
          });
        });
      },
      startChainInit(){
        this.items = [];
        this.landlordItems = [];
        this.infinite();
      },
      showBigImage(img){
        if(isPC){
          this.previewImg = img;
          /*this.previewImg = img.split('#')[2];*/
        }else if(isWeiXin){
          wx.previewImage({
            current: img,
            urls: [img]
          });
        }
      },
      startReply(item){
        this.type = item.type;
        this.name = item.user.name;
        this.cursor = item.cursor;
        this.showReply = true;
      },
      startReport(item) {
        this.curReportCursor = item.cursor;
        this.showReport = true;
      },
      callback(img){
        this.showBigImage(img);
      },
      callbackReport(img){
        this.showBigImage(img);
      },
      startLike(index, isLoad) {
        let temp = this.landlordItems.splice(index, 1);
        temp[0].likeLoading = isLoad;
        this.landlordItems.splice(index, 0, temp[0]);
      },
      endLike(index, data) {
        let temp = this.landlordItems.splice(index, 1)[0];
        if(data.isLike){
          temp.self = {
            isLike: data.isLike,
          };
        }else{
          temp.self = null;
        }
        //
        temp.stats.liked = data.liked || 0;
        temp.likeLoading = false;
        this.landlordItems.splice(index, 0, temp);
      },
      startReplyLike(index, isLoad) {
        let temp = this.items.splice(index, 1);
        temp[0].likeLoading = isLoad;
        this.items.splice(index, 0, temp[0]);
      },
      endReplyLike(index, data) {
        let temp = this.items.splice(index, 1)[0];
        if(data.isLike){
          temp.self = {
            isLike: data.isLike,
          };
        }else{
          temp.self = null;
        }
        //
        temp.stats.liked = data.liked || 0;
        temp.likeLoading = false;
        this.items.splice(index, 0, temp);
      },
      backToHome() {
        this.$router.push({ name: 'list' });
      },
      backToCenter() {
        this.$router.push({ name: 'userCenter' });
      },
      showMenu(show) {
        this.showMore = show;
      },
      showCallReply() {
        if(this.landlordItems.length){
          this.startReply(this.landlordItems[0]);
        }
      },
      replyCallback(cursor) {
        // 获得列表
        this.$router.push({ name: 'messageDetail', params: { cursor: cursor }});
        //this.startInit();
      },
      // 回复
      startReferItems(params, cursor, done) {
        let query = {
          lesson_sn: params.lesson_sn,
          target: params.cursor,
          cursor: cursor,
          limit: 10,
        };

        // 获得相关会话
        this.$store.dispatch('fetchBoardRefer', query).then((data) => {
          //
          this.items = [...this.items, ...data];
          //
          if (done) {
            if (data.length < query.limit) {
              done(true);
            } else {
              setTimeout(()=>{
                done();
              }, 1000);
            };
          };
          console.log('获取列表成功!');
        }, (error) => {
          done(true);
          console.log('fail');
          swal({
            title: '错误提醒',
            text: error,
            confirmButtonText: "知道了"
          });
        });
      },
      // 对话
      startChainItems(params, cursor, done) {
        let query = {
          lesson_sn: params.lesson_sn,
          target: params.cursor,
          cursor: cursor,
          limit: 10,
        };

        // 获得相关会话
        this.$store.dispatch('fetchBoardChain', query).then((data) => {
          //
          this.items = [...this.items, ...data];
          //
          if (done) {
            if (data.length < query.limit) {
              done(true);
            } else {
              setTimeout(()=>{
                done();
              }, 1000);
            };
          };
          console.log('获取列表成功!');
        }, (error) => {
          done(true);
          console.log('fail');
          swal({
            title: '错误提醒',
            text: error,
            confirmButtonText: "知道了"
          });
        });
      },
      // 相关
      startAssocItems(params, cursor, done) {
        let query = {
          lesson_sn: params.lesson_sn,
          target: params.cursor,
          cursor: cursor,
          limit: 10,
        };

        // 获得相关会话
        this.$store.dispatch('fetchBoardAssoc', query).then((data) => {
          //
          this.items = [...this.items, ...data];
          //
          if (done) {
            if (data.length < query.limit) {
              done(true);
            } else {
              setTimeout(()=>{
                done();
              }, 1000);
            };
          };
          console.log('获取列表成功!');
        }, (error) => {
          done(true);
          console.log('fail');
          swal({
            title: '错误提醒',
            text: error,
            confirmButtonText: "知道了"
          });
        });
      },
      historyBack() {
        // 返回上一级
        this.$router.go(-1);
      },
      // 改变
      menuChange(cursor, type) {
        // 开始分类
        switch (type){
          case 'chain':
            this.$router.push({ name: 'messageDetail', params: { cursor: cursor }, query: { model: 'chain' } });
            break;
          case 'assoc':
            this.$router.push({ name: 'messageDetail', params: { cursor: cursor }, query: { model: 'assoc' } });
            break;
          case 'reply':
          default:
            this.$router.push({ name: 'messageDetail', params: { cursor: cursor }, query: { model: 'reply' } });
        }
      },
      // 无穷
      infinite(done) {
        // 开始
        let length = this.items.length;
        let params = this.$route.params;
        let cursor = '';
        try{
          cursor = length ? this.items[length - 1].cursor : '';
        }catch(e){};

        // 开始分类
        switch (this.curModel){
          case 'chain':
            this.startChainItems(params, cursor, done);
            break;
          case 'assoc':
            this.startAssocItems(params, cursor, done);
            break;
          case 'reply':
          default:
            this.startReferItems(params, cursor, done);
        }
      },
    }
  };
</script>

<style lang="stylus" rel="stylesheet/stylus">
  @import "index.styl";
</style>
