<template>
  <div class="c-user">
    <div class="frm-user flex-col" v-if="teachers.length===0">
      <img class="logo" :src="app.linkToAssets('/img/logo/logo@3x.png')">
      <!--<img class="refresh" :src="app.linkToAssets('/img/button/refresh@3x.png')" @click="refresh()">-->
      <div class="userinfo flex-col">
        <div class="avatar">
          <img :src="user.avatar">
        </div>
        <div class="nickname font-medium" v-text="user.name"></div>
      </div>
      <a class="datum" href="/user/datum">
        <span>资料</span>
        <i class="icon-yike icon-arrow-r"></i>
      </a>
    </div>
    <div class="frm-teacher  flex-col" v-else>
      <img class="cover" :src="app.linkToAssets('/img/lesson/lecture-cover.png')"/>
      <div class="userinfo flex-col">
        <div class="avatar">
          <img :src="user.avatar">
        </div>
        <div class="nickname font-medium" v-text="user.name"></div>
        <div class="stats" v-if="stats">
          <a :href="`/home/${user.sn}/#lesson`">课程 {{stats.course}}</a>
          <a :href="`/home/${user.sn}/#article`">文章 {{stats.article}}</a>
        </div>
      </div>
      <a class="datum" href="/user/datum">
        <span>资料</span>
        <i class="icon-yike icon-arrow-r"></i>
      </a>
    </div>
    <ul v-if="teachers.length">
      <li class="flex-row" v-for="(item,index) in teachers" :key="index" @click="turnTo(item.path)">
        <div class="flex-row">
          <i :class="item.icon" class="icon icon-yike"></i>
          <span class="font-medium" v-text="item.text"></span>
        </div>
        <i class="icon-yike icon-arrow-r"></i>
      </li>
    </ul>
    <ul>
      <li class="flex-row" v-for="(user,index) in users" :key="index" @click="turnTo(user.path)">
        <div class="flex-row">
          <i :class="user.icon" class="icon icon-yike"></i>
          <span class="font-medium" v-text="user.text"></span>
        </div>
        <i class="icon-yike icon-arrow-r"></i>
      </li>
    </ul>
    <ul v-if="applys.length">
      <li class="flex-row" v-for="(item,index) in applys" :key="index" @click="turnTo(item.path)">
        <div class="flex-row">
          <i :class="item.icon" class="icon icon-yike"></i>
          <span class="font-medium" v-text="item.text"></span>
        </div>
        <i class="icon-yike icon-arrow-r"></i>
      </li>
    </ul>
    <ul>
      <li class="flex-row" v-for="(item,index) in feedback" :key="index" @click="turnTo(item.path)">
        <div class="flex-row">
          <i :class="item.icon" class="icon icon-yike"></i>
          <span class="font-medium" v-text="item.text"></span>
        </div>
        <i class="icon-yike icon-arrow-r"></i>
      </li>
    </ul>
    <navigation></navigation>
  </div>
</template>

<script>
  import Navigation from "../../components/Navigation";

  export default {
    name: "Home",
    components: {
      Navigation
    },
    data() {
      return {
        user: {},
        teacher: {},
        stats: null,
        teachers: [],
        users: [
          {
            icon: 'icon-money',
            text: '账户资金',
            path: '/user/money'
          },
          {
            icon: 'icon-discount',
            text: '兑换优惠券',
            path: this.app.linkToStudent('/?v=2#/user/money/coupon')
          }
        ],
        applys: [],
        feedback: [
          {icon: 'icon-feedback', text: '建议与反馈', path: this.app.linkToStudent('/?v=2#/user/advise')},
          {icon: 'icon-aboutus', text: '关于我们', path: '/user/about-us'}
        ]
      }
    },
    created() {
      this.api.get('/api/user-profile').then((res) => {
        this.user = res.data
        window.localStorage.setItem('usn', res.data.sn)
      }, this.api.onErrorSign)
      this.api.get('/api/user-teacher').then((res) => {
        if (res.data) { // 讲师栏目
          this.teacher = res.data
          this.stats = {
            course: res.data.stats.lesson + res.data.stats.series,
            article: res.data.stats.article + res.data.stats.column
          }
          this.teachers = [
            // {
            //   icon: 'icon-enrolled-course',
            //   text: '我的课程',
            //   path: '/create/posts'
            // },
            {
              icon: 'icon-article-collection',
              text: '我的文集',
              path: '/create/posts'
            },
            {
              icon: 'icon-notice',
              text: '讲师须知',
              path: '/user/teacher-notice'
            }
          ]
        } else {
          this.applys = [
            {
              icon: 'icon-enroll-teacher',
              text: '成为讲师',
              path: '/user/apply'
            }
          ]
        }
      })
    },
    methods: {
      refresh() {
        this.api.post('/api/user-info-refresh', null, {loading: '正在同步微信头像昵称'}).then((res) => {
          this.user = res.data
        })
      },
      turnTo(path) {
        window.location.href = path
      }
    }
  }
</script>

<style scoped>
  .c-user {
    height: auto;
    padding-bottom: 1.1rem;
    background: #f0eff5;
  }

  .frm-user {
    position: relative;
    width: 100%;
    height: 3rem;
    background: #565AD1;
    background-image: linear-gradient(50deg, transparent 20%, rgba(106, 213, 255, .1) 20%, rgba(106, 213, 255, .04) 55%, rgba(82, 87, 208, 0.01) 55%),
    linear-gradient(125deg, rgba(106, 213, 255, .1) 20%, transparent 20%, transparent 60%, rgba(106, 213, 255, .08) 60%, rgba(106, 213, 255, .08) 97%, transparent 97%),
    linear-gradient(-130deg, rgba(255, 255, 255, .8) 0%, rgba(255, 255, 255, .1) 80%, transparent 80%);
    /*linear-gradient(to right, transparent -10%, #fff 155%);*/
  }
  .frm-teacher {
    position: relative;
    width: 100%;
    /*height: .3rem;*/
  }
  .frm-teacher .cover {
    width: 100%;
    height: 3rem;
  }
  .frm-teacher .userinfo {
    position: absolute;
    left: .3rem;
    top: .3rem;
    padding: 0;
    height: auto;
    align-items: flex-start;
  }
  .frm-teacher .nickname {
    padding-top: .2rem;
  }
  .datum {
    position: absolute;
    right: .3rem;
    bottom: .3rem;
    color: #2F57DA;
    font-size: .26rem;
    background: rgba(255,255,255,0.8);
    border-radius: .08rem;
    padding: .16rem .2rem;
  }
  .datum > i {
    font-size: .26rem;
    color: #2F57DA;
  }
  .stats {
    margin-top: .2rem;
    color: #fff;
    font-size: .26rem;
  }
  .stats > a {
    color: #fff;
    padding: 0 .3rem;
    border-left: 1px solid #b3b3b3;
  }
  .stats > a:first-child {
    padding-left: 0;
    border-left: none;
  }

  .logo {
    position: absolute;
    top: .3rem;
    left: .3rem;
    width: 1.8rem;
    height: .42rem;
  }

  .refresh {
    position: absolute;
    top: .3rem;
    right: .3rem;
    width: .7rem;
    height: .7rem;
  }

  .userinfo {
    position: relative;
    height: 100%;
    padding-top: .5rem;
  }

  .avatar {
    width: 1.27rem;
    height: 1.27rem;
    border: .01rem solid #c1c1c1;
    border-radius: 50%;
  }

  .avatar > img {
    width: 100%;
    height: 100%;
    border-radius: 50%;
  }

  .nickname {
    padding-top: .27rem;
    text-align: center;
    color: #fff;
    font-size: .32rem;
    font-weight: bold;
  }

  ul {
    margin: .4rem 0;
    /*padding: 0 .37rem 0 .32rem;*/
    padding: 0;
    list-style: none;
    border-top: 1px solid #ccc;
    border-bottom: 1px solid #ccc;
    background: white;
  }

  li {
    padding: .17rem .35rem;
    border-bottom: 1px solid #ccc;
    justify-content: space-between;
    cursor: pointer;
  }

  li:last-child {
    border-bottom: 0;
    padding: .17rem .35rem;
  }

  .icon-arrow-r {
    color: #808080;
  }

  .icon-enrolled-course {
    background: linear-gradient(to right, #2C51F2, #8585FE);
    font-size: .5rem;
  }

  .icon-article-collection {
    background: linear-gradient(to right, #5ECDC7, #8EDBD7);
    font-size: .5rem;
  }

  .icon-enrolled-teacher {
    background: linear-gradient(to right, #B3A2FF, #E1B9FF);
    font-size: .5rem;
  }

  .icon-money {
    background: linear-gradient(to right, #FF9E04, #FFD027);
    font-size: .5rem;
  }

  .icon-discount {
    background: linear-gradient(to top, #FF7CA4, #FEACB2 60%);
    font-size: .5rem;
  }

  .icon-person {
    background: linear-gradient(to right, #2C51F2, #8585FE);
    font-size: .5rem;
  }

  .icon-feedback {
    background: linear-gradient(to right, #42C25F, #54E269);
    font-size: .5rem;
  }

  .icon-enroll-teacher {
    background: linear-gradient(to right, #8C61F7, #AD99F7);
    font-size: .5rem;
  }

  .icon-aboutus, .icon-notice {
    background: linear-gradient(to right, #659AFA, #71C2FF);
    font-size: .5rem;
  }

  .icon {
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    padding-right: .36rem;
  }

  .icon + span {
    font-size: .32rem;
    color: #0D0D0D;
    padding-bottom: .03rem;
  }

</style>
