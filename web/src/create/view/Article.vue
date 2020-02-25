<template>
  <div class="v-create-article">
    <div class="head flex-row" v-show="$refs.draft && !$refs.draft.editing">
      <div class="btn back" @click="$router.back()">返回</div>
      <div class="heading flex-item flex-row">文章编辑</div>
      <div class="btn" :class="{preview: profile.title}" @click="preview">预览</div>
    </div>
    <div class="title flex-row icon-yike">
      <input id="input-title" class="flex-item" type="text" v-model="profile.title" @blur="change('title')" placeholder="文章标题"/>
    </div>
    <create-draft ref="draft" :sn="sn"></create-draft>
  </div>
</template>

<script>
  const CreateDraft = r => require.ensure([], () => r(require('../components/Draft')), 'prepare')
  // import CreateDraft from "../components/Draft"

  export default {
    name: 'create-prepare',
    components: {CreateDraft},
    data() {
      return {
        sn: this.$route.params.sn,
        profile: {} // 概况信息
      }
    },
    created() {
      this.api.get('/api/create-profile', {
        sn: this.sn
      }).then((res) => {
        this.profile = res.data
      }, (err) => {
        alert(err.message)
        location.href = `/study/article?sn=${this.sn}`
      })
    },
    methods: {
      change(attr) {
        let data = {
          sn: this.sn
        }
        data[attr] = this.profile[attr]
        this.api.post('/api/create-modify', data).then((res) => {
          this.profile = res.data
        }).catch((e) => {
          this.bus.$emit('dialog', {
            info: {body: e.message}
          })
        })
      },
      cover() {
        console.log('cover')
      },
      preview() {
        if (!this.profile.title) {
          this.bus.$emit('dialog', {
            info: {body: '标题不能为空'}
          })
          return
        }
        if (this.profile.title.split('').length>14) {
          this.bus.$emit('dialog', {
            info: {body: '标题不能超过14字'}
          })
          return
        }
        this.$router.push(`/create/preview/${this.sn}`)
      },
      submit() {
        this.$router.push(`/create/submit/${this.sn}`)
      }
    }
  }
</script>

<style scoped>
  .v-create-article {
    background: #f0eff5;
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
    color: #999;
    font-size: .28rem;
  }
  .head .back {
    color: #999;
  }
  .head .preview {
    color: #2F57DA;
  }
  .title {
    font-size: .36rem;
    background: #fff;
    height: 1.44rem;
    padding: 0 .3rem;
    color: #999;
  }
  #input-title {
    font-size: .36rem;
    outline: none;
    border: none;
    background: transparent;
  }
  #input-title:focus {
    background: #fff;
  }
</style>
