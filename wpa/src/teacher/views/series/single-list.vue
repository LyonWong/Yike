<template>
  <section class="content-list clearfix">
    <div class="single-title font-weight clearfix">
      <span>
        <router-link :to="{name:'seriesList'}">系列课课程列表</router-link>
        &nbsp;&#62;&nbsp;
        {{title}}
      </span>
      <div>
        <a href="javascript:;" class="mr10" @click="inviteTeacher(this.series_sn)">邀请讲师</a>
        <router-link :to="{name:'create', query:{series_sn: series_sn}}">创建课程</router-link>
      </div>
    </div>
    <single-list :single="true" :lists="courseList" @updateCheckList="updateCheckList" @updatePriceTotal="updatePriceTotal" v-if="courseList.length"></single-list>
    <!--激活系列课-->
    <div class="action-btn clearfix" v-if="courseList.length">
      <span class="left">
        未选择课程不会作为系列课课程展示
      </span>
      <div class="right">
        当前所选课程单价总额:&#65509;{{priceTotal}},系列统一购买价:&#65509;
        <input v-model="customPrice" type="text" :placeholder="defaultPrice" />
        <button @click="submitModify">确认修改</button>
      </div>
    </div>
    <!--input域存放复制内容-->
    <input class="transparent" type="text" ref="copy" v-bind:value="invite_url" />
  </section>
</template>

<script>
  import { mapGetters } from 'vuex'
  import singleList from '@teacher/components/singleList.vue';

  export default{
    name: 'teacher-series',
    components: {
      singleList,
    },
    computed: {
      ...mapGetters({
        courseList: 'getSeriesList',
      })
    },
    data() {
      return {
        invite_url: '',
        title: '',
        checkList: '',
        series_sn: '',
        priceTotal: 0,
        customPrice: '',
        defaultPrice: '',
      }
    },
    created() {
      let params = this.$route.params;
      // 赋予标题
      this.title = params.title;
      // 赋予系列课sn
      this.series_sn = params.series_sn;
      //
      this.$store.dispatch('fetchSeriesSingle', {series_sn: params.series_sn}).then((data) => {
        let curPrice = 0;
        let checkStr = '';
        for(let d of data){
          if(d.category_check > 0){
            checkStr = `${checkStr},${d.sn}`;
            curPrice += Number(d.price);
          }
        }
        // 修改选中string
        this.checkList = checkStr.replace(/^,/, '');
        // 修改选中总价
        this.updatePriceTotal(curPrice);
        console.log('success');
      }, (err) => {
        swal({
          title: '错误提醒',
          text: (err.message?err.message:'网络链接失败！'),
          confirmButtonText: "知道了"
        });
      });
      // 获取系列详情
      this.$store.dispatch('fetchSeriesDetail', {series_sn: params.series_sn}).then((data) => {
        this.defaultPrice = data.scheme.price;
        console.log('success');
      }, (err) => {
        swal({
          title: '错误提醒',
          text: (err.message?err.message:'网络链接失败！'),
          confirmButtonText: "知道了"
        });
      });
    },
    methods: {
      goToRefund() {
        this.$router.replace({ name: 'refund', query: {...this.data} });
      },
      inviteTeacher() {
        //
        this.$store.dispatch('fetchInviteTeacher', {series_sn: this.series_sn}).then((data) => {
          console.log('success');
          this.invite_url = data;
          //
          swal({
            title: '邀请嘉宾',
            text: `复制此链接并发送给邀请授课讲师，其创建课程自动加入此系列课。<p class="invite-guest">${data}</p>`,
            html: true,
            confirmButtonText: '复制链接',
            showCancelButton:true,
            closeOnConfirm: false,
            cancelButtonText: '取消',
          }, ()=>{
            let input = this.$refs['copy'];
            input.select(); // 选取input元素的内容
            var succeeded;
            try {
              // 将选区内容复制到剪贴板
              succeeded = document.execCommand('copy');
            } catch (e) {
              succeeded = false;
            }
            // 是否成功
            if (succeeded) {
              swal({
                title: '',
                type: 'success',
                text: '复制成功',
                timer: 1000,
                confirmButtonText: '',
                confirmButtonColor: '#fff',
              });
            } else {
              swal({
                title: '',
                type: 'error',
                text: '复制失败',
                timer: 600,
                confirmButtonText: '',
                confirmButtonColor: '#fff',
              });
            }
          });
        }, () => {
          console.log('fail');
        });
      },
      updatePriceTotal (price) {
        this.priceTotal = price;
      },
      updateCheckList (lists) {
        this.checkList = lists;
      },
      blurPrice () {
        this.customPrice = this.customPrice.match(/\d*/)[0];
      },
      submitModify () {
        //
        if(this.customPrice.replace(/(^\s*)|(\s*$)/g, '') == '')return swal({
          title: '错误提醒',
          text: '请填写系列统一购买价!',
          confirmButtonText: "知道了"
        });
        if(!/^\d+$/.test(this.customPrice))return swal({
          title: '错误提醒',
          text: '系列统一购买价必须为整数!',
          confirmButtonText: "知道了"
        });
        // 选项
        let opt = {
          price: this.customPrice,
          series_sn: this.$route.params.series_sn,
          lesson_sns: this.checkList,
        };
        //
        this.$store.dispatch('fetchSeriesCheck', opt).then((data) => {
          swal({
            title: '',
            text: '修改成功',
            confirmButtonText: "知道了"
          }, ()=>{
              window.location.reload();
          });
        }, (err) => {
          swal({
            title: '错误提醒',
            text: (err.message?err.message:'网络链接失败！'),
            confirmButtonText: "知道了"
          });
        });
      },
    },
  }
</script>

<style scoped lang="stylus" rel="stylesheet/stylus">
  .content-list
    padding: 20px;
    background: #fff;
    border-radius: 4px;
    -webkit-border-radius: 4px;
  .transparent
      width: 1px;
      height: 1px;
      opacity: 0;
      -webkit-opacity: 0;
  .single-title
    padding: 0 0 20px;
    color: #3c4a55;
    a
      color: #3c4a55;
    >:first-child
      float: left;
    >:nth-child(2)
      float: right;
      >*
        padding: 5px 10px;
        color: #fff;
        background: #12b7f5;
        border-radius: 15px;
        text-decoration: none;
        &.mr10
          margin-right: 10px;
  .action-btn
    padding-top: 15px;
    font-size: 16px;
    input
      margin-right: 5px;
      padding: 7px 8px;
      width: 70px;
      border: 1px solid #e3e3e3;
      border-radius: 5px;
      vertical-align: middle;
      outline: none;
    button
      padding: 5px 15px;
      color: #fff;
      font-size: 16px;
      border: 0 none;
      background: #12b7f5;
      border-radius: 5px;
      cursor: pointer;
      text-decoration: none;
      vertical-align: middle;
    >:first-child
      line-height: 30px;
    .left
      float: left;
    .right
      float: right;
</style>
