<template>
  <div class="c-lesson-detail-contact flex-row">
    <div class="desc flex-col font-regular">
      <div>关注易灵微课</div>
      <div>接收开课提醒</div>
      <div>发现更多课程</div>
      <!--<div>添加小助手，可向我们咨询、建议、反馈</div>-->
    </div>
    <div class="qrcode flex-row">
      <div class="qrcode-mp flex-col">
        <img :src="app.linkToAssets('/img/qrcode/yike-fm.png')" @click="app.previewImageOne(app.linkToAssets('/img/qrcode/yike-fm.png'))"/>
        <span>
          <i class="icon-yike icon-wechat font-ragular"></i>
          易灵微课公众号
        </span>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    name: 'lesson-detail-contact'
  }
</script>

<style scoped>
  .c-lesson-detail-contact {
    height: 4rem;
    justify-content: space-around;
    background: white;
  }
  .desc {
    font-size: .32rem;
    color: #333;
    padding: .3rem;
    line-height: .5rem;
  }
  .qrcode {
    justify-content: space-around;
    /*border-left: 1px solid #0D0D0D;*/
  }
  .qrcode img {
    width: 2.5rem;
    height: 2.5rem;
  }
  .qrcode span {
    padding-top: .1rem;
    font-size: .2rem;
    color: #0D0D0D;
  }
  .qrcode-yike_01 {
    padding: 0 .1rem 0 .2rem;
  }
  .qrcode-mp {
    padding: 0 .2rem 0 .1rem;
  }
  .icon-wechat {
    font-size: .2rem;
    color: #29BC60;
  }
</style>
