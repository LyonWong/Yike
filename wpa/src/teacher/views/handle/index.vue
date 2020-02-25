<template>
  <section class="handle">
    <div class="handle-title">退款处理</div>
    <div class="box-body">
      <div class="box-tab">
        <router-link class="button" to="/handle/will">未处理</router-link>
        <router-link class="button" to="/handle/has">已处理</router-link>
      </div>
      <router-view></router-view>
    </div>
  </section>
</template>

<script>
  import { mapGetters } from 'vuex'

  export default{
    name: 'handle',
    computed: {
      ...mapGetters({

      })
    },
    created() {
      this.$store.commit('CHANGE_IS_NOTICE', false);
    },
    methods: {
    },
  }
</script>

<style lang="stylus" rel="stylesheet/stylus">
  .handle {
    background: #fff;
    .handle-title {
      padding: 20px;
      font-size: 16px;
      font-weight: bold;
    }
  }
  .box-body {
    padding: 0 25px 10px;
    .box-tab {
      padding: 10px 0;
      border-top: 1px solid #E6EAF2;
    }
    .button {
      display: inline-block;
      padding: 6px 25px;
      color: #3C4A55;
      background: #EFEFF4;
      font-size: 14px;
      border-radius: 25px;
      -webkit-border-radius: 25px;

      &.active {
        color: #fff;
        background: #12B7F5;
      }
    }
  }
  a {
    text-decoration: none;
  }

</style>
