<template>
  <header class="tips" v-if="show">
    <div class="t-container" :style="">
      {{text}}
    </div>
  </header>
</template>

<script>
    export default{
      name: 'v-tips',
      props: {
        text: {
          type: String,
        },
        show: {
          type: String,
        },
        offsetX: {
          type: Number,
        },
        offsetY: {
          type: Number,
        },
      },
      methods: {
      }
    }
</script>

<style lang="stylus" rel="stylesheet/stylus">
  .header {
    padding: 20px 0;
    background: #fff;
    overflow: hidden;
    -moz-box-shadow:-1px 3px 11px #949494;
    -webkit-box-shadow:-1px 3px 11px #949494;
    box-shadow:-1px 3px 11px #949494;

    h1 {
      float: left;
      margin: 0;
      color: #12B7F5;
      font-size: 20px;
    }
  }
</style>
