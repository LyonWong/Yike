<template>
  <div class="c-lesson-detail-contact flex-col">
    <div class="qrcode flex-row">
      <div class="qrcode-yike_01 flex-col">
        <img :src="app.linkToAssets('/img/qrcode/yike-01.jpg')"/>
        <span>小助手</span>
      </div>
      <div class="qrcode-mp flex-col">
        <img :src="app.linkToAssets('/img/qrcode/mp.png')"/>
        <span>公众号</span>
      </div>
    </div>
    <div class="desc font-24">
      <div>关注公众号，接收开课提醒及新课程消息</div>
      <div>添加小助手，可向我们咨询、建议、反馈</div>
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
    padding: .3rem;
    justify-content: space-between;
    background: white;
  }
  .desc {
    flex-grow: 1;
    color: #666;
    padding: .3rem;
  }
  .qrcode {
    width: 100%;
    justify-content: space-between;
    border-bottom: 1px solid #0D0D0D;
  }
  .qrcode img {
    width: 2rem;
    height: 2rem;
    border: .13rem solid #F7E1BB;
    border-radius: .1rem;
  }
  .qrcode span {
    padding: .1rem 0;
    font-size: .2rem;
    color: #0D0D0D;
  }
  .qrcode-yike_01 {
    padding: 0 .1rem 0 .2rem;
  }
  .qrcode-mp {
    padding: 0 .2rem 0 .1rem;
  }
</style>
