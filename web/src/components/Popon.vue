<template>
  <transition name="slide">
    <div class="c-popon flex-col" v-show="isOpen">
      <div class="btn-mask flex-item" @click="close" @touchmove.prevent></div>
      <div class="frm-popon flex-col" :style="{width: width}">
        <div class="popon-head flex-row" v-if="opt.head !== false">
          <slot name="head">popon head{{option}}</slot>
        </div>
        <div class="popon-body flex-item">
          <slot></slot>
        </div>
        <div class="popon-foot flex-row" v-if="opt.foot !== false">
          <slot name="foot">popon food</slot>
        </div>
      </div>
    </div>
  </transition>
</template>

<script>

  export default {
    name: 'popon',
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
      }
    }
  }
</script>

<style scoped>
  .c-popon {
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

  .frm-popon {
    background: #fff;
    z-index: 999;
    border-radius: .2rem .2rem 0 0;
  }

  .popon-head {
    height: 1rem;
    font-size: .32rem;
    color: #0D0D0D;
  }

  .popon-body {
    width: 100%;
  }

  .popon-foot {
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
