<template>
  <section class="group-order">
    <div class="order">
      <div class="dialog">
        <div class="dialog-head">创建团队订单</div>
        <div class="dialog-body">
          <dl>
            <dt>
              团队订单名称
              <span>限20字</span>
            </dt>
            <dd>
              <input type="text" v-model="data.name" />
            </dd>
          </dl>
          <dl>
            <dt>
              报名须知
            </dt>
            <dd>
              <textarea v-model="data.info" placeholder="请备注部门+职位"></textarea>
            </dd>
          </dl>
          <div class="order-refund" :class="{'is-check':data.check}">
            允许成员退款
            <span @click="changeCheck">
              <i class="iconfont icon-gou"></i>
            </span>
          </div>
          <div class="submit">
            <span class="cancle">
              <button @click="closeSubmit">取消</button>
            </span>
            <span class="ok">
              <button @click="startSubmit">确定</button>
            </span>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
  import { mapGetters } from 'vuex';
  import { trimStr } from '@lib/js/mUtils';
  import swal from 'sweetalert';

  export default{
    name: 'group-order',
    computed: {
      ...mapGetters({

      })
    },
    data() {
      return {
        text: '确认支付',
        showMore: false,
        data: {
          name: '',
          info: '',
          check: true,
        }
      }
    },
    created() {
      let params = this.$route.params;

    },
    methods: {
      backToHome() {
        this.$router.push({ name: 'list' });
      },
      backToCenter() {
        this.$router.push({ name: 'userCenter' });
      },
      showMenu(show) {
        this.showMore = show;
      },
      changeCheck() {
        this.data.check = !this.data.check;
      },
      closeSubmit() {
        this.$parent.showCreate = false;
      },
      startSubmit() {
        this.$store.commit('UPDATE_GROUP_LOADING', true);
      },
    },
  }
</script>

<style scoped lang="stylus" rel="stylesheet/stylus">
  @import "index.styl";
  .group-order
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    margin: 0;
    display: -webkit-flex;
    -webkit-align-items: center;
    -webkit-justify-content: center;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    background-color: rgba(0,0,0,.3);
    z-index: 7;
    .order
      width: 100%;
      .dialog
        margin: 0 65px;
        background: #fff;
        border-radius: 16px;
        >*
          width: 100%;
          color: #333;
        .dialog-head
          padding: 40px 0;
          text-align: center;
          border-bottom: 1px solid #ddd;
          px2px(font-size, 33px);
        .dialog-body
          px2px(font-size, 28px);
          dl
            margin: 0;
            padding: 0 30px;
            dt
              padding: 20px 0;
              span
                color: #999;
                px2px(font-size, 22px);
            dd
              margin: 0;
              padding-bottom: 20px;
              input, textarea
                padding: 20px 0;
                width: 100%;
                background: #F4F4F4;
                text-indent: 20px;
                border: 1px solid #ddd;
                border-radius: 10px;
                px2px(font-size, 30px);
              textarea
                height: 80px;
          .order-refund
            position: relative;
            margin: 0 30px;
            padding: 20px 0;
            >span
              position: absolute;
              right: 0;
              padding: 6px 8px;
              background: #ddd;
              border-radius: 50%;
              px2px(top, 10px);
              .iconfont
                color: #fff;
                px2px(font-size, 30px);
            &.is-check
              >span
                background: #4DA9EB;
          .submit
            padding: 20px 0;
            font-size: 0;
            >*
              display: inline-block;
              width: 50%;
              text-align: center;
              button
                padding: 12px 90px;
                border: 1px solid #4DA9EB;
                border-radius: 60px;
                px2px(font-size, 32px);
            .cancle
              button
                color: #4DA9EB;
                background: #fff;
            .ok
              button
                color: #fff;
                background: #4DA9EB;

  .body-pc
    .group-order
      .order
        .dialog
          border-radius: 8px;
          .dialog-head
            padding: 20px 0;
          .dialog-body
            .order-refund
              >span
                top: 12px;
                padding: 5px 6px;

</style>
