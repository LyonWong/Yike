<template>
    <div class="c-modal flex-col" v-if="display">
      <div class="mask" @click="$emit('cancel')"></div>
      <slot></slot>
    </div>
</template>

<script>
  export default {
    name: 'modal',
    props: ['display'],
    watch: {
      display: (status) => {
        if (status) {
          document.body.style.overflow = 'hidden'
          document.documentElement.style.overflow = 'hidden'
        } else {
          document.body.style.overflow = 'visible'
          document.documentElement.style.overflow = 'visible'
        }
      }
    }
  }
</script>

<style scoped>
  .c-modal {
    position: fixed;
    z-index: 99;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
  }

  .slide-enter-active, .slide-leave-active {
    transition: all .3s;
  }

  .slide-enter, .slide-leave-to {
    transform: translateY(100%);
  }
</style>
