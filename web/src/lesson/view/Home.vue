<template>
  <div class="c-home">
    <div class="search">
      <router-link to="/lesson/search" class="search-btn flex-row">
        <i class="icon-yike icon-search"></i>
        <span>搜索</span>
      </router-link>
    </div>
    <div class="sections">
      <div class="section" v-for="(item,index) in sections" :key="index">
        <lesson-home-banner class="banner" v-if="item.form === 'banner'" :data="item"></lesson-home-banner>
        <lesson-home-block class="block" v-if="item.form === 'block' && item.list.length" :data="item">
          <!--<div slot="another" class="another-frm flex-row">-->
          <!--<div class="another text-desc font-medium" @click="another">换一批</div>-->
          <!--</div>-->
        </lesson-home-block>
      </div>
    </div>
      <div class="overall flex-row font-medium" v-if="sections.length">
        <span @click="go" class="font-medium">查看全部课程</span>
      </div>
    <navigation></navigation>
  </div>
</template>
<script>
  import LessonHomeBanner from "../components/home/banner";
  import LessonHomeBlock from "../components/home/block";
  import Navigation from "../../components/Navigation";

  export default {
    name: 'lesson-home',
    components: {Navigation, LessonHomeBlock, LessonHomeBanner},
    data() {
      return {
        sections: []
      }
    },
    created() {
      this.api.get('/api/lesson-home')
        .then((res) => {
          this.sections = res.data
        })
      this.api.get('/api/jweixin-config', {url: location.href}).then((res) => {
        res.data.jsApiList = ['onMenuShareTimeline', 'onMenuShareAppMessage']
        this.wx.config(res.data)
        this.app.onShare({
          title: '易灵微课-听过的课才有价值',
          desc: '永久回放，1小时不满意退款',
          imgUrl: this.app.linkToAssets('/img/logo/Original_6464@2x.png')
        })
      })
      let mta= document.createElement("script");
      mta.src = "https://pingjs.qq.com/h5/hotclick.js?v2.0";
      mta.setAttribute("name", "mtah5hotclick");
      mta.setAttribute("sid", this.app.config.mta.AppId);
      mta.setAttribute("hid", this.app.config.mta.HidHome);
      let s = document.getElementsByTagName("script")[0];
      s.parentNode.insertBefore(mta, s);
    },
    methods: {
      another() {
        this.api.post('/api/lesson-list', {})
          .then((res) => {

          })
      },
      go() {
        location.href = `/lesson/list?title=课程列表&tag=`
      }
    }
  }
</script>
<style scoped>
  .c-home {
    padding-bottom: 1rem;
    background: #f0eff5;
  }

  .search {
    padding: .25rem .4rem 0 .4rem;
    background: #E5E5E5;
  }
  .search-btn  {
    width: 6.7rem;
    height: .6rem;
    border-radius: .3rem;
    font-size: .28rem;
    background: #fff;
  }
  .search-btn i {
    font-size: .32rem;
    padding:0 .2rem;
    color: #c8c8c8;
  }
  .search-btn span {
    color: #ccc;
  }

  .block {
    margin-bottom: .1rem;
  }

  .overall {
    padding: .5rem;
  }

  .overall span {
    font-size: .2rem;
    color: #aaa;
    padding: .1rem .2rem;
    border: 1px solid #ccc;
    border-radius: 1em;
    cursor: pointer;
  }

  .another-frm {
    width: 100%;
    background: white;
    padding-bottom: .39rem;
    border-bottom: 1px solid #DDDDDD;
  }

  .another {
    width: 1.06rem;
    height: .5rem;
    line-height: .5rem;
    text-align: center;
    background: #fff;
    border-radius: .04rem;
    border: 1px #ccc solid;
    color: #6C6C6C;
  }
</style>
