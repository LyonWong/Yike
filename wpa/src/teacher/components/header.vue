<template>
  <header class="header">
    <div class="container">
      <h1>易灵微课</h1>
    </div>
  </header>
</template>

<script>
    export default{
      name: 'v-header',
      methods: {
      }
    }
</script>

<style lang="stylus" rel="stylesheet/stylus">
  .header {
    padding: 20px 0;
    background: #fff;
    overflow: hidden;
    -moz-box-shadow:-1px 3px 11px #dddddd;
    -webkit-box-shadow:-1px 3px 11px #dddddd;
    box-shadow:-1px 3px 11px #dddddd;

    h1 {
      float: left;
      margin: 0;
      color: #12B7F5;
      font-size: 20px;
    }
  }
</style>
