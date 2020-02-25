<template>
  <div class="c-lesson-cell" @click="go">
    <div class="frm flex-row">
      <img :src="lesson.cover ? `${lesson.cover}!cover.s` : app.linkToAssets('/img/lesson/default-cover.png')">
      <div class="flex-col detail">
        <div class="flex-row detail-top">
          <div class="title font-bold" :class="lesson.status">{{lesson.title}}</div>
          <div class="flex-col tags">
            <status-label :status="lesson.status"/>
            <div class="price font-bold">￥{{lesson.price}}</div>
          </div>
        </div>
        <div class="flex-row detail-bottom">
          <div class="enrollment">
            <i class="icon-yike icon-people"></i>
            <span class="font-bold text-desc">{{lesson.enrollment}}人</span>
          </div>
          <div class="tms font-medium text-desc">{{lesson.plan.dtm_start}}</div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import qs from 'qs'
  import StatusLabel from './StatusLabel'

  export default {
    name: "lesson-cell",
    props: ['lesson', 'action', 'home'],
    components: {StatusLabel},
    methods: {
      go() {
        let href = this.home ? `/home/${this.home}/lesson?sn=${this.lesson.sn}` : `/lesson/detail?sn=${this.lesson.sn}`
        let query = qs.stringify({
          action: this.action
        })
        if (query) {
          href += `&${query}`
        }
        window.location.href = href
      }
    }
  }
</script>

<style scoped>
  .c-lesson-cell {
    padding: .3rem 0;
    border-bottom: 1px solid #DDDDDD;
    cursor: pointer;
  }

  .c-lesson-cell:first-child {
    padding-top: .1rem;
  }

  .c-lesson-cell:last-child {
    border-bottom: 0 !important;
    padding-bottom: .5rem !important;
  }

  .frm {
    justify-content: flex-start;
  }

  .detail {
    flex: 1;
    height: 1.28rem;
  }

  img {
    width: 2.4rem;
    height: 1.28rem;
    padding-right: .3rem;
  }

  .detail-top, .detail-bottom {
    width: 100%;
  }

  .detail-top {
    position: relative;
    flex-grow: 1;
    height: 80%;
  }
  .detail-top .tags {
    position: absolute;
    right: 0;
  }

  .detail-bottom {
    height: 20%;
  }

  .detail, .detail-top, .detail-bottom, .title + .flex-col {
    justify-content: space-between;
  }

  .title {
    width: 75%;
    height: 1rem;
    font-size: .27rem;
    color: #0D0D0D;
    font-weight: bold;
  }

  .title + div {
    height: 100%;
  }
  .title.finish {
    width: 100%;
  }

  .price {
    width: 100%;
    font-size: .3rem;
    color: #F23F15;
    text-align: right;
    padding-bottom: .1rem;
  }

  .tms {
    color: #808080;
    line-height: .36rem;
  }

  .enrollment {
    color: #999;
  }

  .enrollment > i {
    font-size: .32rem;
    color: #ccc;
  }

  .icon-single-people {
    position: relative;
  }

  .icon-single-people:before {
    position: relative;
    z-index: 1;
    color: #999;
  }

  .icon-single-people:after {
    z-index: 0;
    content: "\e623";
    position: absolute;
    top: .01rem;
    left: .1rem;
    font-size: .2rem;
    color: #ccc;
  }

  .icon-single-people + span {
    padding-left: .21rem;
  }

  .icon-people {
    font-size: .28rem;
  }

  .icon-people + span {
    color: #999999;
  }
</style>
