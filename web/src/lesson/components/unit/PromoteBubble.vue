<template>
  <div class="c-promote-bubble" v-if="expire" v-show="info.discount">
    <a :href="app.linkToStudent(`/promote-receive?sn=${info.sn}`)">
    <img id="avatar" :src="info.from.avatar"/>
    <div class="box-bubble">
      <div>优惠 ￥{{info.discount}}</div>
      <div>剩余 {{countdown}}</div>
    </div>
    </a>
  </div>
</template>

<script>
  export default {
    name: 'promote-bubble',
    props: ['info'],
    data() {
      return {
        expire: null
      }
    },
    created() {
      this.expire = this.info.extime
      let si = setInterval(() => {
        if (--this.expire<0) {
          clearInterval(si)
        }
      }, 1000)
    },
    computed: {
      countdown: function() {
        let h = Math.floor(this.expire / 3600)
        let m = Math.floor((this.expire % 3600) / 60)
        let s = this.expire % 60
        if (h<10) {
          h = '0' + h
        }
        if (m<10) {
          m = '0' + m
        }
        if (s<10) {
          s = '0' + s
        }
        return `${h}:${m}:${s}`
      }
    }
  }
</script>

<style scoped>
  .c-promote-bubble {
    width: 3rem;
    height: 1rem;
    /*background: linear-gradient(45grad, rgba(151, 169, 219, 0.9), rgba(128, 150, 229, 0.9));*/
    background: linear-gradient(45grad, rgba(244, 125, 79, 0.9), rgba(252, 63, 63, 0.85));
    border-radius: .5rem 0 0 .5rem;
    position: relative;
  }
  a {
    color: #fff
  }
  #avatar {
    position: absolute;
    border-radius: 50%;
    width: .72rem;
    height: .72rem;
    top: .14rem;
    left: .14rem;
  }
  .box-bubble {
    padding: .1rem 0;
    margin-left: 1rem;
  }
  .box-bubble > div {
    height: .4rem;
    line-height: .4rem;
    width: 100%;
    font-size: .24rem;
  }
</style>
