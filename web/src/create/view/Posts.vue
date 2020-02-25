<template>
  <div class="v-create-posts">
  <Tabs id="tabs" :items="tabs" :active="activeTab" v-on:switch="switchTab">
  </Tabs>
  <div id="article" class="list" v-show="activeTab==='article'">
    <i class="btn create-article icon-yike icon-pencle" @click="createArticle"></i>
    <div class="hint" v-if="articles.length===0">点击下方<i class="icon-yike icon-pencle"></i>创建文章</div>
    <div class="item" v-for="(item, index) in articles" :key="index">
      <a @click="toArticle(item)" class="flex-row">
        <div class="datum flex-item flex-col">
          <div class="title font-bold" :class="{todo: !item.title}">{{item.title || '新的文章'}}</div>
          <div class="desc desc-article flex-row">
            <div v-if="item.status==='finish'">
              <span class="datum-tag">
                <span>浏览</span>
                <span class="datum-num-enrollment">{{item.browse}}</span>
              </span>
              <span class="datum-tag">
                <span>解锁</span>
                <span class="datum-num-enrollment">{{item.enrollment}}</span>
              </span>
            </div>
            <div v-else>
              <span>未发布</span>
            </div>
          </div>
        </div>
        <div class="cover flex-col" v-if="item.cover">
          <img :src="item.cover+'!cover.s'"/>
        </div>
      </a>
    </div>
  </div>
  <div id="column" class="list" v-show="activeTab==='column'">
    <i class="btn create-column icon-yike icon-add" @click="createColumn"></i>
    <div class="hint" v-if="columns.length===0">点击下方<i class="icon-yike icon-add"></i>创建专栏</div>
    <div class="item" v-for="(item, idx) in columns" :key="idx">
      <router-link :to="`${item.status==='finish' ? 'column' : 'submit'}/${item.sn}`" class="flex-row">
        <div class="datum flex-item flex-col">
          <div class="title font-bold" :class="{todo: !item.title}">{{item.title || '新的专栏'}}</div>
          <div class="desc desc-column flex-row">
            <div>
              <span class="datum-tag">
                <span>订阅</span>
                <span class="datum-num-enrollment">{{item.enrollment}}</span>
              </span>
            </div>
          </div>
        </div>
        <div class="cover flex-col">
          <img :src="item.cover+'!cover.s'"/>
        </div>
      </router-link>
    </div>
  </div>
  </div>
</template>

<script>
  import Tabs from "../../components/Tabs";
  export default {
    name: 'create-posts',
    components: {Tabs},
    data() {
      return {
        tabs: [
          {'key': 'article', name: '文章'},
          {'key': 'column', name: '专栏'}
        ],
        activeTab: 'article',
        articles: [],
        columns: []
      }
    },
    created() {
      this.api.get('/api/user-teacher').then((res) => {
        if (!res.data) {
          this.bus.$emit('dialog', {
            info: {body: '您尚未成为讲师'},
            btn: {
              prime: '去注册',
              vice: '再看看'
            },
            call: {
              prime: () => {
                location.href = '/user/apply'
              },
              vice: () => {
                this.$router.back()
              }
            }
          })
        }
      })
      this.api.get('/api/create-list-posts').then((res) => {
        let recent = null
        for (let item of res.data) {
          if (item.type === 'lesson') {
            switch (item.form) {
              case 'article':
                this.articles.push(item)
                recent = recent || item.form
                break
              case 'column':
                this.columns.push(item)
                recent = recent || item.form
                break
            }
          }
        }
        if (!location.hash && recent) { // 默认选中最后创建的项
          this.activeTab= recent
        }
      }, this.api.onErrorSign)
    },
    mounted() {
      if (location.hash) {
        this.switchTab(location.hash.substr(1))
      }
    },
    methods: {
      switchTab(key) {
        this.activeTab = key
        this.$router.replace(`${location.pathname}#${key}`)
      },
      createArticle() {
        this.api.post('/api/create-post-article').then((res) => {
          // this.$router.push(`/create/article/${res.data.sn}`)
          this.toArticle(res.data)
        })
      },
      createColumn() {
        this.api.post('/api/create-post-column').then((res) => {
          this.$router.push(`/create/submit/${res.data.sn}`)
        })
      },
      toArticle(profile) {
        if (profile.status === 'finish') {
          this.$router.push(`/create/submit/${profile.sn}`)
        } else if ( !this.app.env() ) {
          // window.open(`/w/create/prepare?sn=${profile.sn}`, '_blank')
          location.href = `/w/create/prepare?sn=${profile.sn}`
        } else {
          this.$router.push(`/create/article/${profile.sn}`)
        }
      }
    }
  }
</script>

<style scoped>
  .v-create-posts {
    min-height: 100%;
    position: relative;
    background: #f0eff5;
  }
  #tabs {
    z-index: 9;
    position: sticky;
    top: 0;
    height: .8rem;
    box-shadow: 0 0 5px rgba(0,0,0,0.1);
  }
  .list {
    background: #fff;
    padding: 0 .3rem;
  }
  .hint {
    position: absolute;
    left: 0;
    top: 50%;
    margin-top: -1em;
    font-size: .3rem;
    color: #666;
    width: 100%;
    text-align: center;
  }
  .hint .icon-yike {
    font-size: .26rem;
    background: #666;
    color: #fff;
    border-radius: 50%;
    padding: .05rem;
    margin: .05rem;
  }
  .item {
    padding: .55rem 0 .45rem 0;
    border-bottom: 2px solid #e4e4e4;
  }
  .item:last-child {
    border: none;
  }
  .title {
    font-size: .36rem;
    color: #222;
  }
  .todo {
    opacity: 0.5;
  }
  .desc {
    font-size: .24rem;
    color: #999;
  }
  .create-article, .create-column {
    width: 1.24rem;
    height: 1.24rem;
    background: #2F57DA;
    color: #fff;
    border-radius: 50%;
    position: fixed;
    bottom: .45rem;
    left: 50%;
    margin-left: -0.62rem;
    font-size: .8rem;
    text-align: center;
    line-height: 1.24rem;
    box-shadow: 0 0 .13rem #333;
  }
  .cover img {
    width: 1.8rem;
    height: .96rem;
  }
  .datum {
    height: .96rem;
    justify-content: space-between;
  }
  .datum > div {
    width: 100%;
  }
  .datum-tag {
    padding-right: 1em;
  }
  .title {
    justify-content: space-between;
  }
  .title > span{
    /*height: 1rem;*/
    font-size: .32rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }
  .title > i {
    color: #ccc;
    font-size: .32rem;
  }
  .desc {
    justify-content: flex-start;
  }
  #tabs .icon-arrow-l {
    text-align: center;
    font-size: .4rem;
    padding: 0 .2rem;
  }
  .icon-add:before {
    content: "＋";
  }
</style>
