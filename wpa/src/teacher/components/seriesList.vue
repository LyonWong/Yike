<template>
  <div class="course-list">
    <div class="my-lesson clearfix">
      <!--<router-link to="/course/create">创建课程</router-link>-->
      <router-link to="/course/series/create" class="mr10">创建系列课</router-link>
      <router-link to="/course/list" class="lesson-title">课程列表</router-link>
      <router-link to="/course/series/list" class="lesson-title">系列课列表</router-link>
    </div>
    <ul>
      <li class="head">
        <span>课程名称</span>
        <span>课程状态</span>
        <span>统一售价</span>
        <!--<span>分成比例</span>-->
        <!--<span>报名人数</span>-->
        <!--<span>课程收入</span>-->
        <!--<span>分成收入</span>-->
        <span class="handle-btn">操作</span>
      </li>
      <li v-for="list in lists">
        <span>{{list.name}}</span>
        <span>已开{{ list.scheme | specKey('opened') }}/{{ list.scheme | specKey('total') }}节课</span>
        <span>{{ list.scheme | specKey('price') }}</span>
        <!--<span>-->
          <!--<span class="gray" v-if="list.step == 'submit'">-->
            <!--<i class="iconfont icon-dot"></i>-->
            <!--审核中-->
          <!--</span>-->
          <!--<span class="red" v-if="list.step == 'denied'">-->
            <!--<i class="iconfont icon-dot"></i>-->
            <!--未通过-->
          <!--</span>-->
          <!--<span class="red" v-if="list.step == 'closed'">-->
            <!--<i class="iconfont icon-dot"></i>-->
            <!--已下架-->
          <!--</span>-->
          <!--<span class="yellow" v-if="list.step == 'opened'">-->
            <!--<i class="iconfont icon-dot"></i>-->
            <!--报名中-->
          <!--</span>-->
          <!--<span class="green" v-if="list.step == 'onlive'">-->
            <!--<i class="iconfont icon-dot"></i>-->
            <!--授课中-->
          <!--</span>-->
          <!--<span class="green" v-if="list.step == 'repose'">-->
            <!--<i class="iconfont icon-dot"></i>-->
            <!--交流中-->
          <!--</span>-->
          <!--<span class="blue" v-if="list.step == 'finish'">-->
            <!--<i class="iconfont icon-dot"></i>-->
            <!--已结束-->
          <!--</span>-->
        <!--</span>-->
        <!--<span>{{ list.stats | specKey('lesson.enroll.unique') }}</span>-->
        <!--<span>&#65509;{{ list.stats | specKey('lesson.income.sum') }}</span>-->
        <!--<span>&#65509;{{ list.stats | specKey('lesson.payoff.sum') }}</span>-->
        <div class="handle-btn">
          <!--<a class="handle-item" href="javascript:;" :title="(list.step=='opened')?(checkCanOpen(list) ? '点击开课':'点击备课'):'进入直播'" @click="checkTime(list)" v-if="list.step != 'submit' && list.step != 'denied'"><i class="iconfont icon-yuanchengshouke"></i></a>-->
          <!--<span v-if="list.step == 'submit' || list.step == 'denied'"><i class="iconfont icon-yuanchengshouke submit"></i></span>-->
          <!--&nbsp;-->
          <a class="handle-item" href="javascript:;" title="进入列表" @click="enterLesson(list)" v-if="list.step != 'submit'"><i class="iconfont icon-xiangqing"></i></a>
          <span v-if="list.step == 'submit'"><i class="iconfont icon-xiangqing submit"></i></span>
          &nbsp;
          <a class="handle-item" href="javascript:;" title="编辑课程" @click="editLesson(list.sn)" v-if="list.step != 'submit'"><i class="iconfont icon-htmal5icon16"></i></a>
          <span v-if="list.step == 'submit'"><i class="iconfont icon-htmal5icon16 submit"></i></span>
          &nbsp;
          <a class="handle-item" href="javascript:;" title="分享课程" @click="shareLesson(list.sn)" v-if="list.step != 'submit' && list.step != 'denied'"><i class="iconfont icon-fenxiang1"></i></a>
          <span v-if="list.step == 'submit' || list.step == 'denied'"><i class="iconfont icon-fenxiang1 submit"></i></span>
        </div>
      </li>
    </ul>
    <div class="modal-pop" v-if="showPop">
      <div class="pop-body">
        <h2>开课提醒</h2>
        <p class="text">正式开课将向学员发送开课通知</p>
        <div class="button">
          <button class="cancle" @click="canclePop">取消</button>
          <button @click="enterPrepareLesson(curLesson)">备课</button>
          <button @click="openLesson(curLesson)">开课</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
    import { mapGetters } from 'vuex';
    import moment from 'moment';
    import swal from 'sweetalert';
    // 获得config
    let liveHost = process.env.NODE_ENV == 'production' ? process.env.LIVE_HOST.replace(/\/$/,'/live') : '/live.html';

    export default{
      name: 'series-list',
      props: {
        lists: {
          type: Array
        }
      },
      computed: {
      },
      created() {
        this.$store.commit('CHANGE_IS_NOTICE', false);
      },
      data() {
        return {
          showPop: false,
          curLesson: '',
        };
      },
      methods: {
        checkTime(lesson) {
          let diff = this.momentdiff(lesson.plan.dtm_start);
          // check
          if(diff){
            // 可以开课
            this.checkLesson(lesson);
          }else{
            // 不可以开课
            swal({
              title: '开课提醒',
              text: `正式开课不得早于\t${this.momentAdvance(lesson.plan.dtm_start)}\n您可以做备课准备`,
              confirmButtonText: '备课',
              showCancelButton:true,
              closeOnConfirm: false,
              cancelButtonText: '取消',
            }, ()=>{
              this.enterPrepareLesson(lesson);
            });
          }
        },
        checkCanOpen(lesson) {
          return this.momentdiff(lesson.plan.dtm_start);
        },
        checkLesson(lesson){
          //
          if(lesson.step=='opened'){
            this.showPop = true;
            this.curLesson = lesson;
            /*swal({
              title: '开课提醒',
              text: '确定要现在开课吗？\n开课后会给已报名学员推送开课通知。',
              confirmButtonText: '确定开课',
              showCancelButton:true,
              closeOnConfirm: false,
              cancelButtonText: '暂不开课',
            }, ()=>{
              this.openLesson(lesson);
            });*/
          }else{
            this.openLesson(lesson);
          }
        },
        openLesson(lesson) {
          // 开始课程
          // 获得开课信息
          this.$store.dispatch('fetchOpenInfo', {lesson_sn:lesson.sn}).then((data) => {
            let params = `?isOwner=yes&lesson_sn=${lesson.sn}&teacherEnter=yes`;
            for(let d in data){
              params = `${params}&${d}=${data[d]}`;
            };
            // 关闭弹窗
            this.showPop = false;
            // 开始进入课堂
            let newWindow = window.open();
            setTimeout(()=>{
              newWindow.location = `${liveHost}${params}`;
            });
            /*window.location.href = `${liveHost}${params}`;*/
          }, (err) => {
            swal({
              title: '错误提醒',
              text: (err.message?err.message:'网络链接失败！'),
              confirmButtonText: "知道了"
            });
          });
        },
        enterLesson(series) {
          // 进入详情
          this.$router.push({ name: 'singleList', params: { series_sn: series.sn, title: series.name }});
        },
        enterPrepareLesson(lesson) {
          // 关闭弹窗
          swal.close();
          // 进入备课
          this.$router.push({ name: 'prepare', params: { series_sn: lesson.sn }});
        },
        editLesson(series_sn) {
          // 编辑课程
          this.$router.push({ name: 'seriesEdit', params: { series_sn: series_sn }});
        },
        shareLesson(series_sn) {
          // 分享课程
          this.$router.push({ name: 'seriesShare', params: {series_sn:series_sn} });
        },
        momentdiff(value) {
          let advanceTime = moment(value).subtract(15, 'minute');
          let currentTime = moment();
          // 当前时间是否可以开课
          return (currentTime >= advanceTime);
        },
        momentAdvance(value) {
          // 格式化
          return(moment(value).subtract(15, 'minute').format('YYYY-MM-DD HH:mm'));
        },
        canclePop() {
          // 关闭弹窗
          this.showPop = false;
        },
      },
    };
</script>

<style scoped lang="stylus" rel="stylesheet/stylus">
  .course-list{
    .my-lesson {
      padding-bottom: 10px;
      a {
        float: right;
        padding: 5px 10px;
        color: #fff;
        background: #12B7F5;
        border-radius: 15px;
        text-decoration: none;
        font-weight: bold;

        &.mr10 {
          margin-right: 10px;
        }
        &.lesson-title {
          float: left;
          color: #3c4a55;
          background: transparent;
          &.active {
            position: relative;
            color: #12b7f5;
            &:after {
              position: absolute;
              display: block;
              content: '';
              width: 100%;
              height: 1px;
              left: 0;
              bottom: -11px;
              background: #12b7f5;
            }
          }
        }
      }
    }

    ul {
      margin: 0;
      padding: 0;
    }
    li {
      padding: 18px 15px;
      display: -webkit-box;
      display: box;
      border-bottom: 1px solid #E3E3E3;
      line-height: 26px;
      -webkit-box-align: center;
      box-align: center;
      &:hover {
        background: #FAFAFB;
      }

      a {
        text-decoration: none;
        color: #12B7F5;
      }
      /*&:nth-child(2n+3) {
        background: #F7F7F7;
      }*/
      .price {
        color: #fb617f;
      }
      >*{
        display: -webkit-box;
        display: box;
        -webkit-box-flex: 1;
        box-flex: 1;
        width: 98px;
        color: #757F98;
        justify-content: center;
        word-break: break-all;
        font-size: 14px;

        &:first-child{
          -webkit-box-pack: initial;
          -ms-flex-pack: initial;
        }
        &:last-child{
          border: 0 none;
        }
        &:nth-of-type(2){
          -webkit-box-flex: 8;
          box-flex: 8;
        }
        &.handle-btn{
          -webkit-box-flex: 3;
          box-flex: 3;

          a, span {
            position: relative;
            display: inline-block;
            width: 30px;
            height: 30px;
            background: #EFEFF4;
            border-radius: 50%;
            -webkit-border-radius: 50%;
            .iconfont {
              position: absolute;
              font-size: 14px;
              color: #757F98;
              &.icon-yuanchengshouke {
                top: 3px;
                left: 7px;
                font-size: 16px;
              }
              &.icon-htmal5icon16 {
                top: 4px;
                left: 6px;
                font-size: 18px;
              }
              &.icon-fenxiang1 {
                top: 2px;
                left: 6px;
                font-size: 16px;
              }
              &.icon-xiangqing {
                top: 2px;
                left: 8px;
                font-size: 12px;
              }
            }
            .submit {
              color: #d7d9e2;
            }
            &.handle-item:hover {
              background: #12B7F5;
              .iconfont {
                color: #fff;
              }
            }
          }
        }
      }
      &.head {
        padding: 10px 15px;
        border-top: 1px solid #E3E3E3;
        border-bottom: 0 none;
        >*{
          color: #3C4A55;
          font-size: 16px;
        }
      }
      .gray {
        .iconfont {
          color: #d7d9e2;
        }
      }
      .yellow {
        .iconfont {
          color: #ffbd16;
        }
      }
      .green {
        .iconfont {
          color: #34ab46;
        }
      }
      .blue {
        .iconfont {
          color: #12b7f5;
        }
      }
      .red {
        .iconfont {
          color: #fb617f;
        }
      }
    }
  }
  .modal-pop {
    position: fixed;
    left: 0;
    right: 0;
    top: 0;
    bottom: 0;
    background-color: rgba(0, 0, 0, 0.4);
    z-index: 9;
    .pop-body {
      background-color: white;
      font-family: 'Open Sans', 'Helvetica Neue', Helvetica, Arial, sans-serif;
      width: 300px;
      padding: 17px;
      border-radius: 5px;
      text-align: center;
      position: absolute;
      left: 50%;
      top: 50%;
      margin-left: -150px;
      margin-top: -98px;
      overflow: hidden;
      z-index: 10;
      h2 {
        margin: 25px 0;
        padding: 0;
        line-height: 40px;
        color: #575757;
        font-weight: normal;
        font-size: 22px;
      }
      .text {
        color: #797979;
        font-size: 16px;
        text-align: center;
        font-weight: 300;
        text-align: inherit;
        float: none;
        margin: 0;
        padding: 0;
        line-height: normal;
      }
      .button {
        display: -webkit-box;
        display: box;
        >button {
          display: -webkit-box;
          display: box;
          -webkit-box-flex: 1;
          box-flex: 1;
          background-color: #8CD4F5;
          color: white;
          border: none;
          box-shadow: none;
          font-size: 17px;
          font-weight: 500;
          -webkit-border-radius: 4px;
          border-radius: 5px;
          padding: 10px 0;
          margin: 26px 3px 0 3px;
          cursor: pointer;
          &.cancle {
            background: #C1C1C1;
          }
        }
      }
    }
  }
</style>
