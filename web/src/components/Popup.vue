<template>
  <transition name="slide">
    <div class="c-popup flex-col" v-show="isOpen">
      <div class="btn-mask flex-item" @click="close" @touchmove.prevent></div>
      <div class="frm-popup flex-col" :style="{width: width}">
        <div class="popup-head flex-row" v-if="opt.head !== false">
          <slot name="head">popup head{{option}}</slot>
        </div>
        <div class="popup-body flex-item">
          <slot></slot>
        </div>
        <div class="popup-foot flex-row" v-if="opt.foot !== false">
          <slot name="foot">popup food</slot>
        </div>
      </div>
    </div>
  </transition>
</template>

<script>

  export default {
    name: 'popup',
    components: {},
    props: ['isOpen', 'option'],
    data() {
      return {
        opt: this.option || {}
      }
    },
    created() {
    },
    methods: {
      close: function () {
        this.$emit('close')
      }
    },
    computed: {
      width: () => {
        return document.body.offsetWidth + 'px';
      }
    },
    watch: {
      isOpen: (status) => {
        /*
        let ua = navigator.userAgent
        let d = document.documentElement
        if (/(Android)/i.test(ua) || /(iPhone)/i.test(ua)) {
          d = document.body
        }
        if (status) {
          d.style.overflow = 'hidden'
          document.documentElement.style.overflow = 'hidden'
        } else {
          d.style.overflow = 'visible'
          document.documentElement.style.overflow = 'visible'
        }
        */
      }
    }
  }
</script>

<style scoped>
  .c-popup {
    position: fixed;
    justify-content: flex-end;
    z-index: 99;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 200%;
    background-image: linear-gradient(to top, rgba(0, 0, 0, 0.5), transparent);
  }

  .btn-mask {
    width: 100%;
  }

  .frm-popup {
    background: #fff;
    z-index: 999;
    border-radius: .2rem .2rem 0 0;
  }

  .popup-head {
    height: 1rem;
    font-size: .32rem;
    color: #0D0D0D;
  }

  .popup-body {
    width: 100%;
  }

  .popup-foot {
    height: 1rem;
    width: 100%;
    box-shadow: 0 0 0.1rem rgba(0, 0, 0, .1);
  }

  .slide-enter-active, .slide-leave-active {
    transition: all .5s;
  }

  .slide-enter, .slide-leave-to {
    transform: translateY(100%);
  }
</style>
