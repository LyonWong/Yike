<template>
  <div class="enroll-list">
    <ul class="lesson">
      <li v-for="list in lists">
        <a href="javascript:;" class="item" @click="enterDetail(list.lesson.sn)">
          <div class="list-img">
            <img v-if="list.lesson.cover" :src="`${list.lesson.cover}!cover`" />
            <img v-if="!list.lesson.cover" :src="`${assetsHost}/static/student/_static/student/img/default.png`" />
          </div>
          <div class="list-content">
            <div class="list-title" v-text="list.lesson.title"></div>
            <div class="appointment">
              <!--<i class="iconfont icon-people"></i>-->
              <!--{{list.lesson.participants}}-->
              {{ list.lesson.teacher | specKey('name') }}
            </div>
            <div class="list-status new-status clearfix">
              <!--<span class="pull-right" v-if="list.event == 'enroll'">已报名</span>-->
              <!--<span class="pull-right" v-if="list.event == 'browse'">未报名</span>-->
              <!--<span class="pull-right" v-if="list.event == 'access'">听课中</span>-->
              <!--<span class="pull-right" v-if="list.event == 'cancel'">已退出</span>-->
              <!--<span class="pull-right l-red" v-if="list.event == 'refund'">已退款</span>-->
              <!--<span v-if="list.lesson.step == 'submit'">未开放</span>-->
              <span v-if="list.lesson.step == 'opened'" class="opened">{{`${list.lesson.plan.dtm_now}#${list.lesson.plan.dtm_start}` | moment}}</span>
              <span class="l-green" v-if="list.lesson.step == 'onlive'">授课中</span>
              <span class="l-green" v-if="list.lesson.step == 'repose'">交流中</span>
              <span class="l-gray" v-if="list.lesson.step == 'closed'">已下架</span>
              <span class="l-gray" v-if="list.lesson.step == 'finish'">可回放</span>
              <!--<span class="l-gray" v-if="list.lesson.step == 'finish'" v-text="formatTime(list.lesson.plan.dtm_start)"></span>-->
            </div>
          </div>
        </a>
        <div class="list-handler clearfix" v-if="list.event == 'refund'">
          <span class="pull-right status red pdtb19">已退款</span>
        </div>
        <div class="list-handler clearfix" v-if="list.refund_mode && list.event != 'refund' && !list.refund_info && (!list.rated || list.lesson.price > 0)">
          <button class="pull-right" @click="enterEvaluate(list.lesson.sn)" v-if="!list.rated && list.lesson.step != 'opened' && list.lesson.step != 'closed'">评价课程</button>
          <button class="pull-right" @click="refund(list)" v-if="(!list.refund_info && list.event != 'refund' && list.lesson.price > 0)">申请退款</button>
        </div>
        <div class="list-handler clearfix" v-if="!list.refund_mode && list.refund_info && list.refund_info.apply && !list.refund_info.appeal && (list.refund_info.apply.status == 'start' || list.refund_info.apply.status == 'pending')">
          <span class="pull-right status">退款申请中</span>
        </div>
        <div class="list-handler clearfix" v-if="list.refund_mode && list.refund_info && list.refund_info.apply && !list.refund_info.appeal && list.refund_info.apply.status == 'reject'">
          <div class="pull-right status w100 clearfix">
            <!--<button class="pull-right blue" @click="enterEvaluate(list.lesson.sn)" v-if="!list.rated">评价</button>-->
            <span class="reject">
              <i class="iconfont icon-jinggao"></i>
              你的退款申请被拒绝
            </span>
            <button class="pull-right" @click="goReason(list)">&nbsp;&nbsp;&nbsp;&nbsp;查看&nbsp;&nbsp;&nbsp;&nbsp;</button>
          </div>
        </div>
        <div class="list-handler clearfix" v-if="list.refund_mode && list.refund_info && list.refund_info.appeal && list.refund_info.appeal.status != 'agree'">
          <span class="pull-right status" v-if="list.refund_info.appeal.status == 'start' || list.refund_info.appeal.status == 'pending'">退款申诉中</span>
          <span class="pull-right status w100 clearfix" v-if="list.refund_info.appeal.status == 'reject'">
            <span class="reject">
              <i class="iconfont icon-jinggao"></i>
              你的退款申诉被拒绝
            </span>
            <button class="pull-right" @click="goReason(list)">&nbsp;&nbsp;&nbsp;&nbsp;查看&nbsp;&nbsp;&nbsp;&nbsp;</button>
          </span>
        </div>
      </li>
      <li class="no-enroll" v-if="!lists.length">
        <p>暂无已报名课程，快去选择优质课程报名吧</p>
        <router-link to="/course">现在就去</router-link>
      </li>
    </ul>
    <!--<loading :show="refunding"></loading>-->
  </div>
</template>

<script>
    import { mapGetters } from 'vuex';
    //import Loading from '@student/components/loading';

    export default{
      name: 'enroll-list',
      components: {
        //Loading
      },
      props: {
        lists: {
          type: Array
        }
      },
      computed: {
        ...mapGetters({
          assetsHost: 'getAssetsHost',
        })
      },
      data() {
        return {
          studentShareHost: (process.env.NODE_ENV=='production'?process.env.STUDENT_HOST:'/student.html?'),
        };
      },
      methods: {
        enterDetail(lesson_sn) {
          if (process.env.NODE_ENV=='production'){
            window.location.href = `${this.studentShareHost}share?lesson_sn=${lesson_sn}`;
          } else {
            this.$router.push({ name: 'detail', query: { lesson_sn: lesson_sn }});
          }
        },
        enterEvaluate(lesson_sn) {
          this.$router.push({ name: 'evaluate-lesson', params: {lesson_sn:lesson_sn} });
        },
        refund(list) {
          // 组装
          let params = {
            lesson_sn: list.lesson.sn,
            mode: list.refund_mode,
            title: list.lesson.title,
            price: list.lesson.price,
            teacher: list.lesson.teacher.name,
          };
          //
          this.$router.push({ name: 'refund', query: {...params} });
        },
        formatTime(value) {
          return value.split(' ')[0];
        },
        goReason(list) {
          // 组装
          let params = {
            lesson_sn: list.lesson.sn,
            title: list.lesson.title,
            price: list.lesson.price,
            teacher: list.lesson.teacher.name,
            //isApply: Boolean(list.refund_info.apply),
            cur_mode: list.refund_info.appeal ? 'appeal' : 'apply',
            mode: list.refund_mode,
            event: list.refund_info,
          };
          //
          this.$router.push({ name: 'reason', query: {...params} });
        }
      }
    }
</script>

<style scoped lang="stylus" rel="stylesheet/stylus">
  @import '~@lib/css/index.styl';

  .enroll-list{
    .l-red {
      color: #FB6666;
    }

    .lesson {
      margin: 0;
      padding: 0;
      padding-bottom: 200px;
      list-style-type: none;

      a{
        display: block;
        text-decoration: none;
      }
      li+li{
        margin-top: 15px;
      }
      li{
        position: relative;
        padding: 54px 24px 0;
        background: #fff;
        overflow: hidden;
        border-width: 1px 0 1px 0;
        border-color: #E6EAF2;
        border-style: solid;

        &:first-child {
          border-top-width: 0;
        }

        &.no-enroll {
          text-align: center;
          p, a {
            px2px(font-size, 32px);
          }
          a {
            display: inline-block;
            margin-bottom: 50px;
            padding: 10px;
            color: #00a551;
            border: 1px solid #00a551;
            border-radius: 10px;
            -webkit-border-radius: 10px;
          }
        }

        a{
          display: block;
        }
        .item {
          display: -webkit-box;
          display: box;
          /*padding-bottom: 29px;*/

          >* {
            display: -webkit-box;
            display: box;
            -webkit-box-orient: vertical;
            box-orient: vertical;
          }
        }
        .item+div {
          border-top: 1px solid #E6EAF2;
        }
        .list-title{
          padding: 0 0 15px;
          width: 320px;
          color: #3C4A55;
          text-overflow:ellipsis;
          white-space:nowrap;
          overflow: hidden;
          px2px(font-size, 34px);
        }
        .list-img{
          position: relative;
          margin-right: 18px;
          width: 352px;
          height: 240px;
          overflow: hidden;

          img{
            display: block;
            width: 100%;
            height: 180px;
            border-radius: 15px;
            -webkit-border-radius: 15px;
          }
        }
        .list-content{
          px2px(font-size, 30px);

          .appointment{
            padding-top: 20px;
            px2px(font-size, 30px);
            color: #AAA;
            .iconfont {
              px2px(font-size, 30px);
            }
          }
        }
        .list-status{
          padding-top: 65px;
          width: 400px;
          &.new-status{
            padding-top: 38px;
          }
          >span{
            color: #3C4A55;
            &.opened, &.l-red {
              color: #fb6666;
            }
            &.l-gray {
              color: #aaa;
            }
            &.l-green {
              color: #2dc17b;
            }
          }
        }
        .list-handler{
          padding: 19px 0;
          button{
            margin-left: 19px;
            padding: 10px 20px;
            background: transparent;
            border: 1px solid #aaa;
            border-radius: 60px;
            -webkit-border-radius: 60px;
            px2px(font-size, 30px);
            &.blue {
              color: #12B7F5;
              border-color: #12B7F5;
            }
          }
          .status{
            color: #9ca7c1;
            px2px(font-size, 32px);
            px2px(line-height, 32px);
            &.w100 {
              width: 100%;
            }
            .reject {
              color: #fb6666;
              px2px(line-height, 58px);
              .iconfont {
                px2px(font-size, 32px);
                vertical-align: top;
              }
            }
            &.red {
              color: #fb6666;
            }
            &.pdtb19 {
              padding-top: 19px;
              padding-bottom: 19px;
            }
          }
        }
      }
    }

  }
</style>
