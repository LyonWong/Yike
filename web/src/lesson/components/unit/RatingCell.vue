<template>
  <div class="c-rating">
    <div class="frm-rating flex-col">
      <div class="frm-user flex-row">
        <div class="flex-row">
          <img :src="rate.user.avatar"/>
          <div class="nickname">{{rate.user.name}}</div>
        </div>
        <div class="star flex-row">
          <i class="icon-yike icon-star active" v-for="num in rate.score" :key="num.id"></i>
          <i class="icon-yike icon-star" v-for="num in (5-rate.score)" :key="num.id"></i>
        </div>
      </div>
      <div class="frm-remark flex-col">
        <div class="tms font-medium">{{rate.tms}}</div>
        <div class="remark font-medium">{{rate.remark}}</div>
      </div>
      <div class="frm-reply flex-col" v-if="rate.reply">
        <div class="title">讲师回复</div>
        <div class="tms_reply font-medium">{{rate.tms_reply}}</div>
        <div class="reply font-medium">{{rate.reply}}</div>
      </div>
    </div>
  </div>
</template>

<script>

  export default {
    name: "rating-cell",
    props: ['rate']
  }
</script>

<style scoped>
  .c-rating {
    align-items: flex-start;
    background: #fff;
    padding-top: .3rem;
    border-bottom: 1px solid #ddd;
  }

  .c-rating:first-child {
    padding-top: .1rem;
  }

  .c-rating:last-child {
    padding-bottom: .27rem;
    border: 0;
  }

  .frm-user {
    justify-content: space-between;
    width: 100%;
  }

  img {
    width: .6rem;
    height: .6rem;
    border-radius: .6rem;
  }

  .frm-remark, .frm-reply {
    width: 100%;
    align-items: flex-start;
  }

  .frm-reply {
    padding-top: .21rem;
  }

  .tms, .tms_reply {
    padding-top: .21rem;
  }

  .remark, .reply {
    padding: .23rem 0;
  }

  .nickname {
    padding-left: 1em;
    font-size: .32rem;
    color: #0D0D0D;
  }

  .tms, .tms_reply {
    font-size: .21rem;
    color: #808080;
  }

  .remark, .reply {
    width: 100%;
    font-size: .3rem;
    color: #333333;
  }

  .title {
    padding-left: .3rem;
    border-left: .1rem solid #12b7f5;
    font-size: .3rem;
    color: #808388;
  }

  .icon-star {
    font-size: .3rem;
    color: #e4e8ef;
  }

  .active.icon-star:before {
    color: #FFC600;
  }
</style>
