<template>
  <div class="v-create-preview">
    <div class="head flex-row">
      <div class="btn back" :class="{disabled: !canBack}" @click="$router.back()">返回</div>
      <div class="heading flex-item flex-row">文章预览</div>
      <div class="btn submit" @click="submit">下一步</div>
    </div>
    <div class="profile" v-if="profile">
      <div class="cover" v-if="profile.cover">
        <img :src="profile.cover"/>
      </div>
      <div class="title">{{profile.title}}</div>
      <div class="byline flex-row" v-if="profile">
        <a class="name font-bold"  :href="`/home/${profile.teacher.sn}/`">{{profile.teacher.name}}</a>
        <div class="time">{{profile.plan.dtm_start}}</div>
      </div>
      <div class="byline" v-if="profile.category">
        <!--<span>专栏</span>-->
        <a class="series" :href="`/home/${profile.teacher.sn}/column/${profile.category}`">{{profile.categoryInfo.title}}</a>
      </div>
    </div>
    <div class="content">
      <div class="section" v-for="(row,idx) in records" :key="idx" :class="{locked: !unlock && !row.content.free}">
        <c-section :content="row.content"></c-section>
        <div class="section-lock flex-col" @click="unlock=true">
          <div class="hint">剩余内容解锁后可见</div>
          <div class="btn btn-unlock flex-row">立即解锁</div>
        </div>
      </div>
    </div>
    <div class="backpost flex-row" v-if="0">
      <i class="btn icon-yike icon-post" @click="$router.go(-1)"></i>
    </div>
  </div>
</template>

<script>
  import SecCtrl from "../../components/section/control"
  const CSection = r => require.ensure([], () => r(require("../../components/section/index")), 'section')
  export default {
    name: 'create-preview',
    components: { CSection },
    data() {
      return {
        sn: this.$route.params.sn,
        profile: null,
        records: [],
        unlock: false,
        canBack: window.history.length > 1
      }
    },
    created() {
      this.api.get('/api/create-profile', {
        sn: this.sn
      }).then((res) => {
        this.profile = res.data
      }, this.api.onErrorSign)
      this.api.get('/api/prepare-slice', {
        sn: this.sn,
        limit: -1
      }).then((res) => {
        this.records = res.data
      })
      SecCtrl.init()
    },
    methods: {
      submit() {
        this.$router.push(`/create/submit/${this.sn}`)
        /*
        this.bus.$emit('dialog', {
          info: {
            body: '发布后暂不支持内容修改，是否继续？'
          },
          btn: {
            prime: '继续',
            vice: '取消'
          },
          call: {
            prime: () => {
              this.$router.push(`/create/submit/${this.sn}`)
            }
          }
        })
        */
      }
    }
  }
</script>

<style scoped>
  .v-create-preview {
    background: #fff;
    min-height: 100%;
  }
  .head {
    height: 1rem;
    background: #fff;
    border-bottom: 1px solid #ddd;
    position: sticky;
    top: 0;
    z-index: 9;
  }
  .heading {
    font-size: .36rem;
    color: #222;
  }
  .head > .btn {
    padding: 0 .3rem;
    font-size: .28rem;
    color: #333;
  }
  .head .submit {
    color: #2F57DA;
  }
  .cover > img {
    width: 7.5rem;
    height: 4rem;
  }
  .title {
    font-size: .5rem;
    padding: .6rem;
  }
  .back.disabled {
    opacity: 0;
  }
  .byline {
    justify-content: flex-start;
    padding: .1rem .6rem;
    font-size: .28rem;
    color: #999;
  }
  .byline .name {
    color: #2F57DA;
    margin-right: .3rem;
  }
  .byline .series {
    color: #2F57DA;
    /*margin-left: .3rem;*/
  }
  .content {
    padding: .6rem;
  }
  .section {
    margin: 2em 0;
  }
  .backpost {
    position: fixed;
    width: 100%;
    left: 0;
    bottom: .5rem;
  }
  .backpost .icon-post {
    padding: .2rem;
    background: #2F57DA;
    color: #fff;
    font-size: .3rem;
    border-radius: 50%;
    box-shadow: 0 0 5px #ccc;
  }
  /*
  .locked {
    background: linear-gradient(45deg, #eaeaf8, #cbcbe8);
    height: 3rem;
    text-align: center;
    line-height: 3rem;
    border-radius: .1rem;
  }
  .locked i {
    font-size: 0.6rem;
    color: #333;
  }
  .content .locked {
    position: relative;
    display: flex;
    justify-content: center;
    align-items: center;
    overflow: hidden;
    height: 3.6rem;
    background:linear-gradient(-135deg,rgba(203,230,254,1) 0%,rgba(144,200,255,1) 99%);
    border-radius: .1rem;
  }
  .locked div {
    z-index: 2
  }
  .content .locked:before {
    position: absolute;
    left: -.9rem;
    bottom: -.5rem;
    content: ' ';
    width:2.8rem;
    height:2.8rem;
    background:linear-gradient(180deg,rgba(203,230,254,1) 0%,rgba(144,200,255,1) 99%);
    border-radius:50%;
  }

  .content .locked:after {
    position: absolute;
    right: -.5rem;
    top: -.9rem;
    content: ' ';
    width:2.8rem;
    height:2.8rem;
    background:linear-gradient(180deg,rgba(203,230,254,1) 0%,rgba(144,200,255,1) 99%);
    border-radius:50%;
  }
  .locked .section-lock {
    position: relative;
    font-size: .4rem;
    height: .8rem;
    line-height: .8rem;
    padding: 0 1em;
    color: #2F57DA;
    margin: .5em;
    border: 1px solid #2F57DA;
    background: #fff;
    cursor: pointer;
    border-radius: .4rem;
  }
  */
  .locked > .c-section {
    display: none;
  }
  /* 相邻的未解锁段落合并 */
  .locked + .locked {
    display: none;
  }
  .section > .section-lock {
    display: none;
  }
  .section.locked > .section-lock {
    display: flex;
  }
  .section.locked .hint {
    font-size: .28rem;
    color: #2F57DA;
    margin: 1em;
  }
  .section.locked > .section-lock > .btn {
    width: 6.3rem;
    font-size: .36rem;
    height: .8rem;
    color: #2F57DA;
    background: #D8E1FD;
    border: 1px solid #2F57DA;
    border-radius: .4rem;
  }

</style>
