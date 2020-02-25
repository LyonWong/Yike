<template>
  <div class="c-subview-cell" @click="go">
    <div class="frm flex-row">
      <div class="flex-col detail">
        <div class="flex-row detail-top">
          <div class="title" :class="subview.status">{{subview.title}}</div>
        </div>
      </div>
      <div class="tms">{{subview.plan.dtm_start}}</div>
    </div>
  </div>
</template>

<script>
  import StatusLabel from './StatusLabel'

  export default {
    name: "subview-cell",
    props: ['subview', 'home'],
    components: {StatusLabel},
    methods: {
      go() {
        if (this.home) {
          location.href = `/home/${this.home}/${this.subview.form}?sn=${this.subview.sn}`
        } else {
          location.href = `/study/article/${this.subview.sn}`
        }
      }
    }
  }
</script>

<style scoped>
  .c-subview-cell {
    padding: .3rem 0;
    border-bottom: 1px solid #DDDDDD;
    cursor: pointer;
  }

  .c-subview-cell:first-child {
    padding-top: .1rem;
  }

  .c-subview-cell:last-child {
    border-bottom: 0 !important;
    padding-bottom: .5rem !important;
  }

  .frm {
    justify-content: flex-start;
  }

  .detail {
    flex: 1;
    /*height: 1.28rem;*/
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
    /*width: 75%;*/
    /*height: 1rem;*/
    font-size: .27rem;
    color: #0D0D0D;
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
    color: #888;
    line-height: .36rem;
    font-size: .27rem;
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
