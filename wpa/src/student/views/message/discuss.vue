<template>
  <section class="content-dicuss" ref="content-dicuss">
    <div class="loading" v-if="loading">
      数据加载中...
    </div>
    <div class="tab clearfix" v-if="!loading">
      <div class="pull-left ft0">
        <router-link :to="{name:'brief', query:{lesson_sn:lesson_sn}}" replace>
          <span class="back-brief">
            <i class="iconfont icon-kechengxiangqing"></i>
          </span>
        </router-link>
        <a href="javascript:;" @click="switchMessage(key)" v-for="(map, key) in type_map" :class="{'active':(key == curType)}">
          {{map}}
        </a>
        <router-link :to="{name:'messageTask', query:{lesson_sn:lesson_sn}}" :class="{'active': !type_map[curType]}" replace>评价</router-link>
        <!--<router-link :to="{name:'evaluate',query:{lesson_sn:lesson_sn}}" :class="{'active': !type_map[curType]}">评价</router-link>-->
      </div>
      <div class="pull-right sort">
        <!--<select class="select" v-model="sort" @change="selectChange">
          <option value="weight">默认排序</option>
          <option value="time_asc">时间正序</option>
          <option value="time_desc">时间倒序</option>
        </select>
        <i class="iconfont icon-chevron-down"></i>-->
        <div class="select">
          <div @click="toggleSelect">
            <span>
              {{sort == 'default'?'默认排序':(sort == 'time_asc'?'时间正序':'时间倒序')}}
            </span>
            <i class="iconfont icon-chevron-down"></i>
          </div>
        </div>
      </div>
    </div>
    <div class="select-menu" v-if="showSelect">
      <span @click="selectChange('default')">默认排序</span>
      <span @click="selectChange('time_asc')">时间正序</span>
      <span @click="selectChange('time_desc')">时间倒序</span>
    </div>
    <div class="loading" v-if="!loading && !items.length">
      暂无数据
    </div>
    <div class="content" v-if="items.length">
      <scroller :on-infinite="infinite" noDataText="没有更多留言" ref="my_scroller">
        <dicuss-list :items="items" @callback="callback" @startReply="startReply" @startReport="startReport" @startLike="startLike" @endLike="endLike" @menuChange="menuChange"></dicuss-list>
      </scroller>
    </div>
    <div class="lesson-more" v-if="showMore">
      <div class="more-container">
        <div @click="backToHome">回到首页</div>
        <div @click="backToCenter">个人中心</div>
      </div>
    </div>
    <m-button :text="text" :callBack="showCallReply" @showMenu="showMenu"></m-button>
    <!--回复、留言框-->
    <dicuss-reply :name="name" :types="type" :cursor="cursor" :genre="genre" @callback="callback" v-if="showReply"></dicuss-reply>
    <!--举报-->
    <dicuss-report :cursor="curReportCursor" @callback="callbackReport" v-if="showReport"></dicuss-report>
    <!--图片预览-->
    <message-preview v-show="previewImg" :preview="previewImg"></message-preview>
  </section>
</template>

<script>
  import { mapGetters } from 'vuex';
  import dicussList from './list.vue';
  import dicussReply from './reply.vue';
  import dicussReport from './report.vue';
  import mButton from './button.vue';
  import messagePreview from './preview.vue';

  export default{
    name: 'message-dicuss',
    components: {
      mButton,
      dicussList,
      dicussReply,
      dicussReport,
      messagePreview,
    },
    computed: {
      ...mapGetters({
        'messageTypeMap': 'getMessageTypeMap'
      })
    },
    data() {
      return{
        lesson_sn: '',
        sort: 'default',
        loading: true,
        showMore: false,
        showReply: false,
        showReport: false,
        showSelect: false,
        previewImg: '',
        type_map: null,
        items: [],
        type: '',
        name: '',
        genre: 1,
        cursor: '',
        curType: '',
        replyType: '',
        text: '新留言...',
        curReportCursor: '',
      }
    },
    created() {
      //获取路由参数
      let params = this.$route.params;
      let query = this.$route.query;
      this.lesson_sn = params.lesson_sn;
      this.showSelect = false;
      // 初始化留言板
      this.$store.dispatch('fetchBoardInit', {lesson_sn: params.lesson_sn}).then((data) => {
        // 获得列表
        this.curType = data.type_now;
        this.type_map = data.type_map;
        if(query.type_now){
          this.curType = query.type_now;
        }
        // 是否有key
        if(this.messageTypeMap[this.curType]){
          this.sort = this.messageTypeMap[this.curType];
        }
        // 开始
        this.infinite();
      }, (error) => {
        //
        swal({
          title: '错误提醒',
          text: error.message,
          confirmButtonText: "知道了"
        });
        this.loading = false;
        console.log('fail');
      });
    },
    mounted() {
      let contentDicuss = this.$refs['content-dicuss'];
      setTimeout(()=>{
        contentDicuss.style.height = `${window.innerHeight}px`;
      });
    },
    watch: {
      '$route': 'reloadData' //切换路由，调用reloadData方法
    },
    methods: {
      fetchDiscuss(cursor, done) {
        let query = {
          lesson_sn: this.$route.params.lesson_sn,
          sort: this.sort,
          type: this.curType,
          cursor: cursor,
          limit: 10,
        };
        // 获得评价列表
        this.fetchBoardSlice(query, done);
      },
      fetchBoardSlice(query, done) {
        this.$store.dispatch('fetchBoardSlice', query).then((data) => {
          //
          this.items = [...this.items, ...data];
          // 结束加载
          this.loading = false;
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
      switchMessage(type){
        this.$router.push({ name: 'messageDiscuss',
          params: {
            lesson_sn:this.$route.params.lesson_sn
          },
          query: {
            type_now: type
          }
        });
      },
      infinite(done) {
        // 开始
        let length = this.items.length;
        // 是否有游标和长度
        if (!length){
          return this.fetchDiscuss('', done);
        }
        try{
          this.fetchDiscuss(this.items[length - 1].cursor, done);
        }catch(e){};
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
        this.genre = 1;
        this.type = this.curType;
        this.showReply = true;
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
      callback(img){
        this.showBigImage(img);
      },
      callbackReport(img){
        this.showBigImage(img);
      },
      startReply(item) {
        this.genre = 2;
        this.type = item.type;
        this.name = item.user.name;
        this.cursor = item.cursor;
        this.showReply = true;
      },
      startReport(item) {
        this.curReportCursor = item.cursor;
        this.showReport = true;
      },
      reloadData(){
        let query = this.$route.query;
        // 清空选项
        this.showSelect = false;
        // 是否有
        if(query.type_now){
          this.curType = query.type_now;
        }
        // 是否有key
        if(this.messageTypeMap[this.curType]){
          this.sort = this.messageTypeMap[this.curType];
        }else{
          this.sort = 'default';
        }
        this.items = [];
        this.infinite();
      },
      selectChange(value) {
        //
        this.showSelect = false;
        if(this.sort != value){
          this.items = [];
          this.sort = value;
          // 保存当前排序
          this.$store.commit('CHANGE_MESSAGE_TYPE', {[this.curType]:value});
          this.infinite();
        }
      },
      startLike(index, isLoad) {
        let temp = this.items.splice(index, 1);
        temp[0].likeLoading = isLoad;
        this.items.splice(index, 0, temp[0]);
      },
      endLike(index, data) {
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
      toggleSelect() {
        this.showSelect = !this.showSelect;
      },
    }
  };
</script>

<style lang="stylus" rel="stylesheet/stylus">
  @import "index.styl";
</style>
