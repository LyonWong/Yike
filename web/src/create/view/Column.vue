<template>
  <div class="v-create-column">
    <div class="head flex-row">
      <router-link class="btn back" to="/create/posts#column">返回</router-link>
      <div class="heading flex-item flex-row" v-if="profile.step==='finish'">
        <a :href="`/home/${profile.teacher.sn}/${profile.form}?sn=${sn}`" target="_blank">{{profile.title}}</a>
      </div>
      <div class="heading flex-item flex-row" v-else>新的专栏</div>
      <router-link class="btn edit" :to="`/create/submit/${sn}`">设置</router-link>
    </div>
    <div id="list" v-show="activeTab==='list'">
      <div class="hint" v-if="list.length===0">点击下方<i class="icon-yike icon-pencle"></i>添加文章</div>
      <i id="create-article" class="btn icon-yike icon-pencle" @click="createArticle"></i>
      <div class="item" v-for="(item,idx) in list" :key="idx">
        <router-link :to="item.status==='finish' ? `/create/submit/${item.sn}` : `/create/article/${item.sn}`" class="flex-row">
          <div class="datum flex-item flex-col">
            <div class="title font-bold" :class="{todo: !item.title}">{{item.title || '新的文章'}}</div>
            <div class="desc desc-article flex-row">
              <div v-if="item.status==='finish'">
                <span class="datum-tag">
                  <span>浏览</span>
                  <span class="datum-num-enrollment">{{item.browse}}</span>
                </span>
                <span class="datum-tag" v-if="item.conf.indie">
                  <span>解锁</span>
                  <span class="datum-num-enrollment">{{item.enrollment}}</span>
                </span>
              </div>
              <div v-else>未发布</div>
            </div>
          </div>
          <div class="cover flex-col" v-if="item.cover">
            <img :src="item.cover+'!cover.s'"/>
          </div>
        </router-link>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    name: 'create-colomn',
    components: {},
    data() {
      return {
        sn: this.$route.params.sn,
        tabs: [
          {'key': 'list', name: '列表'},
          {'key': 'edit', name: '设置'}
        ],
        activeTab: 'list',
        profile: {},
        list: []
      }
    },
    created() {
      this.api.get('/api/create-profile', {
        sn: this.sn
      }).then((res) => {
        this.profile = res.data
        this.api.get('/api/lesson-subview', {
          sn: this.sn
        }).then((res) => {
          this.list = res.data.reverse()
        })
      }, (err) => {
        alert(err.message)
        location.href = `/lesson/column/${this.sn}`
      })
      if (location.hash) {
        this.activeTab = location.hash.substr(1)
      }
    },
    mounted() {},
    methods: {
      switchTab(key) {
        this.activeTab = key
        location.hash = key
      },
      createArticle() {
        this.api.post('/api/create-post-article', {
          category: this.sn
        }).then((res) => {
          this.$router.push(`/create/article/${res.data.sn}`)
        })
      },
      edit(attr) {
        let data = {
          sn: this.sn
        }
        data[attr] = this.profile[attr]
        this.api.post('/api/create-modify', data).then((res) => {
          this.profile = res.data
        })
      },
      release() {
        this.api.post('/api/create-release-column', {
          sn: this.sn
        }).then(() => {
          location.href = `/lesson/column/${this.sn}`
        })
      }
    }
  }
</script>

<style scoped>
  .v-create-column {
    background: #f0eff5;
  }
  #tabs .icon-arrow-l {
    text-align: center;
    font-size: .4rem;
  }
  .head {
    position: sticky;
    top: 0;
    height: 1rem;
    background: #fff;
    border-bottom: 1px solid #ccc;
    z-index: 9;
  }
  .heading {
    font-size: .36rem;
    color: #222;
  }
  .head > .btn {
    padding: 0 .3rem;
    font-size: .28rem;
  }
  .head > .heading > a{
    color: #2F57DA;
    max-width: 4rem;
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
    /*border-bottom: 1px solid #2F57DA;*/
  }
  .head .back {
    color: #999;
  }
  .head .edit {
    color: #222;
  }
  #list {
    background: #fff;
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
  .item {
    padding: .55rem 0 .45rem 0;
    border-bottom: 2px solid #e4e4e4;
    margin: 0 .3rem;
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
    justify-content: flex-start;
  }
  e.title {
    justify-content: space-between;
    /*padding: .3rem;*/
    background: #fff;
    color: #999;
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
  .cover img {
    width: 1.8rem;
    height: .96rem;
  }
  .hint .icon-yike {
    font-size: .26rem;
    background: #666;
    color: #fff;
    border-radius: 50%;
    padding: .05rem;
    margin: .05rem;
  }
  #input-title {
    background: transparent;
    font-size: .3rem;
    outline: none;
    border: none;
  }
  #input-title:focus {
    background: #fff;
  }
  #create-article {
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
    box-shadow: 0 0 .13rem #000;
  }
</style>
