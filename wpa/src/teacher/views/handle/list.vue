<template>
  <ul class="handle-list">
    <li v-for="handList in getHandleList.pages">
      <div class="handle-info">
        <dl>
          <dt>申请人</dt>
          <dd v-text="handList.ticket.content.applyer">哈喽啊</dd>
        </dl>
        <dl>
          <dt>课程</dt>
          <dd v-text="handList.lesson.title"></dd>
        </dl>
        <dl>
          <dt>退款金额</dt>
          <dd>&#65509;{{handList.lesson.price}}</dd>
        </dl>
        <dl>
          <dt>退款理由</dt>
          <dd v-text="handList.ticket.content.reason"></dd>
        </dl>
        <dl class="reason" v-if="mode">
          <dt>处理意见</dt>
          <dd v-text="handList.ticket.remark"></dd>
        </dl>
        <span class="s-button" v-if="mode && handList.ticket.i_status == 'reject'">已拒绝</span>
        <span class="s-button" v-if="mode && !handList.ticket.i_status">转平台</span>
        <span class="s-button agree" v-if="mode && handList.ticket.i_status == 'agree'">已同意</span>
        <span class="timer" title="未处理转至平台" v-if="!mode">
          <i class="iconfont icon-jishi"></i>
          剩余：{{handList.ticket.tms_end | moment}}
        </span>
      </div>
      <div class="handle-action" v-if="!mode">
        <div class="textarea">
          <textarea @blur="blurEvent" placeholder="如拒绝，请您填写处理意见" :ref="`text-${handList.ticket.id}`"></textarea>
        </div>
        <div class="action">
          <button class="reject" @click="handleEvent(handList.ticket.id, 1)">拒绝</button>
          &nbsp;
          <span>&#124;</span>
          &nbsp;
          <button class="agree" @click="handleEvent(handList.ticket.id, 0)">同意</button>
        </div>
      </div>
    </li>
  </ul>
</template>

<script>
  import { mapGetters } from 'vuex';
  import swal from 'sweetalert';
  import { strlen, trimStr } from '@lib/js/mUtils';

  export default{
    name: 'handle-list',
    props: {
      mode: {
        type: Number,
      },
    },
    computed: {
      ...mapGetters({
        getHandleList: 'getHandleList',
      })
    },
    created() {
      this.fetchData();
    },
    watch: {
      '$route': 'reloadData' //切换路由，调用reloadData方法
    },
    methods: {
      reloadData() {
        this.fetchData();
      },
      fetchData() {
        let query = {
          page: this.$route.params.page,
          status: this.mode,
          //limit: 2,
        };
        // 获取已处理列表
        this.$store.dispatch('fetchHandleList', query).then((data) => {
          console.log('success');
        }, () => {
          console.log('fail');
        });
      },
      blurEvent() {

      },
      handleEvent(id, operate) {
        //
        let value = trimStr(this.$refs[`text-${id}`][0].value);
        let len = strlen(value);
        //
        if(!len && operate){
          return swal({
            title: '错误提醒',
            text: '请填写处理意见',
            confirmButtonText: "知道了"
          });
        }
        if(len > 255 && operate){
          return swal({
            title: '错误提醒',
            text: '填写的意见过长',
            confirmButtonText: "知道了"
          });
        }
        let body = {
          operate: operate,
          id: id,
          //remark: value,
        };
        if(operate)body.remark = value;
        // 获取已处理列表
        this.$store.dispatch('fetchHandleAction', body).then((data) => {
          // 刷新列表
          window.location.reload();
          console.log('success');
        }, () => {
          swal({
            title: '错误提醒',
            text: '处理异常',
            confirmButtonText: "知道了"
          });
          console.log('fail');
        });
      }
    },
  }
</script>

<style lang="stylus" rel="stylesheet/stylus">
  .handle-list {
    margin: 0;
    padding: 0;
    list-style-type: none;
    li {
      margin-bottom: 10px;
      padding: 10px 30px;
      border: 1px solid #E6EAF2;
      dl{
        display: flex;
        display: -webkit-flex;
        margin: 0;
        padding: 5px 0;
        font-size: 14px;
        &.reason {
          border-top: 1px dashed #E6EAF2;
        }
        dt,dd{
          display: flex;
          display: -webkit-flex;
        }
        dt {
          width: 60px;
          color: #aaa;
        }
        dd {
          margin-left: 10px;
          max-width: 600px;
        }
      }
      .handle-info {
        position: relative;
      }
      .s-button {
        position: absolute;
        right: 0;
        bottom: 34px;
        padding: 6px 8px;
        color: #fff;
        background: #B6B6BB;
        font-size: 14px;
        border-radius: 5px;
        -webkit-border-radius: 5px;

        &.agree {
          background: #79D47E;
        }
      }
      .timer {
        position: absolute;
        top: 0;
        right: 0;
        color: #fb617f;
        font-size: 14px;
        .iconfont {
          font-size: 20px;
          vertical-align: middle;
        }
      }
      .handle-action {
        position: relative;
        margin-top: 15px;
        .textarea {
          padding: 10px;
          width: 656px;
          background: #EFEFF4;
          border-radius: 5px;
          -webkit-border-radius: 5px;
        }
        textarea {
          width: 100%;
          height: 60px;
          background: #EFEFF4;
          border: 0 none;
          outline: none;
        }
        .action {
          position: absolute;
          right: -8px;
          top: 8px;
          font-size: 14px;
          button {
            border: 0 none;
            background: #fff;

            &.agree:hover {
              color: #12b7f5;
            }
            &.reject:hover {
              color: #fb617f;
            }
          }
          span {
            color: #EFEFF4;
          }
        }
      }
    }
  }
</style>
