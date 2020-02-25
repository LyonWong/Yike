<template>
  <div class="u-switch-span flex-row" :class="{checked: checked}" @click="$emit('switch')" :style="{width: width}">
    <span class="on">{{on}}</span>
    <span class="space flex-item">&nbsp;</span>
    <span class="off">{{off}}</span>
  </div>
</template>

<script>
  export default {
    name: 'switch-span',
    props: ['on', 'off', 'checked', 'width']
  }
</script>

<style scoped>
  .u-switch-span {
    overflow: hidden;
    color: #2F57DA;
    background: #fff;
  }
  .space {
    background: #fff;
  }
  .on, .checked > .off {
    display: none;
  }
  .checked > .on {
    display: block;
  }

</style>
