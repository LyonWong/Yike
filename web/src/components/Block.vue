<template>
  <div class="c-block">
    <div class="block-head flex-row">
      <div class="flex-row">
        <div class="block-title font-bold">{{title}}</div>
      </div>
      <div class="btn block-more">
        <slot name="more"></slot>
      </div>
    </div>
    <div class="block-body">
      <slot></slot>
    </div>
  </div>
</template>

<script>
  export default {
    name: 'block',
    props: ['title']
  }
</script>

<style scoped>
  .c-block {
    background: #fff;
  }

  .block-head {
    height: 1rem;
    justify-content: space-between;
  }

  .block-body {
    padding: 0 .3rem;
  }

  .block-title {
    height: .36rem;
    line-height: .36rem;
    padding: .12rem 0 .12rem .24rem;
    font-size: .32rem;
    border-left: .1rem solid #2F57DA;
  }
  .block-more {
    font-size: .27rem;
    color: #888;
    padding-right: .3rem;
  }
  .block-more .icon-yike {
    color: #808080;
    font-size: .24rem;
    font-weight: bold;
    padding-left: .2rem;
  }
  .block-more a{
    color: #888;
  }
</style>
