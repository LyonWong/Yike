<template>
  <div class="c-lesson-status-label" :class="status">
    {{label}}
  </div>
</template>

<script>
  export default {
    name: 'lesson-status-label',
    props: ['status'],
    data() {
      return {
        dict: {
          opened: '未开课',
          onlive: '直播中',
          repose: '交流中',
          finish: '已完结',
          closed: '已下架',
          enroll: '待学习',
          access: '已学习',
          refund: '已退款',
          reset: '可复购'
        }
      }
    },
    computed: {
      label: function() {
        return this.dict[this.status] || this.status
      }
    }
  }
</script>

<style scoped>
  .c-lesson-status-label {
    padding: 0 .5em;
    border-radius: 1em;
    color: #fff;
    font-size: .22rem;
    text-align: center;
    line-height: 1.8em;
  }
  .opened, .reset {
    color: #2F57DA;
    border: 1px solid #2F57DA;
    background: #fff;
  }
  .onlive, .repose{
    color: #5DC96A;
    border: 1px solid #5DC96A;
  }
  .enroll {
    background: #2F57DA;
  }
  .finish {
    opacity: 0;
  }
  .access {
    background: #5DC96A;
  }
  .close, .refund {
    background: #FF7E7E;
  }

</style>
