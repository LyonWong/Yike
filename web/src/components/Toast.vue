<template>
  <div class="toast flex-col" v-if="o" :class="{icon: o.icon}" @click="o=null">
    <i class="icon-yike" v-if="o.icon" :class="[`icon-${o.icon}`]"></i>
    <span class="hint" v-if="o.text">{{o.text}}</span>
  </div>
</template>

<script>
  export default {
    name: 'toast',
    props: ['toast'],
    data() {
      return {
        o: this.toast
      }
    },
    created() {
      if (this.o.duration) {
        setTimeout(() => {
          this.o = null
        }, this.o.duration)
      }
    }
  }
</script>

<style scoped>
  .toast {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -100%);
    background: rgba(0, 0, 0, 0.5);
    color: #fff;
    border-radius: .16rem;
    padding: .16rem;
  }
  .toast.icon {
    width: 2.5rem;
    height: 2.5rem;
  }
  .toast .icon-yike {
    font-size: 1rem;
    line-height: 1.5rem;
    width: 1.5rem;
    height: 1.5rem;
    text-align: center;
  }
  .toast .hint {
    font-size: .3rem;
    line-height: .3rem;
    padding: 0.5em 1em;
  }

</style>
