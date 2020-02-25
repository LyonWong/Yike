<template>
  <div>
    <div class="v-star" v-if="mode==1">
      <i class="iconfont icon-star" @click="updateStar(1)" :class="{'active':(starNum >= 1)}"></i>
      <i class="iconfont icon-star" @click="updateStar(2)" :class="{'active':(starNum >= 2)}"></i>
      <i class="iconfont icon-star" @click="updateStar(3)" :class="{'active':(starNum >= 3)}"></i>
      <i class="iconfont icon-star" @click="updateStar(4)" :class="{'active':(starNum >= 4)}"></i>
      <i class="iconfont icon-star" @click="updateStar(5)" :class="{'active':(starNum >= 5)}"></i>
    </div>
    <div class="v-star" v-if="mode==2">
      <i class="iconfont icon-star" :class="{'active':(number >= 1)}"></i>
      <i class="iconfont icon-star" :class="{'active':(number >= 2)}"></i>
      <i class="iconfont icon-star" :class="{'active':(number >= 3)}"></i>
      <i class="iconfont icon-star" :class="{'active':(number >= 4)}"></i>
      <i class="iconfont icon-star" :class="{'active':(number >= 5)}"></i>
    </div>
  </div>
</template>
<script>
  import {mapState} from 'vuex';

  export default {
    name: 'v-star',
    props: {
      mode: Number,
      number: Number,
    },
    data() {
      return {
        starNum: 0,
      }
    },
    methods: {
      updateStar(num) {
        this.starNum = num;
        this.$emit('updateStarNum', num);
      }
    }
  };
</script>

<style lang="stylus" rel="stylesheet/stylus">
  .v-star {
    color: #E4E8EF;
    .iconfont{
      font-size: 16px;
      &.active {
        color: #feb64d;
      }
    }
  }

</style>
