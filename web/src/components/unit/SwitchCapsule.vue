<template>
  <div class="u-switch-capsule btn" @click="$emit('check')" :class="[{on: checked, off: !checked}, tone]" :style="{'border-radius': size}">
    <div class="capsule" :style="{'height': size, 'padding-left': size, 'padding-right': size, 'border-radius': size}" >
      <i class="button" :style="{'height': size, 'width': size}"></i>
    </div>
  </div>
</template>

<script>
  export default {
    name: 'switch-capsule',
    props: {
      checked: Boolean,
      size: String,
      tone: String
    },
    model: {
      prop: 'checked',
      event: 'change'
    }
  }
</script>

<style scoped>
  .u-switch-capsule {
    /*padding: 3px;*/
  }
  .capsule {
    position: relative;
  }
  .button {
    position: absolute;
    border-radius: 50%;
    background: #fff;
  }
  .on {
    background: #CFD7F3
  }
  .off {
    background: lightgrey;
  }
  .on .button {
    right: 0;
    background: #2F57DA;
  }
  .off .button {
    left: 0;
    background: gray;
  }
</style>
