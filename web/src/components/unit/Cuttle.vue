<template>
  <div class="u-cuttle" :style="{'max-height': upperHeight}">
      <div class="content">
        <slot></slot>
      </div>
      <div class="veil"></div>
  </div>
</template>

<script>
  export default {
    name: 'u-cuttle',
    props: ['upperHeight']
  }
</script>

<style scoped>
  .u-cuttle {
    background: #cfc;
    position: relative;
    overflow: hidden;
  }
  .content {
    position: relative;
    z-index: 2;
  }
  .veil {
    position: absolute;
    top: 1rem;
    background: #fcf;
    width: 100%;
    height: 1rem;
    z-index: 1;
  }

</style>
