<template>
  <div class="c-detail-profile">
    <div class="cover icon-course-selected icon-yike">
      <img :src="`${profile.cover}!cc.750_400`"/>
      <div class="enrollment">
        <i class="icon-yike icon-people"></i>
        <span class="text-desc font-bold">{{profile.enrollment}}人</span>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    name: 'lesson-detail-cover',
    props: ['profile']
  }
</script>

<style scoped>
  .cover {
    position: relative;
    height: 4rem;
    background: #ddd;
  }

  .cover:before {
    position: absolute;
    top: 50%;
    left: 50%;
    font-size: 1rem;
    color: #fff;
    margin: -.5rem -.5rem;
  }

  .cover > img {
    position: relative;
    width: 100%;
    height: 4rem;
  }

  .enrollment {
    position: absolute;
    left: .32rem;
    bottom: .24rem;
    font-weight: bold;
    color: #fff;
    text-shadow: 0 0 .05rem #333;
  }

  .icon-people {
    font-size: .28rem;
  }

  .icon-people + span {
    color: #fff;
  }
</style>
