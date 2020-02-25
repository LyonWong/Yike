<template>
  <div class="c-search-cell flex-row" @click="go">
    <div class="cover">
      <img :src="profile.cover ? `${profile.cover}!cover.s` : app.linkToAssets('/img/lesson/default-cover.png')"/>
    </div>
    <div class="datum flex-col">
      <div class="datum-head font-bold">{{profile.title}}</div>
      <div class="datum-body flex-row">
      </div>
      <div class="datum-foot flex-row">
        <div class="teacher font-medium text-desc">讲师：{{profile.teacher.name}}</div>
        <div class="enrollment flex-row">
          <i class="icon-yike icon-people"></i>
          <span class="text-desc">{{profile.enrollment}}</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script>

  export default {
    name: "search-cell",
    props: ['profile'],
    components: {},
    created() {
     // console.log(this.profile)
    },
    methods: {
      go() {
        switch (this.profile.sn[0])  {
          case 'L':
            window.location.href = `/lesson/detail?sn=${this.profile.sn}`
            break;
          case 'S':
            window.location.href = `/lesson/series?sn=${this.profile.sn}`
            break;
        }
      }
    }
  }
</script>

<style scoped>
  .c-search-cell {
    padding: .3rem 0;
    cursor: pointer;
    justify-content: space-between;
    border-bottom: 1px solid #eee;
    height: 1.28rem;
  }

  .c-search-cell:last-child {
    border-bottom: 0;
  }

  .cover > img {
    width: 2.4rem;
    height: 1.28rem;
    padding-top: .05rem;
  }
  .datum {
    justify-content: space-between;
    width: 4.2rem;
    height: 100%;
  }
  .datum>div {
    width: 100%;
  }
  .datum-head {
    font-size: .3rem;
    color: #0D0D0D;
    word-break: break-all;
  }
  .datum-body {
    justify-content: space-between;
    font-size: .24rem;
    color: #666;
  }
  .datum-foot {
    justify-content: space-between;
    align-items: flex-end;
  }

  .price {
    font-size: .3rem;
    color: #F23F15;
  }

  .enrollment {
    font-size: .24rem;
    color: #999;
  }
  .enrollment > i {
    font-size: .32rem;
    margin-right: .5em;
    color: #ccc;
  }

</style>
