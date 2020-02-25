<template>
  <div class="c-loading flex-col" v-show="pending">
    <div class="dots">
      <span class="dot" v-for="(item, idx) in pending" :key="idx"></span>
    </div>
    <div class="text">loading</div>
  </div>
</template>

<script>
  export default {
    name: 'loading',
    props: ['pending']
  }
</script>

<style scoped>
  .c-loading {
    position: fixed;
    justify-content: flex-end;
    top: 40%;
    left: 50%;
    height: 1.6rem;
    width: 1.6rem;
    margin-left: -.8rem;
    background: rgba(0,0,0,0.7);
    color: #fff;
    border-radius: 10%;
  }
  .dots {
    padding: .1rem;
  }
  .text {
    padding: .2rem;
    font-size: .32rem;
  }
  .dot {
    animation: beat 2s infinite;
    width: .15rem;
    height: .15rem;
    background-color: #FFF;
    float: left;
    margin: .05rem;
    -moz-border-radius: 50% 50% 50% 50%;
    -webkit-border-radius: 50% 50% 50% 50%;
    border-radius: 50% 50% 50% 50%;

  }
  @keyframes beat {
    75% {
      transform: scale(0);
      -webkit-transform: scale(0);
    }
  }

</style>
