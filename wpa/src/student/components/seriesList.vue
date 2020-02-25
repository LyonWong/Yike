<template>
  <div class="series-list">
    <ul class="lesson">
      <li v-for="list in lists">
        <a href="javascript:;" class="item" @click="enterDetail(list.sn)">
          <div class="list-img">
            <img v-if="list.introduce.cover" :src="`${list.introduce.cover}!cover`" />
            <img v-if="!list.introduce.cover" :src="`${assetsHost}/static/student/_static/student/img/default.png`" />
          </div>
          <div class="list-content">
            <div class="list-title" v-text="list.name"></div>
            <div class="list-status clearfix">
              <span class="l-gray">&nbsp;</span>
            </div>
            <div class="appointment clearfix">
              <span><i class="iconfont icon-jiage1"></i> {{ list.scheme | specKey('price') }}</span>
              <span>&nbsp;</span>
              <!--<div class="list-status new-status">-->
                <!--<span v-if="list.step == 'opened'" class="opened">{{`${list.plan.dtm_now}#${list.plan.dtm_start}` | moment}}</span>-->
                <!--<span class="l-green" v-if="list.step == 'onlive'">授课中</span>-->
                <!--<span class="l-green" v-if="list.step == 'repose'">交流中</span>-->
                <!--<span class="l-gray" v-if="list.step == 'closed'">已下架</span>-->
                <!--<span class="l-gray" v-if="list.step == 'finish'" v-text="formatTime(list.plan.dtm_start)"></span>-->
              <!--</div>-->
            </div>
          </div>
        </a>
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
      </li>
      <li class="no-enroll" v-if="!lists.length">
        <p>讲师暂无系列课</p>
      </li>
    </ul>
  </div>
</template>

<script>
    import { mapGetters } from 'vuex';

    export default{
      name: 'series-list',
      components: {

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
        enterDetail(series_sn) {
          this.$router.push({ name: 'seriesBrief', params: { series_sn: series_sn }});
        },
        enterEvaluate(lesson_sn) {
          this.$router.push({ name: 'evaluate-lesson', params: {lesson_sn:lesson_sn} });
        },
        formatTime(value) {
          return value.split(' ')[0];
        },
      }
    }
</script>

<style scoped lang="stylus" rel="stylesheet/stylus">
  @import '~@lib/css/index.styl';

  .series-list{
    .l-red {
      color: #FB6666;
    }

    .lesson {
      margin: 0;
      padding: 0;
      list-style-type: none;

      a{
        display: block;
        text-decoration: none;
      }
      li+li{
        /*margin-top: 15px;*/
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
          color: #333;
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
            width: 325px;
            padding-top: 60px;
            px2px(font-size, 26px);
            color: #AAA;
            >*{
              float: left;
            }
            >:last-child{
              float: right;
            }
            .iconfont {
              px2px(font-size, 26px);
            }
          }
        }
        .list-status{
          padding-top: 0;
          width: 400px;
          px2px(font-size, 24px);
          &.new-status {
            padding-top: 0;
            width: auto;
            px2px(font-size, 30px);
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
