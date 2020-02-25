<template>
  <section class="rank">
    <div class="rank-con">
      <scroller :on-infinite="infinite" noDataText="没有更多记录" ref="my_scroller" v-if="rankList.length || !isInfinit">
        <div class="rank-title" v-if="rankLocate">
          <div class="rank-image clearfix">
            <span class="img pull-left">
              <img :src="userInfo.avatar" />
            </span>
            <div class="rank-text pull-left">
              <div>{{userInfo.name}}</div>
              <div v-if="rankLocate">
                邀请了<span>{{rankLocate.score || 0}}</span>位学员
                {{rankLocate.rank?`, 排名`:''}}
                <span v-if="rankLocate.rank" v-text="rankLocate.rank"></span>
              </div>
            </div>
          </div>
          <div class="cur-rank">
            <button @click="shareReward">邀请卡</button>
            <!--当前排名<br />
            <span>{{rankLocate.rank || 0}}</span>-->
          </div>
        </div>
        <p class="tips">　</p>
        <ul>
          <li v-for="(list, index) in rankList">
            <div class="rank-list clearfix">
              <div class="pull-left">
                <div class="rank-left">
                  <span class="no1" v-if="list.rank==1">
                    <i class="iconfont icon-cnlonghubang"></i>
                  </span>
                  <span class="no2" v-if="list.rank==2">
                    <i class="iconfont icon-cnlonghubang"></i>
                  </span>
                  <span class="no3" v-if="list.rank==3">
                    <i class="iconfont icon-cnlonghubang"></i>
                  </span>
                  <span class="no-other" v-if="index>2" v-text="list.rank"></span>
                  <img :src="list.avatar" />
                  <span>{{list.name}}</span>
                </div>
              </div>
              <div class="invitations pull-right">
                <span>
                  邀请了<span class="rank-score">{{list.score}}</span>人
                </span>
              </div>
            </div>
          </li>
        </ul>
      </scroller>
      <div class="rank-spec" v-if="!rankList.length && isInfinit">
        <div class="rank-title" v-if="rankLocate">
          <div class="rank-image clearfix">
            <span class="img pull-left">
              <img :src="userInfo.avatar" />
            </span>
            <div class="rank-text pull-left">
              <div>{{userInfo.name}}</div>
              <div>
                邀请了<span>{{rankLocate.score || 0}}</span>个朋友来听课
                {{rankLocate.rank?`, 当前排名`:''}}
                  <span v-if="rankLocate.rank" v-text="rankLocate.rank"></span>
              </div>
            </div>
          </div>
          <div class="cur-rank">
            <button @click="shareReward">邀请卡</button>
            <!--当前排名<br />
            <span>{{rankLocate.rank || 0}}</span>-->
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
  import { mapGetters } from 'vuex';

  export default{
    name: 'rank',
    components: {
    },
    computed: {
      ...mapGetters({
        rankLocate: 'getRankLocate',
        userInfo: 'getUserInfo',
      }),
    },
    data() {
      return {
        target: '',
        cursor: '',
        rankList: [],
        isInfinit: false,
        studentHost: (process.env.NODE_ENV=='production'?process.env.STUDENT_HOST.replace(/\/$/,''):'/student.html?'),
      }
    },
    created() {
      let params = this.$route.params;
      this.target = params.target
      // 账户余额
      this.$store.dispatch('fetchRankLocate', params).then(()=>{
        console.log('fetchRankLocate success');
      },(err)=>{
        swal({
          title: '错误提醒',
          text: (err.message ? err.message : '网络链接失败'),
          confirmButtonText: "知道了"
        });
      });
    },
    beforeRouteLeave(to, from, next) {
      this.$store.commit('FETCH_RANK_LOCATE', null);
      next();
    },
    methods: {
      infinite(done) {
        // 开始
        let length = this.rankList.length;
        // 是否有游标和长度
        if (!length){
          return this.fetchRank(done);
        }
        try{
          this.fetchRank(done);
        }catch(e){};
      },
      fetchRank(done) {
        let query = this.cursor?{cursor:this.cursor,limit:10,target: this.target}:{limit:10, target: this.target};
        // 获得评价列表
        this.$store.dispatch('fetchRankList', query).then((data) => {
          //
          this.rankList = [...this.rankList, ...data];
          if(data.length){
            this.cursor = data[data.length-1].cursor;
          }
          if(!this.isInfinit)this.isInfinit=true;
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
          //
          if(!this.isInfinit)this.isInfinit=true;
          swal({
            title: '错误提醒',
            text: error.message || '网络链接失败',
            confirmButtonText: "知道了"
          });
        });
      },
      shareReward(){
        window.location.href = `${this.studentHost}/promote-card?target_sn=${this.target}`;
      }
    },
  };
</script>

<style lang="stylus" rel="stylesheet/stylus">
  @import "index.styl";
</style>
