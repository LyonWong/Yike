<template>
  <modal v-on:cancel="$emit('close')" :display="display">
    <div class="c-action flex-col" :class="{headless: !$slots.head}" :style="{width: width}">
      <div class="frm-action">
        <div class="action-head flex-row">
          <slot name="head"></slot>
        </div>
        <div class="action-body">
          <slot></slot>
        </div>
        <div class="action-foot flex-row">
          <slot name="foot"></slot>
        </div>
      </div>
    </div>
  </modal>
</template>

<script>
  import Modal from "../Modal";
  export default {
    name: 'modal-action',
    props: ['display', 'width'],
    components: {Modal},
    created() {
    }
  }
</script>

<style scoped>
  .c-action {
    z-index: 99;
    width: 100%;
    /*height: 100%;*/
  }
  .frm-action {
    width: 80%;
    z-index: 999;
  }
  .action-head {
    height: 1rem;
    color: #222;
    font-size: .3rem;
    font-weight: bold;
    letter-spacing: .05rem;
    background: #e9edff;
    border-radius: .12rem .12rem 0 0;
  }
  .headless .action-head{
    height: .12rem;
    background: #fff;
  }
  .action-body {
    flex-grow: 1;
    background: #fff;
    color: #333;
    font-size: .26rem;
    padding: .3rem;
  }
  /* 无头模式放大字体*/
  .headless .action-body {
    font-size: .3rem;
  }
  .action-foot {
    background: #fff;
    border-radius: 0 0 .12rem .12rem;
    height: 1rem;
  }
</style>
<style>
  .action-foot {
    display: flex;
    justify-content: space-around;
    align-items: center;
  }
  .action-foot .btn {
    border-radius: 1em;
    padding: .2em 1em;
    font-size: .32rem;
  }
  .action-foot .btn-primary, .action-foot .btn-prime  {
    font-size: .32rem;
    color: #fff;
    background: #2F57DA;
  }
  .action-foot .btn-vice {
    border: 1px solid #2F57DA;
    color: #2F57DA;
  }

</style>
