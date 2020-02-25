<template>
  <router-link class="c-blog-cell flex-row" :to="{name: 'View', params: {sn: profile.sn}}">
    <div class="cover">
      <img :src="profile.setting.cover ? `${profile.setting.cover}!cover.s` : app.linkToAssets('/img/lesson/default-cover.png')"/>
    </div>
    <div class="datum flex-col">
      <div class="datum-head font-bold">{{profile.title}}</div>
      <div class="datum-body flex-row">
        <!--<div class="teacher font-medium text-desc">讲师：{{profile.teacher.name}}</div>-->
        <!--<div class="progress" v-if="profile.type === 'lesson'">{{profile.progress[1] ? '已开' : '未开'}}</div>-->
        <!--<div class="progress" v-if="profile.type === 'series'">{{profile.progress[1]}}/{{profile.progress[0]}}</div>-->
      </div>
      <div class="datum-foot flex-row">
        <div class="tags">{{profile.tags}}</div>
        <div class="time">{{profile.tms_update}}</div>
      </div>
    </div>
  </router-link>
</template>

<script>

  export default {
    name: "blog-cell",
    props: ['profile'],
    components: {},
    created() {
    }
  }
</script>

<style scoped>
  .c-blog-cell {
    padding: .3rem 0;
    cursor: pointer;
    justify-content: space-between;
    border-bottom: 1px solid #eee;
    height: 1.28rem;
  }
  a.c-blog-cell {
    color: #666;
  }

  .c-profile-cell:last-child {
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
    height: 113%;
  }
  .datum>div {
    width: 100%;
  }
  .datum-head {
    font-size: .3rem;
    color: #0D0D0D;
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
</style>
