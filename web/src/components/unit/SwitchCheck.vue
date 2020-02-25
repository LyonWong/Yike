<template>
  <div class="u-switch-check btn" :class="[{on: checked, off: !checked}, tone]" :style="{'border-radius': size}">
    <label class="capsule" :style="{'height': size, 'padding-left': size, 'padding-right': size, 'border-radius': size}"  >
      <i class="button" :style="{'height': size, 'width': size}"></i>
      <input type="checkbox" :checked="checked" @input="$emit('input', $event.target.checked)"/>
    </label>
  </div>
</template>

<script>
  export default {
    name: 'switch-check',
    props: ['checked', 'size', 'tone'],
    data() {
      return {
      }
    },
    methods: {
      onChange(e) {
        this.checked_ = e.target.checked
        console.log('checked', e.target.checked, this.checked_, this.check)
        this.$emit('input', e.target.checked)
      }
    }
  }
</script>

<style scoped>
  .u-switch-check {
    background: #0f0;
  }
  .capsule {
    position: relative;
  }
  .button {
    position: absolute;
    border-radius: 50%;
    background: #fff;
  }
  input {
    /*position: absolute;*/
    /*opacity: 0;*/
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
