<template>
  <div>
    <div class="page-cover" v-show="menuShow" @click="isShow"></div>
    <header class="header" :class="{'show': menuShow}" @click="isShow" v-if="isOwner || !isPC">
      <header class="bar">
        <div class="bar-nav back">
          <span class="iconfont icon-open-left" @click="backToHome"></span>
        </div>
        <div class="title">{{headerTitle}}</div>
        <div class="bar-nav more">
          <span class="iconfont icon-dot-more"></span>
        </div>
      </header>
    </header>
  </div>
</template>

<script type="text/ecmascript-6">
  import {mapState} from 'vuex';

  export default
  {
    name: 'v-header',
    components: {

    },
    data() {
      return {
        isPC: isPC,
        show: false,
      };
    },
    computed: {
      ...mapState([
        'teacherHost',
        'headerTitle',
        'menuShow',
        'isOwner',
      ])
    },
    methods: {
      isShow() {
        this.$store.commit('UPDATE_MENUSHOW');
      },
      backToHome() {
        window.location.href = this.teacherHost;
      },
    }
  };
</script>
<style lang="stylus" rel="stylesheet/stylus">
  @import "index.styl";
</style>
