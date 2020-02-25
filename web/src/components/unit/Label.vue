<template>
  <div class="c-label" :class="value">
    {{label}}
  </div>
</template>

<script>
  export default {
    name: 'label',
    props: ['value'],
    data() {
      return {
        dict: {
          opened: '未开课',
          onlive: '直播中',
          repose: '交流中',
          finish: '已完结',
          closed: '已下架',
          enroll: '已报名',
          access: '已听课',
          refund: '已退款',
        }
      }
    },
    computed: {
      label: function() {
        return this.dict[this.value] || this.value
      }
    }
  }
</script>

<style scoped>
  .c-label {
    padding: 0 .5em;
    border-radius: 1em;
    color: #fff;
    font-size: .22rem;
    text-align: center;
    line-height: 1.8em;
  }
  .opened {
    /*background: #D8C199;*/
    color: #2F57DA;
    border: 1px solid #2F57DA;
    background: #fff;
  }
  .onlive, .repose{
    background: #5DC96A;
  }
  .finish {
    background: #2F57DA;
  }
  .close {
    background: #FF7E7E;
  }

</style>
